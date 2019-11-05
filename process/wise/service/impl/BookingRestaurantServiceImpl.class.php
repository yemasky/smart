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
        $objSuccessService = new \SuccessService();
        $objLoginEmployee  = LoginServiceImpl::instance()->checkLoginEmployee();
        $objEmployee       = $objLoginEmployee->getEmployeeInfo();
        $company_id        = $objEmployee->getCompanyId();
        $channel_id        = $objRequest->channel_id;
        $booking_number    = decode($objRequest->getInput('book_id'));//是否挂客房的订单号
        $market_id         = $objRequest->getInput('market_id');//客源
        $arrayInput                        = $objRequest->getInput();
        $check_in                          = $objRequest->validInput('check_in');
        $in_time                           = $objRequest->validInput('in_time');
        $arrayCommonData['booking_number'] = 0;//新订单
        if (!empty($booking_number) && is_numeric($booking_number)) {
            $arrayCommonData['booking_number'] = $booking_number;
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
        $arrayCommonData['company_id']    = $company_id;
        $arrayCommonData['channel']       = ModulesConfig::$channel_value['Hotel'];
        $arrayCommonData['channel_id']    = $channel_id;
        $arrayCommonData['employee_id']   = $objEmployee->getEmployeeId();
        $arrayCommonData['employee_name'] = $objEmployee->getEmployeeName();
        $arrayCommonData['sales_id']      = 0;
        $arrayCommonData['sales_name']    = '';
        $arrayCommonData['add_datetime']  = getDateTime();
        $arrayAllBookData                 = array_merge($arrayInput, $arrayCommonData);
        //booking 预订人信息
        $BookingEntity = new BookingEntity($arrayAllBookData);
        $BookingEntity->setBookingNumber($arrayCommonData['booking_number']);
        $BookingEntity->setBookingStatus('0');//初始状态为 0
        $BookingEntity->setBusinessDay($objResponse->business_day);
        $BookingEntity->setBookingTotalPrice(0);
        $BookingEntity->setInTime($in_time);
        //每一间房
        $arrayBookDetailList = array();
        $BookingDetailEntity = new Booking_detailEntity($arrayAllBookData);
        $BookingDetailEntity->setBusinessDay($objResponse->business_day);
        $BookingDetailEntity->setBookingDetailStatus('0');
        $BookingDetailEntity->setInTime($in_time);
        //每一间房消费
        $BookingDetailConsumeList   = array();
        $BookingDetailConsumeEntity = new Booking_consumeEntity($arrayAllBookData);
        $BookingDetailConsumeEntity->setConsumeTitle('房费');
        //判断会员级别
        //
        //预订数据 每个房间1个BookingDetai，每个房间每天1个BookingConsume
        if (!empty($arrayBookingData)) {
            //取得缺少的取消政策
            $arrayPolicy = ChannelServiceImpl::instance()->getCancellationPolicyCache($company_id);
            //取得缺失的市场佣金
            $arrayCommision = ChannelServiceImpl::instance()->getChannelCommisionCache($company_id, $channel_id, $market_id);
        }






        $BookingEntity            = new BookingEntity();
        $arrayBookDetailList      = [];
        $BookingDetailConsumeList = [];

        $BookingData = new BookingDataModel();
        $BookingData->setBookingEntity($BookingEntity);
        $BookingData->setBookDetailList($arrayBookDetailList);
        $BookingData->setBookingDetailConsumeList($BookingDetailConsumeList);




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

