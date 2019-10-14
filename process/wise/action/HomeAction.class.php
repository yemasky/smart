<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class HomeAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objResponse->nav = 'welcome';
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
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

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //赋值
        //设置类别
        $loginEmployee = LoginServiceImpl::instance()->getLoginInfo();
        $company_id    = $loginEmployee->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //设置类别
        //房间列表
        $objRequest->channel_config = 'room';
        $arrayRoomList              = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //所有房型房间
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('attr_type', 'multipe_room')->EQ('channel_id', $channel_id)->setHashKey('item_id');
        $arrayLayoutRoom = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, 'item_category_id,item_id');
        //今日订单
        $business_day  = LoginServiceImpl::getBusinessDay();
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')
            ->EQ('valid', '1')->EQ('business_day', $business_day)->setHashKey('booking_number');
        $arrayBookList = BookingHotelServiceImpl::instance()->getBooking($whereCriteria, 'booking_number,booking_id');

        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('valid', '1')->LE('check_in', $business_day)
            ->GE('check_out', $business_day)->GE('booking_detail_status', '0');
        $field              = 'actual_check_in';
        $arrayBookingDetail = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria, $field);

        $allRoomNum    = count($arrayLayoutRoom);//所有房间
        $liveInNum     = count($arrayBookingDetail);//在住 预抵数
        $newBookNum    = count($arrayBookList);//新订单数
        $occupancyRate = percent($liveInNum / $allRoomNum, 2, '');//入住率
        //今日进店
        $liveInNumToday = 0;
        if (!empty($arrayBookingDetail)) {
            foreach ($arrayBookingDetail as $k => $arrayData) {
                if (!empty($arrayData['actual_check_in'])) {
                    $liveInNumToday++;
                }
            }
        }
        //新加会员
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->LIMIT(0, 4)->ORDER('member_id');
        $arrayMemberId  = \member\MemberServiceImpl::instance()->getMemberLevel($whereCriteria, 'member_id');
        $arrayNewMember = [];
        if (!empty($arrayMemberId)) {
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->ArrayIN('member_id', array_column($arrayMemberId, 'member_id'));
            $arrayNewMember = \member\MemberServiceImpl::instance()->getMemberList($whereCriteria, 'member_name,wx_avatar,add_datetime');
        }
        //今日流水
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('valid', '1')
            ->EQ('business_day', $business_day);
        $consumePriceTotal = BookingHotelServiceImpl::instance()->getBookingConsume($whereCriteria, 'SUM(consume_price_total) consume_price_total');
        //本月总收入

        $objResponse->allRoomNum        = $allRoomNum;
        $objResponse->liveInNum         = $liveInNum;
        $objResponse->newBookNum        = $newBookNum;
        $objResponse->occupancyRate     = $occupancyRate;
        $objResponse->liveInNumToday    = $liveInNumToday;
        $objResponse->newMember         = $arrayNewMember;
        $objResponse->consumePriceTotal = $consumePriceTotal;
    }


}