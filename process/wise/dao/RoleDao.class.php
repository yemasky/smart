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
    //Role
    public function getRole(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('role')->getList($whereCriteria, $field);
    }

    public function saveRole($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role')->insert($arrayData, $insert_type);
    }

    public function updateRole(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role')->update($whereCriteria, $arrayUpdateData);
    }
    //
    //RoleModule
    public function getRoleModule(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('role_module')->getList($whereCriteria, $field);
    }

    public function saveRoleModule($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role_module')->insert($arrayData, $insert_type);
    }

    public function batchInsertRoleModule($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role_module')->batchInsert($arrayData, $insert_type);
    }

    public function updateRoleModule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role_module')->update($whereCriteria, $arrayUpdateData);
    }

    public function deleteRoleModule(\WhereCriteria $whereCriteria) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('role_module')->delete($whereCriteria);
    }
}