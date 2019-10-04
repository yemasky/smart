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
        $method = $objRequest->method;
        if (!empty($method)) {
            return $this->doMethod($objRequest, $objResponse);
        }
        $company_id    = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id    = $objRequest->channel_id;
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('is_delete', 0)->setHashKey('sector_id');
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

    protected function doMethodSaveSectorPosition(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $successService   = new \SuccessService();
        $company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id       = $objRequest->channel_id;
        $sector_father_id = $objRequest->sector_father_id;
        $sector_name      = $objRequest->sector_name;
        $sector_type      = $objRequest->sector_type;
        $s_id             = decode($objRequest->s_id);
        if ($sector_type == 'edit' && $s_id > 0) {//编辑
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('sector_id', $s_id);
            EmployeeServiceImpl::instance()->updateChannelSector($whereCriteria, ['sector_name' => $sector_name]);
            return $objResponse->successServiceResponse($successService);
        } elseif ($sector_type == 'sector' || $sector_type == 'position') {//部门
            $sectorData['company_id']  = $company_id;
            $sectorData['channel_id']  = $channel_id;
            $sectorData['sector_name'] = $sector_name;
            $sectorData['sector_type'] = $sector_type;
            if ($sector_father_id > 0) $sectorData['sector_father_id'] = $sector_father_id;
            $sector_id = EmployeeServiceImpl::instance()->saveChannelSector($sectorData);
            if (empty($sector_father_id) && $sector_type == 'sector') {
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('sector_id', $sector_id);
                EmployeeServiceImpl::instance()->updateChannelSector($whereCriteria, ['sector_father_id' => $sector_id]);
            }
            $successService->setData(['sector_id' => $sector_id, 's_id' => encode($sector_id)]);
            return $objResponse->successServiceResponse($successService);
        }

        $successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodDeleteSectorPosition(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $successService = new \SuccessService();
        $company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id     = $objRequest->channel_id;
        $s_id           = decode($objRequest->s_id);
        if ($s_id > 0) {//编辑
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('sector_id', $s_id);
            $is_delete = getDateTimeId();
            EmployeeServiceImpl::instance()->updateChannelSector($whereCriteria, ['is_delete' => $is_delete, 'valid' => '0']);
            return $objResponse->successServiceResponse($successService);
        }

        $successService->setCode(ErrorCodeConfig::$errorCode['no_data_update']);
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
        //权限
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1')->setHashKey('role_id');
        $arrayChannelRole = RoleServiceImpl::instance()->getRole($whereCriteria, 'role_id,role_name,tag');
        $arrayResule      = ['channelSectorList' => $arrayChannelSector, 'imagesUploadUrl' => $imagesUploadUrl, 'imagesManagerUrl' => $imagesManagerUrl,
            'channelRoleList' => $arrayChannelRole];
        $successService   = new \SuccessService();
        $successService->setData($arrayResule);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodEmployeePagination(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['employeeList'] = EmployeeServiceImpl::instance()->getEmployeeReceivablePage($objRequest, $objResponse);
        $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

    protected function doMethodSaveEmployee(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $loginEmployeeModel = LoginServiceImpl::instance()->getLoginEmployee();
        $company_id         = $loginEmployeeModel->getEmployeeInfo()->getCompanyId();
        $channel_id         = $objRequest->channel_id;
        $e_id               = decode($objRequest->e_id);
        $sector_id          = $objRequest->position_id;
        $_s_id              = decode($objRequest->_s_id);

        $employeeData['birthday']      = $objRequest->birthday;
        $employeeData['email']         = $objRequest->email;
        $employeeData['employee_name'] = $objRequest->employee_name;
        $employeeData['id_card']       = $objRequest->id_card;
        $employeeData['mobile']        = $objRequest->mobile;
        $employeeData['photo']         = $objRequest->photo;
        $employeeData['sex']           = $objRequest->sex;

        $employeeSectorData['employee_name']    = $objRequest->employee_name;
        $employeeSectorData['mobile']           = $objRequest->mobile;
        $employeeSectorData['email']            = $objRequest->email;
        $employeeSectorData['birthday']         = $objRequest->birthday;
        $employeeSectorData['photo']            = $objRequest->photo;
        $employeeSectorData['id_card']          = $objRequest->id_card;
        $employeeSectorData['role_id']          = $objRequest->role_id;
        $employeeSectorData['sector_father_id'] = $objRequest->sector_id;
        $employeeSectorData['sector_id']        = $objRequest->position_id;
        $employeeSectorData['sex']              = $objRequest->sex;
        $employeeSectorData['valid']            = $objRequest->valid;

        $successService = new \SuccessService();
        CommonServiceImpl::instance()->startTransaction();
        //更新密码
        $password = $objRequest->password;
        if (!empty($password)) {
            $password_salt = md5(getDateTimeId());
            $password      = md5($company_id . '`　-   `' . md5($password) . md5($password_salt));
            //md5($company_id . '`　-   `' . md5($password) . md5($password_salt));
            $employeeData['password_salt'] = $password_salt;
            $employeeData['password']      = $password;
        }
        if (!empty($e_id) && $e_id > 0 && $_s_id > 0) {//edit
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('employee_id', $e_id);
            EmployeeServiceImpl::instance()->updateEmployee($whereCriteria, $employeeData);

            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('employee_id', $e_id)
                ->EQ('sector_id', $_s_id);
            EmployeeServiceImpl::instance()->updateEmployeeSector($whereCriteria, $employeeSectorData);
        } else {
            $employeeData['company_id']         = $company_id;
            $employeeData['default_channel_id'] = $channel_id;
            $employeeData['add_datetime']       = getDateTime();
            $employee_id                        = EmployeeServiceImpl::instance()->saveEmployee($employeeData);
            //
            $arrayEmployeeChannel                    = $loginEmployeeModel->getEmployeeChannel();
            $channel_father_id                       = $arrayEmployeeChannel[$channel_id]['channel_father_id'];
            $employeeSectorData['company_id']        = $company_id;
            $employeeSectorData['channel_id']        = $channel_id;
            $employeeSectorData['channel_father_id'] = $channel_father_id;
            $employeeSectorData['employee_id']       = $employee_id;
            $employeeSectorData['add_datetime']      = getDateTime();
            EmployeeServiceImpl::instance()->saveEmployeeSector($employeeSectorData);

            $e_id = encode($employee_id);
            $successService->setData(['e_id' => $e_id, 'employee_id' => $employee_id]);
        }
        CommonServiceImpl::instance()->commit();
        return $objResponse->successServiceResponse($successService);
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
                        $role_id       = $r_id;//修改
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