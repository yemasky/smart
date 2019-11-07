<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class BookingRestaurantServiceImpl extends \BaseServiceImpl implements BookingService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookingRestaurantServiceImpl();

        return self::$objService;
    }

    public function getBooking(\WhereCriteria $whereCriteria, $field = null) {
        return BookingDao::instance()->getBooking($whereCriteria, $field);
    }

    public function checkBooking(\WhereCriteria $whereCriteria, $field = null) {
        if ($field == '') $field = 'channel_id,item_category_id,item_id,check_in,check_out';
        return BookingDao::instance()->getBookingDetailList($whereCriteria, $field);
    }

    /*
     * booking_room 数据结构 单个时间段订房
     * [channel_id => item_category_id => system_id => room_info]
     */
    public function beginBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService {
        $objSuccessService                 = new \SuccessService();
        $objLoginEmployee                  = LoginServiceImpl::instance()->checkLoginEmployee();
        $objEmployee                       = $objLoginEmployee->getEmployeeInfo();
        $company_id                        = $objEmployee->getCompanyId();
        $channel_id                        = $objRequest->channel_id;
        $booking_number                    = decode($objRequest->getInput('book_id'));//是否挂客房的订单号
        $market_id                         = $objRequest->getInput('market_id');//客源
        $arrayInput                        = $objRequest->getInput();
        $check_in                          = $objRequest->validInput('check_in');
        $in_time                           = $objRequest->validInput('in_time');
        $arrayCommonData['booking_number'] = 0;//新订单
        if (!empty($booking_number) && is_numeric($booking_number)) {
            $arrayCommonData['booking_number'] = $booking_number;//老订单 或者 合并到酒店等订单
        }
        //mobile_email
        $mobile_email = $objRequest->validInput('mobile_email');
        if (strlen($mobile_email) == 11 && is_numeric($mobile_email)) {//mobile
            $arrayCommonData['member_mobile'] = $mobile_email;
        } elseif (strpos($mobile_email, '@') !== false) {
            $arrayCommonData['member_email'] = $mobile_email;
        }
        //
        $channel_father_id = $objRequest->validInput('channel_father_id');
        $arrayBookingData  = $objRequest->validInput('booking_data');
        if ($channel_father_id === false || $arrayBookingData === false) {
            return $objSuccessService->setSuccessService(false, ErrorCodeConfig::$errorCode['parameter_error'], '缺失多个参数');
        }
        $receivable_id = $objRequest->receivable_id;
        if (empty($receivable_id)) $receivable_id = 0;
        $arrayCommonData['company_id']    = $company_id;
        $arrayCommonData['channel']       = ModulesConfig::$channel_value['Meal'];//channel
        $arrayCommonData['channel_id']    = $channel_id;
        $arrayCommonData['employee_id']   = $objEmployee->getEmployeeId();
        $arrayCommonData['employee_name'] = $objEmployee->getEmployeeName();
        $arrayCommonData['receivable_id'] = $receivable_id;
        $arrayCommonData['sales_id']      = 0;//销售ID
        $arrayCommonData['sales_name']    = '';
        $arrayCommonData['business_day']  = $objResponse->business_day;
        $arrayCommonData['add_datetime']  = getDateTime();
        //暂时没开发的必填项
        $arrayCommonData['policy_id'] = 0;

        $arrayAllBookData = array_merge($arrayInput, $arrayCommonData);
        //booking 预订人信息 新订单需要生成第一个主订单
        $BookingEntity = new BookingEntity($arrayAllBookData);
        $BookingEntity->setBookingNumber($arrayCommonData['booking_number']);
        if (empty($arrayCommonData['booking_number'])) {
            $BookingEntity->setBookingStatus('0');//初始状态为 0
            $BookingEntity->setBusinessDay($objResponse->business_day);
            $BookingEntity->setBookingTotalPrice(0);
            $BookingEntity->setInTime($in_time);
        }
        //预订的桌台
        $arrayBookDetailList = array();
        $BookingDetailEntity = new Booking_detailEntity($arrayAllBookData);
        $BookingDetailEntity->setBusinessDay($objResponse->business_day);
        $BookingDetailEntity->setBookingDetailStatus('0');
        $BookingDetailEntity->setInTime($in_time);
        //每一个消费
        $BookingDetailConsumeList   = array();
        $BookingDetailConsumeEntity = new Booking_consumeEntity($arrayAllBookData);
        $BookingDetailConsumeEntity->setConsumeTitle(ModulesConfig::$consumeConfig['Meals']['consume_title']);
        $BookingDetailConsumeEntity->setChannelConsumeFatherId(ModulesConfig::$consumeConfig['Meals']['channel_consume_father_id']);
        $BookingDetailConsumeEntity->setChannelConsumeId(ModulesConfig::$consumeConfig['Meals']['channel_consume_id']);//12为餐费
        //菜式
        $Booking_cuisineEntity = new Booking_cuisineEntity($arrayAllBookData);
        $Booking_cuisineEntity->setAddDatetime(getDateTime());
        $Booking_cuisineEntityList = [];
        //判断会员级别
        //
        //预订数据 每个房间1个BookingDetai，每个房间每天1个BookingConsume
        if (!empty($arrayBookingData)) {
            //取得缺少的取消政策
            //$arrayPolicy = ChannelServiceImpl::instance()->getCancellationPolicyCache($company_id);
            //取得缺失的市场佣金
            //$arrayCommision = ChannelServiceImpl::instance()->getChannelCommisionCache($company_id, $channel_id, $market_id);
            foreach ($arrayBookingData as $item_id => $arrayCuisineData) {//餐桌 房间 => 订菜数据
                $arrayBookDetailList[$item_id]      = clone ($BookingDetailEntity);
                $BookingDetailConsumeList[$item_id] = clone ($BookingDetailConsumeEntity);
                foreach ($arrayCuisineData as $cuisine_id => $cuisine) {
                    $Booking_cuisineEntity->setCuisineId($cuisine_id);
                    $Booking_cuisineEntityList[$cuisine_id] = clone ($Booking_cuisineEntity);
                }
            }
        }
        $BookingData = new BookingDataModel();
        $BookingData->setBookingEntity($BookingEntity);
        $BookingData->setBookDetailList($arrayBookDetailList);
        $BookingData->setBookingDetailConsumeList($BookingDetailConsumeList);
        $BookingData->setBookingExtendDatatList($Booking_cuisineEntityList);
        //
        return $objSuccessService->setSuccessService(true, ErrorCodeConfig::$successCode['success'], '', $BookingData);
    }

    public function saveBooking(BookingDataModel $BookingData): \SuccessService {
        CommonServiceImpl::instance()->startTransaction();
        $objSuccessService        = new \SuccessService();
        $bookingEntity            = $BookingData->getBookingEntity();
        $bookDetailList           = $BookingData->getBookDetailList();//桌态
        $bookingDetailConsumeList = $BookingData->getBookingDetailConsumeList();//消费列表
        $bookingNumber            = $bookingEntity->getBookingNumber();
        if (empty($bookingNumber)) {
            $booking_id    = BookingDao::instance()->saveBooking($bookingEntity);
            $bookingNumber = BookingUtil::instanct()->getBookingNumber($booking_id);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('booking_id', $booking_id);
            BookingDao::instance()->updateBooking($whereCriteria, ['booking_number' => $bookingNumber]);
        }
        $objSuccessService->setData(['booking_number' => $bookingNumber]);
        $arrayBookDetailId = BookingDao::instance()->saveBookingDetailList($bookDetailList);
        if (!empty($arrayBookDetailId)) {
            foreach ($bookingDetailConsumeList as $k => $bookDetailConsume) {
                $_item_id   = $bookDetailConsume->getItemId();
                $_detail_id = $arrayBookDetailId[$_item_id];
                $bookingDetailConsumeList[$k]->setBookingDetailId($_detail_id);
                $bookingDetailConsumeList[$k]->setBookingNumber($bookingNumber);
            }
            BookingDao::instance()->saveBookingDetailConsumeList($bookingDetailConsumeList);
        }

        return $objSuccessService;

    }

    public function editBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService {
        $objSuccessService = new \SuccessService();
        $company_id        = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //

    }

    public function closeBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService {
        // TODO: Implement closeBooking() method.
        $objSuccessService = new \SuccessService();
        $objLoginEmployee  = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id        = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;

    }


}

