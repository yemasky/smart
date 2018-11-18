<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace wise;
class EmployeeDao extends CommonDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new EmployeeDao();
        return self::$objDao;
    }

    public function getEmployee(\WhereCriteria $whereCriteria, $field = null){
        return $this->setDsnRead($this->getDsnRead())->setTable('employee')->getList($field, $whereCriteria);
    }

    //--------Employee//-----------//
    public function saveEmployee($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('employee')->insert($arrayData, $insert_type);
    }

    public function updateEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('employee')->update($arrayUpdateData, $whereCriteria);
    }
    //--------Employee//-----------//

    //employee_sector
    public function getEmployeeSector(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('employee_sector')->getList($field, $whereCriteria);
    }

    public function saveEmployeeSector($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('employee_sector')->insert($arrayData, $insert_type);
    }

    public function updateEmployeeSector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('employee_sector')->update($arrayUpdateData, $whereCriteria);
    }
}