<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace wise;
class CompanyDao extends CommonDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new CompanyDao();
        return self::$objDao;
    }

    public function getCompany(\WhereCriteria $whereCriteria, $field = null){
        return $this->setDsnRead($this->getDsnRead())->setTable('company')->getList($whereCriteria, $field);
    }

    //--------Company//-----------//
    public function saveCompany($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company')->insert($arrayData, $insert_type);
    }

    public function updateCompany(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company')->update($whereCriteria, $arrayUpdateData);
    }
    //company_sector
    public function getCompanySector(\WhereCriteria $whereCriteria, $field = null){
        return $this->setDsnRead($this->getDsnRead())->setTable('company_channel_sector')->getList($whereCriteria, $field);
    }

    public function saveCompanySector($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company_channel_sector')->insert($arrayData, $insert_type);
    }

    public function updateCompanySector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('company_channel_sector')->update($whereCriteria, $arrayUpdateData);
    }

}