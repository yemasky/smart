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
        $company_id    = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id    = $objRequest->channel_id;
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->setHashKey('sector_id');
        $arrayChannelSector = EmployeeServiceImpl::instance()->getChannelSector($whereCriteria);
        if (!empty($arrayChannelSector)) {
            foreach ($arrayChannelSector as $key => $arrayData) {
                $arrayChannelSector[$key]['s_id'] = encode($arrayData['sector_id']);
            }
        }
        $successService = new \SuccessService();
        $successService->setData(['channelSectorList' => $arrayChannelSector]);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doEmployee(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $method     = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->setHashKey('sector_id');
        $arrayChannelSector = EmployeeServiceImpl::instance()->getChannelSector($whereCriteria);
        if (!empty($arrayChannelSector)) {
            foreach ($arrayChannelSector as $key => $arrayData) {
                $arrayChannelSector[$key]['s_id'] = encode($arrayData['sector_id']);
            }
        }
        $imagesUploadUrl  = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'images');
        $imagesManagerUrl = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'manager');
        $successService   = new \SuccessService();
        $successService->setData(['channelSectorList' => $arrayChannelSector, 'imagesUploadUrl' => $imagesUploadUrl, 'imagesManagerUrl' => $imagesManagerUrl]);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodEmployeePagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['employeeList'] = EmployeeServiceImpl::instance()->getEmployeeReceivablePage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doRole(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $method     = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        $arrayRole = RoleServiceImpl::instance()->getRole($whereCriteria);
        if (!empty($arrayRole)) {
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
        if (!empty($arrayModuleChannel)) {
            $arrayModuleId = array_keys($arrayModuleChannel);
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->ArrayIN('module_id', $arrayModuleId);
            $arrayChannelModule = ModuleServiceImpl::instance()->getChannelModule($arrayModuleId, $company_id, $channel_id);

        }


        $successService = new \SuccessService();
        $successService->setData(['roleList' => $arrayRole, 'channelModuleList' => $arrayChannelModule]);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodGetRole(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id     = $objRequest->channel_id;
        $r_id           = decode($objRequest->r_id);
        $successService = new \SuccessService();
        if (!empty($r_id)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('role_id', $r_id)->setHashKey('module_id');
            $arrayRoleModule = RoleServiceImpl::instance()->getRoleModule($whereCriteria, 'role_id,module_id');
            $successService->setData(['roleModuleList' => $arrayRoleModule]);
            return $objResponse->successServiceResponse($successService);
        }
        $successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodSaveRole(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id     = $objRequest->channel_id;
        $r_id           = decode($objRequest->r_id);
        $role_name      = $objRequest->role_name;
        $successService = new \SuccessService();
        if (!empty($role_name)) {
            if (empty($r_id)) {//新增
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('role_name', $role_name);
                $arrayRoleName = RoleServiceImpl::instance()->getRole($whereCriteria, 'role_name');
                if (!empty($arrayRoleName)) {
                    $successService->setSuccess(false);
                    $successService->setMessage($role_name);
                    $successService->setCode(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code']);
                    return $objResponse->successServiceResponse($successService);
                }
            }
            $module     = $objRequest->module;
            $select_tag = $objRequest->select_tag['value'];
            $valid      = $objRequest->valid;
            if (!empty($module)) {
                $arrayRoleModule = [];
                foreach ($module as $key => $isTrue) {
                    if ($isTrue) {
                        $module_id = \Encrypt::instance()->decode($key, getDay());
                        if (!empty($module_id)) {
                            $arrayRoleModule[$module_id] = $module_id;
                        }
                    }
                }
                if (!empty($arrayRoleModule)) {
                    CommonServiceImpl::instance()->startTransaction();
                    $arrayInsertRole['role_name']  = $role_name;
                    $arrayInsertRole['tag']        = $select_tag;
                    $arrayInsertRole['valid']      = $valid;
                    $arrayInsertRole['company_id'] = $company_id;
                    $arrayInsertRole['channel_id'] = $channel_id;
                    if (empty($r_id)) {//新增
                        $role_id = RoleServiceImpl::instance()->saveRole($arrayInsertRole);
                    } else {
                        $role_id = $r_id;//修改
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('role_id', $role_id);
                        RoleServiceImpl::instance()->updateRole($whereCriteria, $arrayInsertRole);
                    }
                    //
                    $arrayInsertRoleModule = [];
                    $i                     = 0;
                    foreach ($arrayRoleModule as $moduie_id => $value) {
                        $arrayInsertRoleModule[$i]['module_id'] = $moduie_id;
                        $arrayInsertRoleModule[$i]['role_id']   = $role_id;
                        $i++;
                    }
                    if (!empty($r_id)) {//删除就数据
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('role_id', $role_id);
                        RoleServiceImpl::instance()->deleteRoleModule($whereCriteria);
                    }
                    RoleServiceImpl::instance()->batchInsertRoleModule($arrayInsertRoleModule);
                    CommonServiceImpl::instance()->commit();
                    return $objResponse->successServiceResponse($successService);
                }
            }
        }

        $successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
        return $objResponse->successServiceResponse($successService);
    }

}