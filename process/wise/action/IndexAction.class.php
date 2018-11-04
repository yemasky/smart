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
        //赋值
        //设置类别
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

            if (method_exists($this, $method)) return $this->$method($objRequest, $objResponse);
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
        $arrayEmployee                   = $loginEmployeeModel->getPrototype();
        $arrayEmployee['employeeInfo']   = $loginEmployeeModel->getEmployeeInfo()->getPrototype();
        $arrayEmployee['module_channel'] = 'Booking';
        //用户菜单
        return $objResponse->successResponse('000001', array('loginEmployee' => $arrayEmployee));
    }

    protected function doCommon(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $objResponse->getResponse());
    }
}