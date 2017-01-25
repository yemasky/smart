<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace api;


class EmployeeAction extends \BaseAction {
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
        $field = 'employee_id,company_id,hotel_id,department_id,department_position_id,role_id,employee_name,employee_sex,employee_birthday,employee_photo,'
                .'employee_mobile,employee_email,employee_weixin,employee_add_date,employee_add_time';
        $arrayDepartment = \hotel\EmployeeService::instance()->getEmployee($conditions, $field);
        $this->successResponse($arrayDepartment);

    }


}