<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class HotelOrderAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "RoomStatus":
                $this->doRoomStatus($objRequest, $objResponse);
                break;
            case "RoomOrder":
                $this->doRoomOrder($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        // TODO: Implement method() method.
        $method = $objRequest->method;
        if (!empty($method)) {
            $method = 'doMethod' . ucfirst($method);

            return $this->$method($objRequest, $objResponse);
        }
    }

    public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->check($objRequest, $objResponse);
        $this->service($objRequest, $objResponse);
    }

    public function tryexecute(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        CommonServiceImpl::instance()->rollback();
    }

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        //赋值
        //设置类别
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
    }

    protected function doRoomStatus(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id              = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $in_date = $objRequest->in_day;
        $in_date = empty($in_date)? LoginServiceImpl::getBusinessDay() : $in_date;
        $out_date = $objRequest->out_date;
        $out_date = empty($out_date) ? getDay(24) : $out_date;
        $arrayResult['in_date']  = $in_date;
        $arrayResult['out_date'] = $out_date;
        //房型
        $objRequest->channel_config = 'layout';
        $objRequest->hashKey        = 'item_id';
        $arrayLayoutList            = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //房间
        $objRequest->channel_config = 'room';
        $objRequest->hashKey        = 'item_attr2_value';
        $objRequest->childrenHash   = 'item_attr1_value';
        $objRequest->toHashArray    = true;
        $arrayRoomList              = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('attr_type', 'multipe_room')->EQ('channel_id', $channel_id)->setHashKey('item_id');
        $arrayLayoutRoom = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, 'category_item_id,item_id');
        //获取预订 条件未完结的今天预抵的所有订单 valid = 1 and check_in <= 今天
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('channel', 'Hotel')->EQ('valid', '1')
            ->LE('check_in', $in_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayBookList = BookingHotelServiceImpl::instance()->getBooking($whereCriteria);
        //查找已住房间[今日房态] 条件未完结的今天预抵的所有订单 valid = 1 and check_in <= 今天
        $whereCriteria = new \WhereCriteria();
        //$whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GE('check_in', $in_date)
            //->LE('check_in', $out_date);
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->EQ('valid', '1')
            ->LE('check_in', $in_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $bookingDetailRoom = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
        //消费

        //入账


        $arrayResult = ['roomList' => $arrayRoomList, 'layoutList' => $arrayLayoutList, 'layoutRoom'=>$arrayLayoutRoom,
            'bookingDetailRoom' => $bookingDetailRoom, 'bookList'=>$arrayBookList];
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doRoomOrder(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        //
        $company_id              = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id              = $objRequest->channel_id;
        $item_id                 = $objRequest->item_id;
        $arrayResult['in_date']  = $in_date = LoginServiceImpl::getBusinessDay();
        $arrayResult['out_date'] = $out_date = getDay(24);
        //
        //channel
        //$arrayChannel               = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id, 'Hotel');
        //$arrayResult['channelList'] = $arrayChannel;
        //channel_item
        $objRequest->hashKey             = 'channel_id';
        $objRequest->toHashArray         = true;
        $arrayChannelItem                = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        $arrayResult['arrayChannelItem'] = $arrayChannelItem;
        //客源市场
        $arrayCustomerMarket       = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        $arrayResult['marketList'] = $arrayCustomerMarket;
        //取出所有有效价格类型
        $arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id, 1);
        //{:
        //獲取價格 所有手动输入价格
        $arraySystemId = null;
        $arraySystem   = ChannelServiceImpl::instance()->getLayoutPriceSystemLayout(2, 'DISTINCT price_system_id,price_system_father_id');
        if (!empty($arraySystem)) {
            foreach ($arraySystem as $i => $systemList) {
                if ($systemList['price_system_father_id'] > 0) {
                    $arraySystemId[$systemList['price_system_father_id']] = $systemList['price_system_father_id'];
                } else {
                    $arraySystemId[$systemList['price_system_id']] = $systemList['price_system_id'];
                }
            }
        }
        //取出默认散客价格
        $arrayResult['priceLayout'] = ChannelServiceImpl::instance()->getLayoutPrice($company_id, $channel_id, $arraySystemId,
            $arrayResult['in_date'], $arrayResult['out_date']);

        //:};
        //查找房型房间
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        $whereCriteria->EQ('attr_type', 'multipe_room');
        if ($channel_id > 0) {
            $whereCriteria->EQ('channel_id', $channel_id);
        }
        $field   = 'channel_id,category_item_id,item_id,attr_type';
        $hashKey = 'channel_id';
        $whereCriteria->setHashKey($hashKey)->setMultiple(false)->setFatherKey('category_item_id')->setChildrenKey('item_id');
        $arrayResult['layoutRoom'] = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GE('check_in', $in_date)
            ->LE('check_in', $out_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['bookingRoom'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        //
        //$arrayResult['defaultChannel_id'] = $channel_id;

        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doMethodCheckOrderData(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = $objRequest->channel_id;
        $arrayInput = $objRequest->getInput();
        $market_id  = isset($arrayInput['market_id']) ? $arrayInput['market_id'] : 0;
        $in_date    = $arrayInput['check_in'] . ' ' . $arrayInput['in_time'];
        $out_date   = $arrayInput['check_out'] . ' ' . $arrayInput['out_time'];
        //

        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GT('check_in', $in_date)
            ->LT('check_in', $out_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['bookingRoom'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        //:獲取價格
        $arraySystemId = null;
        if ($market_id > 0) {
            $arraySystem = ChannelServiceImpl::instance()->getLayoutPriceSystemLayout($market_id, 'DISTINCT price_system_id,price_system_father_id');
            if (!empty($arraySystem)) {
                foreach ($arraySystem as $i => $systemList) {
                    if ($systemList['price_system_father_id'] > 0) {
                        $arraySystemId[$systemList['price_system_father_id']] = $systemList['price_system_father_id'];
                    } else {
                        $arraySystemId[$systemList['price_system_id']] = $systemList['price_system_id'];
                    }
                }
            }
        }
        $arrayResult['priceLayout'] = ChannelServiceImpl::instance()->getLayoutPrice($company_id, $channel_id, $arraySystemId, $in_date, $out_date);
        //:;

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doMethodCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $field       = 'member_id';
        $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, $field);
        if (!empty($arrayMember)) {
            $member_id         = $arrayMember[0]['member_id'];
            $channel_father_id = $objRequest->validInput('channel_father_id');
            $arrayMemberLevel  = \member\MemberServiceImpl::instance()->getMemberLevel($member_id, $channel_father_id);
            if (!empty($arrayMemberLevel)) {
                $arrayMemberLevel[0]['member_id'] = $member_id;
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayMemberLevel[0]);
            }
        }
        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_found']);
    }

    protected function doMethodBookingRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccess = BookingHotelServiceImpl::instance()->beginBooking($objRequest, $objResponse);
        if ($objSuccess->isSuccess()) {
            $objSuccess = BookingHotelServiceImpl::instance()->saveBooking($objSuccess->getData());
            if($objSuccess->isSuccess()) return $objResponse->successResponse($objSuccess->getCode(), '');
        }
        return $objResponse->errorResponse($objSuccess->getCode(), $objSuccess->getData(), $objSuccess->getMessage());
    }
}