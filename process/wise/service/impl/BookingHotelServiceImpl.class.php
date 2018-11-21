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
        return BookingDao::instance()->getBookingDetail($whereCriteria, $field);
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
        $channel_id       = $objRequest->id;
        $channel_id       = !empty($channel_id) ? \Encrypt::instance()->decode($channel_id, getDay()) : '';
        $arrayInput       = $objRequest->getInput();
        $check_in         = $objRequest->validInput('check_in');
        $check_out        = $objRequest->validInput('check_out');

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
        $BookingEntity->setBookNumber(0);
        $BookingEntity->setBookingStatus('0');//初始状态为 0
        $BookingEntity->setBusinessDay($objResponse->business_day);
        $BookingEntity->setBookingTotalPrice(0);
        //每一间房
        $arrayBookDetailList = array();
        $BookingDetailEntity = new Booking_detailEntity($arrayAllBookData);
        $BookingDetailEntity->setBusinessDay($objResponse->business_day);
        $BookingDetailEntity->setBookingDetailStatus('0');
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
            foreach ($arrayBookingData as $dateKey => $arrayChannelData) {
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
                        for ($i = 0; $i < $roomData['value']; $i++) {
                            $BookingDetailEntity->setItemId(0);//
                            $BookingDetailEntity->setItemName('');
                            $BookingDetailEntity->setItemCategoryId($layout_item_id);
                            $BookingDetailEntity->setItemCategoryName('');
                            $BookingDetailEntity->setPriceSystemId($system_id);
                            $BookingDetailEntity->setPriceSystemName('');
                            $arrayBookDetailList[] = $BookingDetailEntity;
                            for ($j = 0; $j < $total_day; $j++) {
                                //消费//2018-08-
                                $consume_key = $system_id . '-' . $i . '-' . $arrayBusinessDay[$j];
                                $BookingDetailConsumeEntity->setPriceSystemId($system_id);
                                $BookingDetailConsumeEntity->setPriceSystemName('');
                                $BookingDetailConsumeEntity->setBusinessDay($arrayBusinessDay[$j]);
                                $BookingDetailConsumeList[$consume_key] = $BookingDetailConsumeEntity;
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
        $bookingEntity = $BookingData->getBookingEntity();
        $bookDetailList = $BookingData->getBookDetailList();
        $bookingDetailConsumeList = $BookingData->getBookingDetailConsumeList();
        $insert_id = BookingDao::instance()->saveBooking($bookingEntity);
        BookingDao::instance()->saveBookingDetailList($bookDetailList);
        BookingDao::instance()->saveBookingDetailConsumeList($bookingDetailConsumeList);
        $ojbSuccess = new \SuccessService();
        return $ojbSuccess;
    }
}

