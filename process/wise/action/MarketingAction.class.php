<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class MarketingAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "SalesTarget":
                $this->doSalesTarget($objRequest, $objResponse);
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
    protected function doSalesTarget(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $sales_date = $objRequest->sales_date;
        if(empty($sales_date)) $sales_date = getYear();

        $objSuccessService = new \SuccessService();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('sales_date', $sales_date);

        $arraySalesTargetData = LogStatementsServiceImpl::instance()->getSalesTarget($whereCriteria);
        if(!empty($arraySalesTargetData)) {
            $arraySalesTargetData[0]['st_id'] = encode($arraySalesTargetData[0]['sales_target_id']);
            $arraySalesTargetData = $arraySalesTargetData[0];
        }
        $objSuccessService->setData(['salesTargetList'=>$arraySalesTargetData]);

        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodSaveSalesTarget(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $st_id = decode($objRequest->st_id);
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $arrayInput = $objRequest->getInput();
        $arraySalesTargetData = [];
        if(empty($st_id)) {
            $arrayInput['company_id'] = $company_id;
            $arrayInput['channel_id'] = $channel_id;
            $sales_target_id = LogStatementsServiceImpl::instance()->saveSalesTarget($arrayInput);
            $arraySalesTargetData['st_id'] = encode($sales_target_id);
            $arraySalesTargetData['sales_target_id'] = $sales_target_id;
        } elseif($st_id > 0) {
            unset($arrayInput['st_id']);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('sales_target_id', $st_id);
            LogStatementsServiceImpl::instance()->updateSalesTarget($whereCriteria, $arrayInput);
            $arraySalesTargetData['st_id'] = encode($st_id);
            $arraySalesTargetData['sales_target_id'] = $st_id;
        }

        $objSuccessService = new \SuccessService();
        $objSuccessService->setData(['salesTargetList'=>$arraySalesTargetData]);
        return $objResponse->successServiceResponse($objSuccessService);
    }

    protected function doMethodGetYearSalesTarget(\HttpRequest $objRequest, \HttpResponse $objResponse) {

    }





}