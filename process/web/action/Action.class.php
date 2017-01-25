<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class Action extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        if($objRequest->getAction() != 'login') {

        }
    }

    protected function service($objRequest, $objResponse) {
        $modules_id = decode(trim($objRequest->module));
        $action = $objRequest->action;
        $this->setDisplay();
        $module = '\web\IndexAction';
        $module_action_tpl = 'index';
        $modules_module = 'index';
        if(!empty($modules_id)) {

                $arrayModules = ModulesService::instance()->getModules();
                if(isset($arrayModules[$modules_id])) {
                    $arrayModule = $arrayModules[$modules_id];
                    $module = '\web\\' . ucwords($arrayModule['modules_module']) . 'Action';
                    if(!empty($arrayModule['modules_action'])) {
                        $action = $arrayModule['modules_action'];
                    }
                    $modules_module = $arrayModule['modules_module'];
                    $module_action_tpl = empty($action) ? $arrayModule['modules_module'] : $arrayModule['modules_module'] . '_' . $action;

                    //语言

                }

        }

        $arrayLaguage = CommonService::instance()->getPageModuleLaguage($modules_module);
        $objResponse->setTplValue('arrayLaguage', $arrayLaguage);
        $objResponse->setTplName("web/modules_" . $module_action_tpl);
        $objAction = new $module();
        $objAction->execute($action, $objRequest, $objResponse);//
    }
}