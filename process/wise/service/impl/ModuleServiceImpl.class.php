<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ModuleServiceImpl extends \BaseServiceImpl implements ModuleService {
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
            $whereCriteria->ORDER('module_father_id', 'ASC')->ORDER('module_order', 'ASC')
                ->ORDER('action_order', 'ASC')->setHashKey('module_id');
            $arrayModule = ModuleDao::instance()->DBCache($cacheModuleId, -1)->getModule($whereCriteria);
        } else {
            $whereCriteria->setHashKey('module_id');
            $arrayModule = ModuleDao::instance()->DBCache($cacheModuleId)->getModule($whereCriteria);
        }

        return $arrayModule;
    }
    //ChannelModule
    public function getChannelModule($arrayModuleId, $company_id, $channel_id, $isUpdate = false) {
        if(empty($arrayModuleId)) return array();
        if(empty($field)) $field = 'module_id,module_channel,module_name,module_father_id,module_view,is_recommend,submenu_father_id,ico';
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('module_id', $arrayModuleId)
            ->ORDER('module_father_id', 'ASC')->ORDER('module_order', 'ASC')->ORDER('action_order', 'ASC')
            ->ORDER('module_id', 'ASC');
        $cacheModuleId = CacheConfig::getCacheId('employee_module_menu', $company_id, '_channel_' . $channel_id);
        if ($isUpdate) {
            $arrayChannelModule = ModuleDao::instance()->DBCache($cacheModuleId, -1)->getModule($whereCriteria, $field);
        } else {
            $arrayChannelModule = ModuleDao::instance()->DBCache($cacheModuleId)->getModule($whereCriteria, $field);
        }
        if (!empty($arrayChannelModule)) {
            foreach ($arrayChannelModule as $k => $v) {
                $arrayChannelModule[$k]['url'] = \Encrypt::instance()->encode($v['module_id'], getDay());
            }
        }
        return $arrayChannelModule;
    }
    ////获取用户菜单
    public function getModuleInModuleId($arrayModuleId, $company_id, $employee_id, $isUpdate = false, $field = '') {
        if(empty($arrayModuleId)) return array();
        if(empty($field)) $field = 'module_id,module_channel,module_name,module_father_id,module_view,is_recommend,submenu_father_id,ico';
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('module_id', $arrayModuleId)->EQ('is_menu', '1')->EQ('is_release', '1')
            ->ORDER('module_father_id', 'ASC')->ORDER('module_order', 'ASC')->ORDER('action_order', 'ASC')
            ->ORDER('module_id', 'ASC');
        $cacheModuleId = CacheConfig::getCacheId('employee_module_menu', $company_id, $employee_id);
        if ($isUpdate) {
            $arrayEmployeeModule = ModuleDao::instance()->DBCache($cacheModuleId, -1)->getModule($whereCriteria, $field);
        } else {
            $arrayEmployeeModule = ModuleDao::instance()->DBCache($cacheModuleId)->getModule($whereCriteria, $field);
        }
        if (!empty($arrayEmployeeModule)) {
            foreach ($arrayEmployeeModule as $k => $v) {
                $arrayEmployeeModule[$k]['url'] = \Encrypt::instance()->encode($v['module_id'], getDay());
            }
        }
        return $arrayEmployeeModule;
    }

    //获取酒店模块
    public function getModuleCompany(\WhereCriteria $whereCriteria, $field) {
        $arrayModuleCompany = ModuleDao::instance()->getModuleCompany($whereCriteria, $field);

        return $arrayModuleCompany;
    }

    //获取模块module_id的encode id
    public function getEncodeModuleId($module, $action) {
        if(isset(ModulesConfig::$module[$module][$action])) {
            return \Encrypt::instance()->encode(ModulesConfig::$module[$module][$action], getDay());
        }
        $arrayModule = $this->getAllModuleCache();
        foreach ($arrayModule as $module_id => $modules) {
            if($modules['module'] == $module && $modules['action'] == $action) {
                return \Encrypt::instance()->encode($module_id, getDay());
            }
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('module', $module)->EQ('action', $action);
        $arrayModuleId = ModuleDao::instance()->getModule($whereCriteria, 'module_id');
        if(!empty($arrayModuleId)) {
            $arrayModuleId = array_keys($arrayModuleId);
            return \Encrypt::instance()->encode($arrayModuleId[0], getDay());
        }
        return null;
    }

    //update
    public function batchUpdateModule($arrayUpdate, \WhereCriteria $whereCriteria) {
        ModuleDao::instance()->batchUpdateModuleByKey($arrayUpdate, $whereCriteria);
    }

    public function saveModule($arrayUpdate) {
        return ModuleDao::instance()->saveModule($arrayUpdate);
    }

    //ModuleChannel
    public function getModuleChannel(\WhereCriteria $whereCriteria, $field = '') {
        return ModuleDao::instance()->getModuleChannel($whereCriteria, $field);
    }

    public function saveModuleChannel($arrayData, $insert_type = 'INSERT') {
        return ModuleDao::instance()->saveModuleChannel($arrayData, $insert_type);
    }

    public function updateModuleChannel(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return ModuleDao::instance()->updateModuleChannel($whereCriteria, $arrayUpdateData);
    }

    public function deleteModuleChannel(\WhereCriteria $whereCriteria) {
        return ModuleDao::instance()->deleteModuleChannel($whereCriteria);
    }
}