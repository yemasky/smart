<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class CompanyService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new CompanyService();
		}
		
		return self::$objService;
	}

    //--------Company//-----------//
	public function getCompany(\WhereCriteria $whereCriteria, $field = null) {
		return CompanyDao::instance()->getCompany($field, $whereCriteria);
	}
    public function saveCompany($arrayData, $insert_type = 'INSERT') {
        return CompanyDao::instance()->setTable('company')->insert($arrayData, $insert_type);
    }

    public function updateCompany(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return CompanyDao::instance()->setTable('company')->update($arrayUpdateData, $whereCriteria);
    }
    //company_sector
	public function getCompanySector(\WhereCriteria $whereCriteria, $field = null) {
		return CompanyDao::instance()->getCompanySector($field, $whereCriteria);
	}
	public function saveCompanySector($arrayData, $insert_type = 'INSERT') {
		return CompanyDao::instance()->setTable('company_sector')->insert($arrayData, $insert_type);
	}

	public function updateCompanySector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		return CompanyDao::instance()->setTable('company_sector')->update($arrayUpdateData, $whereCriteria);
	}


}