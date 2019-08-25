<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class EmployeeAction extends \BaseAction {
    protected $objSuccess;
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $this->objSuccess = new \SuccessService();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "SectorPosition":
                $this->doSectorPosition($objRequest, $objResponse);
                break;
            case "Employee":
                $this->doEmployee($objRequest, $objResponse);
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

    protected function doSectorPosition(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id              = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id              = $objRequest->channel_id;
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->setHashKey('sector_id');
        $arrayChannelSector = EmployeeServiceImpl::instance()->getChannelSector($whereCriteria);
        if(!empty($arrayChannelSector)) {
            foreach ($arrayChannelSector as $key => $arrayData) {
                $arrayChannelSector[$key]['s_id'] = encode($arrayData['sector_id']);
            }
        }
        $this->objSuccess->setData(['channelSectorList'=>$arrayChannelSector]);
        return $objResponse->successServiceResponse($this->objSuccess);
    }

    protected function doEmployee(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        return $objResponse->successServiceResponse($this->objSuccess);
    }

    protected function doMethodEmployeePagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['employeeList'] = EmployeeServiceImpl::instance()->getEmployeeReceivablePage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }


}