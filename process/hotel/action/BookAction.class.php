<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class BookAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        $weekarray = array("日","一","二","三","四","五","六");
        $objResponse -> today = getDay() . " 星期".$weekarray[date("w")];
        $objResponse -> back_lis_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['view'])));
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
        BookOperateService::instance()->rollback();//事务回滚
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        BookOperateService::instance()->getBookInfo($objRequest, $objResponse);

        $objResponse -> search_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['view'])));
        $objResponse -> add_book_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['add'])));
        //
    }

    protected function view($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        $objResponse->view = '1';
        $objResponse->setTplName("hotel/modules_book_edit");
    }

    protected function doAdd($objRequest, $objResponse) {
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        if($objRequest -> search == 'searchRoomLayout') {
            $this -> setDisplay();
            $arrayBookRoomLayout = BookOperateService::instance()->searchISBookRoomLayout($objRequest, $objResponse);
            return $this->successResponse('', $arrayBookRoomLayout);
        }
        if($objRequest -> search == 'searchUserMemberLevel') {
            $this -> setDisplay();
            $arrayBookType = '';
            if(!empty($objRequest -> book_contact_mobile)) {
                $conditions['where'] = array('user_mobile'=>$objRequest -> book_contact_mobile);
                $arrayBookType = BookService::instance()->getBookTypeDiscount($conditions);
            }
            if(!empty($objRequest -> book_contact_email)) {
                $conditions['where'] = array('user_email'=>$objRequest -> book_contact_email);
                $arrayBookType = BookService::instance()->getBookTypeDiscount($conditions);
            }
            return $this->successResponse('', $arrayBookType);
        }
        if($objRequest -> search == 'searchRoom') {
            $this -> setDisplay();
            $room_layout_id = $objRequest -> room_layout_id;
            if(empty($room_layout_id)) return $this->errorResponse('房型错误，请重新选择！');
            //查找已预定和入住的房子
            $arrayRoomId = BookOperateService::instance()->getHaveCheckInRoom($conditions, $hotel_id, $objRequest->book_check_in, $objRequest->book_check_out);
            //$arrayRoomId = '';
            if(empty($arrayRoomId)) {
                $conditions['where'] = array('rlr.room_layout_id'=>$room_layout_id,
                    'r.hotel_id'=>$hotel_id);
            } else {
                $conditions['where'] = array('rlr.room_layout_id'=>$room_layout_id,
                'r.hotel_id'=>$hotel_id,'NOT IN'=>array('r.room_id'=>$arrayRoomId));
            }
            $arrayRoom = RoomService::instance()->getRoomLayoutRoomDetailed($conditions);
            return $this->successResponse('', $arrayRoom);
        }
        if($objRequest -> search == 'discount') {
            $this -> setDisplay();
            $book_type_id = $objRequest -> book_type_id;
            if(empty($book_type_id)) return $this->errorResponse('数据错误，请重新选择！');
            $conditions['where'] = array('book_type_id'=>$book_type_id,
                'hotel_id'=>$hotel_id);
            $fieldid = 'book_discount_id, union_id, book_type_father_id, room_layout_corp_id layout_corp, book_discount, book_discount_type, book_discount_name, agreement_company_name';
            $arrayDiscount = BookService::instance()->getBookDiscount($conditions, $fieldid);
            return $this->successResponse('', $arrayDiscount);
        }

        //$this->doEdit($objRequest, $objResponse);
        $arrayPostValue= $objRequest->getPost();
        $conditions = DbConfig::$db_query_conditions;

        if(!empty($arrayPostValue) && is_array($arrayPostValue) && isset($arrayPostValue['room_layout_id'])) {
            $this->setDisplay();
            if(empty($arrayPostValue['room_layout_id'])) return $this->errorResponse('房型数据错误！');
            unset($arrayPostValue['room_layout_length']);
            //$order_number = 0;
            $order_number = BookOperateService::instance()->saveBookInfo($objRequest, $objResponse);
            if($order_number == 0) return $this->errorResponse('预定失败！', $arrayPostValue);
            $redirect_url =
                \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit']),//edit
                    'order_number'=>encode($order_number)));//array('order_number'=>encode($order_number))
            return $this->successResponse('预定成功', $objRequest->getPost(), $redirect_url);
        }

        //$hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions['where'] = array('IN'=>array('hotel_id'=>array($hotel_id,0)));
        $arrayBookType = BookService::instance()->getBookType($conditions);

        $conditions['where'] = null;
        $conditions['order'] = 'payment_type_id ASC';
        $arrayPaymentType = HotelService::instance()->getHotelPaymentType($conditions);
        $conditions['order'] = '';
        //附加服务项目
        $conditions['where'] = array('hotel_id'=>$hotel_id, '!='=>array('hotel_service_price'=>-1));
        $conditions['order'] = 'hotel_service_father_id ASC';
        $arrayHotelService = HotelService::instance()->getHotelService($conditions);
        $conditions['order'] = '';
        //

        //hotel info
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayHotel = HotelService::instance()->getHotel($conditions);
        $hotel_checkout = empty($arrayHotel[0]['hotel_checkout']) ? '12:00' : $arrayHotel[0]['hotel_checkout'];
        $hotel_checkin = empty($arrayHotel[0]['hotel_checkin']) ? '06:00' : $arrayHotel[0]['hotel_checkin'];
        $hotel_overtime = empty($arrayHotel[0]['hotel_overtime']) ? '18:00' : $arrayHotel[0]['hotel_overtime'];

        //房型
        $conditions['where'] = array('hotel_id'=>$hotel_id, 'room_sell_layout_valid'=>1);
        $arraySellLayout = RoomService::instance()->getRoomSellLayout($conditions);
        //价格体系
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$hotel_id)),'room_layout_price_system_valid'=>1);
        $arrayPriceSystem = RoomService::instance()->getRoomLayoutPriceSystem($conditions);
        //赋值
        $objResponse -> view = '0';
        $objResponse -> arrayBookType = $arrayBookType;
        $objResponse -> arrayPaymentType = $arrayPaymentType;
        $objResponse -> book_check_in = getDay() .' '. date("H") . ':00:00';
        $objResponse -> book_check_out = getDay(24) . ' ' . $hotel_checkout;
        $objResponse -> thisDay = getDay();
        $objResponse -> hotel_checkout = $hotel_checkout;
        $objResponse -> hotel_checkin  = $hotel_checkin;
        $objResponse -> hotel_overtime  = $hotel_overtime;
        $objResponse -> idCardType = ModulesConfig::$idCardType;
        $objResponse -> arrayHotelService = $arrayHotelService;
        $objResponse -> arraySellLayout = $arraySellLayout;
        $objResponse -> arrayPriceSystem = $arrayPriceSystem;
        $objResponse -> searchBookInfoUrl =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['add'])));
        //$objResponse -> book_url =
            //\BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit'])));
        //
        $objResponse -> book_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['add'])));
        $objResponse->view = 'add';
        //$objResponse->setTplName("hotel/modules_book_edit");
    }

    protected function doEdit($objRequest, $objResponse) {
        $order_number = decode($objRequest -> order_number);
        if(empty($order_number) || !is_numeric($order_number)) {
            $objResponse -> message_http404 = '页面没找到！';
            $objResponse -> setTplName("hotel/modules_http404");
            return;
        }
        $act = $objRequest -> act;
        if($act == 'savebook') {
            $this->setDisplay();
            $arrayResult = BookActionServiceImpl::instance()->doSaveEditbookAction($objRequest, $objResponse);
            if($arrayResult[0] == 1) return $this->successResponse('保存数据成功！', $arrayResult[1]);
            return $this->errorResponse($arrayResult[1], $arrayResult);
        }
        if($act == 'saveBookPayment') {
            $this->setDisplay();
            BookActionServiceImpl::instance()->doSaveEditBookPayment($objRequest, $objResponse);
            $redirect_url = \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['edit']),'order_number'=>encode($order_number)));
            return $this->successResponse('保存数据成功！', '' , $redirect_url);
        }
        if($act == 'checkInRoom') {
            $this->setDisplay();
            BookActionServiceImpl::instance()->doEditCheckInRoom($objRequest, $objResponse);
            return $this->successResponse('保存数据成功！', '');
        }
        if($act == 'addBookUser') {
            $this->setDisplay();
            BookActionServiceImpl::instance()->doAddBookUser($objRequest, $objResponse);
            return $this->successResponse('保存数据成功！', '');
        }
        if($act == 'setUserRoomCard') {
            $this->setDisplay();
            BookActionServiceImpl::instance()->setUserRoomCard($objRequest, $objResponse);
            return $this->successResponse('保存数据成功！', '');
        }
        if($act == 'returnBook') {
            $this->setDisplay();
            $arrayRoomConsume = BookActionServiceImpl::instance()->returnBook($objRequest, $objResponse);
            return $this->successResponse('获取数据成功！', $arrayRoomConsume);
        }
        if($act == 'returnBookPrice') {
            $this->setDisplay();
            $arrayRoomConsume = BookActionServiceImpl::instance()->returnBookPrice($objRequest, $objResponse);
            return $this->successResponse('更新数据成功！', $arrayRoomConsume);
        }

        BookActionServiceImpl::instance()->doEditBookAction($objRequest, $objResponse);
        //设置类别
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
    }



}