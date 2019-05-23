<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class HotelOrderAction extends \BaseAction
{
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

    //客房状态
    protected function doRoomStatus(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $in_date = $objRequest->in_day;
        $in_date = empty($in_date) ? LoginServiceImpl::getBusinessDay() : $in_date;
        $out_date = $objRequest->out_date;
        $out_date = empty($out_date) ? getDay(24) : $out_date;
        $arrayResult['in_date'] = $in_date;
        $arrayResult['out_date'] = $out_date;
        //房型
        $objRequest->channel_config = 'layout';
        $objRequest->hashKey = 'item_id';
        $arrayLayoutList = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //房间
        $objRequest->channel_config = 'room';
        $objRequest->hashKey = 'item_attr2_value';
        $objRequest->childrenHash = 'item_attr1_value';
        $objRequest->toHashArray = true;
        $arrayRoomList = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('attr_type', 'multipe_room')->EQ('channel_id', $channel_id)->setHashKey('item_id');
        $arrayLayoutRoom = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, 'category_item_id,item_id');
        if (!empty($arrayLayoutRoom)) {
            foreach ($arrayLayoutRoom as $room_id => $value) {
                $arrayLayoutRoom[$room_id]['r_id'] = encode($room_id);
            }
        }
        //获取预订 条件未完结的所有订单 valid = 1 and check_in <= 今天
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('channel', 'Hotel')
            ->EQ('valid', '1')->GE('booking_status', '0')->LE('check_in', $in_date)->setHashKey('booking_number');
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayBookList = BookingHotelServiceImpl::instance()->getBooking($whereCriteria);
        $arrayBookingNumber = array_keys($arrayBookList);
        //加密
        if (!empty($arrayBookList)) {
            foreach ($arrayBookList as $booking_number => $v) {
                $arrayBookList[$booking_number]['book_id'] = encode($booking_number);
            }
        }
        //查找[今日房态/今天预抵的] 条件未完结的今天预抵的所有订单 valid = 1 and check_in <= 今天
        $whereCriteria = new \WhereCriteria();
        //$whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GE('check_in', $in_date)
        //->LE('check_in', $out_date);
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')
            ->EQ('valid', '1')->GE('booking_detail_status', '0')->LE('check_in', $in_date)->setHashKey('booking_detail_id')->ORDER('check_in');
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $bookingDetailRoom = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
        if (!empty($bookingDetailRoom)) {
            foreach ($bookingDetailRoom as $detail_id => $v) {
                $bookingDetailRoom[$detail_id]['detail_id'] = encode($v['booking_detail_id']);
            }
        }
        //入住人数
        $arrayGuestLiveIn = null;
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
            $whereCriteria->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number')
                ->setFatherKey('booking_detail_id')->setChildrenKey('live_in_id');
            $arrayGuestLiveIn = BookingHotelServiceImpl::instance()->getGuestLiveIn($whereCriteria);
            if (!empty($arrayGuestLiveIn)) {
                foreach ($arrayGuestLiveIn as $booking_number => $guestLiveIn) {
                    foreach ($guestLiveIn as $detail_id => $arrayliveIn) {
                        foreach ($arrayliveIn as $i => $arrayliveIn) {
                            $arrayGuestLiveIn[$booking_number][$detail_id][$i]['l_in_id'] = encode($arrayliveIn['live_in_id']);
                        }
                    }
                }
            }
        }
        //消费
        $arrayConsume = null;
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->EQ('valid', '1');
            $whereCriteria->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number')
                ->setFatherKey('booking_detail_id')->setChildrenKey('consume_id');
            $arrayConsume = BookingHotelServiceImpl::instance()->getBookingConsume($whereCriteria);
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
        //账务
        $arrayAccounts = null;
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->EQ('valid', '1');
            $whereCriteria->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number')->setChildrenKey('accounts_id');
            $arrayAccounts = BookingHotelServiceImpl::instance()->getBookingAccounts($whereCriteria);
            if (!empty($arrayAccounts)) {
                foreach ($arrayAccounts as $number => $value) {
                    foreach ($value as $accounts_id => $accounts) {
                        $arrayAccounts[$number][$accounts_id]['ba_id'] = encode($accounts['accounts_id']);
                    }
                }
            }
        }
        //结账方式
        $arrayPaymentType = null;
        if (!empty($arrayBookingNumber)) {
            $arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);
        }
        //客源市场
        $arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        $arrayResult = ['roomList' => $arrayRoomList, 'layoutList' => $arrayLayoutList, 'layoutRoom' => $arrayLayoutRoom, 'bookingDetailRoom' => $bookingDetailRoom,
            'bookList' => $arrayBookList, 'consumeList' => $arrayConsume, 'accountsList' => $arrayAccounts, 'guestLiveInList' => $arrayGuestLiveIn,
            'paymentTypeList' => $arrayPaymentType, 'marketList' => $arrayCustomerMarket, 'in_date' => getDay(), 'out_date'=>getDay(24)];
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doRoomOrder(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        //
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $item_id = $objRequest->item_id;
        $arrayResult['in_date'] = $in_date = LoginServiceImpl::getBusinessDay();
        $arrayResult['out_date'] = $out_date = getDay(24);
        //
        //channel
        //$arrayChannel               = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id, 'Hotel');
        //$arrayResult['channelList'] = $arrayChannel;
        //channel_item
        $objRequest->hashKey = 'channel_id';
        $objRequest->toHashArray = true;
        $arrayChannelItem = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        $arrayResult['arrayChannelItem'] = $arrayChannelItem;
        //客源市场
        $arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        $arrayResult['marketList'] = $arrayCustomerMarket;
        //取出所有有效价格类型
        $arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id, 1);
        //{:
        //獲取價格 所有手动输入价格
        $arraySystemId = null;
        $arraySystem = ChannelServiceImpl::instance()->getLayoutPriceSystemLayout(2, 'DISTINCT price_system_id,price_system_father_id');
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
        $arrayResult['priceLayout'] = ChannelServiceImpl::instance()->getLayoutPrice($company_id, $channel_id, $arraySystemId, $arrayResult['in_date'], $arrayResult['out_date']);

        //:};
        //查找房型房间
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        $whereCriteria->EQ('attr_type', 'multipe_room');
        if ($channel_id > 0) {
            $whereCriteria->EQ('channel_id', $channel_id);
        }
        $field = 'channel_id,category_item_id,item_id,attr_type';
        $hashKey = 'channel_id';
        $whereCriteria->setHashKey($hashKey)->setMultiple(false)->setFatherKey('category_item_id')->setChildrenKey('item_id');
        $arrayResult['layoutRoom'] = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GE('check_in', $in_date)->LE('check_in', $out_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['bookingRoom'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        //
        //$arrayResult['defaultChannel_id'] = $channel_id;

        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //查询订房数据
    protected function doMethodCheckOrderData(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = $objRequest->channel_id;
        $arrayInput = $objRequest->getInput();
        $market_id = isset($arrayInput['market_id']) ? $arrayInput['market_id'] : 0;
        $in_date = $arrayInput['check_in'] . ' ' . $arrayInput['in_time'];
        $out_date = $arrayInput['check_out'] . ' ' . $arrayInput['out_time'];
        //

        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('booking_type', 'room_day')->GT('check_in', $in_date)->LT('check_in', $out_date);
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

    //查询会员数据
    protected function doMethodCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $field = 'member_id';
        $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, $field);
        if (!empty($arrayMember)) {
            $member_id = $arrayMember[0]['member_id'];
            $channel_father_id = $objRequest->validInput('channel_father_id');
            $arrayMemberLevel = \member\MemberServiceImpl::instance()->getMemberLevelByMemberId($member_id, $channel_father_id);
            if (!empty($arrayMemberLevel)) {
                $arrayMemberLevel[0]['member_id'] = $member_id;
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayMemberLevel[0]);
            }
        }
        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_found']);
    }

    //预订
    protected function doMethodBookingRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccess = BookingHotelServiceImpl::instance()->beginBooking($objRequest, $objResponse);
        if ($objSuccess->isSuccess()) {
            $objSuccess = BookingHotelServiceImpl::instance()->saveBooking($objSuccess->getData());
            if ($objSuccess->isSuccess()) return $objResponse->successResponse($objSuccess->getCode(), '');
        }
        return $objResponse->errorResponse($objSuccess->getCode(), $objSuccess->getData(), $objSuccess->getMessage());
    }

    //排房
    protected function doMethodEditBookRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $detail_id = decode($objRequest->getInput('detail_id'));
        $item_room_name = $objRequest->getInput('item_room_name');
        $item_room = $objRequest->getInput('item_room');
        if ($detail_id > 0 && $item_room > 0) {
            CommonServiceImpl::instance()->startTransaction();
            //更新房间detail
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_detail_id', $detail_id);
            if (!empty($item_room_name)) $arrayUpdate['item_name'] = $item_room_name;//item_room_name 为空则不更新
            $arrayUpdate['item_id'] = $item_room;
            BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $arrayUpdate);
            //更新消费
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_detail_id', $detail_id);
            BookingHotelServiceImpl::instance()->updateBookingConsume($whereCriteria, $arrayUpdate);
            //更新财会
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_detail_id', $detail_id);
            BookingHotelServiceImpl::instance()->updateBookingAccounts($whereCriteria, $arrayUpdate);
            //
            CommonServiceImpl::instance()->commit();
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //保存入住客人
    protected function doMethodSaveGuestLiveIn(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = $objLoginEmployee->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $arrayInput = $objRequest->getInput();
        $member_name = $objRequest->validInput('member_name');
        $booking_number = trim(decode($objRequest->getInput('book_id')));
        $detail_id = decode($objRequest->getInput('detail_id'));
        //
        $is_live_in = $objRequest->getInput('is_live_in');
        //
        $live_in_edit_id = decode($objRequest->getInput('live_in_edit_id'));
        if($live_in_edit_id > 0) {//update
            //找寻会员 根据手机或身份证
            $member_id = 0;
            $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, 'member_id');
            if (!empty($arrayMember)) {
                $member_id = $arrayMember[0]['member_id'];
            }
            $updateData['member_id'] = $member_id;
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('live_in_id', $live_in_edit_id);
            $updateData['item_id'] = $objRequest->getInput('item_id');
            $updateData['member_name'] = $objRequest->getInput('member_name');
            $updateData['member_sex'] = $objRequest->getInput('member_sex');
            $updateData['member_mobile'] = $objRequest->getInput('member_mobile');
            $updateData['member_idcard_type'] = $objRequest->getInput('member_idcard_type');
            $updateData['member_idcard_number'] = $objRequest->getInput('member_idcard_number');
            BookingHotelServiceImpl::instance()->updateGuestLiveIn($whereCriteria, $updateData);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'],['live_in_id'=>$live_in_edit_id, 'l_in_id'=>encode($live_in_edit_id)]);
        }
        if ($detail_id > 0) {
            //整合数据
            $Booking_live_inEntity = new Booking_live_inEntity($arrayInput);
            $Booking_live_inEntity->setBookingDetailId($detail_id);
            $Booking_live_inEntity->setLiveInDatetime(getDateTime());
            $Booking_live_inEntity->setChannelId($channel_id);
            $Booking_live_inEntity->setCompanyId($company_id);
            $Booking_live_inEntity->setBookingNumber($booking_number);
            //检查客房是否能入住
            $arrayChannelItemStatus = null;
            if($Booking_live_inEntity->getItemId() > 0) {//已排房
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('item_type', 'item')->EQ('item_id', $Booking_live_inEntity->getItemId());
                $arrayChannelItemStatus = ChannelServiceImpl::instance()->getChannelItem($whereCriteria, 'booking_number,`status`,clean,`lock`');
                if (!empty($arrayChannelItemStatus)) {
                    $arrayChannelItemStatus = $arrayChannelItemStatus[0];
                    if ($arrayChannelItemStatus['booking_number'] != $booking_number) {
                        if ($arrayChannelItemStatus['status'] != '0') {
                            return $objResponse->errorResponse(ErrorCodeConfig::$errorCode[$arrayChannelItemStatus['status']]);
                        }
                        if ($arrayChannelItemStatus['clean'] != '0') {
                            return $objResponse->errorResponse(ErrorCodeConfig::$errorCode[$arrayChannelItemStatus['clean']]);
                        }
                        if ($arrayChannelItemStatus['lock'] != '0') {
                            return $objResponse->errorResponse(ErrorCodeConfig::$errorCode[$arrayChannelItemStatus['lock']]);
                        }
                    }
                } else {
                    return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
                }
            }
            //找寻会员 根据手机或身份证
            $member_id = 0;
            $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, 'member_id');
            if (!empty($arrayMember)) {
                $member_id = $arrayMember[0]['member_id'];
            }
            $Booking_live_inEntity->setMemberId($member_id);
            //
            $Booking_live_inEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
            $Booking_live_inEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
            $Booking_live_inEntity->setAddDatetime(getDateTime());
            //插入
            CommonServiceImpl::instance()->startTransaction();
            $live_in_id = BookingHotelServiceImpl::instance()->saveGuestLiveIn($Booking_live_inEntity);
            //更新
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_detail_id', $detail_id);
            if($is_live_in == 1) $arrayUpdate['booking_detail_status'] = '1';//设置入住状态
            $arrayUpdate['actual_check_in'] = getDateTime();
            BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $arrayUpdate);
            //
            if ($is_live_in == 1 && isset($arrayChannelItemStatus['booking_number']) && empty($arrayChannelItemStatus['booking_number'])) {
                $arrayUpdate = null;
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('item_type', 'item')->EQ('item_id', $Booking_live_inEntity->getItemId());
                $arrayUpdate['status'] = 'live_in';//设置入住状态
                $arrayUpdate['booking_number'] = $booking_number;
                ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $arrayUpdate);
            }
            CommonServiceImpl::instance()->commit();
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['live_in_id'=>$live_in_id, 'l_in_id'=>encode($live_in_id)]);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }
    //设置订单入住状态
    protected function doMethodLiveInRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $booking_number = decode($objRequest->getInput('book_id'));
        $liveInType = $objRequest->getInput('liveInType');
        if($booking_number > 0) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
            if($liveInType == 'all') {//设置入住状态
                $whereCriteria->EQ('booking_number', $booking_number)->EQ('booking_status', '0');
                $updateData['booking_status'] = '1';
                BookingHotelServiceImpl::instance()->updateBooking($whereCriteria, $updateData);
                $updateData = [];
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                    ->EQ('booking_number', $booking_number)->EQ('booking_detail_status', '0');
                $updateData['booking_detail_status'] = '1';
                $updateData['actual_check_in'] = getDateTime();
                BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $updateData);
                //取得入住房间
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                    ->EQ('booking_number', $booking_number)->GT('item_id', 0);
                $arrayLiveInItem = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria, 'item_id');
                if(!empty($arrayLiveInItem)) {
                    $arrayItem = array_column($arrayLiveInItem, 'item_id');
                    //更新房间入住状态
                    $updateData = [];
                    $updateData['booking_number'] = $booking_number;
                    $updateData['status'] = 'live_in';
                    foreach ($arrayItem as $i => $item_id) {
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('status', '0');
                        $whereCriteria->EQ('item_id', $item_id);
                        ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $updateData);
                    }
                }
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            } elseif($liveInType == 'unall') {//反入住状态主订单
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            } elseif($liveInType == 'one') {//入住单个房间


                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            } elseif($liveInType == 'unone') {//反入住单个房间
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            }
        }
        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //编辑房间状态（锁房、维修房、清洁等）
    protected function doMethodSaveRoomStatusEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = $objLoginEmployee->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $arrayInput = $objRequest->getInput();
        $item_id = decode($objRequest->getInput('r_id'));
        $editType = $objRequest->getInput('editType');
        if ($item_id > 0) {
            CommonServiceImpl::instance()->startTransaction();
            if ($editType == 'lock' || $editType == 'repair') {
                $Booking_evenEntity = new Booking_evenEntity($arrayInput);
                //
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel_config', 'room')
                    ->EQ('item_type', 'item')->EQ('item_id', $item_id);
                if ($editType == 'lock') $arrayUpdate['lock'] = 'lock';
                if ($editType == 'repair') $arrayUpdate['lock'] = 'repair';
                $arrayUpdate['begin_datetime'] = $Booking_evenEntity->getBeginDatetime();
                $arrayUpdate['end_datetime'] = $Booking_evenEntity->getEndDatetime();
                ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $arrayUpdate);
                //
                $Booking_evenEntity->setAddDatetime(getDateTime());
                $Booking_evenEntity->setCompanyId($company_id);
                $Booking_evenEntity->setChannelId($channel_id);
                $Booking_evenEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
                $Booking_evenEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
                $Booking_evenEntity->setItemId($item_id);
                $Booking_evenEntity->setBookingEvenType($editType);
                BookingHotelServiceImpl::instance()->saveRoomEven($Booking_evenEntity);
            } else {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel_config', 'room')
                    ->EQ('item_type', 'item')->EQ('item_id', $item_id);
                if ($editType == 'unlock') {
                    $arrayUpdate['lock'] = '0';
                }
                if ($editType == 'repair_ok') {
                    $arrayUpdate['lock'] = '0';
                }
                if ($editType == 'dirty') {
                    $arrayUpdate['clean'] = 'dirty';
                }
                if ($editType == 'clean') {
                    $arrayUpdate['clean'] = '0';
                }
                if ($editType == 'empty_room') {//设置空房间
                    $arrayUpdate['status'] = '0';
                    $arrayUpdate['booking_number'] = '';
                }
                ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $arrayUpdate);
                //
                if ($editType == 'lock' || $editType == 'repair') {
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('item_id', $item_id)
                        ->EQ('valid', '1');
                    BookingHotelServiceImpl::instance()->updateRoomEven($whereCriteria, ['valid' => 2]);
                }

            }
            CommonServiceImpl::instance()->commit();
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
        }
        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }
    //入账
    protected function doMethodSaveAccounts(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $detail_id = decode($objRequest->getInput('detail_id'));
        $item_name = $objRequest->getInput('item_name');
        $item_id = $objRequest->getInput('item_id');
        if ($detail_id > 0 && $item_id > 0) {
            $arrayInput = $objRequest->getInput();
            //更新房间detail
            $businessDay = LoginServiceImpl::getBusinessDay();
            $Booking_accountsEntity = new Booking_accountsEntity($arrayInput);
            $Booking_accountsEntity->setBookingDetailId($detail_id);
            $Booking_accountsEntity->setCompanyId($company_id);
            $Booking_accountsEntity->setChannel($channel_id);
            $Booking_accountsEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
            $Booking_accountsEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
            $Booking_accountsEntity->setBusinessDay($businessDay);
            $Booking_accountsEntity->setAddDatetime(getDateTime());
            $accounts_id = BookingHotelServiceImpl::instance()->saveBookingAccounts($Booking_accountsEntity);
            //
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['accounts_id'=>$accounts_id, 'ba_id'=>encode($accounts_id), 'business_day'=>$businessDay]);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }
    //结账、退房
    protected function doMethodBookingClose(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objSuccess = BookingHotelServiceImpl::instance()->closeBooking($objRequest, $objResponse);
        $objResponse->successServiceResponse($objSuccess);
    }

    //夜审
    protected function doMethodNightAuditor(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //所有在住的，都要夜审，所有应离未离的都要夜审，所有应到未到的，都要夜审。最晚到达时间每个酒店规定不一样
        //预订数据
        $nowHour = date("H");
        $nightAuditorDay = $nowHour > 0 && $nowHour >= 6 ? getDay() : getDay();//0~6点为当天
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->GE('booking_detail_status', '0')->LE('check_out', $nightAuditorDay . ' 12:00:00');
        $arrayBookingDetail = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
        //
        $business_day = LoginServiceImpl::getBusinessDay();
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('business_day', $business_day);
        $arrayConsume = BookingHotelServiceImpl::instance()->getBookingConsume($whereCriteria);

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'],['nightAuditorList'=>$arrayConsume, 'nightAuditorList'=>$arrayBookingDetail]);
    }
    //远期房态
    protected function doMethodRoomForcasting(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $in_date = getDay();
        $out_date = getDay(30);
        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('channel_id', $channel_id)->EQ('booking_type', 'room_day')->GT('check_in', $in_date)->LT('check_in', $out_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['roomForwardList'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'],$arrayResult);
    }
    //搜索订单
    protected function doMethodSearchBooking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;

        $condition_key = $objRequest->getInput('condition_key');
        $search_value = $objRequest->getInput('search_value');
        $condition_date = $objRequest->getInput('condition_date');
        $search_date = $objRequest->getInput('search_date');
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('channel_id', $channel_id)->EQ('booking_type', 'room_day')
            ->setHashKey('booking_detail_id');
        if(!empty($condition_date) && !empty($search_date)) {
            $whereCriteria->EQ($condition_date, $search_date);
        }
        if(!empty($condition_key) && !empty($search_value)) {
            $whereCriteria->EQ($condition_key, $search_value);
        }
        $arrayResult['bookingSearchList'] = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }
}