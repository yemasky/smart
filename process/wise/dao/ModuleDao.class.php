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

	public function getModuleCompany($field = '', \WhereCriteria $whereCriteria) {
		if($field == '') $field = '*';
        if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('module_id');

		return $this->setDsnRead($this->getDsnRead())->setTable('module_company')->getList($field, $whereCriteria);//DBCache($cacheId)->
	}

}