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
        //
        $successService = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }
    //
    protected function doMethodCuisineList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id      = $objRequest->channel_id;

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
        $successService                = new \SuccessService();
        $field = 'cuisine_id,cuisine_category_id,cuisine_name,cuisine_en_name,image_src,sku,sku_cuisine_id,sku_attr1,sku_attr1_value,sku_attr2,'
            .'sku_attr2_value,sku_attr3,sku_attr3_value,sku_attr4,sku_attr4_value,sku_attr5,sku_attr5_value,cuisine_inventory,cuisine_price,'
            .'cuisine_sell_clear,cuisine_specialty,cuisine_en_specialty,cuisine_is_category';
        $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisine($whereCriteria, $field);
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    //查询协议公司数据
    protected function doMethodGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $hotelOrderAction = new HotelOrderAction('RoomOrder');
        $objRequest->setAction('RoomOrder');
        return $hotelOrderAction->invoking($objRequest, $objResponse);
    }
}