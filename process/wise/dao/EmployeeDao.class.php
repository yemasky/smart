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
    //--------Employee//-----------//
    public function getEmployee(\WhereCriteria $whereCriteria, $field = null){
        return $this->setDsnRead($this->getDsnRead())->setTable('employee')->getList($whereCriteria, $field);
    }

    public function getEmployeeCount(\WhereCriteria $whereCriteria, $field = '') {
        return $this->setDsnRead($this->getDsnRead())->setTable('employee')->getCount($whereCriteria, $field);
    }

    public function saveEmployee($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('employee')->insert($arrayData, $insert_type);
    }

    public function updateEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('employee')->update($whereCriteria, $arrayUpdateData);
    }
    //--------Employee//-----------//

    //employee_sector
    public function getEmployeeSector(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('employee_sector')->getList($whereCriteria, $field);
    }

    public function getEmployeeSectorCount(\WhereCriteria $whereCriteria, $field = '') {
        return $this->setDsnRead($this->getDsnRead())->setTable('employee_sector')->getCount($whereCriteria, $field);
    }

    public function saveEmployeeSector($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('employee_sector')->insert($arrayData, $insert_type);
    }

    public function updateEmployeeSector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('employee_sector')->update($whereCriteria, $arrayUpdateData);
    }

    //company_channel_sector position 职位，sector 部门
    public function getChannelSector(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('company_channel_sector')->getList($whereCriteria, $field);
    }

    public function saveChannelSector($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company_channel_sector')->insert($arrayData, $insert_type);
    }

    public function updateChannelSector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company_channel_sector')->update($whereCriteria, $arrayUpdateData);
    }

}