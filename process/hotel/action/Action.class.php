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
            $objResponse->arrayLoginEmployeeInfo = LoginService::instance()->checkLoginEmployee();
        }
    }

    protected function service($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayLoginEmployeeInfo = $objResponse->arrayLoginEmployeeInfo;
        if(empty($arrayLoginEmployeeInfo) && $objRequest->getAction() != 'login') {
            if(!empty($objRequest->getPost())) {
                $this->errorResponse('登录已失效！请重新登录！', '', __WEB . 'index.php?action=login');
            } else {
                $this->setRedirect(__WEB . 'index.php?action=login');
            }
            return;
        }
        //default setting
        $action = $objRequest->action;
        $module = '\hotel\IndexAction';
        $module_action_tpl = 'index';
        $modules_module = 'index';

        //common setting
        if(!empty($arrayLoginEmployeeInfo)) {
            $objResponse->arrayEmployeeModules = CommonService::instance()->getEmployeeModules($objResponse->arrayLoginEmployeeInfo);
            $arrayNavigation = CommonService::instance()->getNavigation($objResponse->arrayLoginEmployeeInfo, decode(trim($objRequest->module)));
            //
            $objResponse->setTplValue('arrayEmployeeModules', $objResponse -> arrayEmployeeModules);
            $objResponse->setTplValue('arrayNavigation', $arrayNavigation);
            $selfNavigation = isset($arrayNavigation[count($arrayNavigation) - 1]) ? $arrayNavigation[count($arrayNavigation) - 1] : '';
            $objResponse->setTplValue('selfNavigation', $selfNavigation);

            $conditions = \DbConfig::$db_query_conditions;
            if(!empty(decode($objRequest->company_id))) {//公司权限
                $conditions['where'] = array('employee_id'=>$objResponse->arrayLoginEmployeeInfo['employee_id']);
                $arrayCompanyId = EmployeeService::instance()->getEmployeeCompany($conditions, 'company_id');
                $company_id = decode($objRequest->company_id);
                if(!isset($arrayCompanyId[$company_id])) {
                    $module_action_tpl = $action = 'noPermission';
                    $modules_id = null;
                }
            }

            $modules_id = decode(trim($objRequest->module));
            if(!empty($modules_id)) {
                $action = '';
                $arrayRoleEmployee = RoleService::instance()->getRoleEmployee($objResponse->arrayLoginEmployeeInfo['employee_id']);
                $arrayRoleModulesEmployeePermissions = '';
                if(!empty($arrayRoleEmployee)) {
                    foreach($arrayRoleEmployee as $k => $v) {
                        $arrayRoleId [] = $v['role_id'];
                    }
                    $hotel_id = $v['hotel_id'];
                    $conditions = DbConfig::$db_query_conditions;
                    $conditions['where'] = array('hotel_id'=>$hotel_id,
                        'IN'=>array('role_id'=>$arrayRoleId));
                    $arrayRoleModulesEmployeePermissions = RoleService::instance()->getRoleModules($conditions, '*', 'modules_id');
                }
                if(!isset($arrayRoleModulesEmployeePermissions[$modules_id])) {
                    //无权限
                    $module_action_tpl = $action = 'noPermission';
                } else {
                    $arrayModules = ModulesService::instance()->getModules();
                    if(isset($arrayModules[$modules_id])) {
                        $arrayModule = $arrayModules[$modules_id];
                        $module = '\hotel\\' . ucwords($arrayModule['modules_module']) . 'Action';
                        if(!empty($arrayModule['modules_action'])) {
                            $action = $arrayModule['modules_action'];
                        }
                        $modules_module = $arrayModule['modules_module'];
                        $module_action_tpl = empty($action) ? $arrayModule['modules_module'] : $arrayModule['modules_module'] . '_' . $action;

                        //权限
                        $objResponse->arrayRoleModulesEmployeePermissions = $arrayRoleModulesEmployeePermissions;
                        $objResponse->setTplValue('arrayRoleModulesEmployee', $arrayRoleModulesEmployeePermissions[$modules_id]);
                        //语言

                    }
                }
            }
        }
        //end common setting
        $arrayLaguage = CommonService::instance()->getPageModuleLaguage($modules_module);
        $objResponse->setTplValue('arrayLaguage', $arrayLaguage);
        $objResponse -> navigation = 'sales';
        $objResponse -> setTplValue('navigation', 'sales');
        //$objResponse->setTplValue('action', $module_action);
        //$objResponse->setTplValue("hashKey", \Encrypt::instance()->decode(date("Y-m-d") . __WEB_KEY));
        $objResponse -> setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
        $objResponse->setTplName("hotel/modules_" . $module_action_tpl);
        $objAction = new $module();
        $objAction->execute($action, $objRequest, $objResponse);//
    }
}