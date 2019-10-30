<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

use member\MemberServiceImpl;

class DiscountCouponPromotionAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "DiscountCoupon":
                $this->doDiscountCoupon($objRequest, $objResponse);
                break;
            case "RewardPoints":
                $this->doRewardPoints($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
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
        $objSuccessService = new \SuccessService();
        return $objResponse->successServiceResponse($objSuccessService);
    }

    //折扣优惠卷
    protected function doDiscountCoupon(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $objLoginEmployee = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id       = $objLoginEmployee->getEmployeeInfo()->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        //默认channel
        $arrayEmployeeChannel = $objLoginEmployee->getEmployeeChannel();
        $thisChannel          = $arrayEmployeeChannel[$channel_id];
        if ($thisChannel['channel'] == 'Hotel') {
            $objRequest->sqlHashKey       = 'item_id';
            $arrayResult['allLayoutList'] = ChannelServiceImpl::instance()->getChannelItemLayout($objRequest);
        }
        if ($thisChannel['channel'] == 'Meal') {
            $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisineList($objRequest, $objResponse);
        }
        //客源市场
        $arrayResult['marketList'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

        $objSuccessService = new \SuccessService();
        $objSuccessService->setData($arrayResult);

        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodMealSellList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['receivableData'] = DiscountServiceImpl::instance()->getChannelDiscountPage($objRequest, $objResponse);
        $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisineList($objRequest, $objResponse);
        $successService                = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodSavePromotion(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id     = $objRequest->channel_id;
        $module_channel = $objRequest->module_channel;
        $cd_id          = decode($objRequest->cd_id);
        $arrayInput     = $objRequest->getInput();
        unset($arrayInput['tableState']);

        $arraySelectMarket = $objRequest->getInput('selectMarket');
        unset($arrayInput['selectMarket']);
        if (!empty($arraySelectMarket)) {
            $arrayMarketPromotion = [];
            foreach ($arraySelectMarket as $item => $value) {
                $arrayMarketPromotion[] = $item;
            }
            $arrayInput['market_ids'] = implode(',', $arrayMarketPromotion);
        }
        if ($module_channel == 'Hotel') {
            $arrayLayout = $objRequest->getInput('layout');
            if (!empty($arrayLayout)) {
                $arrayyLayoutPromotion = [];
                foreach ($arrayLayout as $item => $value) {
                    if ($value) $arrayyLayoutPromotion[] = $item;
                }
                $arrayInput['discount_item_list'] = implode(',', $arrayyLayoutPromotion);
            }
            unset($arrayInput['layout']);
        }
        if ($module_channel == 'Meal') {
            $arrayCuisine = $objRequest->getInput('cuisine');
            if (!empty($arrayCuisine)) {
                $arrayCuisinePromotion = [];
                foreach ($arrayCuisine as $item => $value) {
                    if ($value) $arrayCuisinePromotion[] = $item;
                }
                $arrayInput['discount_item_list'] = implode(',', $arrayCuisinePromotion);
            }
            unset($arrayInput['cuisine']);
        }
        if (isset($arrayInput['use_week'])) {
            if (!empty($arrayInput['use_week'])) {
                $arrayUse_week = [];
                foreach ($arrayInput['use_week'] as $item => $value) {
                    if ($value) $arrayUse_week[] = $item;
                }
                $arrayInput['use_week'] = implode(',', $arrayUse_week);
            }
        }
        $arrayInput['market_father_ids'] = json_encode($arrayInput['market_father_ids']);
        if (empty($cd_id)) {
            $arrayInput['company_id'] = $company_id;
            $arrayInput['channel_id'] = $channel_id;
            $discount_id              = DiscountServiceImpl::instance()->saveDiscount($arrayInput);
        } else {
            unset($arrayInput['cd_id']);
            unset($arrayInput['use_condition']);
            unset($arrayInput['add_datetime']);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('discount_id', $cd_id);
            DiscountServiceImpl::instance()->updateDiscount($whereCriteria, $arrayInput);
            $discount_id = $cd_id;
        }


        $objSuccessService = new \SuccessService();
        $objSuccessService->setData(['discount_id' => $discount_id, 'cd_id' => encode($discount_id)]);

        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodHotelSellList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objRequest->sqlHashKey        = 'item_id';
        $arrayResult['allLayoutList']  = ChannelServiceImpl::instance()->getChannelItemLayout($objRequest);
        $arrayResult['receivableData'] = DiscountServiceImpl::instance()->getChannelDiscountPage($objRequest, $objResponse);

        $objSuccessService = new \SuccessService();
        $objSuccessService->setData($arrayResult);
        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodDiscountCouponPagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['receivableData'] = DiscountServiceImpl::instance()->getChannelDiscountPage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    //积分兑换
    protected function doRewardPoints(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $objLoginEmployee  = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id        = $objLoginEmployee->getEmployeeInfo()->getCompanyId();
        $channel_id        = $objRequest->channel_id;
        $objSuccessService = new \SuccessService();
        return $objResponse->successServiceResponse($objSuccessService);
    }

    public function doMethodSaveRewardPoints(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id       = $objLoginEmployee->getEmployeeInfo()->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        $arrayInput       = $objRequest->getInput();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);

        $arrayChannelId = ChannelServiceImpl::instance()->getChannelSettingList($whereCriteria, 'channel_id');
        if (empty($arrayChannelId)) {
            $arrayInput['company_id'] = $company_id;
            $arrayInput['channel_id'] = $channel_id;
            ChannelServiceImpl::instance()->saveChannelSetting($arrayInput);
        } else {
            ChannelServiceImpl::instance()->updateChannelSetting($whereCriteria, $arrayInput);
        }
        LoginServiceImpl::instance()->updateEmployeeCookie();

        $objSuccessService = new \SuccessService();
        return $objResponse->successServiceResponse($objSuccessService);
    }


}