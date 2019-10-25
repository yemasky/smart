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
        switch ($objRequest->getAction()) {
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
            case "MarketCommission"://市場佣金
                $this->doMarketCommission($objRequest, $objResponse);
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
        if (isset($arrayInput['payment_id']) && $arrayInput['payment_id'] > 0) {//update
            if (isset($arrayInput['company_id'])) unset($arrayInput['company_id']);
            if (isset($arrayInput['payment_id'])) {
                $payment_id = $arrayInput['payment_id'];
                unset($arrayInput['payment_id']);
                ChannelServiceImpl::instance()->updatePaymentType($company_id, $payment_id, $arrayInput);
                $arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);

                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayPaymentType);
            }
        } else {
            $result = ChannelServiceImpl::instance()->checkSameNamePaymentType($company_id, $arrayInput['payment_name'], $arrayInput['payment_en_name']);
            if (!$result) {
                return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['market_name']);
            }
            $arrayInput['company_id'] = $company_id;
            ChannelServiceImpl::instance()->savePaymentType($arrayInput);
            $arrayPaymentType = ChannelServiceImpl::instance()->getPaymentTypeHash($company_id);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayPaymentType);
        }

        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }

    protected function doMarket(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        //
        $objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('ChannelSetting', 'marketAddEdit'));
        //赋值
        $arrayResult['customerMarket'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //市場佣金
    protected function doMarketCommission(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        //赋值
        $arrayResult['marketList'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        //
        //取出所有有效价格类型
        $arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id, 1);
        //
        $arrayResult['channelCommisionList'] = ChannelServiceImpl::instance()->getChannelCommisionHash($company_id);
        if (!empty($arrayResult['channelCommisionList'])) {
            foreach ($arrayResult['channelCommisionList'] as $channel_id => $v) {
                foreach ($v as $market_id => $value) {
                    foreach ($value as $price_system_id => $item) {
                        $arrayResult['channelCommisionList'][$channel_id][$market_id][$price_system_id]['cc_id'] = encode($item['channel_commision_id']);
                    }
                }

            }
        }
        //
        $successService = new \SuccessService();
        $successService->setCode(ErrorCodeConfig::$successCode['success']);
        $successService->setData($arrayResult);
        $objResponse->successServiceResponse($successService);
    }

    protected function doMethodAddEditMarketCommission(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->getInput('channel_id');
        $cc_id      = decode($objRequest->cc_id);
        //
        $arraySaveData['commision_form']       = $objRequest->getInput('commision_form');
        $arraySaveData['commision_form_value'] = $objRequest->getInput('commision_form_value');
        $arraySaveData['commision_type']       = $objRequest->getInput('commision_type');
        $arraySaveData['market_id']            = $objRequest->getInput('market_id');
        $arraySaveData['price_system_id']      = $objRequest->getInput('price_system_id');
        $arraySaveData['valid']                = $objRequest->getInput('valid');
        if (empty($cc_id)) {
            $arraySaveData['company_id']   = $company_id;
            $arraySaveData['channel_id']   = $channel_id;
            $arraySaveData['add_datetime'] = getDateTime();
            ChannelServiceImpl::instance()->saveChannelCommision($arraySaveData);
        } else {
            ChannelServiceImpl::instance()->updateChannelCommision($arraySaveData, $company_id, $cc_id);
        }


        $successService = new \SuccessService();
        $successService->setCode(ErrorCodeConfig::$successCode['success']);
        $successService->setData('');
        $objResponse->successServiceResponse($successService);
    }
    //客源市场
    protected function doMarketAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $arrayInput = $objRequest->getInput();
        if (isset($arrayInput['market_id']) && $arrayInput['market_id'] > 0) {//update
            if (isset($arrayInput['company_id'])) unset($arrayInput['company_id']);
            if (isset($arrayInput['market_id'])) {
                $market_id = $arrayInput['market_id'];
                unset($arrayInput['market_id']);
                ChannelServiceImpl::instance()->updateCustomerMarket($company_id, $market_id, $arrayInput);
                $arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayCustomerMarket);
            }
        } else {
            $result = ChannelServiceImpl::instance()->checkSameNameCustomerMarket($company_id, $arrayInput['market_name'], $arrayInput['market_en_name']);
            if (!$result) {
                return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['market_name']);
            }
            $arrayInput['company_id'] = $company_id;
            ChannelServiceImpl::instance()->saveCustomerMarket($arrayInput);
            $arrayCustomerMarket = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayCustomerMarket);
        }

        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
    }
    //协议公司
    protected function doMethodReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayLoginEmployee = LoginServiceImpl::instance()->getLoginInfo();
        $company_id         = $arrayLoginEmployee->getCompanyId();
        $channel_id         = $objRequest->getInput('channel_id');
        if (empty($channel_id)) $channel_id = 0;
        $cr_id = decode($objRequest->cr_id);
        if ($cr_id > 0) {
            $receivable_id                         = $cr_id;
            $arrayUpdate['receivable_name']        = $objRequest->receivable_name;
            $arrayUpdate['receivable_type']        = $objRequest->receivable_type;
            $arrayUpdate['receivable_address']     = $objRequest->receivable_address;
            $arrayUpdate['receivable_credit_code'] = $objRequest->receivable_credit_code;
            $arrayUpdate['market_id']              = $objRequest->market_id;
            $arrayUpdate['contact_name']           = $objRequest->contact_name;
            $arrayUpdate['contact_mobile']         = $objRequest->contact_mobile;
            $arrayUpdate['contact_email']          = $objRequest->contact_email;
            $arrayUpdate['bank']                   = $objRequest->bank;
            $arrayUpdate['bank_account']           = $objRequest->bank_account;
            $arrayUpdate['credit']                 = $objRequest->credit;
            $arrayUpdate['valid_date']             = $objRequest->valid_date;
            $arrayUpdate['valid']                  = $objRequest->valid;
            $whereCriteria                         = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('receivable_id', $receivable_id);
            ChannelServiceImpl::instance()->updateChannelReceivable($whereCriteria, $arrayUpdate);
        } else {
            $arrayInput               = $objRequest->getInput();
            $Channel_receivableEntity = new Channel_receivableEntity($arrayInput);
            $Channel_receivableEntity->setCompanyId($company_id);
            $Channel_receivableEntity->setChannelId($channel_id);
            $Channel_receivableEntity->setCreditUsed(0);
            $Channel_receivableEntity->setTheCumulative(0);
            $Channel_receivableEntity->setTheCumulativePayback(0);
            $Channel_receivableEntity->setEmployeeId($arrayLoginEmployee->getEmployeeId());
            $Channel_receivableEntity->setEmployeeName($arrayLoginEmployee->getEmployeeName());
            $Channel_receivableEntity->setAddDatetime(getDateTime());

            $receivable_id = ChannelServiceImpl::instance()->saveChannelReceivable($Channel_receivableEntity);
            $cr_id         = encode($receivable_id);
        }
        $successService = new \SuccessService();
        $successService->setData(['receivable_id' => $receivable_id, 'cr_id' => $cr_id]);
        return $objResponse->successServiceResponse($successService);
    }
    //协议公司
    protected function doMethodReceivablePagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['receivableData'] = ChannelServiceImpl::instance()->getChannelReceivablePage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }
}