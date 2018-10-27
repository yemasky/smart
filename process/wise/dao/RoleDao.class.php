<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class RoleDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new RoleDao();

		return self::$objDao;
	}

	public function getRoleEmployee($field = '', \WhereCriteria $whereCriteria) {
		if(empty($field)) $field = 'role_id, employee_id';

		return $this->setDsnRead($this->getDsnRead())->setTable('role_employee')->getList($field, $whereCriteria);//->DBCache($cacheId)
	}
}