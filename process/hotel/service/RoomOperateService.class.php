<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;

class RoomOperateService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new RoomOperateService();
        return self::$objService;
    }

    public function rollback() {
        RoomDao::instance()->rollback();
    }

    public function saveRoomLayoutPriceWeek($objRequest, $objResponse) {
        $room_layout_corp_id = decode($objRequest -> layout_corp_id);
        $room_layout_corp_id = empty($room_layout_corp_id) ? '0' : $room_layout_corp_id;
        $arrayPostValue= $objRequest->getPost();
        $arrayTimeBegin = explode('-', $arrayPostValue['time_begin']);
        $arrayTimeBegin['1'] = $arrayTimeBegin['1'] - 0;
        $arrayTimeEnd = explode('-', $arrayPostValue['time_end']);
        $arrayTimeEnd['1'] = $arrayTimeEnd['1'] - 0;
        //$room_layout_id = $objRequest -> room_layout;
        $room_sell_layout_id = $objRequest -> sell_layout;
        $room_layout_price_system_id = $objRequest -> system_id;
        $thisYear = getYear();
        $thisMonth = getMonth();

        $extra_bed_price = $objRequest -> extra_bed_price;
        $arrayExtraBed = $objRequest->extra_bed;
        $extraBed = false;
        if(!empty($arrayExtraBed) && is_array($arrayExtraBed)) {
            $extraBed = true;
        }
        //只允许2年内的价格
        if($arrayTimeBegin[0] > $arrayTimeEnd[0] || $arrayTimeBegin[0] < $thisYear || ($arrayTimeBegin[0] - $thisYear) > 1) {
            return array(0, '选择的年份不对！');
        }
        if($arrayTimeBegin[0] == $thisYear && $thisMonth > $arrayTimeBegin[1]) {
            return array(0, '选择的年份不对！');
        }
        if(empty($room_sell_layout_id) || empty($room_layout_price_system_id)) {
            return array(0, '传输数据有误！');
        }
        //
        $beginWeek = date("N", strtotime($arrayPostValue['time_begin']));
        $beginT = date("t", strtotime($arrayPostValue['time_begin']));
        $arrayMonthData = array();
        $arrayMonthExtraBedData = array();
        $isTheSameYear = false;
        $isTheSameMonth = false;
        if($arrayTimeBegin[0] == $arrayTimeEnd[0]) {
            $isTheSameYear = true;
        }
        if($isTheSameYear == true && $arrayTimeBegin[1] == $arrayTimeEnd[1]) {
            $isTheSameMonth = true;//相同的年月
            $beginT = $arrayTimeEnd[2];
        }
        $i = $arrayTimeBegin[2] + 0;
        for($i; $i <= $beginT; $i++) {
            if($beginWeek > 7) $beginWeek = $beginWeek - 7;
            $day = $i < 10 ? '0' . $i : $i;
            if(trim($arrayPostValue['week_' . $beginWeek]) != '')
                $arrayMonthData[$day . '_day'] = $arrayPostValue['week_' . $beginWeek];
            if(!empty($extra_bed_price)) {
                $arrayMonthExtraBedData[$day . '_day'] = $extra_bed_price;
            }
            if($extraBed) {
                if(trim($arrayExtraBed['week_' . $beginWeek]) != '')
                    $arrayMonthExtraBedData[$day . '_day'] = $arrayExtraBed['week_' . $beginWeek];
            }
            $beginWeek++;
        }
        //print_r($arrayMonthData);
        //
        $conditions = DbConfig::$db_query_conditions;
        //售卖房型
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
            'room_layout_date_year'=>$arrayTimeBegin['0'],'room_layout_date_month'=>$arrayTimeBegin['1'],
            'room_sell_layout_id'=>$room_sell_layout_id, 'room_layout_price_system_id'=>$room_layout_price_system_id,
            'room_layout_corp_id'=>$room_layout_corp_id);
        $arrayBeginYear = RoomService::instance()->getRoomLayoutPrice($conditions);
        //
        RoomDao::instance()->startTransaction();
        if(!empty($arrayBeginYear)) {//update
            RoomService::instance()->updateRoomLayoutPrice($conditions['where'], $arrayMonthData);
        } else {
            $arrayMonthData['room_sell_layout_id'] = $room_sell_layout_id;
            $arrayMonthData['room_layout_price_system_id'] = $room_layout_price_system_id;
            $arrayMonthData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
            $arrayMonthData['room_layout_date_year'] = $arrayTimeBegin['0'];
            $arrayMonthData['room_layout_date_month'] = $arrayTimeBegin['1'];
            $arrayMonthData['room_layout_price_add_date'] = getDay();
            $arrayMonthData['room_layout_price_add_time'] = getTime();
            $arrayMonthData['room_layout_price_begin_datetime'] = $arrayTimeBegin['0'] . '-' . $arrayTimeBegin['1'] . '-01';
            $arrayMonthData['room_layout_corp_id'] = $room_layout_corp_id;
            //$arrayMonthData['room_layout_price_end_datetime'] = getTime();
            RoomService::instance()->saveRoomLayoutPrice($arrayMonthData);
        }
        if(!empty($arrayMonthExtraBedData)) {//加床
            $arrayExtenBed = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions);
            if(!empty($arrayExtenBed)) {//update
                RoomService::instance()->updateRoomLayoutExtraBedPrice($conditions['where'], $arrayMonthExtraBedData);
            } else {
                $arrayMonthExtraBedData['room_sell_layout_id'] = $room_sell_layout_id;
                $arrayMonthExtraBedData['room_layout_price_system_id'] = $room_layout_price_system_id;
                $arrayMonthExtraBedData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                $arrayMonthExtraBedData['room_layout_date_year'] = $arrayTimeBegin['0'];
                $arrayMonthExtraBedData['room_layout_date_month'] = $arrayTimeBegin['1'];
                $arrayMonthExtraBedData['room_layout_price_add_date'] = getDay();
                $arrayMonthExtraBedData['room_layout_price_add_time'] = getTime();
                $arrayMonthExtraBedData['room_layout_price_begin_datetime'] = $arrayTimeBegin['0'] . '-' . $arrayTimeBegin['1'] . '-01';
                $arrayMonthExtraBedData['room_layout_corp_id'] = $room_layout_corp_id;
                RoomService::instance()->saveRoomLayoutExtraBedPrice($arrayMonthExtraBedData);
            }
        }
        if($isTheSameYear == true && $isTheSameMonth == false) {//相同的年不同的月
            $beginMonth = $arrayTimeBegin[1] + 1;
            for($i = $beginMonth; $i <= $arrayTimeEnd[1]; $i++) {
                $arrayMonthData = array();
                $arrayMonthExtraBedData = array();
                $endT = date("t", strtotime($arrayPostValue['time_begin']));
                if($i == $arrayTimeEnd[1]) $endT = $arrayTimeEnd[2];
                $beginWeek = date("N", strtotime($arrayTimeBegin[0] . '-' . $i . '-1'));
                for($j = 1; $j <= $endT; $j++) {
                    if($beginWeek > 7) $beginWeek = $beginWeek - 7;
                    $day = $j < 10 ? '0' . $j : $j;
                    if(trim($arrayPostValue['week_' . $beginWeek]) != '')
                        $arrayMonthData[$i][$day . '_day'] = $arrayPostValue['week_' . $beginWeek];
                    if(!empty($extra_bed_price)) {
                        $arrayMonthExtraBedData[$i][$day . '_day'] = $extra_bed_price;
                    }
                    if($extraBed) {
                        if(trim($arrayExtraBed['week_' . $beginWeek]) != '')
                            $arrayMonthExtraBedData[$i][$day . '_day'] = $arrayExtraBed['week_' . $beginWeek];
                    }
                    $beginWeek++;
                }
                //售卖房型
                $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
                    'room_layout_date_year'=>$arrayTimeBegin['0'],'room_layout_date_month'=>$i,
                    'room_sell_layout_id'=>$room_sell_layout_id, 'room_layout_price_system_id'=>$room_layout_price_system_id,
                    'room_layout_corp_id'=>$room_layout_corp_id);
                $arrayBeginYear = RoomService::instance()->getRoomLayoutPrice($conditions);
                //

                if(!empty($arrayBeginYear)) {//update
                    if(isset($arrayMonthData[$i]) && !empty($arrayMonthData[$i]))
                        RoomService::instance()->updateRoomLayoutPrice($conditions['where'], $arrayMonthData[$i]);
                } else {
                    $arrayMonthData[$i]['room_sell_layout_id'] = $room_sell_layout_id;
                    $arrayMonthData[$i]['room_layout_price_system_id'] = $room_layout_price_system_id;
                    $arrayMonthData[$i]['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                    $arrayMonthData[$i]['room_layout_date_year'] = $arrayTimeBegin['0'];
                    $arrayMonthData[$i]['room_layout_date_month'] = $i;
                    $arrayMonthData[$i]['room_layout_price_add_date'] = getDay();
                    $arrayMonthData[$i]['room_layout_price_add_time'] = getTime();
                    $arrayMonthData[$i]['room_layout_price_begin_datetime'] = $arrayTimeBegin['0'] . '-' . $i . '-01';
                    $arrayMonthData[$i]['room_layout_corp_id'] = $room_layout_corp_id;
                    RoomService::instance()->saveRoomLayoutPrice($arrayMonthData[$i]);
                }
                if(!empty($arrayMonthExtraBedData)) {//加床
                    $arrayExtenBed = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions);
                    if(!empty($arrayExtenBed)) {//update
                        RoomService::instance()->updateRoomLayoutExtraBedPrice($conditions['where'], $arrayMonthExtraBedData[$i]);
                    } else {
                        $arrayMonthExtraBedData[$i]['room_sell_layout_id'] = $room_sell_layout_id;
                        $arrayMonthExtraBedData[$i]['room_layout_price_system_id'] = $room_layout_price_system_id;
                        $arrayMonthExtraBedData[$i]['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                        $arrayMonthExtraBedData[$i]['room_layout_date_year'] = $arrayTimeBegin['0'];
                        $arrayMonthExtraBedData[$i]['room_layout_date_month'] = $i;
                        $arrayMonthExtraBedData[$i]['room_layout_price_add_date'] = getDay();
                        $arrayMonthExtraBedData[$i]['room_layout_price_add_time'] = getTime();
                        $arrayMonthExtraBedData[$i]['room_layout_price_begin_datetime'] = $arrayTimeBegin['0'] . '-' . $i . '-01';
                        $arrayMonthExtraBedData[$i]['room_layout_corp_id'] = $room_layout_corp_id;
                        RoomService::instance()->saveRoomLayoutExtraBedPrice($arrayMonthExtraBedData[$i]);
                    }
                }
            }
            //print_r($arrayMonthData);
        }
        if($isTheSameYear == false) {//不相同的年
            for($i = $arrayTimeBegin[0]; $i <= $arrayTimeEnd[0]; $i++) {//year
                $beginMonth = ($i == $arrayTimeBegin[0]) ? $arrayTimeBegin[1] + 1 : 1;
                $endMonth = ($i == $arrayTimeBegin[0]) ? 12 : $arrayTimeEnd[1];
                for($j = $beginMonth; $j <= $endMonth; $j++) {
                    $arrayMonthData = array();
                    $arrayMonthExtraBedData = array();
                    $endT = date("t", strtotime($i . '-' . $j . '-1'));
                    if($i == $arrayTimeEnd[0] && $j == $arrayTimeEnd[1]) $endT = $arrayTimeEnd[2];
                    $beginWeek = date("N", strtotime($i . '-' . $j . '-1'));
                    //echo $i . '-' . $j . '-1---' . $beginWeek . '--<br>';//exit();
                    for($k = 1; $k <= $endT; $k++) {
                        if($beginWeek > 7) $beginWeek = $beginWeek - 7;
                        $day = $k < 10 ? '0' . $k : $k;
                        if(trim($arrayPostValue['week_' . $beginWeek]) != '')
                            $arrayMonthData[$i][$j][$day . '_day'] = $arrayPostValue['week_' . $beginWeek];
                        if(!empty($extra_bed_price)) {
                            $arrayMonthExtraBedData[$i][$j][$day . '_day'] = $extra_bed_price;
                        }
                        if($extraBed) {
                            if(trim($arrayExtraBed['week_' . $beginWeek]) != '')
                                $arrayMonthExtraBedData[$i][$j][$day . '_day'] = $arrayExtraBed['week_' . $beginWeek];
                        }
                        $beginWeek++;
                    }
                    //售卖房型
                    $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
                        'room_layout_date_year'=>$i,'room_layout_date_month'=>$j,
                        'room_sell_layout_id'=>$room_sell_layout_id, 'room_layout_price_system_id'=>$room_layout_price_system_id,
                        'room_layout_corp_id'=>$room_layout_corp_id);
                    $arrayBeginYear = RoomService::instance()->getRoomLayoutPrice($conditions);
                    //

                    if(!empty($arrayBeginYear)) {//update
                        RoomService::instance()->updateRoomLayoutPrice($conditions['where'], $arrayMonthData[$i][$j]);
                    } else {
                        $arrayMonthData[$i][$j]['room_sell_layout_id'] = $room_sell_layout_id;
                        $arrayMonthData[$i][$j]['room_layout_price_system_id'] = $room_layout_price_system_id;
                        $arrayMonthData[$i][$j]['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                        $arrayMonthData[$i][$j]['room_layout_date_year'] = $i;
                        $arrayMonthData[$i][$j]['room_layout_date_month'] = $j;
                        $arrayMonthData[$i][$j]['room_layout_price_add_date'] = getDay();
                        $arrayMonthData[$i][$j]['room_layout_price_add_time'] = getTime();
                        $arrayMonthData[$i][$j]['room_layout_price_begin_datetime'] = $i . '-' . $j . '-01';
                        $arrayMonthData[$i][$j]['room_layout_corp_id'] = $room_layout_corp_id;
                        RoomService::instance()->saveRoomLayoutPrice($arrayMonthData[$i][$j]);
                    }
                    if(!empty($arrayMonthExtraBedData)) {//加床
                        $arrayExtenBed = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions);
                        if(!empty($arrayExtenBed)) {//update
                            RoomService::instance()->updateRoomLayoutExtraBedPrice($conditions['where'], $arrayMonthExtraBedData[$i][$j]);
                        } else {
                            $arrayMonthExtraBedData[$i][$j]['room_sell_layout_id'] = $room_sell_layout_id;
                            $arrayMonthExtraBedData[$i][$j]['room_layout_price_system_id'] = $room_layout_price_system_id;
                            $arrayMonthExtraBedData[$i][$j]['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                            $arrayMonthExtraBedData[$i][$j]['room_layout_date_year'] = $i;
                            $arrayMonthExtraBedData[$i][$j]['room_layout_date_month'] = $j;
                            $arrayMonthExtraBedData[$i][$j]['room_layout_price_add_date'] = getDay();
                            $arrayMonthExtraBedData[$i][$j]['room_layout_price_add_time'] = getTime();
                            $arrayMonthExtraBedData[$i][$j]['room_layout_price_begin_datetime'] = $i . '-' . $j . '-01';
                            $arrayMonthExtraBedData[$i][$j]['room_layout_corp_id'] = $room_layout_corp_id;
                            RoomService::instance()->saveRoomLayoutExtraBedPrice($arrayMonthExtraBedData[$i][$j]);
                        }
                    }
                }
            }
            //print_r($arrayMonthData);
        }
        RoomDao::instance()->commit();
        return array('1', '保存成功！如果价格为空，不做更改！');

    }

    public function saveRoomLayoutPriceMonth($objRequest, $objResponse) {
        $room_layout_corp_id = decode($objRequest -> layout_corp_id);
        $room_layout_corp_id = empty($room_layout_corp_id) ? '0' : $room_layout_corp_id;
        $arrayPostValue= $objRequest->getPost();
        $room_layout_date_month = $arrayPostValue['room_layout_date_month'];
        $room_layout_date_year = $arrayPostValue['room_layout_date_year'];
        $room_sell_layout_id = $objRequest -> sell_layout;
        $room_layout_price_system_id = $objRequest -> system_id;
        $arrayExtraBed = $objRequest -> extra_bed;
        $extra_bed_price = $objRequest -> extra_bed_price;
        $thisYear = getYear();
        $thisMonth = getMonth();
        $arrayMonthExtraBedData = array();
        $arrayMonthData = array();
        //只允许2年内的价格
        if(($room_layout_date_year - $thisYear) > 1) {
            return array(0, '选择的年份不对！');
        }
        if($room_layout_date_year == $thisYear && $thisMonth > $room_layout_date_month) {
            return array(0, '选择的年份不对！');
        }
        if(empty($room_sell_layout_id) || empty($room_layout_price_system_id)) {
            return array(0, '传输数据有误！');
        }
        $day = '';
        for($i = 1; $i <= 31; $i++) {
            $l = $i < 10 ? '0' . $i : $i;
            $day = $l . '_day';
            if(!empty($objRequest -> $day)) {
                $arrayMonthData[$day] = $objRequest -> $day;
                if(!empty($extra_bed_price)) {
                    $arrayMonthExtraBedData[$day] = $extra_bed_price;
                }
            }
            if(!empty($arrayExtraBed)) {
                if(isset($arrayExtraBed[$day]) && !empty($arrayExtraBed[$day]))
                    $arrayMonthExtraBedData[$day] = $arrayExtraBed[$day];
            }
        }

        //
        $conditions = DbConfig::$db_query_conditions;
        //售卖房型
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
            'room_layout_date_year'=>$room_layout_date_year,'room_layout_date_month'=>$room_layout_date_month,
            'room_sell_layout_id'=>$room_sell_layout_id, 'room_layout_price_system_id'=>$room_layout_price_system_id);
        $arrayRoomLayoutPrice = RoomService::instance()->getRoomLayoutPrice($conditions);
        RoomDao::instance()->startTransaction();
        if(!empty($arrayRoomLayoutPrice)) {//update
            RoomService::instance()->updateRoomLayoutPrice($conditions['where'], $arrayMonthData);
        } else {
            $arrayMonthData['room_sell_layout_id'] = $room_sell_layout_id;
            $arrayMonthData['room_layout_price_system_id'] = $room_layout_price_system_id;
            $arrayMonthData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
            $arrayMonthData['room_layout_date_year'] = $room_layout_date_year;
            $arrayMonthData['room_layout_date_month'] = $room_layout_date_month;
            $arrayMonthData['room_layout_price_add_date'] = getDay();
            $arrayMonthData['room_layout_price_add_time'] = getTime();
            $arrayMonthData['room_layout_price_begin_datetime'] = $room_layout_date_year . '-' . $room_layout_date_month . '-01';
            $arrayMonthData['room_layout_corp_id'] = $room_layout_corp_id;
            RoomService::instance()->saveRoomLayoutPrice($arrayMonthData);
        }
        if(!empty($arrayMonthExtraBedData)) {
            $arrayExtenBed = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions);
            if(!empty($arrayExtenBed)) {//update
                RoomService::instance()->updateRoomLayoutExtraBedPrice($conditions['where'], $arrayMonthExtraBedData);
            } else {
                $arrayMonthExtraBedData['room_sell_layout_id'] = $room_sell_layout_id;
                $arrayMonthExtraBedData['room_layout_price_system_id'] = $room_layout_price_system_id;
                $arrayMonthExtraBedData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                $arrayMonthExtraBedData['room_layout_date_year'] = $room_layout_date_year;
                $arrayMonthExtraBedData['room_layout_date_month'] = $room_layout_date_month;
                $arrayMonthExtraBedData['room_layout_price_add_date'] = getDay();
                $arrayMonthExtraBedData['room_layout_price_add_time'] = getTime();
                $arrayMonthExtraBedData['room_layout_price_begin_datetime'] = $room_layout_date_year . '-' . $room_layout_date_month . '-01';
                $arrayMonthExtraBedData['room_layout_corp_id'] = $room_layout_corp_id;
                RoomService::instance()->saveRoomLayoutExtraBedPrice($arrayMonthExtraBedData);
            }
        }
        RoomDao::instance()->commit();
        return array('1', '保存成功！如果价格为空，不做更改！');
    }

    public function getRoomLayoutPriceList($objRequest, $objResponse) {
        $room_layout_corp_id = decode($objRequest -> layout_corp_id);
        $room_layout_corp_id = empty($room_layout_corp_id) ? '0' : $room_layout_corp_id;
        $year = $objRequest -> year;
        $month = $objRequest -> month;
        if(empty($year)) $year = getYear();
        if(empty($month)) $month = date("n");
        $monthT= date('t', strtotime($year . '-' . $month . '-01'));
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'room_sell_layout_valid'=>1);
        //$arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions);
        //print_r($arrayRoomLayout);
        $arrayRoomSellLayout = RoomService::instance()->getRoomSellLayout($conditions);

        $conditions['where'] = array('IN'=>array('hotel_id'=>array($objResponse->arrayLoginEmployeeInfo['hotel_id'], 0)),
                                     'room_layout_price_system_valid'=>1,'room_layout_corp_id'=>$room_layout_corp_id);
        $conditions['order'] = 'room_layout_price_system_id ASC';
        $arrayPriceSystem = RoomService::instance()->getRoomLayoutPriceSystem($conditions, '*', 'room_sell_layout_id', true);
        //print_r($arrayPriceSystem);

        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'room_layout_price_is_active'=>1,
            'room_layout_date_year'=>$year, 'room_layout_date_month'=>$month);
        $arrayRoomLayoutPrice = RoomService::instance()->getRoomLayoutPrice($conditions, '*', 'room_sell_layout_id', true);
        //print_r($arrayRoomLayoutPrice);

        //$arrayRoomLayoutPriceList = array();
        if(!empty($arrayRoomSellLayout)) {
            foreach($arrayRoomSellLayout as $i => $arrayRoom) {
                $arrayRoomSellLayout[$i]['price_system'] = array();
                if(isset($arrayPriceSystem[$arrayRoom['room_sell_layout_id']])) {
                    $arrayRoomSellLayout[$i]['price_system'] = $arrayPriceSystem[$arrayRoom['room_sell_layout_id']];
                }
                if(isset($arrayPriceSystem[0])) {
                    $arrayRoomSellLayout[$i]['price_system'] = array_merge($arrayRoomSellLayout[$i]['price_system'], $arrayPriceSystem[0]);
                }
                if(!empty($arrayRoomSellLayout[$i]['price_system'])) {
                    foreach($arrayRoomSellLayout[$i]['price_system'] as $j => $arraySystem) {
                        $arrayRoomSellLayout[$i]['price_system'][$j]['price'] = '';
                        if(isset($arrayRoomLayoutPrice[$arrayRoom['room_sell_layout_id']])) {
                            foreach($arrayRoomLayoutPrice[$arrayRoom['room_sell_layout_id']] as $k => $arrayPrice) {
                                if($arraySystem['room_layout_price_system_id'] == $arrayPrice['room_layout_price_system_id']) {
                                    $arrayRoomSellLayout[$i]['price_system'][$j]['price'][] = $arrayPrice;
                                    //break;
                                }
                            }
                        }
                    }
                }
            }
        }
        //赋值
        $objResponse -> year = $year;
        $objResponse -> month = $month;
        $objResponse -> monthT = $monthT;
        return $arrayRoomSellLayout;
    }


}