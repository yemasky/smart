<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;

class BookOperateService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookOperateService();
        return self::$objService;
    }

    public function rollback() {
        BookDao::instance()->rollback();
    }

    public function saveBookInfo($objRequest, $objResponse) {
        //print_r($objRequest);
        //return;
        $arrayPostValue = $objRequest->getPost();
        $payment = $arrayPostValue['payment'];//支付 类型
        $payment_type = $arrayPostValue['payment_type'];//支付方式  微信支付宝等
        $is_pay = $arrayPostValue['is_pay'];//付款已到账
        $is_pay = $is_pay == 2 ? '0' : '1';
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $employee_id = $objResponse->arrayLoginEmployeeInfo['employee_id'];
        $arrayThenRoomPrice = json_decode(stripslashes($objRequest->thenRoomPrice), true);
        $arrayUserLodger = json_decode(stripslashes($objRequest->user_lodger), true);
        $room_layout_corp_id = $objRequest -> layout_corp;
        $room_layout_corp_id = empty($room_layout_corp_id) ? '0' : $room_layout_corp_id;
        //联系信息 <!-- -->
        $arrayBill['book_contact_name']             = $arrayPostValue['book_contact_name'];//联系人
        if(!empty($arrayPostValue['book_contact_mobile']))
            $arrayBill['book_contact_mobile']       = $arrayPostValue['book_contact_mobile'];//联系人移动电话
        $arrayBill['book_contact_email']            = $arrayPostValue['book_contact_email'];//联系人email
        $arrayBill['user_id']                       = '0';////
        $arrayBill['employee_id']                   = $employee_id;
        $arrayBill['hotel_id']                      = $hotel_id;
        //来源 <!-- -->
        $arrayBill['room_layout_corp_id']           = $room_layout_corp_id;
        $arrayBill['book_type_id']                  = $arrayPostValue['book_type_id'];
        $arrayBill['book_order_number_ourter']      = $objRequest -> book_order_number_ourter;//如果是OTA 有外部订单号
        //折扣 <!-- -->
        $arrayBill['book_discount']                 = $arrayPostValue['book_discount'];//实际折扣
        $arrayBill['book_discount_type']            = $arrayPostValue['book_discount_type'];
        if(isset($arrayPostValue['book_discount_id']))
            $arrayBill['book_discount_id']          = $arrayPostValue['book_discount_id'];//折扣ID
        $arrayBill['book_discount_describe']        = $arrayPostValue['book_discount_describe'];//折扣描述
        //半天房费计算时间 <!-- -->
        $arrayBill['book_half_price']               = $arrayPostValue['half_price'];
        //入住日期 <!-- -->
        $arrayBill['book_check_in']                 = $arrayPostValue['book_check_in'];//入住时间
        $arrayBill['book_check_out']                = $arrayPostValue['book_check_out'];//退房时间
        $arrayBill['book_days_total']               = $arrayPostValue['book_days_total'];//退房时间
        $arrayBill['book_order_retention_time']     = $arrayPostValue['book_order_retention_time'];//订单保留时间
        //主订单 <!-- -->
        $arrayBill['book_order_number_main']        = '0';//主订单号
        $arrayBill['book_order_number']             = 1;//订单号
        //订单状态 <!-- -->
        $arrayBill['book_order_number_status']      = '0';//订单状态 -1 失效 0预定成功 1入住 2退房完成

        //支付
            //预付
        $arrayBill['book_prepayment_price']         = $arrayPostValue['book_prepayment_price'];//预付费
        if($payment == 1) {
            $arrayBill['book_is_prepayment']        = $is_pay;//是否已支付预付费
            if($is_pay == 1) {//支付是否到账
                $arrayBill['book_prepayment_date']  = getDateTime();//预付费支付时间
            }
            $arrayBill['prepayment_type_id']        = $payment_type;//预付 支付方式  微信支付宝等
            $arrayBill['book_prepayment_account']       = '';//预付支付帐号
        }
            //全额支付 余额
        if($payment == 3 || $payment == 2) {
            $arrayBill['book_is_pay']               = '1';//是否已经全额支付房费
            $arrayBill['book_is_payment']           = $is_pay;//是否已到帐
            $arrayBill['payment_type_id']           = $payment_type;//预付 支付方式  微信支付宝等
            if($is_pay == 1) {//支付是否到账
                $arrayBill['book_pay_date']  = getDateTime();//支付时间
            }
        }
        if($payment == 2) {//余额支付时间
            $arrayBill['book_balance_payment_date'] = getDateTime();
        }
        $arrayBill['book_payment_voucher']          = $arrayPostValue['book_payment_voucher'];//付款凭证

        //计算价格
        //总房费
        $arrayBill['book_total_room_rate']          = $arrayPostValue['total_room_rate'];//总房费
        //总押金
        $arrayBill['book_total_cash_pledge']        = $arrayPostValue['book_total_cash_pledge'];//总房费
        //需要服务的费用
        $arrayBill['book_need_service_price']       = $arrayPostValue['need_service_price'];//附加服务的费用
        //服务费
        $arrayBill['book_service_charge']           = $arrayPostValue['book_service_charge'];//服务费
        //总费用
        $arrayBill['book_total_price']              = $arrayPostValue['book_total_price'];//计算支付总价

        //备注
        $arrayBill['book_comments']                 = $arrayPostValue['comments'];//计算支付总价
        //时间
        $arrayBill['book_add_date']                 = getDay();
        $arrayBill['book_add_time']                 = getTime();
        $arrayBill['book_add_datetime']             = getDateTime();
        $arrayBill['book_night_audit_date']         = getDay(-24);//昨天的时间表示还可以夜审
        /******************************************************/
        //
        //$arrayLayoutPrice   = $arrayPostValue['layout_price'];
        //$arrayExtraBedPrice = $arrayPostValue['extra_bed_price'];
        //加房
        $arrayExtraBed = isset($arrayPostValue['extra_bed']) ? $arrayPostValue['extra_bed'] : null;
        //根据房间插入不同的房间
        $arraybatchInsert = array();
        $arraybatchInsertValue = array();
        $arrayRoomLayoutRoomHash = array();
        $first = true;
        $i = 0;
        $arrayNightAudit = array();
        //room_layout_id[6-19-1][18] [sell_id-room_layout-system_id][room]
        foreach($arrayPostValue['room_layout_id'] as $roomLayoutSystem => $arrayRoom) {
            $arrayLayoutSystem = explode('-', $roomLayoutSystem);
            $sell_id = $arrayLayoutSystem[0];
            $room_layout_id = $arrayLayoutSystem[1];
            $system_id = $arrayLayoutSystem[2];
            foreach($arrayRoom as $k => $room_id) {
                //$room_id = $arrayRoom[0];
                $arrayRoomLayoutRoomHash[$room_id] = $room_layout_id;
                //第一个个设为主订单
                if($first) {
                    $arrayBill['book_order_number_main'] = '1';//主订单号
                    $arrayBill['room_sell_layout_id'] = $sell_id;
                    $arrayBill['room_layout_id'] = $room_layout_id;
                    $arrayBill['room_id'] = $room_id;
                    $arrayBill['room_layout_price_system_id'] = $system_id;
                    $arrayBill['book_room_extra_bed']    = '0';
                    if(isset($arrayExtraBed[$sell_id.'-'.$room_layout_id.'-'.$system_id][$room_id])) {
                        $arrayBill['book_room_extra_bed'] = $arrayExtraBed[$sell_id.'-'.$room_layout_id.'-'.$system_id][$room_id];
                    }
                    $arrayBill['book_cash_pledge'] = 0;//押金
                    if(isset($arrayThenRoomPrice['pledge'][$sell_id.'-'.$room_layout_id.'-'.$system_id])) {
                        $arrayBill['book_cash_pledge'] =
                            $arrayThenRoomPrice['pledge'][$sell_id.'-'.$room_layout_id.'-'.$system_id] * $arrayBill['book_discount'] / 100;
                    }
                    $arrayBill['book_room_price'] = $arrayThenRoomPrice['room'][$sell_id.'-'.$room_layout_id.'-'.$system_id]['room_price'];
                    $first = false;
                } else {
                    $arraybatchInsertValue[$i]['book_order_number_main'] = '0';//主订单号
                    $arraybatchInsertValue[$i]['room_sell_layout_id'] = $sell_id;
                    $arraybatchInsertValue[$i]['room_layout_id'] = $room_layout_id;
                    $arraybatchInsertValue[$i]['room_id'] = $room_id;
                    $arraybatchInsertValue[$i]['room_layout_price_system_id'] = $system_id;
                    $arraybatchInsertValue[$i]['book_room_extra_bed'] = '0';
                    //$arraybatchInsertValue['book_room_sell_layout_price'] = '';//check-in ~ check-out房间总价
                    if(isset($arrayExtraBed[$sell_id.'-'.$room_layout_id.'-'.$system_id][$room_id])) {
                        $arraybatchInsertValue[$i]['book_room_extra_bed'] = $arrayExtraBed[$sell_id.'-'.$room_layout_id.'-'.$system_id][$room_id];
                    }
                    $arraybatchInsertValue[$i]['book_cash_pledge'] = '0';
                    if(isset($arrayThenRoomPrice['pledge'][$sell_id.'-'.$room_layout_id.'-'.$system_id])) {
                        $arraybatchInsertValue[$i]['book_cash_pledge'] =
                            $arrayThenRoomPrice['pledge'][$sell_id.'-'.$room_layout_id.'-'.$system_id] * $arrayBill['book_discount'] / 100;
                    }
                    $i++;
                }
            }
            //foreach($arrayRoom as $layout_system => $room_id) {
            //}
        }

        //事务开启
        BookDao::instance()->startTransaction();
        $book_id = BookService::instance()->saveBook($arrayBill);
        $book_order_number = \Utilities::getOrderNumber($book_id);
        BookService::instance()->updateBook(array('book_id'=>$book_id), array('book_order_number'=>$book_order_number));
        unset($arrayBill['book_contact_name']);
        unset($arrayBill['book_contact_mobile']);
        if(isset($arrayBill['book_total_room_rate']))unset($arrayBill['book_total_room_rate']);//总房费
        if(isset($arrayBill['book_prepayment_price']))unset($arrayBill['book_prepayment_price']);//预付价
        if(isset($arrayBill['book_total_cash_pledge']))unset($arrayBill['book_total_cash_pledge']);//A1 总押金
        if(isset($arrayBill['book_need_service_price']))unset($arrayBill['book_need_service_price']);//附加服务费
        if(isset($arrayBill['book_service_charge']))unset($arrayBill['book_service_charge']);//服务费
        if(isset($arrayBill['book_total_price']))unset($arrayBill['book_total_price']);//A1 支付总价
        //if(isset($arrayBill['prepayment_type_id']))unset($arrayBill['prepayment_type_id']);
        //if(isset($arrayBill['book_prepayment_date']))unset($arrayBill['book_prepayment_date']);
        //if(isset($arrayBill['book_is_prepayment'])) unset($arrayBill['book_is_prepayment']);
        if(!empty($arraybatchInsertValue)) {
            foreach($arraybatchInsertValue as $k => $v) {
                $arraybatchInsert[$k] = $arrayBill;
                $arraybatchInsert[$k]['book_order_number_main'] = '0';//主订单号
                $arraybatchInsert[$k]['book_order_number']   = $book_order_number;
                $arraybatchInsert[$k]['room_sell_layout_id'] = $v['room_sell_layout_id'];
                $arraybatchInsert[$k]['room_layout_id']      = $v['room_layout_id'];
                $arraybatchInsert[$k]['room_id']             = $v['room_id'];
                $arraybatchInsert[$k]['room_layout_price_system_id'] = $v['room_layout_price_system_id'];
                //$arraybatchInsert[$k]['book_room_sell_layout_price'] = $v['book_room_sell_layout_price'];//check-in ~ check-out房间总价
                $arraybatchInsert[$k]['book_room_extra_bed'] = $v['book_room_extra_bed'];
                $arraybatchInsert[$k]['book_cash_pledge']    = $v['book_cash_pledge'];

            }
        }
        if(!empty($arraybatchInsert)) BookDao::instance()->setTable('book')->batchInsert($arraybatchInsert);

        //添加住客
        $arrayBookUserData = array();
        foreach($arrayPostValue['user_name'] as $i => $bookUser) {
            if(!empty($bookUser)) {
                $arrayBookUserData[$i]['book_id'] = $book_id;
                $arrayBookUserData[$i]['employee_id'] = $employee_id;
                $arrayBookUserData[$i]['hotel_id'] = $hotel_id;
                $arrayBookUserData[$i]['book_user_name'] = $bookUser;
                $arrayBookUserData[$i]['book_order_number'] = $book_order_number;
                $arrayBookUserData[$i]['book_user_id_card'] = $arrayPostValue['user_id_card'][$i];
                $arrayBookUserData[$i]['book_user_id_card_type'] = $arrayPostValue['user_id_card_type'][$i];
                $arrayBookUserData[$i]['room_layout_id'] = '0';//$arrayRoomLayoutRoomHash[$arrayPostValue['book_user_room'][$i]];
                $arrayBookUserData[$i]['room_id'] = $arrayPostValue['user_room'][$i];
                $arrayBookUserData[$i]['book_user_sex'] = $arrayPostValue['user_sex'][$i];
                $arrayBookUserData[$i]['book_user_comments'] = $arrayPostValue['user_comments'][$i];
                $arrayBookUserData[$i]['book_check_in'] = $arrayPostValue['book_check_in'];
                $arrayBookUserData[$i]['book_check_out'] = $arrayPostValue['book_check_out'];
                $arrayBookUserData[$i]['book_user_lodger_type'] = $arrayUserLodger[$i];
                $arrayBookUserData[$i]['book_add_date'] = getDay();
                $arrayBookUserData[$i]['book_add_time'] = getTime();
            }
        }
        if(!empty($arrayBookUserData)) BookDao::instance()->setTable('book_user')->batchInsert($arrayBookUserData);

        //房价数据
        $arraySameYearAndMonth = '';$iNightAudit = 0;$iNightAuditDate = '';
        foreach($arrayThenRoomPrice['room'] as $roomLayoutSystem => $arrayDatePrice) {//当时房价
            $arrayLayoutSystem = explode('-', $roomLayoutSystem);
            $sell_id = $arrayLayoutSystem[0];
            $room_layout_id = $arrayLayoutSystem[1];
            $system_id = $arrayLayoutSystem[2];
            foreach($arrayDatePrice as $date => $roomKeyPrice) {
                $roomPrice = $roomKeyPrice['price'];
                $roomDiscountPrice = $roomKeyPrice['discount_price'];
                if($date == 'room_price') continue;
                $arrayDate = explode('-', $date);
                $year = $arrayDate[0]; $month = trim($arrayDate[1] - 0); $day = $arrayDate[2];
                if($iNightAudit == 0)
                    $iNightAuditDate = $year . '-' . $month . '-' . $day;
                $id = $year . '-' . $month . '-' . $sell_id . '-' . $room_layout_id . '-' . $system_id;
                if(isset($arraySameYearAndMonth[$id])) {
                    $arraySameYearAndMonth[$id][$day . '_day'] = $roomPrice;
                } else {
                    for($i = 1; $i <= 31; $i++) {
                        $i_day = $i < 10 ? '0' . $i : $i;
                        $arraySameYearAndMonth[$id][$i_day . '_day'] = '-1';
                    }
                    $arraySameYearAndMonth[$id][$day . '_day'] = $roomPrice;
                    $arraySameYearAndMonth[$id]['book_order_number'] = $book_order_number;
                    $arraySameYearAndMonth[$id]['employee_id'] = $employee_id;
                    $arraySameYearAndMonth[$id]['book_id'] = $book_id;
                    $arraySameYearAndMonth[$id]['room_sell_layout_id'] = $sell_id;
                    $arraySameYearAndMonth[$id]['room_layout_id'] = $room_layout_id;
                    $arraySameYearAndMonth[$id]['hotel_id'] = $hotel_id;
                    $arraySameYearAndMonth[$id]['room_layout_price_system_id'] = $system_id;
                    $arraySameYearAndMonth[$id]['room_layout_date_year'] = $year;
                    $arraySameYearAndMonth[$id]['room_layout_date_month'] = $month;
                }
                //夜审数据
                foreach($arrayThenRoomPrice['room_id'][$roomLayoutSystem] as $r_id => $room_id) {
                    $arrayNightAudit[$iNightAudit]['room_id'] = $room_id;
                    $arrayNightAudit[$iNightAudit]['hotel_service_id'] = '0';
                    $arrayNightAudit[$iNightAudit]['hotel_service_num'] = '0';
                    $arrayNightAudit[$iNightAudit]['book_order_number'] = $book_order_number;
                    $arrayNightAudit[$iNightAudit]['book_id'] = $book_id;
                    $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
                    $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
                    $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayPostValue['book_type_id'];
                    if(!isset($arrayPostValue['book_discount_id'])) {
                        $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';
                    } else {
                        $arrayNightAudit[$iNightAudit]['book_discount_id'] = $arrayPostValue['book_discount_id'];
                    }
                    if(!isset($arrayPostValue['book_discount'])) {
                        $arrayNightAudit[$iNightAudit]['book_discount'] = 100;
                    } else {
                        $arrayNightAudit[$iNightAudit]['book_discount'] = $arrayPostValue['book_discount'];
                    }
                    $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = $sell_id;
                    $arrayNightAudit[$iNightAudit]['room_layout_id'] = $room_layout_id;
                    $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = $system_id;
                    $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $year . '-' . $month . '-' . $day;//财日
                    $arrayNightAudit[$iNightAudit]['price'] = $roomPrice;
                    $fiscal_day = date("Y-m-d", strtotime($year . '-' . $month . '-' . $day . ' - 24 HOUR'));
                    $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = $fiscal_day . ' 12:00:00';
                    $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = $year . '-' . $month . '-' . $day . ' 12:00:00';
                    $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $roomDiscountPrice;
                    if(isset($arrayThenRoomPrice['half'][$date])) {
                        //$arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $arrayThenRoomPrice['half'][$date] * $arrayNightAudit[$iNightAudit]['book_discount'] / 100;
                        $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = $year . '-' . $month . '-' . $day . ' 12:00:00';//半天房费计算时间 ？
                        $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = $year . '-' . $month . '-' . $day . ' 18:00:00';//半天房费计算时间 ？
                    } else {
                        //$roomPrice * $arrayNightAudit[$iNightAudit]['book_discount'] / 100;
                    }
                    $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'room';
                    $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
                    $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
                    $arrayNightAudit[$iNightAudit]['unique_key'] = '0';
                    $iNightAudit++;
                }
            }
        }
        if(!empty($arraySameYearAndMonth)) BookDao::instance()->setTable('book_room_price')->batchInsert($arraySameYearAndMonth);
        //加床价格数据
        $arraySameYearAndMonth = '';
        foreach($arrayThenRoomPrice['bed'] as $roomLayoutSystem => $arrayDatePrice) {
            $arrayLayoutSystem = explode('-', $roomLayoutSystem);
            $sell_id = $arrayLayoutSystem[0];
            $room_layout_id = $arrayLayoutSystem[1];
            $system_id = $arrayLayoutSystem[2];
            foreach($arrayDatePrice as $date => $bedPrice) {
                if($date == 'bed_price') continue;
                $arrayDate = explode('-', $date);
                $year = $arrayDate[0]; $month = trim($arrayDate[1] - 0); $day = $arrayDate[2];
                $id = $year . '-' . $month . '-' . $sell_id . '-' . $room_layout_id . '-' . $system_id;
                if(isset($arraySameYearAndMonth[$id])) {
                    $arraySameYearAndMonth[$id][$day . '_day'] = $bedPrice;
                } else {
                    for($i = 1; $i <= 31; $i++) {
                        $i_day = $i < 10 ? '0' . $i : $i;
                        $arraySameYearAndMonth[$id][$i_day . '_day'] = '0';
                    }
                    $arraySameYearAndMonth[$id][$day . '_day'] = $bedPrice;
                    $arraySameYearAndMonth[$id]['book_order_number'] = $book_order_number;
                    $arraySameYearAndMonth[$id]['employee_id'] = $employee_id;
                    $arraySameYearAndMonth[$id]['book_id'] = $book_id;
                    $arraySameYearAndMonth[$id]['room_sell_layout_id'] = $sell_id;
                    $arraySameYearAndMonth[$id]['room_layout_id'] = $room_layout_id;
                    $arraySameYearAndMonth[$id]['hotel_id'] = $hotel_id;
                    $arraySameYearAndMonth[$id]['room_layout_price_system_id'] = $system_id;
                    $arraySameYearAndMonth[$id]['room_layout_date_year'] = $year;
                    $arraySameYearAndMonth[$id]['room_layout_date_month'] = $month;
                }
                //夜审数据
                foreach($arrayThenRoomPrice['bed_room_id'][$roomLayoutSystem] as $r_id => $room_id) {
                    $arrayNightAudit[$iNightAudit]['room_id'] = $room_id;
                    $arrayNightAudit[$iNightAudit]['hotel_service_id'] = '0';
                    $arrayNightAudit[$iNightAudit]['hotel_service_num'] = '0';
                    $arrayNightAudit[$iNightAudit]['book_order_number'] = $book_order_number;
                    $arrayNightAudit[$iNightAudit]['book_id'] = $book_id;
                    $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
                    $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
                    $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayPostValue['book_type_id'];
                    $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';//加床默认为 100 折扣
                    $arrayNightAudit[$iNightAudit]['book_discount'] = 100;//加床默认为 100 折扣
                    $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = $sell_id;
                    $arrayNightAudit[$iNightAudit]['room_layout_id'] = $room_layout_id;
                    $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = $system_id;
                    $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $year . '-' . $month . '-' . $day;//财日
                    $arrayNightAudit[$iNightAudit]['price'] = $bedPrice;
                    $fiscal_day = date("Y-m-d", strtotime($year . '-' . $month . '-' . $day . ' - 24 HOUR'));
                    $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = $fiscal_day . ' 12:00:00';
                    $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = $year . '-' . $month . '-' . $day . ' 12:00:00';
                    if(isset($arrayThenRoomPrice['half'][$date])) {
                        $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = $year . '-' . $month . '-' . $day . ' 12:00:00';//半天房费计算时间 ？
                        $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = $year . '-' . $month . '-' . $day . ' 18:00:00';//半天房费计算时间 ？
                    }
                    $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $bedPrice;
                    $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'extra_bed';
                    $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
                    $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
                    $arrayNightAudit[$iNightAudit]['unique_key'] = rand(100000000, 999999999);
                    $iNightAudit++;
                }
            }
        }
        if(!empty($arraySameYearAndMonth)) BookDao::instance()->setTable('book_room_extra_bed_price')->batchInsert($arraySameYearAndMonth);
        //服务 service
        $arrayServiceData = '';
        foreach($arrayThenRoomPrice['service'] as $service_id => $price) {
            $arrayService = explode('-', $price);
            $arrayServiceData[$service_id]['book_order_number'] = $book_order_number;
            $arrayServiceData[$service_id]['book_id'] = $book_id;
            $arrayServiceData[$service_id]['employee_id'] = $employee_id;
            $arrayServiceData[$service_id]['hotel_id'] = $hotel_id;
            $arrayServiceData[$service_id]['hotel_service_id'] = $service_id;
            $arrayServiceData[$service_id]['hotel_service_price'] = $arrayService[0];
            $arrayServiceData[$service_id]['book_hotel_service_num'] = $arrayService[1];
            $arrayServiceData[$service_id]['book_hotel_service_discount'] = $arrayService[2];
            //夜审数据
            $arrayNightAudit[$iNightAudit]['room_id'] = '0';//
            $arrayNightAudit[$iNightAudit]['hotel_service_id'] = $service_id;
            $arrayNightAudit[$iNightAudit]['hotel_service_num'] = $arrayService[1];
            $arrayNightAudit[$iNightAudit]['book_order_number'] = $book_order_number;
            $arrayNightAudit[$iNightAudit]['book_id'] = $book_id;
            $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
            $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayPostValue['book_type_id'];
            $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';//加床默认为 100 折扣
            $arrayNightAudit[$iNightAudit]['book_discount'] = $arrayService[2];
            $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = '0';
            $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $iNightAuditDate;//算在第一天的夜审 财日
            $arrayNightAudit[$iNightAudit]['price'] = $arrayService[0];
            $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = NULL;
            $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = NULL;
            $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $arrayService[0] * $arrayService[2] * $arrayService[1] / 100;
            $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'extra_service';
            $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
            $arrayNightAudit[$iNightAudit]['unique_key'] = rand(100000000, 999999999);
            $iNightAudit++;
        }
        if(!empty($arrayPostValue['book_service_charge'])) {
            //夜审数据
            $arrayNightAudit[$iNightAudit]['room_id'] = '0';//
            $arrayNightAudit[$iNightAudit]['hotel_service_id'] = -1;
            $arrayNightAudit[$iNightAudit]['hotel_service_num'] = 1;
            $arrayNightAudit[$iNightAudit]['book_order_number'] = $book_order_number;
            $arrayNightAudit[$iNightAudit]['book_id'] = $book_id;
            $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
            $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayPostValue['book_type_id'];
            $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';//加床默认为 100 折扣
            $arrayNightAudit[$iNightAudit]['book_discount'] = 100;
            $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = '0';
            $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $iNightAuditDate;//算在第一天的夜审 财日
            $arrayNightAudit[$iNightAudit]['price'] = $arrayPostValue['book_service_charge'];
            $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_begin'] = NULL;
            $arrayNightAudit[$iNightAudit]['book_fiscal_day_quantum_to'] = NULL;
            $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $arrayPostValue['book_service_charge'];
            $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'service_charge';
            $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
            $arrayNightAudit[$iNightAudit]['unique_key'] = rand(100000000, 999999999);
        }
        if(!empty($arrayThenRoomPrice)) BookDao::instance()->setTable('book_hotel_service')->batchInsert($arrayServiceData);
        if(!empty($arrayNightAudit)) BookDao::instance()->setTable('book_night_audit')->batchInsert($arrayNightAudit);
        BookDao::instance()->commit();
        return $book_order_number;
    }

    public function saveEditReBookInfo($objRequest, $objResponse) {
        $order_number = decode($objRequest -> order_number);
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $employee_id  = $objResponse->arrayLoginEmployeeInfo['employee_id'];
        $data = json_decode(stripslashes($objRequest->data), true);
        $service = json_decode(stripslashes($objRequest->service), true);
        $book_prepayment_price = $objRequest -> book_prepayment_price;
        $book_is_payment = $objRequest -> is_pay;
        if($book_is_payment == 2) $book_is_payment = '0';
        $payment_type = $objRequest -> payment_type;
        $is_pay = $objRequest -> payment;
        $book_is_prepayment = '0';
        if($is_pay < 2) {//预付
            $book_is_prepayment = '1';
            $prepayment_type_id = $payment_type;
        }
        if($is_pay >= 2) $is_pay = 1;
        //old info
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'book_order_number_main'=>1);
        $arrayOldBookInfo = BookService::instance()->getBook($conditions);
        if(empty($arrayOldBookInfo)) {
            return array(0, '找不到可更改的订单！');
        }
        $arrayOldBookInfo = $arrayOldBookInfo[0];
        $arrayBookInfo = '';//book数据
        $arrayRoomPrice = $arrayRoomExtraBedPrice = '';//房价和床价历史成交数据
        $arrayNightAudit = '';//book_night_audit数据
        $arrayHotelService = '';//book_hotel_service数据
        $iNightAuditDate = '';$arrayUpdateRoom = '';$arrayUpdateNightAudit = '';
        foreach($data as $room_id => $arrayData) {
            $arrayBookInfo[$room_id]['book_type_id'] = $arrayOldBookInfo['book_type_id'];//?
            $arrayBookInfo[$room_id]['user_id'] = '0';
            $arrayBookInfo[$room_id]['employee_id'] = $employee_id;
            $arrayBookInfo[$room_id]['hotel_id'] = $hotel_id;
            $arrayBookInfo[$room_id]['room_id'] = $room_id;
            $book_change = 0;
            if($arrayData['type'] != '0') $book_change = $arrayData['type'];
            //add_room新增 change_room换房 continued_room续房
            if($arrayData['type'] =='change_room' || $arrayData['type'] =='continued_room') {
                if($arrayData['type'] =='change_room') {
                    $arrayUpdateRoom[$arrayData['type_room_id']]['book_change'] = 'have_change_room';
                    $arrayUpdateNightAudit[$arrayData['type_room_id']]['book_night_audit_valid_reason'] = '-2';
                } else {
                    $arrayUpdateRoom[$arrayData['type_room_id']]['book_change'] = 'have_continued_room';
                    $arrayUpdateNightAudit[$arrayData['type_room_id']]['book_night_audit_valid_reason'] = '-3';
                }
                $arrayUpdateRoom[$arrayData['type_room_id']]['book_cash_pledge_returns'] = '1';
                $arrayUpdateRoom[$arrayData['type_room_id']]['book_order_number_status'] = '-1';
                //夜审
                $arrayUpdateNightAudit[$arrayData['type_room_id']]['book_night_audit_valid'] = '0';

            }
            foreach($arrayData['data'] as $k => $arrayInfo) {
                $arrayKey = explode('-', $arrayInfo['room_key']);
                $sell_id = $arrayKey[0];$room_layout_id = $arrayKey[1];$system_id = $arrayKey[2];
                $arrayBookInfo[$room_id]['room_sell_layout_id'] = $sell_id;
                $arrayBookInfo[$room_id]['room_layout_id'] = $room_layout_id;
                $arrayBookInfo[$room_id]['room_layout_price_system_id'] = $system_id;

                if($k == 'room') {
                    $arrayBookInfo[$room_id]['book_room_extra_bed'] = $arrayInfo['extra_bed'];
                    $arrayBookInfo[$room_id]['book_room_price'] = $arrayInfo['total_room_rate'];
                    $arrayBookInfo[$room_id]['book_cash_pledge'] = $arrayInfo['cash_pledge'] * $arrayInfo['discount'] / 100 ;
                    $arrayBookInfo[$room_id]['book_check_in'] = $arrayInfo['room_check_in'];
                    $arrayBookInfo[$room_id]['book_check_out'] = $arrayInfo['room_check_out'];
                    $arrayBookInfo[$room_id]['book_order_number'] = $order_number;
                    $arrayBookInfo[$room_id]['book_discount_id'] = $arrayOldBookInfo['book_discount_id'];
                    $arrayBookInfo[$room_id]['book_discount_type'] = $arrayOldBookInfo['book_discount_type'];
                    $arrayBookInfo[$room_id]['book_discount'] = $arrayInfo['discount'];
                    //付款信息
                    if($book_is_prepayment == 1) {//预付 //已入住不能预付
                        if($arrayOldBookInfo['book_order_number_main_status'] != '0') {
                            return array(0, '只有预定的订单能进行预付费更改,请选择全额付款！');
                        }
                        $arrayBookInfo[$room_id]['book_prepayment_price'] = $book_prepayment_price;;//预付费
                        $arrayBookInfo[$room_id]['book_is_prepayment'] = $book_is_payment;  //A2 是否已预付
                        if($book_is_payment == 1)  $arrayBookInfo[$room_id]['book_prepayment_date'] = getDateTime();
                        $arrayBookInfo[$room_id]['prepayment_type_id'] = $payment_type;  //A2 预付支付类型
                        //
                    } else {
                        $arrayBookInfo[$room_id]['book_is_pay'] = $is_pay;//A2 是否全额支付
                        $arrayBookInfo[$room_id]['book_is_payment'] = $book_is_payment; //A2 全额是否到帐
                        if($is_pay == 1) $arrayBookInfo[$room_id]['book_pay_date'] = getDateTime(); //A2 全额支付时间 只改变当前的book_id
                        $arrayBookInfo[$room_id]['payment_type_id'] = $payment_type;//A 支付类型

                    }
                    $updateMain['book_total_price'] = ''; //A1 支付总价
                    $updateMain['book_total_room_rate'] = ''; //A1 总房价
                    $updateMain['book_need_service_price'] = ''; //A1 附加服务费
                    $updateMain['book_service_charge'] = ''; //A1 服务费
                    $updateMain['book_total_cash_pledge'] = ''; //A1 总押金

                    //
                    $arrayBookInfo[$room_id]['book_payment_voucher'] = $objRequest -> book_payment_voucher;//A 付款凭证
                    $arrayBookInfo[$room_id]['book_days_total'] = $arrayInfo['book_days_total'];

                    $arrayBookInfo[$room_id]['book_add_date'] = getDay();
                    $arrayBookInfo[$room_id]['book_add_time'] = getTime();
                    $arrayBookInfo[$room_id]['book_add_datetime'] = getDateTime();
                    $arrayBookInfo[$room_id]['book_change'] = $book_change;
                    //房价
                    foreach($arrayInfo['rdate'] as $date => $price) {
                        $arrayDate = explode('-', $date);
                        $year = $arrayDate[0]; $month = trim($arrayDate[1] - 0); $day = $arrayDate[2];
                        $id = $year . '-' . $month . '-' . $sell_id . '-' . $room_layout_id . '-' . $system_id;
                        if($iNightAuditDate == '') $iNightAuditDate = $date;
                        //房价
                        if(!isset($arrayRoomPrice[$id])) {
                            $arrayRoomPrice[$id]['book_id'] = $arrayOldBookInfo['book_id'];
                            $arrayRoomPrice[$id]['book_order_number'] = $order_number;
                            $arrayRoomPrice[$id]['room_sell_layout_id'] = $sell_id;
                            $arrayRoomPrice[$id]['room_layout_id'] = $room_layout_id;
                            $arrayRoomPrice[$id]['room_layout_price_system_id'] = $system_id;
                            $arrayRoomPrice[$id]['employee_id'] = $employee_id;
                            $arrayRoomPrice[$id]['hotel_id'] = $hotel_id;
                            $arrayRoomPrice[$id]['room_layout_date_year'] = $year;
                            $arrayRoomPrice[$id]['room_layout_date_month'] = $month;
                        }
                        $arrayRoomPrice[$id][$day . '_day'] = $price;
                        //夜审
                        $nightAuditKey = $id . '-' . $day;
                        $arrayNightAudit[$nightAuditKey]['book_order_number'] = $order_number;
                        $arrayNightAudit[$nightAuditKey]['book_id'] = $arrayOldBookInfo['book_id'];
                        $arrayNightAudit[$nightAuditKey]['employee_id'] = $employee_id;
                        $arrayNightAudit[$nightAuditKey]['hotel_id'] = $hotel_id;
                        $arrayNightAudit[$nightAuditKey]['book_type_id'] = $arrayOldBookInfo['book_type_id'];
                        $arrayNightAudit[$nightAuditKey]['book_discount_id'] = $arrayOldBookInfo['book_discount_id'];
                        $arrayNightAudit[$nightAuditKey]['book_discount'] = $arrayOldBookInfo['book_discount'];
                        $arrayNightAudit[$nightAuditKey]['room_sell_layout_id'] = $sell_id;
                        $arrayNightAudit[$nightAuditKey]['room_layout_id'] = $room_layout_id;
                        $arrayNightAudit[$nightAuditKey]['room_layout_price_system_id'] = $system_id;
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_fiscal_day'] = $date;
                        $arrayNightAudit[$nightAuditKey]['room_id'] = $room_id;
                        $arrayNightAudit[$nightAuditKey]['price'] = $price;
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_income'] = $price;//收入（折扣后）
                        //营收类型 room：客房 service：服务类型 service_charge  服务费 extra_bed 加床
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_income_type'] = 'room';
                        $arrayNightAudit[$nightAuditKey]['book_is_check_employee_id'] = $employee_id;
                        $arrayNightAudit[$nightAuditKey]['book_is_check_add_datetime'] = getDateTime();

                    }
                }
                if($k == 'bed') {
                    foreach($arrayInfo['beddate'] as $date => $price) {
                        $arrayDate = explode('-', $date);
                        $year = $arrayDate[0]; $month = trim($arrayDate[1] - 0); $day = $arrayDate[2];
                        $id = $year . '-' . $month . '-' . $sell_id . '-' . $room_layout_id . '-' . $system_id;
                        if($iNightAuditDate == '') $iNightAuditDate = $date;
                        //房价
                        if(!isset($arrayRoomExtraBedPrice[$id])) {
                            $arrayRoomExtraBedPrice[$id]['book_id'] = $arrayOldBookInfo['book_id'];
                            $arrayRoomExtraBedPrice[$id]['book_order_number'] = $order_number;
                            $arrayRoomExtraBedPrice[$id]['room_sell_layout_id'] = $sell_id;
                            $arrayRoomExtraBedPrice[$id]['room_layout_id'] = $room_layout_id;
                            $arrayRoomExtraBedPrice[$id]['room_layout_price_system_id'] = $system_id;
                            $arrayRoomExtraBedPrice[$id]['employee_id'] = $employee_id;
                            $arrayRoomExtraBedPrice[$id]['hotel_id'] = $hotel_id;
                            $arrayRoomExtraBedPrice[$id]['room_layout_date_year'] = $year;
                            $arrayRoomExtraBedPrice[$id]['room_layout_date_month'] = $month;
                        }
                        $arrayRoomExtraBedPrice[$id][$day . '_day'] = $price;
                        //夜审
                        $nightAuditKey = $id . '-' . $day;
                        $arrayNightAudit[$nightAuditKey]['book_order_number'] = $order_number;
                        $arrayNightAudit[$nightAuditKey]['book_id'] = $arrayOldBookInfo['book_id'];
                        $arrayNightAudit[$nightAuditKey]['employee_id'] = $employee_id;
                        $arrayNightAudit[$nightAuditKey]['hotel_id'] = $hotel_id;
                        $arrayNightAudit[$nightAuditKey]['book_type_id'] = $arrayOldBookInfo['book_type_id'];
                        $arrayNightAudit[$nightAuditKey]['book_discount_id'] = $arrayOldBookInfo['book_discount_id'];
                        $arrayNightAudit[$nightAuditKey]['book_discount'] = $arrayOldBookInfo['book_discount'];
                        $arrayNightAudit[$nightAuditKey]['room_sell_layout_id'] = $sell_id;
                        $arrayNightAudit[$nightAuditKey]['room_layout_id'] = $room_layout_id;
                        $arrayNightAudit[$nightAuditKey]['room_layout_price_system_id'] = $system_id;
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_fiscal_day'] = $date;
                        $arrayNightAudit[$nightAuditKey]['room_id'] = $room_id;
                        $arrayNightAudit[$nightAuditKey]['price'] = $price;
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_income'] = $price;//收入（折扣后）
                        //营收类型 room：客房 service：服务类型 service_charge  服务费 extra_bed 加床
                        $arrayNightAudit[$nightAuditKey]['book_night_audit_income_type'] = 'extra_bed';
                        $arrayNightAudit[$nightAuditKey]['book_is_check_employee_id'] = $employee_id;
                        $arrayNightAudit[$nightAuditKey]['book_is_check_add_datetime'] = getDateTime();
                    }
                }

            }
        }

        if(!empty($service)) {
            if($iNightAuditDate == '') $iNightAuditDate = getDay();
            foreach($service as $k => $arrayData) {
                $arrayHotelService[$k]['book_id'] = $arrayOldBookInfo['book_id'];
                $arrayHotelService[$k]['book_order_number'] = $order_number;
                $arrayHotelService[$k]['hotel_service_id'] = $arrayData['id'];
                $arrayHotelService[$k]['employee_id'] = $employee_id;
                $arrayHotelService[$k]['hotel_id'] = $hotel_id;
                $arrayHotelService[$k]['hotel_service_price'] = $arrayData['service_price'];
                $arrayHotelService[$k]['book_hotel_service_num'] = $arrayData['service_num'];
                $arrayHotelService[$k]['book_hotel_service_discount'] = $arrayData['service_discount'];
                $arrayHotelService[$k]['book_hotel_service_total_price'] = $arrayData['service_total_price'];
                //夜审
                $iNightAudit = $k;
                //夜审数据
                $arrayNightAudit[$iNightAudit]['room_id'] = '0';//
                $arrayNightAudit[$iNightAudit]['hotel_service_id'] = $arrayData['id'];
                $arrayNightAudit[$iNightAudit]['hotel_service_num'] = $arrayData['service_num'];
                $arrayNightAudit[$iNightAudit]['book_order_number'] = $order_number;
                $arrayNightAudit[$iNightAudit]['book_id'] = $arrayOldBookInfo['book_id'];
                $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
                $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
                $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayOldBookInfo['book_type_id'];
                $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';//加床默认为 100 折扣
                $arrayNightAudit[$iNightAudit]['book_discount'] = $arrayData['service_discount'];
                $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = '0';
                $arrayNightAudit[$iNightAudit]['room_layout_id'] = '0';
                $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = '0';
                $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $iNightAuditDate;//财日
                $arrayNightAudit[$iNightAudit]['price'] = $arrayData['service_price'];
                $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $arrayData['service_total_price'];
                $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'extra_service';
                $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
                $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
                $arrayNightAudit[$iNightAudit]['unique_key'] = rand(100000000, 999999999);
            }
        }
        $book_service_charge = $objRequest -> book_service_charge;
        if(!empty($book_service_charge)) {
            //夜审数据
            $arrayNightAudit[$iNightAudit]['room_id'] = '0';//
            $arrayNightAudit[$iNightAudit]['hotel_service_id'] = -1;
            $arrayNightAudit[$iNightAudit]['hotel_service_num'] = 1;
            $arrayNightAudit[$iNightAudit]['book_order_number'] = $order_number;
            $arrayNightAudit[$iNightAudit]['book_id'] = $arrayOldBookInfo['book_id'];
            $arrayNightAudit[$iNightAudit]['employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['hotel_id'] = $hotel_id;
            $arrayNightAudit[$iNightAudit]['book_type_id'] = $arrayOldBookInfo['book_type_id'];
            $arrayNightAudit[$iNightAudit]['book_discount_id'] = '0';//加床默认为 100 折扣
            $arrayNightAudit[$iNightAudit]['book_discount'] = 100;
            $arrayNightAudit[$iNightAudit]['room_sell_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_id'] = '0';
            $arrayNightAudit[$iNightAudit]['room_layout_price_system_id'] = '0';
            $arrayNightAudit[$iNightAudit]['book_night_audit_fiscal_day'] = $iNightAuditDate;//算在第一天的夜审 财日
            $arrayNightAudit[$iNightAudit]['price'] = $book_service_charge;
            $arrayNightAudit[$iNightAudit]['book_night_audit_income'] = $book_service_charge;
            $arrayNightAudit[$iNightAudit]['book_night_audit_income_type'] = 'service_charge';
            $arrayNightAudit[$iNightAudit]['book_is_check_employee_id'] = $employee_id;
            $arrayNightAudit[$iNightAudit]['book_is_check_add_datetime'] = getDateTime();
            $arrayNightAudit[$iNightAudit]['unique_key'] = rand(100000000, 999999999);
        }


        //事务开启

        BookDao::instance()->startTransaction();
        //先更新
        if(!empty($arrayUpdateRoom)) {
            foreach($arrayUpdateRoom as $room_id => $arrayUpdate) {
                $where = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'room_id'=>$room_id);
                BookDao::instance()->setTable('book')->update($where, $arrayUpdate);
            }
        }
        if(!empty($arrayUpdateNightAudit )) {
            foreach($arrayUpdateNightAudit  as $room_id => $arrayUpdate) {
                $where = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'room_id'=>$room_id,
                    'book_is_night_audit'=>'0', 'book_night_audit_valid'=>'1');
                BookDao::instance()->setTable('book_night_audit')->update($where, $arrayUpdate);
            }
        }
        if(!empty($arrayBookInfo)) {
            BookDao::instance()->setTable('book')->batchInsert($arrayBookInfo);
        }
        if(!empty($arrayRoomPrice)) {
            BookDao::instance()->setTable('book_room_price')->batchInsert($arrayRoomPrice);
        }
        if(!empty($arrayRoomExtraBedPrice)) {
            BookDao::instance()->setTable('book_room_extra_bed_price')->batchInsert($arrayRoomExtraBedPrice);
        }
        if(!empty($arrayHotelService)) {
            BookDao::instance()->setTable('book_hotel_service')->batchInsert($arrayHotelService);
        }
        BookDao::instance()->commit();
        return array(1,'');
    }
    public function getBookInfo($objRequest, $objResponse) {
        $thisDay = $objRequest -> time_begin;
        $toDay = $objRequest -> time_end;
        $thisDay = empty($thisDay) ? getDay() : $thisDay;
        $toDay = empty($toDay) ? getDay(7*24) : $toDay;
        $user_name = $objRequest -> user_name;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];

        $arrayPostValue = $objRequest -> getPost();
        $conditions = DbConfig::$db_query_conditions;
        if(!empty($arrayPostValue)) {
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                '>='=>array('book_check_in'=>$thisDay));//'<='=>array('book_check_out'=>$toDay)
            if(!empty($user_name)) {
                $conditions['where'] = array('hotel_id'=>$hotel_id,
                    '>='=>array('book_check_in'=>$thisDay),
                    'LIKE'=>array('book_contact_name'=>$user_name));//'<='=>array('book_check_out'=>$toDay)
            }
        } else {
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                '>='=>array('book_order_number_main_status'=>'0'));//'<='=>array('book_check_out'=>$toDay)
        }
        $conditions['order'] = 'book_check_in ASC, book_order_number ASC, book_order_number_main DESC';
        $arrayBookInfo = BookService::instance()->getBook($conditions);
        $conditions['order'] = '';
        $arrayBookList = array();
        if(!empty($arrayBookInfo)) {
            foreach($arrayBookInfo as $i => $arrayBook) {
                $arrayBook['edit_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit']),'order_number'=>encode($arrayBook['book_order_number'])));
                if($arrayBook['book_order_number_main'] == '1') {
                    $arrayBookList[$arrayBook['book_order_number']]['number_main'] = $arrayBook;
                    $arrayBookList[$arrayBook['book_order_number']]['child'][] = $arrayBook;
                } else {
                    $arrayBookList[$arrayBook['book_order_number']]['child'][] = $arrayBook;

                }
                if(isset($arrayBookList[$arrayBook['book_order_number']]['room_num'])) {
                    $arrayBookList[$arrayBook['book_order_number']]['room_num'] = $arrayBookList[$arrayBook['book_order_number']]['room_num'] + 1;
                } else {
                    $arrayBookList[$arrayBook['book_order_number']]['room_num'] = 1;
                }
            }
        }
        sort($arrayBookList);
        //
        $objResponse -> arrayBookList = $arrayBookList;
        $objResponse -> thisYear = getYear();
        $objResponse -> thisMonth = getMonth();
        $objResponse -> thisDay = $thisDay;
        $objResponse -> toDay = $toDay;
        $objResponse -> user_name = $user_name;
    }

    public function getHaveCheckInRoom($conditions, $hotel_id, $book_check_in, $book_check_out) {
        //step1 {begin} 取得已住房间
        //$hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions['where'] = "hotel_id = '".$hotel_id."' AND book_order_number_status >= 0 AND "
            ."(book_check_in <= '".$book_check_in."' AND '".$book_check_in."' < book_check_out) "
            ."OR ('".$book_check_in."' <= book_check_in AND book_check_in < '".$book_check_out."')";
        $arrayISBookRoomLayout = BookService::instance()->getBook($conditions, 'room_id, room_layout_id');
        /************************************************************************************************/
        $arrayRoomId = array();
        if(!empty($arrayISBookRoomLayout)) {
            foreach($arrayISBookRoomLayout as $k => $v) {
                $arrayRoomId[$v['room_id']] = $v['room_id'];
            }
        }
        //step1 {end} 取得已住房间
        return $arrayRoomId;
    }

    public function searchISBookRoomLayout($objRequest, $objResponse) {
        $conditions = DbConfig::$db_query_conditions;
        $book_check_in = $objRequest -> book_check_in;
        $book_check_out = $objRequest -> book_check_out;
        $max_check_out = $objRequest -> max_check_out;
        $arrayBookCheckIn = explode('-', $book_check_in);
        $arrayBookCheckOut = explode('-', $book_check_out);
        $arrayBookMaxCheckOut = explode('-', $max_check_out);
        $sell_layout_list = $objRequest -> sell_layout_list;
        $sell_layout_list = trim($sell_layout_list, ',');
        if(empty($sell_layout_list)) {
            return '';
        }
        $layout_corp = $objRequest -> layout_corp;
        $layout_corp = empty($layout_corp) ? '0' : $layout_corp;
        $arraySellIdSystemID = explode(',', $sell_layout_list);
        $arraySellSystem = '';
        foreach($arraySellIdSystemID as $i => $value) {
            $arrayKey = explode('-', $value);
            $arraySellSystem[$arrayKey[0]][] = $arrayKey[1];
        }
        $whereSqlStr = '';
        $or = '';
        foreach($arraySellSystem as $sell_id => $arraySystemId) {
            $whereSqlStr .= $or . ' (room_sell_layout_id = '.$sell_id.' AND room_layout_price_system_id IN('.implode(',', $arraySystemId).')) ';
            $or = ' OR';
        }
        $whereSqlStr = '('.$whereSqlStr.')';
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        //$arrayRoomId = $this->getHaveCheckInRoom($conditions, $hotel_id, $book_check_in, $book_check_out);
        //step1 {end} 取得已住房间

        //{begin} 查找房型房价
        $conditions['where'] = array('hotel_id'=>$hotel_id,'room_layout_corp_id'=>$layout_corp,
            '>='=>array('room_layout_price_begin_datetime'=>$arrayBookCheckIn[0] . '-' . $arrayBookCheckIn[1] . '-01'),
            '<='=>array('room_layout_price_begin_datetime'=>$arrayBookMaxCheckOut[0] . '-' . $arrayBookMaxCheckOut[1] . '-28'),
            '-'=>$whereSqlStr);
        $fieid = 'room_layout_price_id, room_sell_layout_id sell_layout_id, room_layout_price_system_id,room_layout_price_begin_datetime,'
            .'room_layout_date_year this_year,room_layout_date_month this_month,room_layout_corp_id layout_corp,';
        for($i = 1; $i <= 31; $i++) {
            $day = $i < 10 ? '0' . $i . '_day,' : $i . '_day,';
            $fieid .= $day;
        }
        $fieid = trim($fieid, ',');
        $conditions['order'] = 'room_layout_id ASC, room_layout_price_system_id ASC';
        $arrayLayoutPrice = RoomService::instance()->getRoomLayoutPrice($conditions, $fieid);
        $arrayLayoutPrice = empty($arrayLayoutPrice) ? '' : $arrayLayoutPrice;
        /************************************************************************************************/
        //{end} 查找房型房价
        //加床房价
        $fieid = 'room_sell_layout_id sell_layout_id, room_layout_id, room_layout_price_system_id,room_layout_price_begin_datetime,room_layout_date_year this_year,room_layout_date_month this_month,';
        for($i = 1; $i <= 31; $i++) {
            $day = $i < 10 ? '0' . $i . '_day,' : $i . '_day,';
            $fieid .= $day;
        }
        $fieid = trim($fieid, ',');
        $arrayLayoutExtraBedPrice = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions, $fieid);
        /************************************************************************************************/
        $conditions['order'] = '';


        $arrayBookPriceSystem['layoutPrice'] = $arrayLayoutPrice;
        $arrayBookPriceSystem['extraBedPrice'] = $arrayLayoutExtraBedPrice;
        return $arrayBookPriceSystem;
    }

    public function searchISBookRoomLayoutOld($objRequest, $objResponse) {
        $conditions = DbConfig::$db_query_conditions;
        $book_check_in = $objRequest -> book_check_in;
        $book_check_out = $objRequest -> book_check_out;
        $max_check_out = $objRequest -> max_check_out;
        $arrayBookCheckIn = explode('-', $book_check_in);
        $arrayBookCheckOut = explode('-', $book_check_out);
        $arrayBookMaxCheckOut = explode('-', $max_check_out);
        $hotel_service = $objRequest -> hotel_service;
        $hotel_service = trim($hotel_service, ',');
        if(empty($hotel_service)) {
            return '';
        }
        //step1 {begin} 取得已住房间
        /*$hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions['where'] = "hotel_id = '".$hotel_id."' AND book_order_number_status >= 0 AND "
                                          ."(book_check_in <= '".$book_check_in."' AND '".$book_check_in."' < book_check_out) "
                                          ."OR ('".$book_check_in."' <= book_check_in AND book_check_in < '".$book_check_out."')";
        $arrayISBookRoomLayout = BookService::instance()->getBook($conditions, 'room_id, room_layout_id');

        $arrayRoomId = array();
        if(!empty($arrayISBookRoomLayout)) {
            foreach($arrayISBookRoomLayout as $k => $v) {
                $arrayRoomId[] = $v['room_id'];
            }
        }*/
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $arrayRoomId = $this->getHaveCheckInRoom($conditions, $hotel_id, $book_check_in, $book_check_out);
        //step1 {end} 取得已住房间

        //{begin} 取得未住的房间和房型
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        if(!empty($arrayRoomId))
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                                         'NOT IN'=>array('room_id'=>$arrayRoomId));
        $arrayRoomLayoutRoom = RoomService::instance()->getRoomLayoutRoom($conditions,
            'room_layout_id,room_id,room_layout_room_extra_bed,room_layout_room_max_people max_people,room_layout_room_max_children max_children','room_layout_id',true);
        /************************************************************************************************/
        //$arrayRoomLayoutId[0] = 0;
        $arrayRoomLayoutId = $arrayRoomSellLayoutId = $arrayRoomSellLayout = $arraySellLayout = '';
        $roomLayoutPriceSystem = $arrayLayoutPrice = $arrayLayoutExtraBedPrice = '';
        $arrayBookPriceSystem['room'] = $arrayRoomLayoutRoom;
        if(!empty($arrayRoomLayoutRoom)) {
            //查找售卖房型
            $conditions['where'] = array('hotel_id'=>$hotel_id,'room_sell_layout_valid'=>1);
            $arrayRoomSellLayout = RoomService::instance()->getRoomSellLayout($conditions,
                'room_sell_layout_id, room_layout_id, room_sell_layout_name', 'room_layout_id', true);
            /************************************************************************************************/
            foreach($arrayRoomLayoutRoom as $room_layout_id => $value) {
                if(isset($arrayRoomSellLayout[$room_layout_id])) {
                    $arrayRoomLayoutId[$room_layout_id] = $room_layout_id;//这个房型正在售卖才算数
                    foreach($arrayRoomSellLayout[$room_layout_id] as $i => $arraySellLayout) {
                        $arrayRoomSellLayoutId[$arraySellLayout['room_sell_layout_id']] = $arraySellLayout['room_sell_layout_id'];
                        //$arraySellLayout[$arraySellLayout['room_sell_layout_id']] = $arraySellLayout;
                    }
                }
            }
            foreach($arrayRoomSellLayout as $room_layout_id => $arrayValues) {
                foreach($arrayValues as $i => $arrayItem) {
                    $arraySellLayout[$arrayItem['room_sell_layout_id']] = $arrayItem;
                }
            }
            //{begin} 根据hotel_server_id查找价格体系
            $arrayRoomLayoutPriceSystemId = '';
            $base_price_system = 0;
            if(strpos($hotel_service, '-1') !== false) {//单是基本房价
                $base_price_system = 1;
                $hotel_service = str_replace('-1','',$hotel_service);
                $hotel_service = str_replace(',,',',',$hotel_service);
                $hotel_service = trim($hotel_service, ',');
            }
            $hotel_service = str_replace(',',"','",$hotel_service);

            if(!empty($hotel_service)) {
                $conditions['where'] = array('rsf.hotel_id'=>$hotel_id, 'rs.`room_layout_price_system_valid`'=>1,
                                             'IN'=>array('rsf.hotel_service_id'=>$hotel_service));

                $table = '`room_layout_price_system_filter` rsf LEFT JOIN `room_layout_price_system` rs ON rs.`room_layout_price_system_id` = rsf.`room_layout_price_system_id`';
                $field = 'DISTINCT(rs.`room_layout_price_system_id`),rs.`room_layout_price_system_name`';
                $conditions['order'] = 'rs.`room_sell_layout_id`';
                $roomLayoutPriceSystem = RoomDao::instance()->setTable($table)->getList($conditions, $field, 'room_layout_price_system_id');
                /************************************************************************************************/
                $conditions['order'] = '';
            }
            if($base_price_system == 1) {
                $roomLayoutPriceSystem[1]['room_layout_price_system_id'] = 1;
                $roomLayoutPriceSystem[1]['room_layout_price_system_name'] = $objResponse->arrayLaguage['base_room_price']['page_laguage_value'];
            }
            if(!empty($roomLayoutPriceSystem)) {
                foreach($roomLayoutPriceSystem as $room_layout_price_system_id => $arrayValue) {
                    $arrayRoomLayoutPriceSystemId[] = $room_layout_price_system_id;
                }
            }

            //{end}
            //{begin} 查找房型房价
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                '>='=>array('room_layout_price_begin_datetime'=>$arrayBookCheckIn[0] . '-' . $arrayBookCheckIn[1] . '-01'),
                '<='=>array('room_layout_price_begin_datetime'=>$arrayBookMaxCheckOut[0] . '-' . $arrayBookMaxCheckOut[1] . '-28'),
                'IN'=>array('room_sell_layout_id'=>$arrayRoomSellLayoutId,'room_layout_price_system_id'=>$arrayRoomLayoutPriceSystemId));
            $fieid = 'room_layout_price_id, room_sell_layout_id sell_layout_id, room_layout_price_system_id,room_layout_price_begin_datetime,'
                    .'room_layout_date_year this_year,room_layout_date_month this_month,';
            for($i = 1; $i <= 31; $i++) {
                $day = $i < 10 ? '0' . $i . '_day,' : $i . '_day,';
                $fieid .= $day;
            }
            $fieid = trim($fieid, ',');
            $conditions['order'] = 'room_layout_id ASC, room_layout_price_system_id ASC';
            $arrayLayoutPrice = RoomService::instance()->getRoomLayoutPrice($conditions, $fieid);
            /************************************************************************************************/
            //{end} 查找房型房价
            //加床房价
            $fieid = 'room_sell_layout_id sell_layout_id, room_layout_id, room_layout_price_system_id,room_layout_price_begin_datetime,room_layout_date_year this_year,room_layout_date_month this_month,';
            for($i = 1; $i <= 31; $i++) {
                $day = $i < 10 ? '0' . $i . '_day,' : $i . '_day,';
                $fieid .= $day;
            }
            $fieid = trim($fieid, ',');
            $arrayLayoutExtraBedPrice = RoomService::instance()->getRoomLayoutExtraBedPrice($conditions, $fieid);
            /************************************************************************************************/
            $conditions['order'] = '';

        }
        //$conditions['where'] = array('hotel_id'=>$hotel_id,'room_layout_valid'=>1);
        //$fieid = 'room_layout_id,room_layout_name,room_layout_max_people max_people,room_layout_max_children max_children,room_layout_orientations';
        //$arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions, $fieid, 'room_layout_id');
        $arrayBookPriceSystem['layoutPrice'] = $arrayLayoutPrice;
        $arrayBookPriceSystem['priceSystem'] = $roomLayoutPriceSystem;
        $arrayBookPriceSystem['roomSellLayout'] = $arraySellLayout;
        $arrayBookPriceSystem['extraBedPrice'] = $arrayLayoutExtraBedPrice;
        return $arrayBookPriceSystem;


        /*$conditions['where'] = array('rl.hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
            'NOT IN'=>array('rlr.room_id'=>$arrayRoomId));
        $conditions['group'] = 'rlp.`room_layout_id`';
        $table = "`room_layout_room` rlr LEFT JOIN `room_layout` rl ON rlr.`room_layout_id` = rl.room_layout_id "
            ."LEFT JOIN `room_layout_price` rlp ON rlp.`room_layout_id` = rlr.`room_layout_id` AND rlp.`room_layout_price_is_active` = '1'";
        $fieid = 'COUNT(rlp.`room_layout_id`) room_layout_num, rlp.`room_layout_price`, rlp.room_layout_extra_bed_price, rl.*';//rlr.`room_id`*/
        //return BookDao::instance()->setTable($table)->getList($conditions, $fieid);
    }


}