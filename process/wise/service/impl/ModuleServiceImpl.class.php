<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ModuleServiceImpl implements \BaseServiceImpl {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new ModuleServiceImpl();

        return self::$objService;
    }

    public function getAllModuleCache($isUpdate = false) {
        $whereCriteria = new \WhereCriteria();
        $cacheModuleId = CacheConfig::getCacheId('module', 'ALL');
        if ($isUpdate) {
            $whereCriteria->ORDER('module_father_id', 'ASC')->setHashKey('module_id');
            $arrayModule = ModuleService::instance()->DBCache($cacheModuleId, -1)->getModule($whereCriteria);
        } else {
            $whereCriteria->setHashKey('module_id');
            $arrayModule = ModuleService::instance()->DBCache($cacheModuleId)->getModule($whereCriteria);
        }

        return $arrayModule;
    }

    ////获取用户菜单
    public function getModuleInModuleId($arrayModuleId, $field = '') {
        if(empty($arrayModuleId)) return array();
        if(empty($field)) $field = 'module_id,module_channel,module_name,module_father_id,module_view,is_recommend,submenu_father_id,ico';
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('module_id', $arrayModuleId)->EQ('is_menu', '1')->EQ('is_release', '1');
        $whereCriteria->ORDER('module_order', 'ASC')->ORDER('action_order', 'ASC')->ORDER('module_id', 'ASC');
        $arrayEmployeeModule = ModuleService::instance()->getModule($whereCriteria, $field);
        if (!empty($arrayEmployeeModule)) {
            foreach ($arrayEmployeeModule as $k => $v) {
                $arrayEmployeeModule[$k]['url'] = \Encrypt::instance()->encode($v['module_id'], getDay());
            }
        }

        return $arrayEmployeeModule;
    }

    //获取酒店模块
    public function getModuleCompany(\WhereCriteria $whereCriteria, $field) {
        $arrayModuleCompany = ModuleService::instance()->getModuleCompany($whereCriteria, $field);

        return $arrayModuleCompany;
    }

    //获取模块encodeid
    public function getEncodeModuleId($module, $action) {
        return \Encrypt::instance()->encode(ModulesConfig::$module[$module][$action], getDay());
    }

    //update
    public function batchUpdateModule($arrayUpdate, \WhereCriteria $whereCriteria) {
        ModuleService::instance()->batchUpdateModuleByKey($arrayUpdate, $whereCriteria);
    }

}