<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class HotelOrderAction extends \BaseAction {
    protected $Booking_operationEntity;
    protected $successService;

    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $_self_module     = $objResponse->getResponse('_self_module');
        //获取channel
        $channel_id                    = $objRequest->channel_id;
        $this->Booking_operationEntity = new Booking_operationEntity();
        $this->Booking_operationEntity->setAddDatetime(getDateTime());
        $this->Booking_operationEntity->setBusinessDay(LoginServiceImpl::getBusinessDay());
        $this->Booking_operationEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
        $this->Booking_operationEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
        $this->Booking_operationEntity->setCompanyId($company_id);
        $this->Booking_operationEntity->setChannelId($channel_id);
        $this->Booking_operationEntity->setModuleId($_self_module['module_id']);
        $this->Booking_operationEntity->setModuleName($_self_module['module_name']);
        $this->Booking_operationEntity->setMethod($objRequest->method);
        $request = json_encode($objRequest->get()) . json_encode($objRequest->getInput()) . json_encode($objRequest->getPost());
        $this->Booking_operationEntity->setRequest($request);
        //
        $this->successService = new  \SuccessService();
        $this->successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
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

    //客房状态页面 单个channel_id 的数据 并无多个，如需切换，则重新获取数据
    protected function doRoomStatus(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $objLoginEmployee = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id       = $objLoginEmployee->getEmployeeInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //默认channel
        $arrayEmployeeChannel = $objLoginEmployee->getEmployeeChannel();
        $thisChannel          = $arrayEmployeeChannel[$channel_id];
        //
        $in_date                 = $objRequest->in_day;
        $in_date                 = empty($in_date) ? LoginServiceImpl::getBusinessDay() : $in_date;
        $out_date                = $objRequest->out_date;
        $out_date                = empty($out_date) ? getDay(24) : $out_date;
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
        $arrayLayoutRoom = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, 'item_category_id,item_id');
        if (!empty($arrayLayoutRoom)) {
            foreach ($arrayLayoutRoom as $room_id => $value) {
                $arrayLayoutRoom[$room_id]['r_id'] = encode($room_id);
            }
        }
        //获取预订 条件未完结的所有订单 valid = 1 and check_in <= 今天  今天以前的所有未结账订单
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')
            ->EQ('valid', '1')->GE('booking_status', '0')->LE('check_in', $in_date)->setHashKey('booking_number');
        $arrayBookList      = BookingHotelServiceImpl::instance()->getBooking($whereCriteria);
        $arrayBookingNumber = [];
        if (!empty($arrayBookList)) {
            $arrayBookingNumber = array_keys($arrayBookList);
            foreach ($arrayBookList as $booking_number => $v) {
                $arrayBookList[$booking_number]['book_id'] = encode($booking_number);//加密
            }
        }
        //查找[今日房态/今天预抵的] 条件未完结的今天预抵的所有订单 valid = 1 and check_in <= 今天
        $bookingDetailRoom = [];
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();
            //$whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->GE('check_in', $in_date)
            //->LE('check_in', $out_date);//->LE('check_in', $in_date)//
            //->GE('booking_detail_status', '0')
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')
                ->EQ('valid', '1')->ArrayIN('booking_number', $arrayBookingNumber)
                ->setHashKey('booking_detail_id')->ORDER('check_in');
            $bookingDetailRoom = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
            if (!empty($bookingDetailRoom)) {
                foreach ($bookingDetailRoom as $detail_id => $v) {
                    $bookingDetailRoom[$detail_id]['detail_id'] = encode($v['booking_detail_id']);
                    $bookingDetailRoom[$detail_id]['book_id']   = encode($v['booking_number']);
                }
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
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('valid', '1');
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
        //消费类别
        $arrayChannelConsume = ChannelServiceImpl::instance()->getChannelConsume($company_id, $channel_id, $thisChannel['channel']);
        //客房借物
        $arrayBorrowing = [];
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
            $whereCriteria->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number')
                ->setFatherKey('booking_detail_id')->setChildrenKey('booking_borrowing_id');
            $arrayBorrowing = BookingHotelServiceImpl::instance()->getBookingBorrowing($whereCriteria);
            if (!empty($arrayBorrowing)) {
                foreach ($arrayBorrowing as $number => $value) {
                    foreach ($value as $detail_id => $borrowings) {
                        foreach ($borrowings as $booking_borrowing_id => $borrowing) {
                            $arrayBorrowing[$number][$detail_id][$booking_borrowing_id]['bb_id'] = encode($borrowing['booking_borrowing_id']);
                        }
                    }
                }
            }
        }
        //借物物件
        $arrayChannelBorrowing = ChannelServiceImpl::instance()->getChannelBorrowing($company_id, $channel_id);
        //账务
        $arrayAccounts = null;
        if (!empty($arrayBookingNumber)) {
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('valid', '1');
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
        //取出所有有效价格类型
        $priceSystemHash = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id, 1);

        //客源市场
        $arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        $arrayResult         = ['roomList' => $arrayRoomList, 'layoutList' => $arrayLayoutList, 'layoutRoom' => $arrayLayoutRoom, 'bookingDetailRoom' => $bookingDetailRoom,
            'bookList' => $arrayBookList, 'consumeList' => $arrayConsume, 'accountsList' => $arrayAccounts, 'guestLiveInList' => $arrayGuestLiveIn, 'channelConsumeList' => $arrayChannelConsume,
            'paymentTypeList' => $arrayPaymentType, 'marketList' => $arrayCustomerMarket, 'channelBorrowing' => $arrayChannelBorrowing, 'bookBorrowingList' => $arrayBorrowing,
            'priceSystemHash' => $priceSystemHash, 'in_date' => getDay(), 'out_date' => getDay(24)];
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //预订页面booking
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
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('market_id', 2);
        $arraySystem = ChannelServiceImpl::instance()->getLayoutPriceSystemLayout($whereCriteria, 'DISTINCT price_system_id,price_system_father_id');
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
        $arrayResult['priceLayout'] = ChannelServiceImpl::instance()->getLayoutPrice($company_id, $channel_id, $arraySystemId, null, $arrayResult['in_date'], $arrayResult['out_date']);

        //:};
        //查找房型房间
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        $whereCriteria->EQ('attr_type', 'multipe_room');
        if ($channel_id > 0) {
            $whereCriteria->EQ('channel_id', $channel_id);
        }
        $field   = 'channel_id,item_category_id,item_id,attr_type';
        $hashKey = 'channel_id';
        $whereCriteria->setHashKey($hashKey)->setMultiple(false)->setFatherKey('item_category_id')->setChildrenKey('item_id');
        $arrayResult['layoutRoom'] = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
        //查找已住房间[远期房态] ->LE('check_in', $check_out)->GE('check_out', $check_in)
        $whereCriteria = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->LE('check_in', $out_date)->GE('check_out', $in_date)
            ->GE('booking_detail_status', '0');
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['bookingRoom'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        //
        //$arrayResult['defaultChannel_id'] = $channel_id;

        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //查询协议公司数据
    protected function doMethodGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        BookCommon::instance()->doGetReceivable($objRequest, $objResponse);
        /*$company_id    = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id    = $objRequest->channel_id;
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->ArrayIN('channel_id', [0, $channel_id])->EQ('valid', '1');
        $arrayReceivable   = ChannelServiceImpl::instance()->getChannelReceivable($whereCriteria, 'receivable_id,receivable_name');
        $objSuccessService = new \SuccessService();
        $objSuccessService->setData(['receivableData' => $arrayReceivable]);
        return $objResponse->successServiceResponse($objSuccessService);*/
    }

    //查询订房数据
    protected function doMethodCheckOrderData(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        $arrayInput       = $objRequest->getInput();
        $market_id        = isset($arrayInput['market_id']) ? $arrayInput['market_id'] : 0;
        $in_date          = $arrayInput['check_in'];// . ' ' . $arrayInput['in_time'];
        $out_date         = $arrayInput['check_out'];// . ' ' . $arrayInput['out_time'];
        $price_system_id  = $objRequest->getInput('price_system_id');
        $item_category_id = $objRequest->getInput('item_category_id');
        //

        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->LE('check_in', $out_date)->GE('check_out', $in_date)
            ->GE('booking_detail_status', '0');
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['bookingRoom'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);
        //:獲取價格
        $arraySystemId = null;
        if ($market_id > 0) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('market_id', $market_id);
            if (!empty($price_system_id)) {
                $whereCriteria->EQ('price_system_id', $price_system_id);
            }
            if (!empty($item_category_id)) {
                $whereCriteria->EQ('item_category_id', $item_category_id);
            }
            $arraySystem = ChannelServiceImpl::instance()->getLayoutPriceSystemLayout($whereCriteria, 'DISTINCT price_system_id,price_system_father_id');
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
        $arrayResult['priceLayout'] = ChannelServiceImpl::instance()->getLayoutPrice($company_id, $channel_id, $arraySystemId, $item_category_id, $in_date, $out_date);
        //:;

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //查询会员数据
    protected function doMethodCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        BookCommon::instance()->doCheckMember($objRequest,$objResponse);
        /*$field       = 'member_id';
        $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, $field);
        if (!empty($arrayMember)) {
            $member_id         = $arrayMember[0]['member_id'];
            $channel_father_id = $objRequest->validInput('channel_father_id');
            $arrayMemberLevel  = \member\MemberServiceImpl::instance()->getMemberLevelByMemberId($member_id, $channel_father_id);
            if (!empty($arrayMemberLevel)) {
                $arrayMemberLevel[0]['member_id'] = $member_id;
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayMemberLevel[0]);
            }
        }
        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_found']);*/
    }

    //预订
    protected function doMethodBookingRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccess = BookingHotelServiceImpl::instance()->beginBooking($objRequest, $objResponse);
        if ($objSuccess->isSuccess()) {
            $objSuccess = BookingHotelServiceImpl::instance()->saveBooking($objSuccess->getData());
            if ($objSuccess->isSuccess()) {
                $this->Booking_operationEntity->setOperationTitle('预订成功');
                $this->Booking_operationEntity->setOperationContent('预订客房成功');
                $arrayData      = $objSuccess->getData();
                $booking_number = $arrayData['booking_number'];
                $this->Booking_operationEntity->setBookingNumber($booking_number);
                BookingOperationServiceImpl::instance()->saveBookingOperation($this->Booking_operationEntity);
                return $objResponse->successResponse($objSuccess->getCode(), '');
            }
        }
        return $objResponse->errorResponse($objSuccess->getCode(), $objSuccess->getData(), $objSuccess->getMessage());
    }

    //排房 修改 延住 提前预离 换房等
    protected function doMethodEditBookRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $successService = BookingHotelServiceImpl::instance()->editBooking($objRequest, $objResponse);

        $objResponse->successServiceResponse($successService);
    }

    //查询订房数据
    protected function doMethodChangeCheckDate(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        return $this->doMethodCheckOrderData($objRequest, $objResponse);
    }

    //保存入住客人
    protected function doMethodSaveGuestLiveIn(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $arrayInput     = $objRequest->getInput();
        $member_name    = $objRequest->validInput('member_name');
        $booking_number = trim(decode($objRequest->getInput('book_id')));
        $detail_id      = decode($objRequest->getInput('detail_id'));
        //
        $is_live_in = $objRequest->getInput('is_live_in');
        //
        $live_in_edit_id = decode($objRequest->getInput('live_in_edit_id'));
        if ($live_in_edit_id > 0) {//update
            //找寻会员 根据手机或身份证
            $member_id   = 0;
            $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, 'member_id');
            if (!empty($arrayMember)) {
                $member_id = $arrayMember[0]['member_id'];
            }
            $updateData['member_id'] = $member_id;
            $whereCriteria           = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('live_in_id', $live_in_edit_id);
            $updateData['item_id']              = $objRequest->getInput('item_id');
            $updateData['member_name']          = $objRequest->getInput('member_name');
            $updateData['member_sex']           = $objRequest->getInput('member_sex');
            $updateData['member_mobile']        = $objRequest->getInput('member_mobile');
            $updateData['member_idcard_type']   = $objRequest->getInput('member_idcard_type');
            $updateData['member_idcard_number'] = $objRequest->getInput('member_idcard_number');
            BookingHotelServiceImpl::instance()->updateGuestLiveIn($whereCriteria, $updateData);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['live_in_id' => $live_in_edit_id, 'l_in_id' => encode($live_in_edit_id)]);
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
            if ($Booking_live_inEntity->getItemId() > 0) {//已排房
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
            $member_id   = 0;
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
            if ($is_live_in == 1) $arrayUpdate['booking_detail_status'] = '1';//设置入住状态
            $arrayUpdate['actual_check_in'] = getDateTime();
            BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $arrayUpdate);
            //
            if ($is_live_in == 1 && isset($arrayChannelItemStatus['booking_number']) && empty($arrayChannelItemStatus['booking_number'])) {
                $arrayUpdate   = null;
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('item_type', 'item')->EQ('item_id', $Booking_live_inEntity->getItemId());
                $arrayUpdate['status']         = 'live_in';//设置入住状态
                $arrayUpdate['booking_number'] = $booking_number;
                ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $arrayUpdate);
            }
            CommonServiceImpl::instance()->commit();
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['live_in_id' => $live_in_id, 'l_in_id' => encode($live_in_id)]);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //设置订单入住状态
    protected function doMethodLiveInRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id     = $objRequest->channel_id;
        $booking_number = decode($objRequest->getInput('book_id'));
        $liveInType     = $objRequest->getInput('liveInType');
        if ($booking_number > 0) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
            if ($liveInType == 'all') {//设置入住状态
                $businessDay = LoginServiceImpl::getBusinessDay();
                $whereCriteria->EQ('booking_number', $booking_number)->EQ('booking_status', '0');
                $updateData['booking_status'] = '1';
                BookingHotelServiceImpl::instance()->updateBooking($whereCriteria, $updateData);
                $updateData    = [];
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_number', $booking_number)
                    ->EQ('booking_detail_status', '0')->LE('check_in', $businessDay);
                $updateData['booking_detail_status'] = '1';
                $updateData['actual_check_in']       = getDateTime();
                BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $updateData);
                //取得入住房间
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                    ->EQ('booking_number', $booking_number)->GT('item_id', 0);
                $arrayLiveInItem = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria, 'item_id');
                if (!empty($arrayLiveInItem)) {
                    $arrayItem = array_column($arrayLiveInItem, 'item_id');
                    //更新房间入住状态
                    $updateData                   = [];
                    $updateData['booking_number'] = $booking_number;
                    $updateData['status']         = 'live_in';
                    foreach ($arrayItem as $i => $item_id) {
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('status', '0');
                        $whereCriteria->EQ('item_id', $item_id);
                        ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $updateData);
                    }
                }
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            } elseif ($liveInType == 'unall') {//反入住状态主订单
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            } elseif ($liveInType == 'one') {//入住单个房间
                $detail_id = decode($objRequest->getInput('detail_id'));
                if ($detail_id > 0) {
                    $businessDay = LoginServiceImpl::getBusinessDay();
                    $whereCriteria->EQ('booking_number', $booking_number)->EQ('booking_status', '0');
                    $updateData['booking_status'] = '1';
                    BookingHotelServiceImpl::instance()->updateBooking($whereCriteria, $updateData);
                    $updateData    = [];
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_number', $booking_number)
                        ->EQ('booking_detail_status', '0')->EQ('booking_detail_id', $detail_id)->LE('check_in', $businessDay);
                    $updateData['booking_detail_status'] = '1';
                    $updateData['actual_check_in']       = getDateTime();
                    BookingHotelServiceImpl::instance()->updateBookingDetail($whereCriteria, $updateData);
                    //取得入住房间
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                        ->EQ('booking_number', $booking_number)->GT('item_id', 0)->EQ('booking_detail_id', $detail_id);
                    $arrayLiveInItem = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria, 'item_id');
                    if (!empty($arrayLiveInItem)) {
                        $arrayItem = array_column($arrayLiveInItem, 'item_id');
                        //更新房间入住状态
                        $updateData                   = [];
                        $updateData['booking_number'] = $booking_number;
                        $updateData['status']         = 'live_in';
                        foreach ($arrayItem as $i => $item_id) {
                            $whereCriteria = new \WhereCriteria();
                            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('status', '0');
                            $whereCriteria->EQ('item_id', $item_id);
                            ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $updateData);
                        }
                    } else {
                        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_room_id_found']);
                    }
                    return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
                }
            } elseif ($liveInType == 'unone') {//反入住单个房间
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
            }
        }
        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //编辑房间状态（锁房、维修房、清洁等）
    protected function doMethodSaveRoomStatusEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $arrayInput = $objRequest->getInput();
        $item_id    = decode($objRequest->getInput('r_id'));
        $editType   = $objRequest->getInput('editType');
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
                $arrayUpdate['end_datetime']   = $Booking_evenEntity->getEndDatetime();
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
                    $arrayUpdate['status']         = '0';
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
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        //
        $ba_id     = decode($objRequest->getInput('ba_id'));
        $detail_id = decode($objRequest->getInput('detail_id'));
        $item_name = $objRequest->getInput('item_name');
        $item_id   = $objRequest->getInput('item_id');
        if (!empty($ba_id)) {//edit
            if (!empty($item_name)) $arrayUpdate['item_name'] = $item_name;
            $arrayUpdate['item_id']           = $item_id;
            $arrayUpdate['money']             = $objRequest->getInput('money');
            $arrayUpdate['payment_father_id'] = $objRequest->getInput('payment_father_id');
            $arrayUpdate['payment_id']        = $objRequest->getInput('payment_id');
            $arrayUpdate['payment_name']      = $objRequest->getInput('payment_name');
            if ($arrayUpdate['payment_id'] == '11') {
                $arrayUpdate['credit_authorized_number'] = $objRequest->getInput('credit_authorized_number');
                $arrayUpdate['credit_card_number']       = $objRequest->getInput('credit_card_number');
            }
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('accounts_id', $ba_id);
            BookingHotelServiceImpl::instance()->updateBookingAccounts($whereCriteria, $arrayUpdate);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['accounts_id' => $ba_id, 'ba_id' => encode($ba_id), 'business_day' => '']);
        } elseif ($detail_id > 0 && $item_id > 0) {
            $arrayInput = $objRequest->getInput();
            if ($objRequest->getInput('payment_id') != 11) {
                unset($arrayInput['credit_authorized_days']);
            }
            //入账
            $businessDay            = LoginServiceImpl::getBusinessDay();
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
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['accounts_id' => $accounts_id, 'ba_id' => encode($accounts_id), 'business_day' => $businessDay]);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //消费
    protected function doMethodSaveConsume(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $book_id    = decode($objRequest->getInput('book_id'));
        $c_id       = decode($objRequest->getInput('c_id'));
        //
        $item_name = $objRequest->getInput('item_name');
        $item_id   = $objRequest->getInput('item_id');
        if (!empty($c_id)) {//update
            if (!empty($item_name)) $arrayUpdate['item_name'] = $item_name;
            $arrayUpdate['item_id']        = $item_id;
            $arrayUpdate['original_price'] = $arrayUpdate['consume_price'] = $arrayUpdate['consume_price_total'] = $objRequest->getInput('money');
            $arrayUpdate['consume_title']  = $objRequest->getInput('consume_title');
            $arrayUpdate['consume_id']     = $objRequest->getInput('consume_id');
            $whereCriteria                 = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('consume_id', $c_id);
            BookingHotelServiceImpl::instance()->updateBookingConsume($whereCriteria, $arrayUpdate);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['consume_id' => $c_id, 'c_id' => encode($c_id), 'business_day' => '']);
        } elseif ($book_id > 0 && $item_id > 0) {
            $arrayInput = $objRequest->getInput();
            $money      = $objRequest->getInput('money');
            //更新房间detail
            $businessDay           = LoginServiceImpl::getBusinessDay();
            $Booking_consumeEntity = new Booking_consumeEntity($arrayInput);
            $Booking_consumeEntity->setCompanyId($company_id);
            $Booking_consumeEntity->setChannel($channel_id);
            $Booking_consumeEntity->setOriginalPrice($money);
            $Booking_consumeEntity->setConsumePrice($money);
            $Booking_consumeEntity->setConsumePriceTotal($money);
            $Booking_consumeEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
            $Booking_consumeEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
            $Booking_consumeEntity->setBusinessDay($businessDay);
            $Booking_consumeEntity->setAddDatetime(getDateTime());
            $consume_id = BookingHotelServiceImpl::instance()->saveBookingConsume($Booking_consumeEntity);
            //
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['consume_id' => $consume_id, 'c_id' => encode($consume_id), 'business_day' => $businessDay]);
        }

        $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);

    }

    //消费借物
    protected function doMethodRevokesOperations(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $revokes          = $objRequest->revokes;
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $booking_number   = $objRequest->booking_number;
        //获取channel
        $channel_id   = $objRequest->channel_id;
        $item_name    = $objRequest->item_name;
        $business_day = $objRequest->business_day;
        if ($revokes == 'consume') {//取消消费
            $c_id          = decode($objRequest->c_id);
            $consume_title = $objRequest->consume_title;
            if ($c_id > 0) {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('consume_id', $c_id);
                BookingHotelServiceImpl::instance()->updateBookingConsume($whereCriteria, ['valid' => '0']);
                $this->Booking_operationEntity->setBookingNumber($booking_number);
                $this->Booking_operationEntity->setOperationTitle('取消消费');
                $content = '取消房号:' . $item_name . ' 营业日:' . $business_day . ' 的' . $consume_title;
                $this->Booking_operationEntity->setOperationContent($content);
                BookingOperationServiceImpl::instance()->saveBookingOperation($this->Booking_operationEntity);
            }
            $this->successService->setCode(ErrorCodeConfig::$successCode['success']);
            return $objResponse->successServiceResponse($this->successService);
        }
        if ($revokes == 'account') {//取消账务
            $ba_id          = decode($objRequest->ba_id);
            $payment_name   = $objRequest->payment_name;
            $accounts_type  = $objRequest->accounts_type;
            $accounts_title = '';
            if ($accounts_type == 'receipts') $accounts_title = '收款';
            if ($accounts_type == 'refund') $accounts_title = '退款';
            if ($accounts_type == 'hanging') $accounts_title = '挂账';
            if ($accounts_type == 'pre-authorization') $accounts_title = '预授权';
            if ($ba_id > 0) {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('accounts_id', $ba_id);
                BookingHotelServiceImpl::instance()->updateBookingAccounts($whereCriteria, ['valid' => '0']);
                $this->Booking_operationEntity->setBookingNumber($booking_number);
                $this->Booking_operationEntity->setOperationTitle('取消账务');
                $content = '取消房号:' . $item_name . ' 营业日:' . $business_day . ' 的' . $payment_name . ' ， 类型:' . $accounts_title;
                $this->Booking_operationEntity->setOperationContent($content);
                BookingOperationServiceImpl::instance()->saveBookingOperation($this->Booking_operationEntity);
            }
            $this->successService->setCode(ErrorCodeConfig::$successCode['success']);
            return $objResponse->successServiceResponse($this->successService);
        }
        if ($revokes == 'borrow') {//归还借物
            $bb_id          = decode($objRequest->bb_id);
            $borrowing_name = $objRequest->borrowing_name;
            $borrowing_num  = $objRequest->borrowing_num;
            $cash_pledge    = $objRequest->cash_pledge;
            if ($bb_id > 0) {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_borrowing_id', $bb_id);
                $arrayUpdate['borrowing_return']     = '1';
                $arrayUpdate['borrowing_return_num'] = $borrowing_num;
                BookingHotelServiceImpl::instance()->updateBookingBorrowing($whereCriteria, $arrayUpdate);
                $this->Booking_operationEntity->setBookingNumber($booking_number);
                $this->Booking_operationEntity->setOperationTitle('归还借物');
                $content = '房号:' . $item_name . ' 营业日:' . $business_day . '，归还借物' . $borrowing_name
                    . ' ， 数量:' . $borrowing_num . '，押金' . $cash_pledge;
                $this->Booking_operationEntity->setOperationContent($content);
                BookingOperationServiceImpl::instance()->saveBookingOperation($this->Booking_operationEntity);
            }
            $this->successService->setCode(ErrorCodeConfig::$successCode['success']);
            return $objResponse->successServiceResponse($this->successService);
        }

        return $objResponse->successServiceResponse($this->successService);
    }

    //借物
    protected function doMethodSaveBorrowing(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id  = $objRequest->channel_id;
        $book_id     = decode($objRequest->getInput('book_id'));
        $bb_id       = decode($objRequest->getInput('bb_id'));
        $cash_pledge = $objRequest->getInput('money');
        //
        $item_name = $objRequest->getInput('item_name');
        $item_id   = $objRequest->getInput('item_id');
        if (!empty($bb_id)) {//update
            if (!empty($item_name)) $arrayUpdate['item_name'] = $item_name;
            $arrayUpdate['item_id']              = $item_id;
            $arrayUpdate['cash_pledge']          = $cash_pledge;
            $arrayUpdate['borrowing_num']        = $objRequest->getInput('borrowing_num');
            $arrayUpdate['payment_id']           = $objRequest->getInput('payment_id');
            $arrayUpdate['payment_name']         = $objRequest->getInput('payment_name');
            $arrayUpdate['borrowing_name']       = $objRequest->getInput('borrowing_name');
            $arrayUpdate['booking_borrowing_id'] = $objRequest->getInput('booking_borrowing_id');

            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_borrowing_id', $bb_id);
            BookingHotelServiceImpl::instance()->updateBookingBorrowing($whereCriteria, $arrayUpdate);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['booking_borrowing_id' => $bb_id, 'bb_id' => encode($bb_id), 'business_day' => '']);
        } elseif ($book_id > 0 && $item_id > 0) {
            $arrayInput = $objRequest->getInput();
            //开启事务
            CommonServiceImpl::instance()->startTransaction();
            //收款

            //插入数据
            $businessDay             = LoginServiceImpl::getBusinessDay();
            $Booking_borrowingEntity = new Booking_borrowingEntity($arrayInput);
            $Booking_borrowingEntity->setCompanyId($company_id);
            $Booking_borrowingEntity->setChannel($channel_id);
            $Booking_borrowingEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
            $Booking_borrowingEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
            $Booking_borrowingEntity->setCashPledge($cash_pledge);
            $Booking_borrowingEntity->setAddDatetime(getDateTime());
            $booking_borrowing_id = BookingHotelServiceImpl::instance()->saveBookingBorrowing($Booking_borrowingEntity);
            CommonServiceImpl::instance()->commit();
            //
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['booking_borrowing_id' => $booking_borrowing_id, 'bb_id' => encode($booking_borrowing_id), 'business_day' => $businessDay]);
        }

        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
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
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $bc_id      = decode($objRequest->get);
        //所有在住的，都要夜审，所有应离未离的都要夜审，所有应到未到的，都要夜审。最晚到达时间每个酒店规定不一样
        //预订数据
        $nowHour         = date("H");
        $nightAuditorDay = $nowHour > 0 && $nowHour >= 6 ? getDay() : getDay();//0~6点为当天
        if ($bc_id == -1 || empty($bc_id)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->GE('booking_detail_status', '0')->LE('check_out', $nightAuditorDay);
            $arrayBookingDetail = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
            //
            $night_date    = $objRequest->night_date;
            $business_day  = empty($night_date) ? LoginServiceImpl::getBusinessDay() : $night_date;
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('business_day', $business_day);
            $arrayConsume = BookingHotelServiceImpl::instance()->getBookingConsume($whereCriteria);
            if (!empty($arrayConsume)) {
                foreach ($arrayConsume as $i => $data) {
                    $arrayConsume[$i]['bc_id'] = encode($data['consume_id']);
                }
            }
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['nightAuditorList' => $arrayConsume, 'bookingDetailList' => $arrayBookingDetail]);
        } else {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('consume_id', $bc_id);
            $arrayUpdate['confirm']               = '1';
            $arrayUpdate['confirm_employee_id']   = $objLoginEmployee->getEmployeeId();
            $arrayUpdate['confirm_employee_name'] = $objLoginEmployee->getEmployeeName();
            $arrayUpdate['confirm_datetime']      = getDateTime();
            BookingHotelServiceImpl::instance()->updateBookingConsume($whereCriteria, $arrayUpdate);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], []);
        }

        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    //夜审过营业日
    protected function doMethodPassBusinessDay(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee   = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id         = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $default_channel_id = $objLoginEmployee->getDefaultChannelId();
        //获取channel
        $channel_id     = $objRequest->channel_id;
        $businessDay    = LoginServiceImpl::getBusinessDay();
        $successService = new \SuccessService();
        if (getDay() == $businessDay) {
            $successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
            return $objResponse->successServiceResponse($successService);
        }
        $newBusinessDay                   = date("Y-m-d", strtotime($businessDay) - 0 + 86400);
        $arrayBusinessDay['company_id']   = $company_id;
        $arrayBusinessDay['channel_id']   = $default_channel_id;
        $arrayBusinessDay['business_day'] = $newBusinessDay;
        $arrayBusinessDay['add_datetime'] = getDateTime();
        ChannelServiceImpl::instance()->saveBusinessDay($arrayBusinessDay);

        $successService->setCode(ErrorCodeConfig::$successCode['success']);
        $successService->setData(['business_day' => $newBusinessDay]);
        return $objResponse->successServiceResponse($successService);
    }

    //远期房态
    protected function doMethodRoomForcasting(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $in_date    = $objRequest->eta_date;
        $out_date   = date("Y-m-d", strtotime($in_date) - 0 + 2678400);//5184000
        //查找已住房间[远期房态]
        /*$whereCriteria = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('channel_id', $channel_id)->GT('check_in', $in_date)->LT('check_in', $out_date);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $arrayResult['roomForwardList'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria);*/

        //查找已住房间[远期房态]
        $whereCriteria = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('valid', '1')->LE('check_in', $out_date)->GE('check_out', $in_date)
            ->GE('booking_detail_status', '0')->ORDER('check_in', 'ASC');
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        $field                          = 'booking_detail_id,booking_number,channel_id,item_category_id,item_id,check_in,check_out,booking_detail_status';
        $arrayResult['roomForwardList'] = BookingHotelServiceImpl::instance()->checkBooking($whereCriteria, $field);
        $arrayBookingNumber             = [];
        if (!empty($arrayResult['roomForwardList'])) {
            foreach ($arrayResult['roomForwardList'] as $k => $arrayData) {
                $arrayBookingNumber[$arrayData['booking_number']] = $arrayData['booking_number'];
            }
        }
        $arrayResult['liveInForwardList'] = [];
        $arrayResult['bookForwardList']   = [];
        if (!empty($arrayBookingNumber)) {
            //查找住客
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1')->ArrayIN('booking_number', $arrayBookingNumber)
                ->setHashKey('booking_detail_id')->setMultiple(true);
            $arrayResult['liveInForwardList'] = BookingHotelServiceImpl::instance()->getGuestLiveIn($whereCriteria, 'booking_detail_id,member_name,item_id');
            //查找订单
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1')->ArrayIN('booking_number', $arrayBookingNumber)->setHashKey('booking_number');
            $arrayResult['bookForwardList'] = BookingHotelServiceImpl::instance()->getBooking($whereCriteria, 'member_name,booking_number');
        }

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //搜索订单
    protected function doMethodSearchBooking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;

        $condition_key  = $objRequest->getInput('condition_key');
        $search_value   = $objRequest->getInput('search_value');
        $condition_date = $objRequest->getInput('condition_date');
        $search_date    = $objRequest->getInput('search_date');
        $whereCriteria  = new \WhereCriteria();//
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel', 'Hotel')->EQ('channel_id', $channel_id)
            ->setHashKey('booking_detail_id');
        if (!empty($condition_date) && !empty($search_date)) {
            $whereCriteria->EQ($condition_date, $search_date);
        }
        if (!empty($condition_key) && !empty($search_value)) {
            $whereCriteria->EQ($condition_key, $search_value);
        }
        $arrayResult['bookingSearchList'] = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
        if (!empty($arrayResult['bookingSearchList'])) {
            foreach ($arrayResult['bookingSearchList'] as $i => $arrayData) {
                $arrayResult['bookingSearchList'][$i]['detail_id'] = encode($arrayData['booking_detail_id']);
            }
        }
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //补全订单其它显示信息
    protected function doMethodGetEditRoomBookInfo(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult      = [];
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id     = $objRequest->channel_id;
        $booking_number = $objRequest->getInput('booking_number');

        if (!empty($booking_number)) {
            //订单详情
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('channel', 'Hotel')
                ->EQ('booking_number', $booking_number)->setHashKey('booking_number');
            if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
            $arrayBookList = BookingHotelServiceImpl::instance()->getBooking($whereCriteria);
            if (!empty($arrayBookList)) {
                foreach ($arrayBookList as $booking_number => $v) {
                    $arrayBookList[$booking_number]['book_id'] = encode($booking_number);
                }
            }
            //单个订单下面的所有房间
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')
                ->EQ('booking_number', $booking_number)->setHashKey('booking_detail_id')->ORDER('check_in');
            if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
            $bookingDetailRoom = BookingHotelServiceImpl::instance()->getBookingDetailList($whereCriteria);
            if (!empty($bookingDetailRoom)) {
                foreach ($bookingDetailRoom as $detail_id => $v) {
                    $bookingDetailRoom[$detail_id]['detail_id'] = encode($v['booking_detail_id']);
                }
            }
            //入住人数
            $arrayGuestLiveIn = null;
            $whereCriteria    = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
            $whereCriteria->EQ('booking_number', $booking_number)->setHashKey('booking_number')
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
            //消费
            $arrayConsume  = null;
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('booking_number', $booking_number)->setHashKey('booking_number')
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

            //账务
            $arrayAccounts = null;
            $whereCriteria = new \WhereCriteria();//
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel', 'Hotel')->EQ('valid', '1');
            $whereCriteria->EQ('booking_number', $booking_number)->setHashKey('booking_number')->setChildrenKey('accounts_id');
            $arrayAccounts = BookingHotelServiceImpl::instance()->getBookingAccounts($whereCriteria);
            if (!empty($arrayAccounts)) {
                foreach ($arrayAccounts as $number => $value) {
                    foreach ($value as $accounts_id => $accounts) {
                        $arrayAccounts[$number][$accounts_id]['ba_id'] = encode($accounts['accounts_id']);
                    }
                }
            }
            $arrayResult = ['book' => $arrayBookList, 'consume' => $arrayConsume, 'accounts' => $arrayAccounts, 'guestLiveIn' => $arrayGuestLiveIn, 'detailRoom' => $bookingDetailRoom];
        }

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //查看日志
    protected function doMethodGetBookingOperation(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id     = $objRequest->channel_id;
        $booking_number = decode($objRequest->getInput('book_id'));
        if (!empty($booking_number)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)
                ->EQ('booking_number', $booking_number);
            $arrayBookingOperation = BookingOperationServiceImpl::instance()->getBookingOperation($whereCriteria);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayBookingOperation);
        }
        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_found']);
    }
}