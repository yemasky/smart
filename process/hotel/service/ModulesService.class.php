<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class ModulesService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new ModulesService();
        return self::$objService;
    }

    public function getModules($module_id = null) {
        $conditions = \DbConfig::$db_query_conditions;
        $conditions['where'] = array('modules_open'=>1);
        $arrayModules = ModulesDao::instance()->getModules($conditions);
        if(!empty($module_id)) {
            return $arrayModules[$module_id];
        }
        return $arrayModules;
    }

    public function getModulesSort() {
        $conditions = \DbConfig::$db_query_conditions;
        $conditions['where'] = array('modules_open'=>1);
        $field = 'modules_id, modules_father_id father_id, modules_name, modules_module module, modules_action, modules_action_permissions permissions';
        $arrayModules = ModulesDao::instance()->getModules($conditions, $field, 'modules_id');
        $arrarSortModules = '';$arrayKey = '';
        $i = 0;$key = 0;
        foreach($arrayModules as $id => $arrayModule) {
            //$arrayModule['modules_id'] = encode($arrayModule['modules_id']);
            if(empty($arrayModule['modules_action'])) $action = 0;
            if($arrayModule['modules_action'] == 'add') $action = 1;
            if($arrayModule['modules_action'] == 'edit') $action = 2;
            if($arrayModule['modules_action'] == 'delete') $action = 3;
            if($arrayModule['modules_action'] == 'editSystem') $action = 4;
            if($arrayModule['modules_action'] == 'saveAttrValue') $action = 5;
            if($arrayModule['modules_action'] == 'uploadImages') $action = 6;
            if(!isset($arrayKey[$arrayModule['module']])) {
                $arrayKey[$arrayModule['module']] = $i;
                $i++;
            }
            $key = $arrayKey[$arrayModule['module']];
            $arrarSortModules[$arrayModule['father_id']][$key][$action] = $arrayModule;
        }
        return $arrarSortModules;
    }

    public function saveModules($arrayData) {
        return ModulesDao::instance()->setTable('modules')->insert($arrayData);
    }

    public function updateModules($where, $row) {
        return ModulesDao::instance()->setTable('modules')->update($where, $row);
    }

    public function deleteModules($where) {
        return ModulesDao::instance()->setTable('modules')->delete($where);
    }

}