<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class EmployeeAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case "SectorPosition":
                $this->doSectorPosition($objRequest, $objResponse);
                break;
            case "Employee":
                $this->doEmployee($objRequest, $objResponse);
                break;
            case "Role":
                $this->doRole($objRequest, $objResponse);
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
        $successService = new \SuccessService();
        return $objResponse->successServiceResponse($successService);
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
        $successService = new \SuccessService();
        $successService->setData(['channelSectorList'=>$arrayChannelSector]);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doEmployee(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id              = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id              = $objRequest->channel_id;
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->setHashKey('sector_id');
        $arrayChannelSector = EmployeeServiceImpl::instance()->getChannelSector($whereCriteria);
        if(!empty($arrayChannelSector)) {
            foreach ($arrayChannelSector as $key => $arrayData) {
                $arrayChannelSector[$key]['s_id'] = encode($arrayData['sector_id']);
            }
        }
        $imagesUploadUrl    = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'images');
        $imagesManagerUrl   = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'manager');
        $successService = new \SuccessService();
        $successService->setData(['channelSectorList'=>$arrayChannelSector,'imagesUploadUrl'=>$imagesUploadUrl,'imagesManagerUrl'=>$imagesManagerUrl]);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodEmployeePagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['employeeList'] = EmployeeServiceImpl::instance()->getEmployeeReceivablePage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doRole(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id              = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id              = $objRequest->channel_id;
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        $arrayRole = RoleServiceImpl::instance()->getRole($whereCriteria);
        if(!empty($arrayRole)) {
            foreach ($arrayRole as $key => $arrayData) {
                $arrayRole[$key]['r_id'] = encode($arrayData['role_id']);
            }
        }
        //取出本企业的权限module_channel
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('channel_id', $channel_id)->setHashKey('module_id');
        $arrayModuleChannel = ModuleServiceImpl::instance()->getModuleChannel($whereCriteria);
        //
        $arrayChannelModule = [];
        if(!empty($arrayModuleChannel)) {
            $arrayModuleId = array_keys($arrayModuleChannel);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->ArrayIN('module_id', $arrayModuleId);
            $arrayChannelModule = ModuleServiceImpl::instance()->getChannelModule($arrayModuleId, $company_id, $channel_id);
        }


        $successService = new \SuccessService();
        $successService->setData(['roleList'=>$arrayRole,'moduleChannelList'=>$arrayModuleChannel, 'channelModuleList'=>$arrayChannelModule]);
        return $objResponse->successServiceResponse($successService);
    }

}