<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class ConsumeAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "HotelConsume":
                $this->doHotelConsume($objRequest, $objResponse);
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
        $this->setDisplay();

        //赋值
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);

    }

    protected function doHotelConsume(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $arrayResult['channelConsume'] = ChannelServiceImpl::instance()->getChannelConsume($company_id);

        $objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId($objRequest->getModule(), $objRequest->getAction()));

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

}