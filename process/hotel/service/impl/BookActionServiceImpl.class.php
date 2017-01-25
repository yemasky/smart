<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2016/12/1
 * Time: 12:39
 */

namespace hotel;


use vakata\database\Exception;

class BookActionServiceImpl extends \BaseService  {
    private static $objService = null;

    public static function instance() {
        // TODO: Implement instance() method.
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookActionServiceImpl();
        return self::$objService;
    }

    public function doEditBookAction($objRequest, $objResponse) {
        $order_number = decode($objRequest -> order_number);

        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number);
        $conditions['order'] = 'book_order_number_main DESC';
        $arrayBookInfo = BookService::instance()->getBook($conditions);
        $conditions['order'] = '';
        if(!empty($arrayBookInfo)) {
            //协议公司
            $arrayLayoutCorp = '';
            if($arrayBookInfo[0]['room_layout_corp_id'] > 0) {
                $conditions['where'] = array('hotel_id'=>$hotel_id, 'room_layout_corp_id'=>$arrayBookInfo[0]['room_layout_corp_id']);
                $arrayLayoutCorp = RoomService::instance()->getRoomLayoutCorp($conditions, '*', 'room_layout_corp_id');
            }
            $objResponse -> arrayLayoutCorp = $arrayLayoutCorp;
            $this -> showBookInfo($objRequest, $objResponse, $arrayBookInfo, $order_number);
        } else {
            $objResponse -> message_http404 = '没找到相关订单记录！';
            $objResponse->setTplName("hotel/modules_http404");
            return;
        }
    }

    protected function showBookInfo($objRequest, $objResponse, $arrayBookInfo, $order_number) {
        $arrayRoomId = '';$arraySellLayoutId = '';$arrayLayoutId = '';$arraySystemID = '';
        foreach($arrayBookInfo as $k => $arrayBook) {
            $arrayRoomId[]       = $arrayBook['room_id'];
            $arraySellLayoutId[] = $arrayBook['room_sell_layout_id'];
            $arrayLayoutId[]     = $arrayBook['room_layout_id'];
            $arraySystemID[]     = $arrayBook['room_layout_price_system_id'];
        }
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        //房间
        $conditions['where'] = array('hotel_id'=>$hotel_id,'IN'=>array('room_id'=>$arrayRoomId));
        $arrayRoomInfo = RoomService::instance()->getRoom($conditions, '*', 'room_id');
        //房型
        $conditions['where'] = array('hotel_id'=>$hotel_id,'room_sell_layout_valid'=>1);
        $arraySellLayout = RoomService::instance()->getRoomSellLayout($conditions, '*', 'room_sell_layout_id');
        //价格体系
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$hotel_id)),'room_layout_price_system_valid'=>1);
        $arrayPriceSystem = RoomService::instance()->getRoomLayoutPriceSystem($conditions, '*', 'room_layout_price_system_id');
        //入住信息
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_order_number'=>$order_number);
        $arrayBookUser = BookService::instance()->getBookUser($conditions);
        //入住房价信息
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_order_number'=>$order_number);
        $arrayBookRoomPrice = BookService::instance()->getBookRoomPrice($conditions, '*', 'room_sell_layout_id');
        //入住加床价格信息
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_order_number'=>$order_number);
        $arrayBookRoomExtraBedPrice = BookService::instance()->getBookRoomExtraBedPrice($conditions, '*', 'room_sell_layout_id');
        //附加服务信息
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_order_number'=>$order_number);
        $arrayBookHotelService = BookService::instance()->getBookHotelService($conditions);
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $conditions['order'] = 'hotel_service_father_id ASC';
        $arrayHotelService = HotelService::instance()->getHotelService($conditions, '*', 'hotel_service_id');
        $conditions['order'] = '';
        //支付方式
        $conditions['where'] = array('IN'=>array('hotel_id'=>array('0', $hotel_id)));
        $conditions['order'] = 'payment_type_id ASC';
        $arrayPaymentType = HotelService::instance()->getHotelPaymentType($conditions, '*', 'payment_type_id');
        $conditions['order'] = '';
        //来源
        $conditions['where'] = array('IN'=>array('hotel_id'=>array($hotel_id,0)));
        $arrayBookType = BookService::instance()->getBookType($conditions, '*', 'book_type_id');
        //变更历史
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_order_number'=>$order_number);
        $arrayBookChange = BookService::instance()->getBookChange($conditions, '*', 'book_type_id');

        //hotel info
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayHotel = HotelService::instance()->getHotel($conditions);
        $hotel_checkout = empty($arrayHotel[0]['hotel_checkout']) ? '12:00' : $arrayHotel[0]['hotel_checkout'];
        $hotel_checkin = empty($arrayHotel[0]['hotel_checkin']) ? '06:00' : $arrayHotel[0]['hotel_checkin'];
        $hotel_overtime = empty($arrayHotel[0]['hotel_overtime']) ? '18:00' : $arrayHotel[0]['hotel_overtime'];
        //赋值
        $objResponse -> idCardType = ModulesConfig::$idCardType;
        $objResponse -> orderStatus = ModulesConfig::$orderStatus;
        $objResponse -> arrayDataInfo    = $arrayBookInfo;
        $objResponse -> arrayBookRoomPrice    = $arrayBookRoomPrice;
        $objResponse -> arrayBookRoomExtraBedPrice    = $arrayBookRoomExtraBedPrice;
        $objResponse -> arrayRoomInfo    = $arrayRoomInfo;
        $objResponse -> arraySellLayout  = $arraySellLayout;
        $objResponse -> arrayPriceSystem = $arrayPriceSystem;
        $objResponse -> arrayBookUser = $arrayBookUser;
        $objResponse -> arrayBookHotelService = $arrayBookHotelService;
        $objResponse -> arrayHotelService = $arrayHotelService;
        $objResponse -> arrayPaymentType = $arrayPaymentType;
        $objResponse -> arrayBookType = $arrayBookType;
        $objResponse -> arrayBookChange = $arrayBookChange;
        $objResponse -> thisDay = getDay();
        $objResponse -> thisDayTime = getDateTime();

        $objResponse -> hotel_checkout = $hotel_checkout;
        $objResponse -> hotel_checkin  = $hotel_checkin;
        $objResponse -> hotel_overtime  = $hotel_overtime;
        $objResponse -> searchBookInfoUrl =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['add'])));
        $objResponse -> saveBookInfoUrl =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit']),'order_number'=>encode($order_number)));
    }

    public function doSaveEditbookAction($objRequest, $objResponse) {
        return BookOperateService::instance()->saveEditReBookInfo($objRequest, $objResponse);
    }

    public function doSaveEditBookPayment($objRequest, $objResponse) {
        $order_number = decode($objRequest -> order_number);
        $book_is_pay = $objRequest -> book_is_pay;
        $book_is_pay = $book_is_pay == 2 ? '0' : '1';
        $book_payment = $objRequest -> book_payment;
        $book_payment = $book_payment < 2 ? '0' : '1';
        $book_payment_type = $objRequest -> book_payment_type;
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'book_order_number'=>$order_number);
        $arrayUpdate['book_is_pay'] = $book_is_pay;
        $arrayUpdate['book_is_payment'] = $book_payment;
        $arrayUpdate['payment_type_id'] = $book_payment_type;
        if($book_is_pay > 0) $arrayUpdate['book_pay_date'] = getDateTime();
        BookService::instance()->updateBook($conditions['where'], $arrayUpdate);
    }

    public function doEditCheckInRoom($objRequest, $objResponse) {
        $order_number = decode($objRequest -> order_number);
        $book_id = $objRequest -> book_id;
        $room_id = $objRequest -> room_id;
        $conditions = DbConfig::$db_query_conditions;
        if($book_id == 'ALL' && $room_id = 'ALL') {
            $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'book_order_number'=>$order_number,
                'book_order_number_status'=>'0');
            $arrayUpdate['book_order_number_main_status'] = '1';
        } else {
            $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'book_order_number'=>$order_number,
                'book_order_number_status'=>'0', 'book_id'=>$book_id, 'room_id'=>$room_id);
        }
        $arrayUpdate['book_order_number_status'] = '1';
        BookService::instance()->updateBook($conditions['where'], $arrayUpdate);

    }

    public function doAddBookUser($objRequest, $objResponse) {
        $arraySaveData['book_id'] = $objRequest -> book_id;//
        $arraySaveData['employee_id'] = $objResponse->arrayLoginEmployeeInfo['employee_id'];//
        $arraySaveData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $arraySaveData['book_order_number'] = decode($objRequest -> order_number);
        $arraySaveData['room_id'] = $objRequest -> room_num;//
        $arraySaveData['room_sell_layout_id'] = $objRequest -> sell_layout_id;//
        $arraySaveData['room_layout_id'] = $objRequest -> layout_id;//
        //
        $arraySaveData['book_user_name'] = $objRequest->room_user_name;
        $arraySaveData['book_user_sex'] = $objRequest->room_user_sex;
        $arraySaveData['book_user_lodger_type'] = $objRequest->user_lodger_type;
        $arraySaveData['book_user_id_card_type'] = $objRequest->user_id_card_type;
        $arraySaveData['book_user_id_card'] = $objRequest->user_id_card;
        $arraySaveData['book_user_comments'] = $objRequest->user_comments;

        $arraySaveData['book_add_date'] = getDay();
        $arraySaveData['book_add_time'] = getTime();
        BookService::instance()->saveBookUser($arraySaveData);
    }

    public function setUserRoomCard($objRequest, $objResponse) {
        $type = $objRequest -> val;
        $where['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $where['book_order_number'] = decode($objRequest -> order_number);
        $where['book_user_id'] = $objRequest -> id;//
        if($where['book_user_id'])
        $book_user_room_card = 0;
        if($type == 1) $book_user_room_card = 1;
        if($type == 2) $book_user_room_card = 2;
        $arrayUpdate['book_user_room_card'] = $book_user_room_card;
        return BookService::instance()->updateBookUser($where, $arrayUpdate);
    }

    //退订
    public function returnBook($objRequest, $objResponse) {
        //计算退款金额 订单
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $arrayRoomBookId = json_decode(stripslashes($objRequest->room_id), true);
        $arrayRoomId = '';
        if(!empty($arrayRoomBookId)) {
            foreach($arrayRoomBookId as $room_id =>$book_id) {
                if(empty($book_id)) {
                    unset($arrayRoomBookId[$room_id]);
                } else {
                    $arrayRoomId[] = $room_id;
                }
            }
        }
        $order_number = decode($objRequest -> order_number);
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number,
                                     '>='=>array('book_order_number_status'=>'0'),
                                     'IN'=>array('room_id'=>$arrayRoomId));
        $field = 'book_id, room_id, book_room_price, book_room_price, book_extra_bed_price, book_cash_pledge, book_cash_pledge_returns, book_total_price, book_total_room_rate,
            book_need_service_price, book_service_charge, book_total_cash_pledge, book_is_pay, book_is_payment, payment_type_id, book_prepayment_price,
            book_is_prepayment, prepayment_type_id, book_order_number_main, book_order_number_main, book_order_number_main_status, book_order_number_status,
            book_half_price, book_days_total';
        $arrayBookInfo = BookService::instance()->getBook($conditions, $field, 'room_id');
        if(empty($arrayBookInfo)) {
            throw new \Exception("订单错误，找不到数据.");
        }

        $arrayBookMainInfo = '';$arrayRoomId = '';$arrayRoomConsume = '';
        foreach($arrayBookInfo as $room_id => $arrayData){
            if($arrayData['book_order_number_main'] == 1) {
                $arrayBookMainInfo = $arrayData;
            }
            $arrayRoomId[$arrayData['room_id']] = $arrayData['room_id'];
            $arrayRoomConsume[$arrayData['room_id']]['cash_pledge'] = $arrayData['book_cash_pledge'];//押金
            $arrayRoomConsume[$arrayData['room_id']]['days'] = $arrayData['book_days_total'];//天数
            $arrayRoomConsume[$arrayData['room_id']]['return_room_rate'] = 0;
            $arrayRoomConsume[$arrayData['room_id']]['return_cash_pledge'] = 0;
            if($arrayData['book_is_prepayment'] == 1 || $arrayData['book_is_pay'] == 1) {
                if($arrayData['payment_type_id'] != '9' && $arrayData['prepayment_type_id'] != '9') {
                    $arrayRoomConsume[$arrayData['room_id']]['return_cash_pledge'] = $arrayData['book_cash_pledge'];//退押金
                }
            }

        };
        if(empty($arrayBookMainInfo)) {
            $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number_main'=>1, 'book_order_number'=>$order_number);
            $arrayBookMainInfo = BookService::instance()->getBook($conditions);
            if(!empty($arrayBookMainInfo)) {
                $arrayBookMainInfo = $arrayBookMainInfo[0];
            } else {
                throw new \Exception("订单错误，找不到主订单.");
            }
        }

        //夜审数据 book_night_audit
        //退款 客房退款 -- 服务退款
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number,//未夜审
            'book_night_audit_valid'=>'1',//有效
            'IN'=>array('room_id'=>$arrayRoomId));
        $conditions['order'] = 'book_night_audit_fiscal_day ASC';
        $arrayNightAudit = BookService::instance()->getBookNightAudit($conditions);
        $conditions['order'] = '';

        //计算公式： 总收款 = (房费 + 加床费 + 附加服务费 + 服务费) - 以消费的费用 = 余额 ，退押金 =  押金 - 超额消费
        //全额是否到帐 0 book_is_payment 未到账
        //计算以消费


        $book_total_price = 0;//总额
        if($arrayBookMainInfo['book_is_payment'] == '0') {
            if($arrayBookMainInfo['book_is_prepayment'] == '0') {//未预付 什么都没有退
            } else {
                $book_total_price = $arrayBookMainInfo['book_prepayment_price'];//预付费就是总额
            }
        } else {
            //已到账
            $book_total_price = $arrayBookMainInfo['book_total_price'];
        }
        //已消费
        //当天日期
        $thisDay = getDay();
        $checkoutDate = getDay(-24);//结算日期 昨天
        //现在结算时间
        $thisHoue = date("H");
        $checkoutHalf = 0;
        //半天消费计算
        //hotel info
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayHotel = HotelService::instance()->getHotel($conditions);
        $hotel_checkout = empty($arrayHotel[0]['hotel_checkout']) ? '12:00' : $arrayHotel[0]['hotel_checkout'];
        $hotel_overtime = $arrayBookMainInfo['book_half_price'];//半天时间
        if($thisHoue > $hotel_checkout && $thisHoue < $hotel_overtime) {//半天
            $checkoutHalf = 1;$checkoutDate = getDay();
        }
        if($thisHoue > $hotel_overtime) {//加1天
            $checkoutDate = getDay();
        }
        //消费的金额
        foreach($arrayNightAudit as $i => $arrayData) {
            $room_id = $arrayData['room_id'];
            if(!isset($arrayRoomConsume[$room_id]['consume'])) {
                $arrayRoomConsume[$room_id]['consume'] = 0;
                $arrayRoomConsume[$room_id]['count_days'] = 1;
                $arrayRoomConsume[$room_id]['have_lived'] = 0;
                $arrayRoomConsume[$room_id]['room_rate'] = 0;
                $arrayRoomConsume[$room_id][''] = 0;
            } else {
                $arrayRoomConsume[$room_id]['count_days']++;
            }
            $arrayRoomConsume[$room_id]['room_rate'] += $arrayData['book_night_audit_income'];
            if($checkoutDate <= $arrayData['book_night_audit_fiscal_day']) {
                if($arrayData['room_id'] > 0 && $arrayData['book_night_audit_income_type'] == 'room' &&
                    $arrayData['book_is_night_audit'] == '1' && $arrayData['book_night_audit_valid'] == '1') {
                    $arrayRoomConsume[$room_id]['have_lived']++;
                    $arrayRoomConsume[$room_id]['consume'] += $arrayData['book_night_audit_income'];
                }
            }
        }
        $arrayResunt['return']['return_room_rate'] = 0;
        $arrayResunt['return']['return_cash_pledge'] = 0;
        if($arrayBookMainInfo['book_is_prepayment'] == '1' || $arrayBookMainInfo['book_is_pay'] == '1') {
            if($arrayBookMainInfo['payment_type_id'] != '9' && $arrayBookMainInfo['prepayment_type_id'] != '9') {
                foreach($arrayRoomConsume as $room_id => $arrayPrice) {
                    $arrayRoomConsume[$room_id]['return_room_rate'] = 0;
                    if($arrayBookMainInfo['book_is_pay'] == 1) {
                        $arrayRoomConsume[$room_id]['return_room_rate'] = $arrayRoomConsume[$room_id]['room_rate'] - $arrayRoomConsume[$room_id]['consume'];
                    }
                    $arrayResunt['return']['return_room_rate'] += $arrayRoomConsume[$room_id]['return_room_rate'];
                    $arrayResunt['return']['return_cash_pledge'] += $arrayRoomConsume[$room_id]['return_cash_pledge'];
                }
            }
        }
        //print_r($arrayNightAudit);
        $arrayResunt['room'] = $arrayRoomConsume;
        return $arrayResunt;

    }

    public function returnBookPrice($objRequest, $objResponse) {
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $employee_id = $objResponse->arrayLoginEmployeeInfo['employee_id'];
        $arrayRoomId = json_decode(stripslashes($objRequest->room_id), true);
        $arrayReturn = json_decode(stripslashes($objRequest->return), true);
        $order_number = decode($objRequest -> order_number);
        BookService::instance()->startTransaction();
        if(!empty($arrayRoomId)) {
            foreach($arrayRoomId as $room_id =>$book_id) {
                if(empty($book_id)) {
                    unset($arrayRoomId[$room_id]);
                } else {
                    //更新book
                    $where = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'room_id'=>$room_id);
                    $arrayUpdateData['book_cash_pledge_returns'] = '1';
                    $arrayUpdateData['book_order_number_status'] = '-1';
                    $arrayUpdateData['book_total_cash_pledge_returns'] = '2';
                    BookService::instance()->updateBook($where, $arrayUpdateData);
                    //插入数据
                    $arrayInsert['employee_id'] = $employee_id;
                    $arrayInsert['hotel_id'] = $hotel_id;
                    $arrayInsert['book_id'] = $book_id;
                    $arrayInsert['room_id'] = $room_id;
                    $arrayInsert['book_order_number'] = $order_number;
                    $arrayInsert['book_returns_date'] = getDay();
                    $arrayInsert['book_returns_price'] = $arrayReturn['room'][$room_id]['return_room_rate'];
                    $arrayInsert['book_returns_cash_pledge'] = $arrayReturn['room'][$room_id]['cash_pledge'];
                    $arrayInsert['book_returns_type'] = 'room';
                    $arrayInsert['book_returns_add_date'] = getDateTime();
                    BookService::instance()->saveBookReturns($arrayInsert);
                    //更新夜审数据
                    $where = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'room_id'=>$room_id);
                    $arrayUpdateNightAudit['book_night_audit_valid_reason'] = '-1';
                    $arrayUpdateNightAudit['book_night_audit_valid'] = '0';
                    BookService::instance()->updateBookNightAudit($where, $arrayUpdateNightAudit);

                }
            }
        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number);
        $field = 'book_id, room_id, book_order_number_main, book_order_number_main, book_order_number_main_status, book_order_number_status,'
                 .'book_cash_pledge_returns';
        $arrayBookInfo = BookService::instance()->getBook($conditions, $field, 'room_id');
        $book_cash_pledge_returns = 0;
        if(!empty($arrayBookInfo)) {
            foreach($arrayBookInfo as $room_id => $arrayBook) {
                if($arrayBook['book_cash_pledge_returns'] == '1') $book_cash_pledge_returns++;
            }
            if($book_cash_pledge_returns == count($arrayBookInfo)) {//押金全部退完
                $where = array('hotel_id'=>$hotel_id, 'book_order_number'=>$order_number, 'book_order_number_main'=>'1');
                $arrayUpdateData['book_total_cash_pledge_returns'] = '1';
                $arrayUpdateData['book_order_number_main_status'] = '-1';
                BookService::instance()->updateBook($where, $arrayUpdateData);
            }
        }
        BookService::instance()->commit();
    }
}