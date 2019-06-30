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
        if ($field == '') $field = 'channel_id,item_category_id,item_id,check_in,check_out';
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
        $booking_number   = decode($objRequest->getInput('book_id'));
        $arrayCommonData['booking_number'] = 0;
        if(!empty($booking_number) && is_numeric($booking_number)) {
            $arrayCommonData['booking_number'] = $booking_number;
        }
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
        //booking 预订人信息
        $BookingEntity = new BookingEntity($arrayAllBookData);
        $BookingEntity->setBookingNumber($arrayCommonData['booking_number']);
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
        $BookingDetailConsumeEntity->setConsumeTitle('房费');
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
                            $DetailEntity->setPriceSystemName($roomInfo['price_system_name']);
                            $arrayBookDetailList[] = $DetailEntity;
                            for ($j = 0; $j < $total_day; $j++) {
                                //消费//2018-08-
                                $consume_key         = $layout_item_id . '-' . $system_id . '-' . $i . '-' . $arrayBusinessDay[$j];
                                $DetailConsumeEntity = clone $BookingDetailConsumeEntity;
                                $DetailConsumeEntity->setItemId($_item_id);
                                $DetailConsumeEntity->setItemCategoryId($layout_item_id);
                                $DetailConsumeEntity->setItemCategoryName($roomInfo['item_category_name']);
                                $DetailConsumeEntity->setPriceSystemId($system_id);
                                $DetailConsumeEntity->setPriceSystemName($roomInfo['price_system_name']);
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
                    return $objSuccess->setSuccessService(false,'000009','价格体系为空', []);
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
                //計算價格 $arrayFormulaSystem[system_id] 公式放盘[根据上面传输数据构造出来] $arrayDirectSystemId[system_id] 手动放盘
                if (!empty($arrayFormulaSystem)) {//计算价格[公式放盘]
                    foreach ($arrayFormulaSystem as $price_system_id => $formulaData) {//$formulaData->价格体系对应的公式和名称以及适用的房型
                        if (isset($arrayPriceLayout[$formulaData['system_father_id']])) {//如果存在父价格[公式放盘需要父价格，公式放盘只有公式]
                            $formulaLayout = json_decode($formulaData['formula'], true);//那么取出公式放盘公式 formula->公式
                            foreach ($formulaData['layout_item_id'] as $layout_item_id => $item_id) {//取出对应的房型ID system_id =>[layout_id=>layout_id]
                                //判断是否存在$arrayLayoutRooms[$layout_item_id][$price_system_id] 房型所对应的价格体系ID
                                if (!isset($arrayLayoutRooms[$layout_item_id][$price_system_id])) continue;
                                if (isset($arrayPriceLayout[$formulaData['system_father_id']][$item_id])) {
                                    if (isset($formulaLayout[$channel_id . '-' . $item_id])) {
                                        $formula = $formulaLayout[$channel_id . '-' . $item_id];//取出公式
                                    } else {
                                        $formula['formula_value']        = '';
                                        $formula['formula_second_value'] = '';
                                    }
                                    //公式继承的父类价格//父价格
                                    $arrayFormulaFatherPrice = $arrayPriceLayout[$formulaData['system_father_id']][$item_id];//取出父价格
                                    foreach ($arrayFormulaFatherPrice as $monthDate => $priceDateList) {
                                        //时间矩阵月的第几天
                                        if (!isset($arrayDateMatrix[$monthDate])) continue;//在时间矩阵$arrayDateMatrix找不到则跳过
                                        $arrayThisDay = $arrayDateMatrix[$monthDate];
                                        //解析价格
                                        foreach ($arrayThisDay as $day => $price) {
                                            $insert_key = $channel_id . '-' . $price_system_id . '-' . $item_id . '-' . $monthDate . $day;
                                            //每一个price day是一个夜审价格
                                            $price_day = 'day_' . $day;
                                            $price     = $priceDateList[$price_day];
                                            if (empty($price)) {
                                                return $objSuccess->setSuccessService(false, '100001', '时间：' . substr($monthDate, 0, 8) . $day, []);
                                            }
                                            $decimalPrice = $objLoginEmployee->getChannelSetting($channel_id)->getDecimalPrice();
                                            if (!empty($formula['formula_value'])) {
                                                if ($formula['formula'] == '*') $price = bcmul($price, $formula['formula_value'], $decimalPrice);//$price * $formula['formula_value'];
                                                if ($formula['formula'] == '+') $price = bcadd($price, $formula['formula_value'], $decimalPrice);//$price + $formula['formula_value'];
                                                if ($formula['formula'] == '-') $price = bcsub($price, $formula['formula_value'], $decimalPrice);//$price - $formula['formula_value'];
                                            }
                                            if (!empty($formula['formula_second_value'])) {
                                                if ($formula['formula_second'] == '*') $price = bcmul($price, $formula['formula_value'], $decimalPrice);//$price * $formula['formula_second_value'];
                                                if ($formula['formula_second'] == '+') $price = bcadd($price, $formula['formula_value'], $decimalPrice);//$price + $formula['formula_second_value'];
                                                if ($formula['formula_second'] == '-') $price = bcsub($price, $formula['formula_value'], $decimalPrice);//$price - $formula['formula_second_value'];
                                            }
                                            //得到计算后价格
                                            $price = decimal($price, $objLoginEmployee->getChannelSetting($channel_id)->getDecimalPrice());
                                            //sprintf("%.2f", $price);//小数点后2位
                                            //$bookPrice[$insert_key]['price'] = $price;//售卖价格
                                            for ($i = 0; $i < $arrayLayoutRooms[$layout_item_id][$price_system_id]['value']; $i++) {//订相同的房间的价格
                                                //消费//2018-08-
                                                $consume_key = $layout_item_id . '-' . $price_system_id . '-' . $i . '-' . substr($monthDate, 0, 8) . $day;
                                                $BookingDetailConsumeList[$consume_key]->setOriginalPrice($price);
                                                $BookingDetailConsumeList[$consume_key]->setConsumePrice($price);
                                                $BookingDetailConsumeList[$consume_key]->setConsumePriceTotal($price);
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
                                    if (!isset($arrayDateMatrix[$monthDate])) continue;//在时间矩阵$arrayDateMatrix找不到则跳过
                                    $arrayThisDay = $arrayDateMatrix[$monthDate];
                                    //解析价格
                                    foreach ($arrayThisDay as $day => $price) {
                                        $insert_key = $channel_id . '-' . $system_id . '-' . $layout_item_id . '-' . $monthDate . $day;
                                        //每一个price day是一个夜审价格
                                        $price_day                       = 'day_' . $day;
                                        $price                           = $priceDateList[$price_day];
                                        if (empty($price)) {
                                            return $objSuccess->setSuccessService(false, '100001', '时间：' . substr($monthDate, 0, 8) . $day, []);
                                        }
                                        $bookPrice[$insert_key]['price'] = $price;//售卖价格
                                        for ($i = 0; $i < $arrayLayoutRooms[$layout_item_id][$system_id]['value']; $i++) {
                                            //2018-08-
                                            $consume_key = $layout_item_id . '-' . $system_id . '-' . $i . '-' . substr($monthDate, 0, 8) . $day;
                                            $BookingDetailConsumeList[$consume_key]->setOriginalPrice($price);
                                            $BookingDetailConsumeList[$consume_key]->setConsumePrice($price);
                                            $BookingDetailConsumeList[$consume_key]->setConsumePriceTotal($price);
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
        $objSuccess = new \SuccessService();
        $bookingEntity = $BookingData->getBookingEntity();
        $bookDetailList = $BookingData->getBookDetailList();
        $bookingDetailConsumeList = $BookingData->getBookingDetailConsumeList();
        $bookingNumber = $bookingEntity->getBookingNumber();
        if (empty($bookingNumber)) {
            $booking_id = BookingDao::instance()->saveBooking($bookingEntity);
            $bookingNumber = BookingUtil::instanct()->getBookingNumber($booking_id);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('booking_id', $booking_id);
            BookingDao::instance()->updateBooking($whereCriteria, ['booking_number' => $bookingNumber]);
        }
        $objSuccess->setData(['booking_number'=>$bookingNumber]);
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
            $whereCriteria->LE('booking_detail_id', $_max_detail_id)->EQ('channel_id', $channel_id)->LE('check_in', $check_out)->GE('check_out', $check_in)
                ->EQ('valid', '1')->GE('booking_detail_status', '0');
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

    public function closeBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService {
        // TODO: Implement closeBooking() method.
        $objSuccess               = new \SuccessService();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        //获取channel
        $channel_id = $objRequest->channel_id;
        $closeType = $objRequest->getInput('closeType');
        //
        $booking_number = decode($objRequest->getInput('book_id'));

        if ($booking_number > 0) {
            $arrayInput = $objRequest->getInput();
            //取得所有未退房房间
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1')
                ->GE('booking_detail_status', '0')->EQ('booking_number', $booking_number)->setHashKey('item_id');
            $arrayLiveInRoom = $this->getBookingDetailList($whereCriteria, 'item_id');
            if(empty($arrayLiveInRoom)) {
                $objSuccess->setSuccess(false);
                $objSuccess->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
                return $objSuccess;
            }
            $arrayLiveInRoomId = array_keys($arrayLiveInRoom);
            //计算消费
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
            $arrayConsume = $this->getBookingConsume($whereCriteria);
            $totalConsume = 0;
            if(!empty($arrayConsume)) {
                foreach ($arrayConsume as $i => $consume) {
                    $totalConsume = bcadd($consume['consume_price_total'], $totalConsume, 2);
                }
            }
            //计算账务
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
            $arrayAccounts = $this->getBookingAccounts($whereCriteria);
            $totalAccounts = 0;
            if(!empty($arrayAccounts)) {
                foreach ($arrayAccounts as $i => $account) {
                    if($account['accounts_type'] == 'receipts') $totalAccounts = bcadd($account['money'], $totalAccounts, 2);
                    if($account['accounts_type'] == 'refund') $totalAccounts = bcsub($totalAccounts, $account['money'], 2);
                    if($account['accounts_type'] == 'hanging') $totalAccounts = bcadd($account['money'], $totalAccounts, 2);
                }
            }
            if($totalConsume != $totalAccounts && $closeType != 'escape') {//$totalConsume == 0 || $totalAccounts == 0 || 走结无需平账
                $objSuccess->setNotice(true);
                $objSuccess->setCode(ErrorCodeConfig::$notice['no_equal_account']);
                $objSuccess->setData([bcsub($totalAccounts, $totalConsume, 2)]);
                return $objSuccess;
            }
            CommonServiceImpl::instance()->startTransaction();
            //取消订单
            if($closeType != 'cancel') {
                //结账退房//更新数据
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->ArrayIN('item_id', $arrayLiveInRoomId);
                $updateData['booking_number'] = '';//取消关联ID
                $updateData['clean'] = 'dirty';//设置脏房
                $updateData['status'] = '0';//取消入住状态
                ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $updateData);
            }
            //设置预订完成
            $updateData = [];
            $updateData['booking_status'] = '-1';//[-1结束 已完成] 设置booking
            if($closeType == 'cancel') $updateData['booking_status'] = '-2';//[-2结束 取消订单] 设置booking
            if($closeType == 'hanging') $updateData['booking_status'] = '-4';//[-4 挂账退房订单]
            if($closeType == 'escape') $updateData['booking_status'] = '-5';//[-5 走结退房订单]
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_number', $booking_number);
            $this->updateBooking($whereCriteria, $updateData);
            //
            $updateData = [];
            $updateData['booking_detail_status'] = '-1';//[-1退房]
            if($closeType == 'cancel') $updateData['booking_detail_status'] = '-2';//[-2 取消订单]
            if($closeType == 'hanging') $updateData['booking_detail_status'] = '-4';//[-4 挂账退房订单]
            if($closeType == 'escape') $updateData['booking_detail_status'] = '-5';//[-5 走结退房订单]
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('booking_number', $booking_number)->EQ('valid', '1');
            $this->updateBookingDetail($whereCriteria, $updateData);
            CommonServiceImpl::instance()->commit();
            return $objSuccess;
        }
        return $objSuccess;

    }

    public function getBookingDetailEntity(\WhereCriteria $whereCriteria) {
        return BookingDao::instance()->getBookingDetailEntity($whereCriteria);
    }

    public function getBookingDetailList(\WhereCriteria $whereCriteria, $field = '*') {
        return BookingDao::instance()->getBookingDetailList($whereCriteria, $field);
    }

    public function updateBookingDetail(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateBookingDetail($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function updateBooking($whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateBooking($whereCriteria, $arrayUpdateData, $update_type);
    }
    //取得消费记录
    public function getBookingConsume(\WhereCriteria $whereCriteria, $field = '*') {
        return BookingDao::instance()->getBookingConsume($whereCriteria, $field);
    }

    public function saveBookingConsume(Booking_consumeEntity $Booking_consumeEntity) {
        return BookingDao::instance()->saveBookingConsume($Booking_consumeEntity);
    }

    public function updateBookingConsume($whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateBookingConsume($whereCriteria, $arrayUpdateData, $update_type);
    }
    //取得借物
    public function getBookingBorrowing(\WhereCriteria $whereCriteria, $field = '*') {
        return BookingDao::instance()->getBookingBorrowing($whereCriteria, $field);
    }

    public function saveBookingBorrowing(Booking_borrowingEntity $Booking_borrowingEntity) {
        return BookingDao::instance()->saveBookingBorrowing($Booking_borrowingEntity);
    }

    public function updateBookingBorrowing($whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateBookingBorrowing($whereCriteria, $arrayUpdateData, $update_type);
    }
    //取得账务
    public function getBookingAccounts(\WhereCriteria $whereCriteria, $field = '*') {
        return BookingDao::instance()->getBookingAccounts($whereCriteria, $field);
    }

    public function updateBookingAccounts($whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateBookingAccounts($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function saveBookingAccounts(Booking_accountsEntity $Booking_accountsEntity) {
        return BookingDao::instance()->saveBookingAccounts($Booking_accountsEntity);
    }
    //入住信息
    public function saveGuestLiveIn(Booking_live_inEntity $Booking_live_inEntity) {
        return BookingDao::instance()->saveGuestLiveIn($Booking_live_inEntity);
    }

    public function updateGuestLiveIn(\WhereCriteria $whereCriteria, $updateData) {
        return BookingDao::instance()->updateGuestLiveIn($whereCriteria, $updateData);
    }

    public function getGuestLiveIn(\WhereCriteria $whereCriteria, $field = null) : array {
        return BookingDao::instance()->getGuestLiveIn($whereCriteria, $field);
    }

    //房间状态操作
    public function saveRoomEven(Booking_evenEntity $booking_evenEntity) {
        return BookingDao::instance()->saveRoomEven($booking_evenEntity);
    }

    public function updateRoomEven($whereCriteria, $arrayUpdateData, $update_type = '') {
        return BookingDao::instance()->updateRoomEven($whereCriteria, $arrayUpdateData, $update_type);
    }
}

