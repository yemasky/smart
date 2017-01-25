<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class AccessorialServiceAction extends \BaseAction {
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
        HotelService::instance()->rollback();//事务回滚
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'], 'hotel_service_valid'=>1);
        $arrayAccessorialService = HotelService::instance()->getHotelService($conditions, '*', 'hotel_service_id', true, 'hotel_service_father_id');
        sort($arrayAccessorialService);
        //print_r($arrayAccessorialService);
        //赋值
        //sort($arrayRoomAttribute, SORT_NUMERIC);
        //
        $objResponse -> arrayData = $arrayAccessorialService;
        $objResponse -> add_accessorialService_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['accessorialService']['add'])));
        $objResponse -> edit_accessorialService_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['accessorialService']['edit'])));
        $objResponse -> delete_add_accessorialService_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['accessorialService']['delete'])));
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> hotel_service_id = 0;
        $this->doEdit($objRequest, $objResponse);
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();

        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $where = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],
                'hotel_service_id'=>$objRequest -> hotel_service_id);
            $arrayPostValue['hotel_service_father_id'] = $arrayPostValue['hotel_service'];
            unset($arrayPostValue['hotel_service']);
            $arrayPostValue['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
            $url = \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['accessorialService']['view'])));
            if($objRequest -> hotel_service_id > 0) {
                HotelService::instance()->updateHotelService($where, $arrayPostValue);
                return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
            } else {
                if(empty($arrayPostValue['hotel_service_father_id'])) {
                    $arrayPostValue['hotel_service_price'] = -1;
                }
                unset($arrayPostValue['hotel_service_id']);
                HotelService::instance()->startTransaction();
                $hotel_server_id = HotelService::instance()->saveHotelService($arrayPostValue);
                if(empty($arrayPostValue['hotel_service_father_id'])) {
                    HotelService::instance()->updateHotelService(array('hotel_service_id'=>$hotel_server_id),
                        array('hotel_service_father_id'=>$hotel_server_id));
                }
                HotelService::instance()->commit();
                return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
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