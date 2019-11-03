<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class MealOrderAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case 'RestaurantReservation':
                $this->doRestaurantReservation($objRequest, $objResponse);
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
        $successService = new \SuccessService();

        return $objResponse->successServiceResponse($successService);
    }

    protected function doRestaurantReservation(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        //
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        //
        $arrayResult['in_date'] = $in_date = LoginServiceImpl::getBusinessDay();
        //客源市场
        $arrayResult['marketList'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
        //房间
        $objRequest->channel_config = 'table';
        $objRequest->hashKey        = 'item_attr2_value';
        $objRequest->childrenHash   = 'item_attr1_value';
        $objRequest->toHashArray    = true;
        $arrayResult['roomList']    = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        //取出折扣
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->ArrayIN('channel_id', ['0', $channel_id])->GE('end_date', getDay());
        $arrayResult['channelDiscountList'] = DiscountServiceImpl::instance()->getDiscount($whereCriteria);
        //
        $successService = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    //
    protected function doMethodCuisineList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisineList($objRequest, $objResponse);
        $successService                = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    //查询协议公司数据
    protected function doMethodGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $hotelOrderAction = new HotelOrderAction();
        $objRequest->setAction('RoomOrder');
        $hotelOrderAction->invoking($objRequest, $objResponse);
    }

    //保存预订数据
    protected function doMethodSaveRestaurantBook(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = BookingRestaurantServiceImpl::instance()->beginBooking($objRequest, $objResponse);
        if ($objSuccessService->isSuccess()) {
            $objSuccessService = BookingRestaurantServiceImpl::instance()->saveBooking($objSuccessService->getData());
            if ($objSuccessService->isSuccess()) {

                return $objResponse->successServiceResponse($objSuccessService);
            }
        }
        return $objResponse->successServiceResponse($objSuccessService);
    }
}