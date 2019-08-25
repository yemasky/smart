<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class LogStatementsAction extends \BaseAction {
    protected $objSuccess;
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $this->objSuccess = new \SuccessService();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "ShiftStatements":
                $this->doShiftStatements($objRequest, $objResponse);
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

        return $objResponse->successServiceResponse($this->objSuccess);
    }

    protected function doShiftStatements(\HttpRequest $objRequest, \HttpResponse $objResponse) {

        return $objResponse->successServiceResponse($this->objSuccess);
    }



}