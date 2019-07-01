<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class ModuleDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new ModuleDao();

		return self::$objDao;
	}

	public function getModule(\WhereCriteria $whereCriteria, $field = '') {
		if(empty($field)) $field = '*';
        //if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('module_id');
		return $this->setDsnRead($this->getDsnRead())->setTable('module')->getList($whereCriteria, $field);
	}

    //--------Module//-----------//

    public function saveModule($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('module')->insert($arrayData, $insert_type);
    }

    public function updateModule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('module')->update($whereCriteria, $arrayUpdateData);
    }

    public function batchUpdateModuleByKey($arrayUpdate, \WhereCriteria $whereCriteria) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('module')->batchUpdateByKey($whereCriteria, $arrayUpdate);
    }
    //--------Module//-----------//

    //ModuleCompany
    public function getModuleCompany(\WhereCriteria $whereCriteria, $field = '') {
        if($field == '') $field = '*';
        if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('module_id');

        return $this->setDsnRead($this->getDsnRead())->setTable('module_company')->getList($whereCriteria, $field);//DBCache($cacheId)->
    }

}