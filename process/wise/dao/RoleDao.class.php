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

    //--------RoleEmployee//-----------//
    public function getRoleEmployee(\WhereCriteria $whereCriteria, $field = '') {
        if(empty($field)) $field = 'role_id, employee_id';

        return $this->setDsnRead($this->getDsnRead())->setTable('role_employee')->getList($field, $whereCriteria);//->DBCache($cacheId)
    }

    public function saveRoleEmployee($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('role_employee')->insert($arrayData, $insert_type);
    }

    public function updateRoleEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('role_employee')->update($arrayUpdateData, $whereCriteria);
    }
    //--------RoleEmployee//-----------//

    //--------RoleMudule//-----------//
    public function getRoleMudule(\WhereCriteria $whereCriteria, $field = 'module_id') {
        return $this->setDsnRead($this->getDsnRead())->setTable('role_module')->getList($field, $whereCriteria);
    }

    public function saveRoleMudule($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('role_module')->insert($arrayData, $insert_type);
    }

    public function updateRoleMudule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnRead($this->getDsnWrite())->setTable('role_module')->update($arrayUpdateData, $whereCriteria);
    }
    //--------RoleMudule//-----------//
}