<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class MemberSettingAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'add':
                $this->doAdd($objRequest, $objResponse);
                break;
            case 'edit':
                $this->doEdit($objRequest, $objResponse);
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
        BookService::instance()->rollback();//事务回滚
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0, $objResponse->arrayLoginEmployeeInfo['hotel_id'])));
        $conditions['order'] = 'book_sales_type_id ASC';
        //$arrayBookType = BookService::instance()->getBookType($conditions, '*', 'book_type_id', true, 'book_type_father_id');
        //print_r($arrayBookType);
        $arrayBookType = BookService::instance()->getBookType($conditions, '*', 'book_sales_type_id', false, 'book_type_father_id', 'book_type_id');
        // 折扣
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $conditions['order'] = 'book_type_id ASC';
        $arrayDiscount = BookService::instance()->getBookDiscount($conditions);
        $conditions['order'] = '';
        //销售来源
        $conditions['where'] = array('IN'=>array('hotel_id'=>array('0', $hotel_id)));
        $arrayBookSalesType = BookService::instance()->getBookSalesType($conditions, '*', 'book_sales_type_id');

        $arrayType = '';
        if(!empty($arrayDiscount)) {
            foreach($arrayBookType as $key => $arrayDateBook) {
                foreach($arrayDateBook as $key => $BookType) {
                    if(!empty($BookType['children'])) {
                        foreach($BookType['children'] as $j => $Type) {
                            $arrayType[$Type['book_type_id']] = $Type;
                        }
                    }
                }
            }
        }
        //协议价
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayRoomLayoutCorp = RoomService::instance()->getRoomLayoutCorp($conditions, '*', 'room_layout_corp_id');
        //print_r($arrayAccessorialService);
        //赋值
        //sort($arrayRoomAttribute, SORT_NUMERIC);
        //
        $objResponse -> arrayDataInfo = $arrayBookType;
        $objResponse -> memberType = ModulesConfig::$memberType;
        $objResponse -> arrayDiscount = $arrayDiscount;
        $objResponse -> arrayType = $arrayType;
        $objResponse -> arrayBookSalesType = $arrayBookSalesType;
        $objResponse -> arrayRoomLayoutCorp = $arrayRoomLayoutCorp;
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['memberSetting']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['memberSetting']['edit'])));
        $objResponse -> delete_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['memberSetting']['delete'])));
        $objResponse -> searchBookInfoUrl =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['book']['add'])));
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> book_type_id = 0;
        $objRequest -> book_discount_id = '';
        $this->doEdit($objRequest, $objResponse);
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();
        $book_type_id = $objRequest -> book_type_id;
        $act = $objRequest -> act;

        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $arrayData['book_type_name'] = $objRequest -> book_type_name;
            $arrayData['type'] = $objRequest -> type;
            $arrayData['book_sales_type_id'] = $objRequest -> book_sales_type_id;
            $book_type_father_id = $objRequest -> book_type;
            if(empty($arrayPostValue['agreement_active_time_begin'])) unset($arrayPostValue['agreement_active_time_begin']);
            if(empty($arrayPostValue['agreement_active_time_end'])) unset($arrayPostValue['agreement_active_time_end']);
            if($act == 'booktype') {
                if(isset($arrayPostValue['book_type_name']) && !empty($arrayData['book_type_name']) && !empty($arrayData['type'])) {
                    $url = \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['memberSetting']['view'])));
                    if($book_type_id > 0) {
                        $arrayData['book_type_father_id'] = $arrayPostValue['book_type'];
                        $where = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
                            'book_type_id'=>$book_type_id);
                        BookService::instance()->updateBookType($where, $arrayData);
                        return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
                    } else {
                        BookService::instance()->startTransaction();
                        $arrayData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                        if($book_type_father_id > 0 && is_numeric($book_type_father_id)) {
                            $arrayData['book_type_father_id'] = $book_type_father_id;
                        }
                        $book_type_id = BookService::instance()->saveBookType($arrayData);
                        if($book_type_father_id > 0 && is_numeric($book_type_father_id)) {
                        } else {
                            BookService::instance()->updateBookType(array('book_type_id'=>$book_type_id),
                                array('book_type_father_id'=>$book_type_id));
                        }
                        BookService::instance()->commit();
                        return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
                    }
                }
            }
            $book_discount_id = $objRequest -> book_discount_id;
            $book_discount_name = $objRequest -> book_discount_name;
            if($act == 'discount') {
                if(isset($arrayPostValue['book_discount_name']) && !empty($book_discount_name) && isset($arrayPostValue['book_discount'])) {
                    $url = \BaseUrlUtil::Url(array('module' => encode(ModulesConfig::$modulesConfig['memberSetting']['view'])));
                    //$url = '';
                    if($book_discount_id > 0) {
                        $where = array('hotel_id' => $objResponse->arrayLoginEmployeeInfo['hotel_id'],
                            'book_discount_id' => $book_discount_id);
                        unset($arrayPostValue['book_discount_id']);
                        unset($arrayPostValue['discount_book_type_id']);
                        unset($arrayPostValue['book_type_father_id']);
                        BookService::instance()->updateBookDiscount($where, $arrayPostValue);
                        return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'], '', $url);
                    } else {
                        if(empty($arrayPostValue['room_layout_corp_id'])) $arrayPostValue['room_layout_corp_id'] = '0';
                        if(empty($arrayPostValue['book_discount'])) $arrayPostValue['book_discount'] = '100';
                        unset($arrayPostValue['book_discount_id']);
                        $arrayPostValue['book_type_id'] = $arrayPostValue['discount_book_type_id'];
                        unset($arrayPostValue['discount_book_type_id']);
                        $arrayPostValue['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                        BookService::instance()->saveBookDiscount($arrayPostValue);
                        return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'], '', $url);
                    }
                }
            }
        }
        return $this->errorResponse($objResponse->arrayLaguage['save_nothings']['page_laguage_value']);
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {

            return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value']);
        }
        return $this->errorResponse($objResponse->arrayLaguage['save_nothings']['page_laguage_value']);
    }

}