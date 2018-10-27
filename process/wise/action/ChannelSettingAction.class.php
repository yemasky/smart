<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class ChannelSettingAction extends \BaseAction {
	protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
	}

	protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		switch($objRequest->getAction()) {
			case "paymentAddEdit":
				$this->doPaymentAddEdit($objRequest, $objResponse);
			break;
			case "payment":
				$this->doPayment($objRequest, $objResponse);
			break;
			case "marketAddEdit":
				$this->doMarketAddEdit($objRequest, $objResponse);
			break;
			case "market":
				$this->doMarket($objRequest, $objResponse);
			break;
			default:
				$this->doDefault($objRequest, $objResponse);
			break;
		}
	}

	protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		// TODO: Implement method() method.
		$method = $objRequest->method;
		if(!empty($method)) {
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

		$objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
	}

	protected function doPayment(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('ChannelSetting', 'paymentAddEdit'));
		//赋值
		$arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);
		$objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayPaymentType);
	}

	protected function doPaymentAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$arrayInput = $objRequest->getInput();
		if(isset($arrayInput['payment_id']) && $arrayInput['payment_id'] > 0) {//update
			if(isset($arrayInput['company_id'])) unset($arrayInput['company_id']);
			if(isset($arrayInput['payment_id'])) {
				$payment_id = $arrayInput['payment_id'];
				unset($arrayInput['payment_id']);
				ChannelServiceImpl::instance()->updatePaymentType($company_id, $payment_id, $arrayInput);
				$arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);

				return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayPaymentType);
			}
		} else {
			$result                    = ChannelServiceImpl::instance()->checkSameNamePaymentType($company_id, $arrayInput['payment_name'], $arrayInput['payment_en_name']);
			if(!$result) {
				return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['market_name']);
			}
			$arrayInput['company_id']  = $company_id;
			ChannelServiceImpl::instance()->savePaymentType($arrayInput);
			$arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);

			return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayPaymentType);
		}

		return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
	}

	protected function doMarket(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('ChannelSetting', 'marketAddEdit'));
		//赋值
		$arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
		$objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayCustomerMarket);
	}

	protected function doMarketAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$arrayInput = $objRequest->getInput();
		if(isset($arrayInput['market_id']) && $arrayInput['market_id'] > 0) {//update
			if(isset($arrayInput['company_id'])) unset($arrayInput['company_id']);
			if(isset($arrayInput['market_id'])) {
				$market_id = $arrayInput['market_id'];
				unset($arrayInput['market_id']);
				ChannelServiceImpl::instance()->updateCustomerMarket($company_id, $market_id, $arrayInput);
				$arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

				return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayCustomerMarket);
			}
		} else {
			$result                    = ChannelServiceImpl::instance()->checkSameNameCustomerMarket($company_id, $arrayInput['market_name'], $arrayInput['market_en_name']);
			if(!$result) {
				return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['market_name']);
			}
			$arrayInput['company_id']  = $company_id;
			ChannelServiceImpl::instance()->saveCustomerMarket($arrayInput);
			$arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

			return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayCustomerMarket);
		}

		return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
	}

}