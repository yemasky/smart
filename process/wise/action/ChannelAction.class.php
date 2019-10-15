<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;
/*
//Channel 管理
*/
class ChannelAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objResponse->nav = '';
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {

        switch ($objRequest->getAction()) {
            case "AddEdit":
                $this->doAddEdit($objRequest, $objResponse);
                break;
            case "Config":
                $this->doConfig($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        // TODO: Implement method() method.
        $method = $objRequest->method;
        if (!empty($method)) {
            $method = 'doMethod' . ucfirst($method);

            return $this->$method($objRequest, $objResponse);
        }

        return false;
    }

    public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->check($objRequest, $objResponse);
        $this->service($objRequest, $objResponse);
    }

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $arrayChannelList = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
        //赋值
        $objResponse->arrayChannelList = json_encode($arrayChannelList);
        $objResponse->channel_type     = json_encode(ModulesConfig::$channel_type);
        //设置URL
        $objResponse->add_url    = ModuleServiceImpl::instance()->getEncodeModuleId('Channel', 'Add');
        $objResponse->config_url = ModuleServiceImpl::instance()->getEncodeModuleId('Channel', 'Config');
    }

    protected function doAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        $method     = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $arrayChannel      = $arrayChannelList = array();
        $arrayChannelList  = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
        $channel_father_id = 0;
        if (!empty($channel_id) && $channel_id > 0) {
            if (isset($arrayChannelList[$channel_id])) {
                $arrayChannel      = $arrayChannelList[$channel_id];
                $channel_father_id = $arrayChannel['channel_father_id'];
            } else {
                return $objResponse->errorResponse('000007');
            }
        }
        $objResponse->arrayChannelList = $arrayChannelList;
        $objResponse->arrayChannel     = json_encode($arrayChannel);
        $objResponse->c_fatcher_id     = $channel_father_id;
        //channel_type
        $objResponse->channel_type = ModulesConfig::$channel_type;
    }

    protected function doMethodAdd(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $arrayInput = $objRequest->getInput();
        unset($arrayInput['channel_id']);
        $arrayInput['company_id'] = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $arrayInput['add_datetime'] = getDateTime();
        $channel_id                 = ChannelServiceImpl::instance()->saveChannel($arrayInput);
        if (empty($arrayInput['channel_father_id']) || $arrayInput['channel_father_id'] == 0) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('channel_id', $channel_id);
            ChannelServiceImpl::instance()->updateChannel($whereCriteria, ['channel_father_id' => $channel_id]);
        }

        return $objResponse->successResponse('000001', $channel_id);
    }

    protected function doMethodUpdate(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $arrayInput               = $objRequest->getInput();
        $arrayInput['company_id'] = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = isset($arrayInput['channel_id']) ? decode($arrayInput['channel_id'], getDay()) : '';
        if (empty($channel_id) || !is_numeric($channel_id)) {
            return $objResponse->errorResponse('000007');
        }
        unset($arrayInput['channel_id']);
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $arrayInput['company_id'])->EQ('channel_id', $channel_id);
        ChannelServiceImpl::instance()->updateChannel($whereCriteria, $arrayInput);

        return $objResponse->successResponse('000001', $channel_id);
    }

    protected function doConfig(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        if (empty($channel_id) || !is_numeric($channel_id)) {
            return $objResponse->setResponse('error', '000008');
        }

        $arrayChannelList = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
        if (isset($arrayChannelList[$channel_id])) {
            $arrayChannel = $arrayChannelList[$channel_id];
        } else {
            return $objResponse->setResponse('error', '000009');
        }
        //
        $arrayConfig = ModulesConfig::$channel_config[$arrayChannel['channel']];
        //设置 nav 企业管理->配置企业->配置属性
        $arrayEmployeeModule = ModuleServiceImpl::instance()->getAllModuleCache();
        $objResponse->nav  = CommonServiceImpl::instance()->getNavbar($arrayEmployeeModule, $objResponse->getResponse('_self_module'));
        $objResponse->arrayChannel = json_encode($arrayChannel);
        $objResponse->arrayConfig  = json_encode($arrayConfig);
        $objResponse->channel_id   = encode($channel_id, getDay());
        //设置URL
        $objResponse->channel_config_url = ModuleServiceImpl::instance()->getEncodeModuleId('ChannelConfig', 'default');
    }

}