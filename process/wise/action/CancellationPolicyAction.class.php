<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class CancellationPolicyAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "PolicyAddEdit":
                $this->doPolicyAddEdit($objRequest, $objResponse);
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
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
		$arrayResult['policyList'] = ChannelServiceImpl::instance()->getCancellationPolicy($company_id);

		$objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('CancellationPolicy', 'PolicyAddEdit'));
        //赋值
        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);

    }

    protected function doPolicyAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $arrayInput = $objRequest->getInput();
        if (isset($arrayInput['policy_id']) && $arrayInput['policy_id'] > 0) {//update
            $policy_id = $arrayInput['policy_id'];
            unset($arrayInput['policy_id']);
            ChannelServiceImpl::instance()->updateCancellationPolicy($company_id, $policy_id, $arrayInput);
            $arrayResult['policyList'] = ChannelServiceImpl::instance()->getCancellationPolicy($company_id);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);

        } else {
            $result = ChannelServiceImpl::instance()->checkSameCancellationPolicy($company_id, $arrayInput['policy_name'], $arrayInput['policy_en_name']);
            if (!$result) {
                return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['policy_name']);
            }
            $arrayInput['company_id']   = $company_id;
            $arrayInput['add_datetime'] = getDateTime();
            ChannelServiceImpl::instance()->saveCancellationPolicy($arrayInput);
			$arrayResult['policyList'] = ChannelServiceImpl::instance()->getCancellationPolicy($company_id);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
        }

        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

}