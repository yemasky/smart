<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class EmployeeService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new EmployeeService();
        return self::$objService;
    }

    public function getEmployeeCompany($conditions, $hashKey = null) {
        return EmployeeDao::instance()->getEmployeeDepartment($conditions, 'DISTINCT company_id', $hashKey);
    }

    public function pageEmployeeCompany($conditions, $pn, $pn_rows, $parameters) {
        $pn_rows = empty($pn_rows) ? DbConfig::page_rows : $pn_rows;
        $count = EmployeeDao::instance()->getEmployeeDepartmentCount($conditions['where'], 'DISTINCT company_id');
        $all_page_num = ceil($count/$pn_rows);
        $pn = $pn > $all_page_num ? $all_page_num : $pn;
        $pn = $pn <= 0 ? 1 : $pn;
        $conditions['limit'] = ($pn - 1) * $pn_rows . ',' . $pn_rows;
        $conditions['order'] = 'company_id DESC';
        $arrayEmployeeCompany = self::getEmployeeCompany($conditions);
        return page($pn, $all_page_num, $arrayEmployeeCompany, $parameters);
    }

    public function getEmployeeHotel($conditions, $hashKey = null) {
        return EmployeeDao::instance()->getEmployeeDepartment($conditions, 'DISTINCT hotel_id', $hashKey);
    }

    public function pageEmployeeHotel($conditions, $pn, $pn_rows, $parameters) {
        $pn_rows = empty($pn_rows) ? DbConfig::page_rows : $pn_rows;
        $count = EmployeeDao::instance()->getEmployeeDepartmentCount($conditions['where'], 'DISTINCT hotel_id');
        $all_page_num = ceil($count/$pn_rows);
        $pn = $pn > $all_page_num ? $all_page_num : $pn;
        $pn = $pn <= 0 ? 1 : $pn;
        $conditions['limit'] = ($pn - 1) * $pn_rows . ',' . $pn_rows;
        $conditions['order'] = 'hotel_id DESC';
        $arrayEmployeeHotel = $this->getEmployeeHotel($conditions);
        return page($pn, $all_page_num, $arrayEmployeeHotel, $parameters);
    }
    
    public function saveEmployeeDepartment($arrayData) {
        return EmployeeDao::instance()->saveEmployeeDepartment($arrayData);
    }
    //
    public function pageEmployee($conditions, $pn, $pn_rows, $parameters) {
        $pn_rows = empty($pn_rows) ? DbConfig::page_rows : $pn_rows;
        $count = EmployeeDao::instance()->setTable('employee')->getCount($conditions['where'], 'employee_id');
        $all_page_num = ceil($count/$pn_rows);
        $pn = $pn > $all_page_num ? $all_page_num : $pn;
        $conditions['limit'] = ($pn - 1) * $pn_rows . ',' . $pn_rows;
        $conditions['order'] = 'employee_id DESC';
        $field = 'employee_id,company_id,department_id,department_position_id,employee_name,employee_photo,employee_sex,employee_add_date,employee_add_time';
        $arrayEmployee = $this->getEmployee($conditions, $field);
        return page($pn, $all_page_num, $arrayEmployee, $parameters);
    }

    public function getEmployee($conditions, $field = null, $hashKey = null, $multiple = false) {
        return EmployeeDao::instance()->setTable('employee')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveEmployee($arrayData) {
        return EmployeeDao::instance()->setTable('employee')->insert($arrayData);
    }

    public function updateEmployee($where, $row) {
        return EmployeeDao::instance()->setTable('employee')->update($where, $row);
    }

    public function deleteEmployee($where) {
        return EmployeeDao::instance()->setTable('employee')->delete($where);
    }
    //employee_images
    public function getEmployeeImages($conditions, $field = null, $hashKey = null, $multiple = false) {
        return EmployeeDao::instance()->setTable('employee_images')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveEmployeeImages($arrayData) {
        return EmployeeDao::instance()->setTable('employee_images')->insert($arrayData);
    }

    public function updateEmployeeImages($where, $row) {
        return EmployeeDao::instance()->setTable('employee_images')->update($where, $row);
    }

    public function deleteEmployeeImages($where) {
        return EmployeeDao::instance()->setTable('employee_images')->delete($where);
    }

    //employee_personnel_file
    public function insertEmployeePersonnelFile($row) {
        return EmployeeDao::instance()->setTable('employee_personnel_file')->insert($row, 'REPLACE');
    }

    public function getEmployeePersonnelFile($conditions, $field = null, $hashKey = null, $multiple = false) {
        return EmployeeDao::instance()->setTable('employee_personnel_file')->getList($conditions, $field, $hashKey, $multiple);
    }

}