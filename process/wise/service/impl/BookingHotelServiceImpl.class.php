<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class BookingHotelServiceImpl extends \BaseServiceImpl implements BookingService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookingHotelServiceImpl();

        return self::$objService;
    }

    public function getBooking(\WhereCriteria $whereCriteria, $field = null) {
        return BookingDao::instance()->getBooking($whereCriteria, $field);
    }

    public function checkBooking(\WhereCriteria $whereCriteria, $field = null) {
        if ($field == '') $field = 'channel_id,item_id,check_in,check_out';
        return BookingDao::instance()->getBookingDetailList($whereCriteria, $field);
    }

    public function getBookingDetailEntity(\WhereCriteria $whereCriteria) {
        return BookingDao::instance()->getBookingDetailEntity($whereCriteria);
    }

    public function getBookingDetailList(\WhereCriteria $whereCriteria, $field = '*') {
        return BookingDao::instance()->getBookingDetailList($whereCriteria, $field);
    }


    /*
     * booking_room 数据结构 单个时间段订房
     * [channel_id => layout_item_id => system_id => room_info]
     */
    public function beginBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService {
        $objSuccess       = new \SuccessService();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee();
        $objEmployee      = $objLoginEmployee->getEmployeeInfo();
        $company_id       = $objEmployee->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        $arrayInput       = $objRequest->getInput();
        $check_in         = $objRequest->validInput('check_in');
        $check_out        = $objRequest->validInput('check_out');
        $in_time          = $objRequest->validInput('in_time');
        $out_time         = $objRequest->validInput('out_time');
        //mobile_email
        $mobile_email = $objRequest->validInput('mobile_email');
        if(strlen($mobile_email) == 11 && is_numeric($mobile_email)) {//mobile
            $arrayCommonData['member_mobile'] = $mobile_email;
        } elseif (strpos($mobile_email,'@') !== false) {
            $arrayCommonData['member_email'] = $mobile_email;
        }
        //
        $channel_father_id = $objRequest->validInput('channel_father_id');
        $arrayBookingData  = $objRequest->validInput('booking_data');
        if ($channel_father_id === false || $arrayBookingData === false) {
            return $objSuccess->setSuccessService(false, ErrorCodeConfig::$errorCode['parameter_error'], '缺失多个参数');
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
        //预订人信息
        $BookingEntity = new BookingEntity($arrayAllBookData);
        $BookingEntity->setBookingNumber(0);
        $BookingEntity->setBookingStatus('0');//初始状态为 0
        $BookingEntity->setBusinessDay($objResponse->business_day);
        $BookingEntity->setBookingTotalPrice(0);
        $BookingEntity->setInTime($in_time);
        $BookingEntity->setOutTime($out_time);
        //每一间房
        $arrayBookDetailList = array();
        $BookingDetailEntity = new Booking_detailEntity($arrayAllBookData);
        $BookingDetailEntity->setBusinessDay($objResponse->business_day);
        $BookingDetailEntity->setBookingDetailStatus('0');
        $BookingDetailEntity->setInTime($in_time);
        $BookingDetailEntity->setOutTime($out_time);
        //每一间房消费
        $BookingDetailConsumeList   = array();
        $BookingDetailConsumeEntity = new Booking_consumeEntity($arrayAllBookData);
        //判断会员级别
        //
        //预订数据 每个房间1个BookingDetai，每个房间每天1个BookingConsume
        if (!empty($arrayBookingData)) {
            //时间矩阵
            $arrayDateMatrix  = [];
            $startDateTime    = strtotime($check_in); //转换一下
            $endDateTime      = strtotime($check_out); //转换一下
            $total_day        = ($endDateTime - $startDateTime) / 86400;
            $arrayBusinessDay = array();
            for ($i = $startDateTime; $i < $endDateTime; $i += 86400) {
                $businessDay                       = date("Y-m-d", $i);
                $arrayBusinessDay[]                = $businessDay;
                $day                               = substr($businessDay, 8, 2);
                $monthDate                         = substr($businessDay, 0, 8) . '01';
                $arrayDateMatrix[$monthDate][$day] = '0';
            }
            $arraySystemId    = [];//有效system_id
            $systemLayoutItem = [];//layout
            $arrayLayoutRooms = [];//有效的房型房间
            //查找过滤有效的价格体系
            $_item_key = substr(getMicrotime(), 2);//构造虚拟item_id
            foreach ($arrayBookingData as $dateKey => $arrayChannelData) {
                if(empty($arrayChannelData['booking_room'])) continue;//
                foreach ($arrayChannelData['booking_room'][$channel_id] as $layout_item_id => $systemData) {//组合价格体系
                    foreach ($systemData as $system_id => $roomData) {
                        if (empty($roomData['value'])) continue;//房间数量为0 也直接跳过
                        $arrayLayoutRooms[$layout_item_id][$system_id]['value'] = $roomData['value'];//预订的价格体系的房型数量
                        //有效system_id
                        $arraySystemId[$system_id] = $system_id;//价格体系
                        if (isset($systemLayoutItem[$system_id])) {
                            $systemLayoutItem[$system_id]['item_ids'] .= ',' . $layout_item_id;
                        } else {
                            $systemLayoutItem[$system_id]['item_ids'] = $layout_item_id;
                        }
                        $systemLayoutItem[$system_id]['layout_item_id'][$layout_item_id] = $layout_item_id;
                        //预订的房型房子
                        $roomInfo = $roomData['room_info'];
                        for ($i = 0; $i < $roomData['value']; $i++) {
                            $_item_key++;
                            $_item_id     = '-' . $_item_key;
                            $DetailEntity = clone $BookingDetailEntity;
                            $DetailEntity->setItemId($_item_id);//
                            $DetailEntity->setItemName('');
                            $DetailEntity->setItemCategoryId($layout_item_id);
                            $DetailEntity->setItemCategoryName($roomInfo['item_category_name']);
                            $DetailEntity->setPriceSystemId($system_id);
                            $DetailEntity->setPriceSystemName($roomInfo['item_category_name']);
                            $arrayBookDetailList[] = $DetailEntity;
                            for ($j = 0; $j < $total_day; $j++) {
                                //消费//2018-08-
                                $consume_key         = $system_id . '-' . $i . '-' . $arrayBusinessDay[$j];
                                $DetailConsumeEntity = clone $BookingDetailConsumeEntity;
                                $DetailConsumeEntity->setItemId($_item_id);
                                $DetailConsumeEntity->setPriceSystemId($system_id);
                                $DetailConsumeEntity->setPriceSystemName('');
                                $DetailConsumeEntity->setBusinessDay($arrayBusinessDay[$j]);
                                $BookingDetailConsumeList[$consume_key] = $DetailConsumeEntity;
                            }
                        }
                    }
                }
            }
            if (empty($arraySystemId)) {
                return $objSuccess->setSuccessService(false, ErrorCodeConfig::$errorCode['parameter_error'], '没有价格体系', []);
            }
            //
            $bookData['item_category_id']   = '';
            $bookData['item_category_name'] = '';
            $bookData['price_system_id']    = '';
            $bookData['price_system_name']  = '';

            if (!empty($arraySystemId)) {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('valid', '1')->ArrayIN('price_system_id', $arraySystemId);
                $field = 'price_system_id,price_system_name,price_system_father_id,book_min_day,cancellation_policy,price_system_type,formula';
                //查找价格体系
                $arrayPriceSystem = ChannelServiceImpl::instance()->getLayoutPriceSystem($whereCriteria, $field);
                if (empty($arrayPriceSystem)) {
                    return $objSuccess->setSuccessService(false, '000009', '找不到价格体系', []);
                }
                $arrayDirectSystemId = [];//手动放盘价格体系ID
                $arrayFormulaSystem  = [];//公式放盘价格体系ID

                foreach ($arrayPriceSystem as $i => $priceSystem) {
                    if ($priceSystem['price_system_type'] == 'formula') {//公式放盘
                        $arrayDirectSystemId[$priceSystem['price_system_father_id']]              = $priceSystem['price_system_father_id'];
                        $arrayFormulaSystem[$priceSystem['price_system_id']]['price_system_name'] = $priceSystem['price_system_name'];
                        $arrayFormulaSystem[$priceSystem['price_system_id']]['formula']           = $priceSystem['formula'];
                        //父价格[父价格设置相应的layout_item才行]
                        $arrayFormulaSystem[$priceSystem['price_system_id']]['system_father_id'] = $priceSystem['price_system_father_id'];
                        $arrayFormulaSystem[$priceSystem['price_system_id']]['layout_item_id']   = $systemLayoutItem[$priceSystem['price_system_id']]['layout_item_id'];
                        if (isset($systemLayoutItem[$priceSystem['price_system_father_id']])) {
                            $systemLayoutItem[$priceSystem['price_system_father_id']]['item_ids'] .= ',' . $systemLayoutItem[$priceSystem['price_system_id']]['item_ids'];
                        } else {
                            $systemLayoutItem[$priceSystem['price_system_father_id']]['item_ids'] = $systemLayoutItem[$priceSystem['price_system_id']]['item_ids'];
                        }
                    } else {//手动放盘
                        $arrayDirectSystemId[$priceSystem['price_system_id']] = $priceSystem['price_system_id'];
                    }
                }

                if (empty($arrayDirectSystemId)) {
                    return $objSuccess->setSuccessService(false, '000009', '价格体系为空', []);
                }
                //{begin} 根据手动放盘价格体系[$arrayDirectSystemId]查找房型房价 父价格 组合system 和 item_id
                $whereSqlStr = '';
                $or          = '';//
                foreach ($arrayDirectSystemId as $system_id => $s_id) {
                    $layout_item_ids = $systemLayoutItem[$system_id]['item_ids'];
                    $whereSqlStr     .= $or . '(price_system_id = ' . $system_id . ' AND item_id IN(' . $layout_item_ids . '))';
                }
                if (!empty($whereSqlStr)) $whereSqlStr = ' (' . $whereSqlStr . ')';
                $arrayHashKey['hashKey']     = 'price_system_id';
                $arrayHashKey['fatherKey']   = 'item_id';
                $arrayHashKey['childrenKey'] = 'layout_price_date';
                //根据价格体系ID取出價格 包含子价格和父价格
                $arrayPriceLayout = ChannelServiceImpl::instance()->getLayoutPrice(null, $channel_id, null, $check_in,
                    $check_out, $whereSqlStr, $arrayHashKey);
                if (empty($arrayPriceLayout)) {
                    return $objSuccess->setSuccessService(false, '100001', '价格体系没有设置价格', []);
                }

                //計算價格
                if (!empty($arrayFormulaSystem)) {//计算价格[公式放盘]
                    foreach ($arrayFormulaSystem as $price_system_id => $formulaData) {
                        //父价格
                        if (isset($arrayPriceLayout[$formulaData['system_father_id']])) {
                            $formulaLayout = json_decode($arrayFormulaSystem[$price_system_id]['formula'], true);//公式
                            foreach ($formulaData['layout_item_id'] as $layout_item_id => $item_id) {
                                //判断是否存在$arrayLayoutRooms[$layout_item_id][$system_id]
                                if (!isset($arrayLayoutRooms[$layout_item_id][$system_id])) continue;
                                if (isset($arrayPriceLayout[$formulaData['system_father_id']][$item_id])) {
                                    if (isset($formulaLayout[$channel_id . '-' . $item_id])) {
                                        $formula = $formulaLayout[$channel_id . '-' . $item_id];
                                    } else {
                                        $formula['formula_value']        = '';
                                        $formula['formula_second_value'] = '';
                                    }
                                    //公式继承的父类价格
                                    $arrayFormulaFatherPrice = $arrayPriceLayout[$formulaData['system_father_id']][$item_id];
                                    foreach ($arrayFormulaFatherPrice as $monthDate => $priceDateList) {
                                        //时间矩阵月的第几天
                                        $arrayThisDay = $arrayDateMatrix[$monthDate];
                                        //解析价格
                                        foreach ($arrayThisDay as $day => $price) {
                                            $insert_key = $channel_id . '-' . $price_system_id . '-' . $item_id . '-' . $monthDate . $day;
                                            //每一个price day是一个夜审价格
                                            $price_day = 'day_' . $day;
                                            $price     = $priceDateList[$price_day];
                                            if (empty($price)) {
                                                return $objSuccess->setSuccessService(false, '100001', '时间:' . substr($arrayThisDay, 0, 8) . $price_day, []);
                                            }
                                            if (!empty($formula['formula_value'])) {
                                                if ($formula['formula'] == '*') $price = $price * $formula['formula_value'];
                                                if ($formula['formula'] == '+') $price = $price + $formula['formula_value'];
                                                if ($formula['formula'] == '-') $price = $price - $formula['formula_value'];
                                            }
                                            if (!empty($formula['formula_second_value'])) {
                                                if ($formula['formula_second'] == '*') $price = $price * $formula['formula_second_value'];
                                                if ($formula['formula_second'] == '+') $price = $price + $formula['formula_second_value'];
                                                if ($formula['formula_second'] == '-') $price = $price - $formula['formula_second_value'];
                                            }
                                            //得到计算后价格
                                            $price = decimal($price, $objLoginEmployee->getChannelSetting($channel_id)->getDecimalPrice());
                                            //sprintf("%.2f", $price);//小数点后2位
                                            //$bookPrice[$insert_key]['price'] = $price;//售卖价格
                                            for ($i = 0; $i < $arrayLayoutRooms[$layout_item_id][$system_id]['value']; $i++) {
                                                //消费//2018-08-
                                                $consume_key = $system_id . '-' . $i . '-' . substr($monthDate, 0, 8) . $day;
                                                $BookingDetailConsumeList[$consume_key]->setOriginalPrice($price);
                                                $BookingDetailConsumeList[$consume_key]->setConsumePrice($price);
                                            }
                                        }
                                    }
                                } else {//如果找不到说明没有价格返回错误。
                                    return $objSuccess->setSuccessService(false, '100001', $formulaData['price_system_name'], []);
                                }
                            }
                        } else {//如果找不到说明没有价格返回错误。
                            return $objSuccess->setSuccessService(false, '100001', $formulaData['price_system_name'], []);
                        }
                        //$fatherPrice = $arrayPriceLayout[$formulaData['system_father_id']];
                        //计算本体系价格
                    }
                } else {//手动放盘价格
                    foreach ($arraySystemId as $system_id => $v) {
                        //有效的价格体系
                        if (isset($arrayPriceLayout[$system_id])) {
                            $arraySystemPrice = $arrayPriceLayout[$system_id];
                        } elseif ((isset($arrayPriceLayout[$arrayFormulaSystem[$system_id]['system_father_id']]))) {
                            $arraySystemPrice = $arrayPriceLayout[$arrayFormulaSystem[$system_id]['system_father_id']];
                        } else {
                            return $objSuccess->setSuccessService(false, '100001', '没有找到价格体系', []);
                        }
                        foreach ($arraySystemPrice as $layout_item_id => $arrayMonthPrice) {
                            //判断是否存在$arrayLayoutRooms[$layout_item_id][$system_id]
                            if (!isset($arrayLayoutRooms[$layout_item_id][$system_id])) continue;
                            if (isset($systemLayoutItem[$system_id]['layout_item_id'][$layout_item_id])) {
                                foreach ($arrayMonthPrice as $monthDate => $priceDateList) {
                                    //时间矩阵月的第几天
                                    $arrayThisDay = $arrayDateMatrix[$monthDate];
                                    //解析价格
                                    foreach ($arrayThisDay as $day => $price) {
                                        $insert_key = $channel_id . '-' . $system_id . '-' . $layout_item_id . '-' . $monthDate . $day;
                                        //每一个price day是一个夜审价格
                                        $price_day                       = 'day_' . $day;
                                        $price                           = $priceDateList[$price_day];
                                        $bookPrice[$insert_key]['price'] = $price;//售卖价格
                                        for ($i = 0; $i < $arrayLayoutRooms[$layout_item_id][$system_id]['value']; $i++) {
                                            //2018-08-
                                            $consume_key = $system_id . '-' . $i . '-' . substr($monthDate, 0, 8) . $day;
                                            $BookingDetailConsumeList[$consume_key]->setOriginalPrice($price);
                                            $BookingDetailConsumeList[$consume_key]->setConsumePrice($price);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $BookingData = new BookingDataModel();
                $BookingData->setBookingEntity($BookingEntity);
                $BookingData->setBookDetailList($arrayBookDetailList);
                $BookingData->setBookingDetailConsumeList($BookingDetailConsumeList);
                return $objSuccess->setSuccessService(true, ErrorCodeConfig::$successCode['success'], '', $BookingData);
            }
        }

        //返回参数错误
        return $objSuccess->setSuccessService(false, ErrorCodeConfig::$errorCode['parameter_error'], '没有取到预订数据', []);
    }

    public function saveBooking(BookingDataModel $BookingData): \SuccessService {
        CommonServiceImpl::instance()->startTransaction();
        $objSuccess               = new \SuccessService();
        $bookingEntity            = $BookingData->getBookingEntity();
        $bookDetailList           = $BookingData->getBookDetailList();
        $bookingDetailConsumeList = $BookingData->getBookingDetailConsumeList();
        $booking_id               = BookingDao::instance()->saveBooking($bookingEntity);
        $bookingNumber            = BookingUtil::instanct()->getBookingNumber($booking_id);
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('booking_id', $booking_id);
        BookingDao::instance()->updateBooking($whereCriteria, ['booking_number'=>$bookingNumber]);
        foreach ($bookDetailList as $k => $bookDetail) {
            $bookDetailList[$k]->setBookingNumber($bookingNumber);
        }
        $arrayBookDetailId = BookingDao::instance()->saveBookingDetailList($bookDetailList);
        if (!empty($arrayBookDetailId)) {
            $_max_detail_id = 0;
            foreach ($bookingDetailConsumeList as $k => $bookDetailConsume) {
                $_item_id   = $bookDetailConsume->getItemId();
                $_detail_id = $arrayBookDetailId[$_item_id];
                $bookingDetailConsumeList[$k]->setBookingDetailId($_detail_id);
                $bookingDetailConsumeList[$k]->setBookingNumber($bookingNumber);
                $_max_detail_id = $_max_detail_id < $_detail_id ? $_detail_id : $_max_detail_id;
            }
            BookingDao::instance()->saveBookingDetailConsumeList($bookingDetailConsumeList);
            //超订检查
            $channel_id    = $bookingEntity->getChannelId();
            $check_in      = $bookingEntity->getCheckIn();
            $check_out     = $bookingEntity->getCheckOut();
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->LE('booking_detail_id', $_max_detail_id)->EQ('channel_id', $channel_id);
            $whereCriteria->LE('check_in', $check_out)->GE('check_out', $check_in);
            //已定房间
            $arrayHaveBook = BookingDao::instance()->getBookingDetailList($whereCriteria, 'item_id, item_category_id, check_in, check_out, price_system_id');
            //查找现有房间数
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('attr_type', 'multipe_room')->EQ('channel_id', $channel_id);
            //$whereCriteria->setHashKey('category_item_id');
            $field = 'channel_id,category_item_id,item_id,attr_type';
            //$hashKey = 'channel_id';
            $whereCriteria->setHashKey('category_item_id')->setMultiple(false)->setChildrenKey('item_id');
            $layoutRoom = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
            //
            $totalDay      = (strtotime($check_out) - strtotime($check_in)) / 86400;
            $matrixRoomDay = [];
            if (!empty($layoutRoom)) {
                foreach ($layoutRoom as $category_id => $value) {
                    $layoutRoom[$category_id]['room_num'] = count($value);
                    //矩阵化
                    $matrixDay = $check_in;
                    for ($i = 0; $i < $totalDay; $i++) {
                        if ($i > 0) $matrixDay = date("Y-m-d", strtotime("$matrixDay +24 HOUR"));
                        $matrixRoomDay[$matrixDay][$category_id] = $layoutRoom[$category_id]['room_num'];
                    }
                }
            }
            //计算超定
            $isOverBook    = false;
            $overBook      = array();//超定
            $overI         = 0;
            $arrayLockRoom = null;
            foreach ($arrayHaveBook as $i => $arrayBookRoomLayout) {
                foreach ($matrixRoomDay as $matrixDay => $LayoutRoomNum) {
                    if (isset($arrayLockRoom[$matrixDay][$arrayBookRoomLayout['item_category_id']])) {
                        //锁房、维修房
                        $matrixRoomDay[$matrixDay][$arrayBookRoomLayout['item_category_id']] -= $arrayLockRoom[$matrixDay][$arrayBookRoomLayout['item_category_id']];//全日房状态
                        unset($arrayLockRoom[$matrixDay][$arrayBookRoomLayout['item_category_id']]);
                    }
                    $nCin  = substr($arrayBookRoomLayout['check_in'], 0, 10);
                    $nCout = substr($arrayBookRoomLayout['check_out'], 0, 10);
                    if ($nCin <= $matrixDay && $matrixDay < $nCout && $nCout != $matrixDay) {
                        $matrixRoomDay[$matrixDay][$arrayBookRoomLayout['item_category_id']]--;//全日房状态
                    }
                    if ($nCin == $nCout) {
                        $matrixRoomDay[$matrixDay][$arrayBookRoomLayout['item_category_id']]--;
                    }
                    if ($matrixRoomDay[$matrixDay][$arrayBookRoomLayout['item_category_id']] < 0) {
                        $isOverBook                           = true;
                        $overBook[$overI]['price_system_id']  = $arrayBookRoomLayout['price_system_id'];
                        $overBook[$overI]['item_category_id'] = $arrayBookRoomLayout['item_category_id'];
                        $overBook[$overI]['over_num']         = $matrixRoomDay[$matrixDay][$arrayBookRoomLayout['item_category_id']];
                        $overBook[$overI]['day']              = $matrixDay;
                        $overI++;
                    }
                }
            }
            if($isOverBook) {
                CommonServiceImpl::instance()->rollback();
                $objSuccess->setSuccess(false);
                $objSuccess->setCode(ErrorCodeConfig::$errorCode['over_booking']);
            } else {
                CommonServiceImpl::instance()->commit();
            }
        }

        return $objSuccess;
    }
}

