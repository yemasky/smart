<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class RoleService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new RoleService();
        return self::$objService;
    }

    //员工权限
    public function getRoleEmployee($employee_id) {
        $conditions = \DbConfig::$db_query_conditions;
        $conditions['where'] = array('employee_id'=>$employee_id);
        return RoleDao::instance()->getRoleEmployee($conditions);
    }

    public function saveRoleEmployee($arrayData) {
        return RoleDao::instance()->setTable('role_employee')->insert($arrayData);
    }

    public function updateRoleEmployee($where, $row) {
        return RoleDao::instance()->setTable('role_employee')->update($where, $row);
    }

    public function deleteRoleEmployee($where) {
        return RoleDao::instance()->setTable('role_employee')->delete($where);
    }
    //角色
    public function getRole($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return RoleDao::instance()->setTable('role')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveRole($arrayData) {
        return RoleDao::instance()->setTable('role')->insert($arrayData);
    }

    public function updateRole($where, $row) {
        return RoleDao::instance()->setTable('role')->update($where, $row);
    }

    public function deleteRole($where) {
        return RoleDao::instance()->setTable('role')->delete($where);
    }

    //角色权限
    public function getRoleModules($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return RoleDao::instance()->setTable('role_modules')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveRoleModules($arrayData, $insert_type = 'INSERT') {
        return RoleDao::instance()->setTable('role_modules')->insert($arrayData, $insert_type);
    }

    public function updateRoleModules($where, $row) {
        return RoleDao::instance()->setTable('role_modules')->update($where, $row);
    }

    public function deleteRoleModules($where) {
        return RoleDao::instance()->setTable('role_modules')->delete($where);
    }

}