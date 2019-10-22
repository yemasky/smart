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
        $encode_module_id = $objRequest->channel;
        $module_id        = empty($encode_module_id) ? '' : \Encrypt::instance()->decode($encode_module_id, getDay());
        $action           = $objRequest->action;
        $module           = $module_id == 'home' ? 'home' : '';
        $module_channel   = 'Booking';
        $module_name      = '首页';
        //
        $channel_id = $objRequest->id;
        $channel_id = !empty($channel_id) ? decode($channel_id, getDay()) : null;
        if (!empty($channel_id)) $objRequest->channel_id = $channel_id;//如果没有传递channl_id 系统会在下面默认为用户登录的第一个channel_id

        $objResponse->__nav_name = '';
        $objLoginEmployee        = LoginServiceImpl::instance()->checkLoginEmployee();
        if (empty($objLoginEmployee->getEmployeeInfo()->getEmployeeId()) && $objRequest->getAction() != 'login') {
            //登录action
            $action               = empty($action) ? 'login' : $action;
            $objResponse->noLogin = 1;
            if ((isset($_SERVER['HTTP_AJAXREQUEST']) && $_SERVER['HTTP_AJAXREQUEST'] == true) || $objRequest->view != '') {
                return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['login_over_time']['code']);
            } else {
                //$action = 'login_over_time';
                if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
                    && $objRequest->method != 'checkLogin') {
                    return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['login_over_time']['code']);
                }
            }
        } else {
            $objResponse->noLogin = '0';
            $objEmployee          = $objLoginEmployee->getEmployeeInfo();
            //根据切换来变换default_channel_father_id
            //每个酒店有自己的营业时间 default_channel_id 做为切换的酒店的设置 子酒店 使用母酒店的数据
            //顶上的菜单做为切换的母酒店（菜单栏第一行,页面的第一行的切换）
            //如果有酒店那么酒店一定做为母酒店，餐饮和其他没有营业日 所以不能。
            //母酒店ID default_channel_id 不受下面的酒店或餐饮等切换的影响
            $default_channel_id                     = $objEmployee->getDefaultChannelId();
            $arrayEmployeeChannel                   = $objLoginEmployee->getEmployeeChannel();
            $default                                = $arrayEmployeeChannel[$default_channel_id];
            //取出切换的酒店
            $default_channel                        = $arrayEmployeeChannel[$default['default_id']];
            $default_channel_father_id              = $default_channel['channel_id'];
            $objResponse->default_channel_father_id = $default_channel_father_id;
            $channelSettingList = $objLoginEmployee->getChannelSettingList();
            //根据母酒店default_channel_father_id 来取得营业日
            $business_day = getDay();//默认营业日 自动营业日
            if (isset($channelSettingList[$default_channel_father_id])) {
                //设置默认的channel_id
                $thisChannelSeting = $objLoginEmployee->getChannelSetting($default_channel_father_id);
                if ($thisChannelSeting->getisBusinessDay() == 1) {//手动营业日
                    $business_day = ChannelServiceImpl::instance()->getBusinessDay($default_channel_father_id);
                    if (empty($business_day)) {
                        $business_day                      = getDay();
                        $arrayBussinessDay['business_day'] = $business_day;
                        $arrayBussinessDay['company_id']   = $objEmployee->getCompanyId();
                        $arrayBussinessDay['channel_id']   = $default_channel_father_id;//$channel_id
                        $arrayBussinessDay['add_datetime'] = getDateTime();
                        ChannelServiceImpl::instance()->saveBusinessDay($arrayBussinessDay);
                    }
                }
            }
            LoginServiceImpl::setBusinessDay($business_day);
            $objResponse->business_day = $business_day;//给tpl赋值
            $_self_module              = array();
            if (is_numeric($module_id) && $module_id > 0) {
                ////判断权限
                $arrayEmployeeRoleModule = RoleServiceImpl::instance()->getEmployeeRoleModuleCache($objEmployee->getCompanyId(), $objEmployee->getEmployeeId());//已删除参数$channel_id
                if (isset($arrayEmployeeRoleModule[$module_id])) {//有权限
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
                    //默认值 channel_id
                    if (empty($channel_id)) {
                        $objRequest->channel_id = $channel_id = $default_channel['default_id'];
                        if($module == 'MealOrder') {//餐饮服务 取出第一个餐饮的channel_id
                            if(!empty($arrayEmployeeChannel)) {
                                foreach ($arrayEmployeeChannel as $key => $channelValue) {
                                    if($channelValue['channel'] == 'Meal') {
                                        $objRequest->channel_id = $channelValue['channel_id'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else {//无权限
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
        $objRequest->setModule($module);//设置module

        //common setting
        $objResponse->home_channel  = \Encrypt::instance()->encode('home', getDay());
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