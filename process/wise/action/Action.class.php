<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class Action {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //common setting//变量:index_url arrayXxxXxx; function : doXxxx getXxxx
        $objResponse->thisDateTime = getDateTime();
        $objResponse->__VERSION    = ModulesConfig::$__VERSION;
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //default setting
        $module_id      = $objRequest->channel;
        $module_id      = empty($module_id) ? '' : \Encrypt::instance()->decode($module_id, getDay());
        $action         = $objRequest->action;
        $module         = $module_id == 'home' ? 'home' : '';
        $module_channel = 'Booking';

        $objResponse->__nav_name = '';
        $objLoginEmployee        = LoginServiceImpl::instance()->checkLoginEmployee();
        if (empty($objLoginEmployee->getEmployeeInfo()->getEmployeeId()) && $objRequest->getAction() != 'login') {
            $action               = empty($action) ? 'login' : $action;
            $objResponse->noLogin = 1;
            if (isset($_SERVER['HTTP_AJAXREQUEST']) && $_SERVER['HTTP_AJAXREQUEST'] == true) {
                return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['login_over_time']['code']);
            } else {
                //$action = 'login_over_time';
            }
        } else {
            $objResponse->noLogin = '0';
            $objEmployee          = $objLoginEmployee->getEmployeeInfo();
            //根据切换来变换default_channel_father_id
            $arrayEmployeeChannel                   = $objLoginEmployee->getEmployeeChannel();
            $default_channel                        = count($arrayEmployeeChannel) > 0 ? current($arrayEmployeeChannel) : null;
            $default_channel_father_id              = $default_channel == null ? $default_channel[0]['channel_id'] : '0';
            $objResponse->default_channel_father_id = $default_channel_father_id;
            $channelSettingList                     = $objLoginEmployee->getChannelSettingList();
            //根据default_channel_father_id 来取得营业日
            $business_day = getDay();//默认营业日
            if (isset($channelSettingList[$default_channel_father_id])) {
                $thisChannelSeting = $objLoginEmployee->getChannelSetting($default_channel_father_id);
                if ($thisChannelSeting->getisBusinessDay() == 1) {
                    $business_day = ChannelServiceImpl::instance()->getBusinessDay($default_channel_father_id);
                }
            }
            LoginServiceImpl::setBusinessDay($business_day);
            $objResponse->business_day = $business_day;//给tpl赋值
            $_self_module              = array();
            if (is_numeric($module_id) && $module_id > 0) {
                ////判断权限
                $arrayEmployeeRoleModule = RoleServiceImpl::instance()->getEmployeeRoleModuleCache($objEmployee->getCompanyId(), $objEmployee->getEmployeeId());
                if (isset($arrayEmployeeRoleModule[$module_id])) {
                    $arrayEmployeeModule = ModuleServiceImpl::instance()->getAllModuleCache();
                    if (isset($arrayEmployeeModule[$module_id])) {
                        $module         = $arrayEmployeeModule[$module_id]['module'];
                        $action         = !empty($arrayEmployeeModule[$module_id]['action']) ? $arrayEmployeeModule[$module_id]['action'] : $action;
                        $module_channel = $arrayEmployeeModule[$module_id]['module_channel'];
                    } else {
                        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['no_module']['code']);
                    }
                    $objResponse->__module = $arrayEmployeeModule[$module_id]['module'];
                    $_self_module          = $arrayEmployeeModule[$module_id];
                } else {
                    return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['no_permission']['code']);
                }
            }
            //get employee module
            if (isset($_SERVER['HTTP_REFRESH']) && $_SERVER['HTTP_REFRESH'] == 1) {//刷新
                $objResponse->setResponse('__module_id', $module_id);
                $objResponse->setResponse('business_day', $objResponse->business_day);
                $objResponse->setResponse('module_channel', $module_channel);
                $objResponse->setResponse('employeeMenu', $objLoginEmployee->getEmployeeMenu());
                $objEmployee->setEmployeeId(0);
                $objResponse->setResponse('employeeInfo', $objEmployee->getPrototype());
                $objResponse->setResponse('employeeChannel', $objLoginEmployee->getEmployeeChannel());
                $objResponse->setResponse('channelSettingList', $objLoginEmployee->getChannelSettingList());
            }
            $objResponse->setResponse('_self_module', $_self_module);
        }
        $module = empty($module) ? 'Index' : ucwords($module);
        //common setting
        $objResponse->__module_id  = $module_id;
        $objResponse->home_channel = \Encrypt::instance()->encode('home', getDay());
        //$objResponse->index_url       = 'app.do';
        //$objResponse->check_login_url = \BaseUrlUtil::getHtmlUrl(['method' => 'checkLogin']);
        $objResponse->setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
        //
        $action_tpl = empty($action) ? 'default' : $action;
        $objResponse->setTplName("wise/" . $module . "/" . $action_tpl);

        $module    = '\wise\\' . $module . 'Action';
        $objAction = new $module();
        //
        $objAction->execute($action, $objRequest, $objResponse);//
    }

    public function execute() {
        try {
            set_error_handler("ErrorHandler");//$error_handler =
            $objRequest  = new \HttpRequest();
            $objResponse = new \HttpResponse();

            // 入力检查
            $this->check($objRequest, $objResponse);
            //接入服务
            $this->service($objRequest, $objResponse);
        } catch (\Exception $e) {
            if (__Debug) {
                echo('错误信息: ' . $e->getMessage() . "<br>");
                echo(str_replace("\n", "\n<br>", $e->getTraceAsString()));
            }
            // 错误日志
            logError($e->getMessage(), __MODEL_EXCEPTION);
            logError($e->getTraceAsString(), __MODEL_EMPTY);
        }
    }
}