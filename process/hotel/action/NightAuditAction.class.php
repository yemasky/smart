<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;

class NightAuditAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'edit':
                $this->doEdit($objRequest, $objResponse);
                break;
            case 'add':
                $this->doAdd($objRequest, $objResponse);
                break;
            case 'delete':
                $this->doDelete($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    protected function tryexecute($objRequest, $objResponse) {
        BookService::instance()->rollback();
    }
    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        $act = $objRequest -> act;
        $thisDay = $objRequest -> time_begin;
        $thisDay = empty($thisDay) ? getDay() : $thisDay;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];

        //赋值
        $objResponse -> thisYear = getYear();
        $objResponse -> thisMonth = getMonth();
        $objResponse -> thisDay = $thisDay;
        $objResponse -> nextYear = $objResponse -> nextYear = $objResponse -> thisYear + 1;

        $objResponse -> module = $objRequest->module;
        $objResponse -> search_url = \BaseUrlUtil::Url('');
        $objResponse -> act = $act;

        if($act == 'night_audit') {
            $this->doNightAudit($objRequest, $objResponse);
            return;
        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'book_night_audit_date'=>$thisDay);
        $arrayThisDayBook = BookService::instance()->getBookNightAudit($conditions);

        //赋值
        $objResponse -> arrayData = $arrayThisDayBook;
        //设置类别
    }

    protected function doNightAudit($objRequest, $objResponse) {
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $nowDay = getDay();
        $yesterday = getDay(-24);
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('IN'=>array('hotel_id'=>array($hotel_id,0)), 'hotel_service_setting_type'=>'night_audit');
        $nightAuditTime = HotelService::instance()->getHotelServiceSetting($conditions);
        if(empty($nightAuditTime)) throw new \Exception('$nightAuditTime is Empty');
        $nightAuditTime = $nightAuditTime[0]['hotel_service_setting_value'];//夜审开始和截止时间

        //判断是否到时间开始夜审
        $isArriveTime = false;
        if(strtotime(getDateTime()) >= strtotime($nowDay . ' ' . $nightAuditTime)) {
            $isArriveTime = true;
            $nightAuditDate = getDateTime();
        }
        $objResponse-> isArriveTime = $isArriveTime;
        $arrayBookInfo = '';$arrayBookOrderNumber = '';
        $arrayBookEditUrl = '';
        if($isArriveTime) {
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                                         '>='=>array('book_order_number_status'=>0,'book_check_out'=>$nightAuditDate),
                                         '<='=>array('book_check_in'=>$nightAuditDate),
                                         '<>'=>array('book_night_audit_date'=>$nowDay));
            $field = 'book_id, room_id, room_sell_layout_id, room_layout_id,book_room_extra_bed,book_is_pay,book_order_number,book_order_number_status,book_check_in,book_check_out,'
                .'book_order_retention_time,book_contact_name,book_contact_mobile,book_comments';
            $arrayBookInfo = BookService::instance()->getBook($conditions, $field, 'book_order_number', true);
            if(!empty($arrayBookInfo)) {
                foreach($arrayBookInfo as $book_order_number => $arrayBook) {
                    $arrayBookOrderNumber[] = $book_order_number;
                    /*$arrayBookInfo[$i]['is_correct'] = 0;
                    if($arrayBookInfo[$i]['book_order_number_status'] != '0') {
                        $arrayBookInfo[$i]['is_correct'] = 1;
                    }*/
                    $arrayBookEditUrl[$book_order_number]['url'] =
                        \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit']),'order_number'=>encode($book_order_number)));

                }
            }
        }
        //房子
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'room_type'=>1);
        $conditions['order'] = 'room_mansion, room_floor, room_number, room_id';
        $arrayRoom = RoomService::instance()->getRoom($conditions, '*', 'room_id');
        $conditions['order'] = '';
        //服务
        $conditions['where'] = array('IN'=>array('hotel_id'=>array($hotel_id, 0)), 'hotel_service_valid'=>1, '<>'=>array('hotel_service_price'=>-1));
        $arrayService = HotelService::instance()->getHotelService($conditions, '*', 'hotel_service_id');

        //核对价格
        $conditions['where'] = array('hotel_id'=>$hotel_id,'book_is_night_audit'=>'0',
                                     'IN'=>array('book_order_number'=>$arrayBookOrderNumber),
                                     '<='=>array('book_night_audit_fiscal_day'=>getDay()));
        $arrayBookNightAudit = BookService::instance()->getBookNightAudit($conditions, '*', 'book_order_number', true, '', 'room_id');

        $objResponse -> arrayDataInfo = $arrayBookInfo;
        $objResponse -> arrayBookEditUrl = $arrayBookEditUrl;
        $objResponse -> arrayRoom = $arrayRoom;
        $objResponse -> arrayBookNightAudit = $arrayBookNightAudit;
        $objResponse -> arrayService = $arrayService;

        //
        $objResponse -> nightAuditUrl =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['nightAudit']['add'])));
    }

    protected function doAdd($objRequest, $objResponse) {
        $key = $objRequest->key;
        $room = $objRequest->room;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $this->setDisplay();
        $arrayBookNightAudit = json_decode(stripslashes($key), true);//
        $arrayRoom  = json_decode(stripslashes($room), true);//
        if(empty($arrayBookNightAudit) || empty($arrayRoom)) {
            return $this->errorResponse('数据异常');
        }
        $arrayBookNightAuditId = '';$arrayOrderNumber = '';$arrayRoomId = '';
        foreach($arrayBookNightAudit as $id => $value) {
            $arrayBookNightAuditId[$id] = $id;
            $arrayOrderNumber[$value] = $value;
        }
        foreach($arrayRoom as $id => $value) {
            if($id > 0) $arrayRoomId[] = $id;
        }
        if(empty($arrayRoomId)) {
            return $this->errorResponse("参数错误！");
        }

        $where = array('hotel_id'=>$hotel_id,
            'IN'=>array('book_night_audit_id'=>$arrayBookNightAuditId));
        $updateData['book_night_audit_date'] = getDay();
        $updateData['book_night_audit_datetime'] = getDateTime();
        $updateData['book_is_night_audit'] = '1';
        $updateData['book_is_check_employee_id'] = $objResponse->arrayLoginEmployeeInfo['employee_id'];
        BookService::instance()->startTransaction();
        BookService::instance()->updateBookNightAudit($where, $updateData);
        $where = array('hotel_id'=>$hotel_id,
            'IN'=>array('book_order_number'=>$arrayOrderNumber));
        $updateBook['book_night_audit_date'] = getDay();
        BookService::instance()->updateBook($where, $updateBook);
        //设置脏房
        $where = array('IN'=>array('room_id'=>$arrayRoomId),'hotel_id'=>$hotel_id);
        $updateRoom['room_status'] = 4;
        RoomService::instance()->updateRoom($where, $updateRoom);
        BookService::instance()->commit();
        $url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['nightAudit']['view']),'act'=>'night_audit'));
        return $this->successResponse('操作成功！','', $url);
    }

}