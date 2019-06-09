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
            case "Borrowing":
                $this->doBorrowing($objRequest, $objResponse);
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
        $method = $objRequest->method;
        if(!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $arrayResult['channelConsume'] = ChannelServiceImpl::instance()->getChannelConsume($company_id);
        if(!empty($arrayResult['channelConsume'])) {
            foreach ($arrayResult['channelConsume'] as $k => $value) {
                $arrayResult['channelConsume'][$k]['c_c_id'] = encode($value['channel_consume_id']);
            }
        }

        $objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId($objRequest->getModule(), $objRequest->getAction()));

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doMethodEditChannelConsume(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $channel_consume_father_id = $objRequest->getInput('channel_consume_father_id');
        if($channel_consume_father_id == 0) {//新类别

        }
        $c_c_id = $objRequest->getInput('c_c_id');
        $c_c_id = !empty($c_c_id) ? decode($c_c_id) : null;
        $arrayInput = $objRequest->getInput();
        $arrayInput['company_id']   = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id                 = $objRequest->channel_id;
        if(is_numeric($channel_id) && $channel_id > 0) $arrayInput['channel_id'] = $channel_id;
        if(is_numeric($c_c_id) && $c_c_id > 0) {
            unset($arrayInput['c_c_id']);
            ChannelServiceImpl::instance()->updateChannelConsume($arrayInput['company_id'], $c_c_id, $arrayInput);
        } else {
            $arrayInput['add_datetime'] = getDateTime();
            $c_c_id = ChannelServiceImpl::instance()->saveChannelConsume($arrayInput);
        }

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['channel_consume_id'=>$c_c_id]);
    }

    protected function doBorrowing(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method = $objRequest->method;
        if(!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $arrayResult['channelBorrowing'] = ChannelServiceImpl::instance()->getChannelBorrowing($company_id);
        if(!empty($arrayResult['channelBorrowing'])) {
            foreach ($arrayResult['channelBorrowing'] as $k => $value) {
                $arrayResult['channelBorrowing'][$k]['c_b_id'] = encode($value['borrowing_id']);
            }
        }

        $objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId($objRequest->getModule(), $objRequest->getAction()));

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doMethodEditChannelBorrowing(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();

        $c_b_id = $objRequest->getInput('c_b_id');
        $c_b_id = !empty($c_b_id) ? decode($c_b_id) : null;
        $arrayInput = $objRequest->getInput();
        $arrayInput['company_id']   = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id                 = $objRequest->channel_id;
        if(is_numeric($channel_id) && $channel_id > 0) $arrayInput['channel_id'] = $channel_id;
        if(is_numeric($c_b_id) && $c_b_id > 0) {
            unset($arrayInput['c_b_id']);
            ChannelServiceImpl::instance()->updateChannelBorrowing($arrayInput['company_id'], $c_b_id, $arrayInput);
        } else {
            $arrayInput['add_datetime'] = getDateTime();
            $c_b_id = ChannelServiceImpl::instance()->saveChannelBorrowing($arrayInput);
        }

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], ['borrowing_id'=>$c_b_id]);
    }

}