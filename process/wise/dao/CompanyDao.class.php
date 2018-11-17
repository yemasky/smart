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

    public function getCompany($field = null, \WhereCriteria $whereCriteria){
        return $this->setDsnRead($this->getDsnRead())->setTable('company')->getList($field, $whereCriteria);
    }

	public function getCompanySector($field = null, \WhereCriteria $whereCriteria){
		return $this->setDsnRead($this->getDsnRead())->setTable('company_sector')->getList($field, $whereCriteria);
	}

    //--------Company//-----------//
    public function saveCompany($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('company')->insert($arrayData, $insert_type);
    }

    public function updateCompany(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('company')->update($arrayUpdateData, $whereCriteria);
    }
    //company_sector
    public function saveCompanySector($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('company_sector')->insert($arrayData, $insert_type);
    }

    public function updateCompanySector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('company_sector')->update($arrayUpdateData, $whereCriteria);
    }

}