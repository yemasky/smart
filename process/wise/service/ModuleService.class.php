<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ModuleService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new ModuleService();
		}
		
		return self::$objService;
	}

	//--------Module//-----------//
	public function getModule(\WhereCriteria $whereCriteria, $field = null) {
		return ModuleDao::instance()->getModule($field, $whereCriteria);
	}

	public function saveModule($arrayData, $insert_type = 'INSERT') {
		return ModuleDao::instance()->setTable('module')->insert($arrayData, $insert_type);
	}

	public function updateModule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		return ModuleDao::instance()->setTable('module')->update($arrayUpdateData, $whereCriteria);
	}

    public function batchUpdateModuleByKey($arrayUpdate, \WhereCriteria $whereCriteria) {
        return ModuleDao::instance()->setTable('module')->batchUpdateByKey($arrayUpdate, $whereCriteria);
    }
	//--------Module//-----------//

	//ModuleCompany
	public function getModuleCompany(\WhereCriteria $whereCriteria, $field = null) {
		return ModuleDao::instance()->getModuleCompany($field, $whereCriteria);
	}
}