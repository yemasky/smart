<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class RoleService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new RoleService();
		}

		return self::$objService;
	}

    //--------RoleEmployee//-----------//
	public function getRoleEmployee(\WhereCriteria $whereCriteria, $field = null) {
		return RoleDao::instance()->getRoleEmployee($field, $whereCriteria);
	}
    public function saveRoleEmployee($arrayData, $insert_type = 'INSERT') {
        return EmployeeDao::instance()->setTable('role_employee')->insert($arrayData, $insert_type);
    }

    public function updateRoleEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return EmployeeDao::instance()->setTable('role_employee')->update($arrayUpdateData, $whereCriteria);
    }
    //--------RoleEmployee//-----------//

    //--------RoleMudule//-----------//
	public function getRoleMudule(\WhereCriteria $whereCriteria, $field = 'module_id') {
		return RoleDao::instance()->setTable('role_module')->getList($field, $whereCriteria);
	}

    public function saveRoleMudule($arrayData, $insert_type = 'INSERT') {
        return EmployeeDao::instance()->setTable('role_module')->insert($arrayData, $insert_type);
    }

    public function updateRoleMudule(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return EmployeeDao::instance()->setTable('role_module')->update($arrayUpdateData, $whereCriteria);
    }
    //--------RoleMudule//-----------//

}