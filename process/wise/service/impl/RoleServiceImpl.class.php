<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class RoleServiceImpl extends \BaseServiceImpl implements RoleService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
			return self::$objService;
		}
		self::$objService = new RoleServiceImpl();

		return self::$objService;
	}

	public function getEmployeeRoleModuleCache($company_id, $employee_id) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('employee_id', $employee_id);
		$cacheRoleEmployeeId       = CacheConfig::getCacheId('role_employee', $company_id, $employee_id);
		$arrayEmployeeRole         = RoleDao::instance()->DBCache($cacheRoleEmployeeId)->getRoleEmployee($whereCriteria);
        $arrayEmployeeModuleId = array();
        if(!empty($arrayEmployeeRole)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->ArrayIN('role_id', array_column($arrayEmployeeRole,'role_id'));
            $whereCriteria->setHashKey('module_id');
			$cacheRoleEmployeeModuleId = CacheConfig::getCacheId('employee_module', $company_id, $employee_id);
			$arrayEmployeeRoleModule   = RoleDao::instance()->DBCache($cacheRoleEmployeeModuleId)->getRoleMudule($whereCriteria);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id);
            $whereCriteria->setHashKey('module_id');
			$cacheCompanyModuleId      = CacheConfig::getCacheId('module_company', $company_id, $employee_id);
			$arrayModuleCompany        = ModuleDao::instance()->DBCache($cacheCompanyModuleId)->getModuleCompany($whereCriteria, 'module_id');
			if(!empty($arrayEmployeeRoleModule) && !empty($arrayModuleCompany)) {
				foreach ($arrayEmployeeRoleModule as $module_id => $arrayEmployeeModule) {
				    if(isset($arrayModuleCompany[$module_id])) {
                        $arrayEmployeeModuleId[$module_id] = $module_id;
                    }
                }
			} else {
				return array();
			}
		}//array_flip($arrayEmployeeModule);//array_fill_keys($arrayEmployeeModule, '');
		return $arrayEmployeeModuleId;
	}

}