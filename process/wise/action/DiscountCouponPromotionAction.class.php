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
            case "ThemePromotion":
                $this->doThemePromotion($objRequest, $objResponse);
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
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        //取得门店会员促销
        //客源市场
        $arrayResult['marketList'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);

        $objSuccessService = new \SuccessService();
        $objSuccessService->setData($arrayResult);

        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doThemePromotion(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = new \SuccessService();


        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodMealSellList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $mealAction = new MealOrderAction();
        $objRequest->setAction('RestaurantReservation');
        unset($objRequest->method);
        $objRequest->method = 'CuisineList';
        $mealAction->invoking($objRequest, $objResponse);

    }

    protected function doMethodSaveMealPromotion(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $cd_id = decode($objRequest->cd_id);
        $arrayInput = $objRequest->getInput();

        $arraySelectMarket = $objRequest->getInput('selectMarket');
        unset($arrayInput['selectMarket']);
        if(!empty($arraySelectMarket)) {
            $arrayMarketPromotion = [];
            foreach ($arraySelectMarket as $item => $value) {
                $arrayMarketPromotion[] = $item;
            }
            $arrayInput['market_ids'] = implode(',', $arrayMarketPromotion);
        }
        $arrayCuisine = $objRequest->getInput('cuisine');
        if(!empty($arrayCuisine)) {
            $arrayCuisinePromotion = [];
            foreach ($arrayCuisine as $item => $value) {
                if($value) $arrayCuisinePromotion[] = $item;
            }
            $arrayInput['discount_item_list'] = implode(',', $arrayCuisinePromotion);
        }
        unset($arrayInput['cuisine']);
        $arrayInput['use_week'] = implode(',', $arrayInput['use_week']);
        $arrayInput['market_father_ids'] = json_encode($arrayInput['market_father_ids']);
        if(empty($cd_id)) {
            $arrayInput['company_id'] = $company_id;
            $arrayInput['channel_id'] = $channel_id;
        }
        $discount_id = DiscountServiceImpl::instance()->saveDiscount($arrayInput);

        $objSuccessService = new \SuccessService();
        $objSuccessService->setData(['discount_id'=>$discount_id,'cd_id'=>encode($discount_id)]);

        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodHotelSellList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;

        $objSuccessService = new \SuccessService();


        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodDiscountCouponPagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['receivableData'] = DiscountServiceImpl::instance()->getChannelDiscountPage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

}