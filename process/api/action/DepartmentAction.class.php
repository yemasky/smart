<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace api;


class DepartmentAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        $this->setDisplay();
        switch($objRequest->getAction()) {
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        $conditions = \DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>1);
        $arrayDepartment = \hotel\HotelService::instance()->getHotelDepartment($conditions);
        $this->successResponse($arrayDepartment);

    }


}