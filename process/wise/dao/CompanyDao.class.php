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

}