<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class MealOrderAction extends \BaseAction {
    protected $Booking_operationEntity;

    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $_self_module     = $objResponse->getResponse('_self_module');
        //获取channel
        $channel_id              = $objRequest->channel_id;
        $Booking_operationEntity = new Booking_operationEntity();
        $Booking_operationEntity->setAddDatetime(getDateTime());
        $Booking_operationEntity->setBusinessDay(LoginServiceImpl::getBusinessDay());
        $Booking_operationEntity->setEmployeeId($objLoginEmployee->getEmployeeId());
        $Booking_operationEntity->setEmployeeName($objLoginEmployee->getEmployeeName());
        $Booking_operationEntity->setCompanyId($company_id);
        $Booking_operationEntity->setChannelId($channel_id);
        $Booking_operationEntity->setModuleId($_self_module['module_id']);
        $Booking_operationEntity->setModuleName($_self_module['module_name']);
        $Booking_operationEntity->setMethod($objRequest->method);
        $request = json_encode($objRequest->get()) . json_encode($objRequest->getInput()) . json_encode($objRequest->getPost());
        $Booking_operationEntity->setRequest($request);
        $this->Booking_operationEntity = $Booking_operationEntity;
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

    //餐饮预订
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
        $arrayResult['channelDiscountList'] = DiscountServiceImpl::instance()->getBookingDiscount($company_id, $channel_id);
        //
        $successService = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        BookCommon::instance()->doCheckMember($objRequest, $objResponse);
    }

    //
    protected function doMethodCuisineList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objRequest->order             = ['cuisine_category_id' => 'ASC'];
        $arrayResult['allCuisineList'] = CuisineServiceImpl::instance()->getCuisineList($objRequest, $objResponse);
        $successService                = new \SuccessService();
        $successService->setData($arrayResult);
        return $objResponse->successServiceResponse($successService);
    }

    //查询协议公司数据
    protected function doMethodGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        BookCommon::instance()->doGetReceivable($objRequest, $objResponse);
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