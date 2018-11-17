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

	public function getModule($field = '', \WhereCriteria $whereCriteria) {
		if(empty($field)) $field = '*';
        if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('module_id');

		return $this->setDsnRead($this->getDsnRead())->setTable('module')->getList($field, $whereCriteria);
	}

    //--------Module//-----------//

    public function saveModule($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('module')->insert($arrayData, $insert_type);
    }

    public function updateModule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('module')->update($arrayUpdateData, $whereCriteria);
    }

    public function batchUpdateModuleByKey($arrayUpdate, \WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('module')->batchUpdateByKey($arrayUpdate, $whereCriteria);
    }
    //--------Module//-----------//

    //ModuleCompany
    public function getModuleCompany($field = '', \WhereCriteria $whereCriteria) {
        if($field == '') $field = '*';
        if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('module_id');

        return $this->setDsnRead($this->getDsnRead())->setTable('module_company')->getList($field, $whereCriteria);//DBCache($cacheId)->
    }

}