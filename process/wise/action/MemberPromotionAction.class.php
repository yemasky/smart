<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class MemberPromotionAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "MemberPromotion":
                $this->doMemberPromotion($objRequest, $objResponse);
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

    //交班收银报表
    protected function doMemberPromotion(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = new \SuccessService();
        
        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doThemePromotion(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objSuccessService = new \SuccessService();


        return $objResponse->successServiceResponse($objSuccessService);
    }



}