<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class IndexAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case 'login':
                $this->doLogin($objRequest, $objResponse);
                break;
            case 'logout':
                $this->doLogout($objRequest, $objResponse);
                break;
            case 'noPermission':
                $this->doNoPermission($objRequest, $objResponse);
                break;
            case 'common':
                $this->doCommon($objRequest, $objResponse);
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
    }

    public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->check($objRequest, $objResponse);
        $this->service($objRequest, $objResponse);
    }

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //
    }

    /**
     * 首页显示
     */
    protected function doNoPermission(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        //赋值
        //设置类别
    }

    protected function doLogin(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            $method = 'doMethod' . ucfirst($method);
            if (method_exists($this, $method))
                return $this->$method($objRequest, $objResponse);
        }
        if ($objRequest->_ != '') {
            $objResponse->setTplName("wise/index/nologin");
            return false;
        }
        //
        $objResponse->setTplName("wise/index/default");
    }

    protected function doLogout(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        LoginServiceImpl::instance()->logout();
        $this->setDisplay();

        return $objResponse->successResponse('000001');
    }

    //登錄註冊
    protected function doMethodCheckLogin(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        //sleep(31);
        $loginEmployeeModel = LoginServiceImpl::instance()->doLoginEmployee($objRequest, $objResponse);
        if (empty($loginEmployeeModel->getEmployeeInfo()->getEmployeeId())) {
            return $objResponse->errorResponse('000002');
        }
        //
        //根据切换来变换default_channel_father_id
        $arrayEmployeeChannel      = $loginEmployeeModel->getEmployeeChannel();
        $default                   = current($arrayEmployeeChannel);
        $default_channel           = $arrayEmployeeChannel[$default['default_id']];
        $default_channel_father_id = $default_channel['channel_id'];
        $channel_id                = $default_channel['default_id'];

        $channelSettingList = $loginEmployeeModel->getChannelSettingList();
        //根据default_channel_father_id 来取得营业日
        $business_day = getDay();//默认营业日 自动营业日
        if (isset($channelSettingList[$default_channel_father_id])) {
            //设置默认的channel_id
            $thisChannelSeting = $loginEmployeeModel->getChannelSetting($default_channel_father_id);
            if ($thisChannelSeting->getisBusinessDay() == 1) {//手动营业日
                $business_day = ChannelServiceImpl::instance()->getBusinessDay($default_channel_father_id);
                if (empty($business_day)) {
                    $business_day                      = getDay();
                    $arrayBussinessDay['business_day'] = $business_day;
                    $arrayBussinessDay['company_id']   = $loginEmployeeModel->getEmployeeInfo()->getCompanyId();
                    $arrayBussinessDay['channel_id']   = $channel_id;
                    $arrayBussinessDay['add_datetime'] = getDateTime();
                    ChannelServiceImpl::instance()->saveBusinessDay($arrayBussinessDay);
                }
            }
        }
        LoginServiceImpl::setBusinessDay($business_day);
        //
        $arrayEmployee                   = $loginEmployeeModel->getPrototype();
        $arrayEmployee['employeeInfo']   = $loginEmployeeModel->getEmployeeInfo()->getPrototype();
        $arrayEmployee['module_channel'] = 'Booking';
        $arrayEmployee['business_day']   = $business_day;
        //用户菜单
        return $objResponse->successResponse('000001', array('loginEmployee' => $arrayEmployee));
    }

    protected function doCommon(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $objResponse->getResponse());
    }
}