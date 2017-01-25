<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class ModeOfPaymentAction extends \BaseAction {
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
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0, $objResponse->arrayLoginEmployeeInfo['hotel_id'])));
        $conditions['order'] = 'payment_type_id ASC';
        $arrayHotelPayment = HotelService::instance()->getHotelPaymentType($conditions, '*', 'payment_type_id', true, 'payment_type_father_id');
        $conditions['order'] = '';
        //赋值
        $objResponse -> arrayData = $arrayHotelPayment;
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['modeOfPayment']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['modeOfPayment']['edit'])));
        $objResponse -> delete_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['modeOfPayment']['delete'])));
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> payment_type_id = 0;
        $this->doEdit($objRequest, $objResponse);
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();
        $payment_type_id = $objRequest -> payment_type_id;

        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $arrayData['payment_type_name'] = $objRequest -> payment_type_name;
            $arrayData['payment_type_father_id'] = $objRequest -> father_id;
            if(isset($arrayPostValue['payment_type_name']) && !empty($arrayData['payment_type_name'])) {
                $url = \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['modeOfPayment']['view'])));
                //$url = '';
                if($payment_type_id > 0) {
                    $where = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
                        'payment_type_id'=>$payment_type_id);
                    HotelService::instance()->updateHotelPaymentType($where, $arrayData);
                    return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
                } else {
                    $arrayData['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                    HotelService::instance()->saveHotelPaymentType($arrayData);
                    return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
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