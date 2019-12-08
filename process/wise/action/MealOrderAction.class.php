<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class MealOrderAction extends \BaseAction {
    protected $Booking_operationEntity;

    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $_self_module     = $objResponse->getResponse('_self_module');
        //获取channel
        $channel_id              = $objRequest->channel_id;
        $Booking_operationEntity = new Booking_operationEntity();
        $Booking_operationEntity->setAddDatetime(getDateTime());
        $Booking_operationEntity->setBusinessDay(LoginServiceImpl::getBusinessDay());
        $Booking_operationEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
        $Booking_operationEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
        $Booking_operationEntity->setCompanyId($company_id);
        $Booking_operationEntity->setChannelId($channel_id);
        $Booking_operationEntity->setModuleId($_self_module['module_id']);
        $Booking_operationEntity->setModuleName($_self_module['module_name']);
        $Booking_operationEntity->setMethod($objRequest->method);
        $request = json_encode($objRequest->get()) . json_encode($objRequest->getInput()) . json_encode($objRequest->getPost());
        $Booking_operationEntity->setRequest($request);
        $this->Booking_operationEntity = $Booking_operationEntity;
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case 'RestaurantReservation':
                $this->doRestaurantReservation($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->check($objRequest, $objResponse);
        $this->service($objRequest, $objResponse);
    }

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //赋值
        $successService = new \SuccessService();

        return $objResponse->successServiceResponse($successService);
    }

    //餐饮预订
    protected function doRestaurantReservation(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        //
        $objLoginEmployee = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id       = $objLoginEmployee->getEmployeeInfo()->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        //默认channel
        $arrayEmployeeChannel = $objLoginEmployee->getEmployeeChannel();
        $thisChannel          = $arrayEmployeeChannel[$channel_id];
        //
        $arrayResult['in_date'] = $in_date = LoginServiceImpl::getBusinessDay();
        //客源市场
        $arrayResult['marketList'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        //房间
        $objRequest->channel_config = 'table';
        $objRequest->hashKey        = 'item_attr2_value';
        $objRequest->childrenHash   = 'item_attr1_value';
        $objRequest->toHashArray    = true;
        $arrayResult['roomList']    = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //取出折扣
        $arrayResult['channelDiscountList'] = DiscountServiceImpl::instance()->getBookingDiscount($company_id, $channel_id);
        //取出订单数据
        //获取预订 条件未完结的所有订单 valid = 1 所有未结账订单包括预订
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Meal')
            ->EQ('valid', '1')->GE('booking_status', '0')->setHashKey('booking_number');
        $field              = 'booking_number,booking_number_ext,booking_type,receivable_id,receivable_name,member_id,member_name,booking_status,check_in,number_of_people,node,remarks';
        $arrayBookList      = BookingHotelServiceImpl::instance()->getBooking($whereCriteria, $field);
        $arrayBookingNumber = [];
        if (!empty($arrayBookList)) {
            $arrayBookingNumber = array_keys($arrayBookList);
            foreach ($arrayBookList as $booking_number => $v) {
                $arrayBookList[$booking_number]['book_id'] = encode($booking_number);//加密//默认按天加密
            }
        }
        $arrayResult['bookList'] = $arrayBookList;
        //查找[今日房态/今天预抵的] 条件未完结的今天预抵的所有订单 valid = 1 and check_in <= 今天
        $bookingDetailRoom = [];
        $arrayConsume      = [];
        $bookingCuisine    = [];
        if (!empty($arrayBookingNumber)) {
            //桌态
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Meal')
                ->EQ('valid', '1')->ArrayIN('booking_number', $arrayBookingNumber)
                ->setHashKey('booking_detail_id')->ORDER('check_in');
            $field             = 'booking_detail_id,booking_number,booking_number_ext,booking_type,market_father_id,market_id,market_name,'
                . 'item_id,item_category_id,check_in,actual_check_in,booking_detail_status,client,valid,add_datetime';
            $bookingDetailRoom = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria, $field);
            if (!empty($bookingDetailRoom)) {
                foreach ($bookingDetailRoom as $detail_id => $v) {
                    $bookingDetailRoom[$detail_id]['ec_detail_id'] = encode($v['booking_detail_id']);
                    $bookingDetailRoom[$detail_id]['book_id']      = encode($v['booking_number']);
                }
            }
            //消费菜式
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                ->EQ('valid', '1')->ArrayIN('booking_number', $arrayBookingNumber)
                ->setHashKey('booking_detail_id')->setChildrenKey('cuisine_id');
            $field          = 'booking_detail_id,booking_number,cuisine_id,cuisine_name,cuisine_number,cuisine_number_over,'
                . 'cuisine_number_return,cuisine_total_price,cuisine_sell_price,cuisine_price,is_discount,item_id,valid,add_datetime';
            $bookingCuisine = BookingRestaurantServiceImpl::instance()->getBookingCuisine($whereCriteria, $field);
            //折扣
            //消费
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Meal')->EQ('valid', '1');
            $whereCriteria->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number')
                ->setFatherKey('booking_detail_id')->setChildrenKey('consume_id');
            $field               = 'consume_id,booking_detail_id,booking_number,booking_number_ext,channel_consume_id,booking_type,market_id,item_id,'
                . 'valid,add_datetime';
            $arrayBookingConsume = BookingHotelServiceImpl::instance()->getBookingConsume($whereCriteria, $field);
            if (!empty($arrayConsume)) {
                foreach ($arrayConsume as $number => $value) {
                    foreach ($value as $detail_id => $consumes) {
                        foreach ($consumes as $accounts_id => $consume) {
                            $arrayConsume[$number][$detail_id][$accounts_id]['c_id'] = encode($consume['consume_id']);
                        }
                    }
                }
            }
        }
        $arrayResult['bookDetailRoom']  = $bookingDetailRoom;
        $arrayResult['bookCuisineList'] = $bookingCuisine;
        $arrayResult['bookConsumeList'] = $arrayBookingConsume;
        //消费类别
        $arrayChannelConsume               = ChannelServiceImpl::instance()->getChannelConsume($company_id, $channel_id, $thisChannel['channel']);
        $arrayResult['channelConsumeList'] = $arrayChannelConsume;
        //结账方式
        $arrayPaymentType = [];
        if (!empty($arrayBookingNumber)) {
            $arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);
        }
        $arrayResult['paymentTypeList'] = $arrayPaymentType;

        $successService = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        BookCommon::instance()->doCheckMember($objRequest, $objResponse);
    }

    //
    protected function doMethodCuisineList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objRequest->order             = ['cuisine_category_id' => 'ASC'];
        $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisineList($objRequest, $objResponse);
        $successService                = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    //查询协议公司数据
    protected function doMethodGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        BookCommon::instance()->doGetReceivable($objRequest, $objResponse);
    }

    //保存预订数据
    protected function doMethodSaveRestaurantBook(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = BookingRestaurantServiceImpl::instance()->beginBooking($objRequest, $objResponse);
        if ($objSuccessService->isSuccess()) {
            $objSuccessService = BookingRestaurantServiceImpl::instance()->saveBooking($objSuccessService->getData());
            if ($objSuccessService->isSuccess()) {

                return $objResponse->successServiceResponse($objSuccessService);
            }
        }
        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodEditBookEditCuisine(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = new \SuccessService();
        //
        $edit_type      = $objRequest->type;
        $booking_number = decode($objRequest->book_id);
        $detail_id      = $objRequest->detail_id;
        if (!empty($booking_number)) {
            if ($edit_type == 'Add') {
                if ($detail_id == 0) {//新增

                } else if ($detail_id > 0) {//update

                }

            }
        }
        return $objResponse->successServiceResponse($objSuccessService);
    }
}