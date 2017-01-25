<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class RoleAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'edit':
                $this->doEdit($objRequest, $objResponse);
                break;
            case 'add':
                $this->doAdd($objRequest, $objResponse);
                break;
            case 'delete':
                $this->doDelete($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        $role_id = $objRequest -> role_id;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $role_id = trim($role_id, 'R');
        if($role_id > 0) {
            $have_modules = $objRequest->have_modules;
            $this->setDisplay();
            $conditions = DbConfig::$db_query_conditions;
            $conditions['where'] = array('hotel_id'=>$hotel_id,'role_id' => $role_id);
            //$arrayRole = RoleService::instance()->getRole($conditions);
            $arrayRoleModules = RoleService::instance()->getRoleModules($conditions,'modules_id, role_modules_action_permissions permissions', 'modules_id');
            $arrayModules = '';
            if($have_modules == '0') {
                $arrayModules = ModulesService::instance()->getModulesSort();
            }
            $arrayResult = array('RoleModules'=>$arrayRoleModules,'Modules'=>$arrayModules);
            return $this->successResponse('', $arrayResult);
        }
        $objDepartment = new DepartmentAction();
        $objResponse->setTplName("hotel/modules_department");
        $objDepartment-> doDefault($objRequest, $objResponse);
    }

    protected function doAdd($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        //更改tpl
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $department_parent_id = $objRequest -> department_parent_id;
        $department_self_id = $objRequest -> department_self_id;
        $department_self_name = trim($objRequest -> department_self_name);
        $department_position = $objRequest -> department_position;
        $arrayPostValue= $objRequest->getPost();
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $act = $objRequest -> act;
        if($act == 'editRoleModule') {
            $type = $objRequest->type;
            $arrayData['role_id'] = trim($objRequest -> role_id, 'R');
            $arrayData['hotel_id'] = $hotel_id;
            $arrayData['modules_id'] = $objRequest->id;
            if($type == 'false') {
                $father_id = $objRequest->father_id;
                $father_type = $objRequest->father_type;
                $arrayData['role_modules_action_permissions'] = '4';
                RoleService::instance()->saveRoleModules($arrayData);
                if($father_type == 'false' && $father_id > 0) {
                    $arrayData['modules_id'] = $father_id;
                    RoleService::instance()->saveRoleModules($arrayData);
                }
            } else {
                RoleService::instance()->deleteRoleModules($arrayData);
            }
            return $this->successResponse('保存数据成功！');
        }

        if(!empty($arrayPostValue) && is_array($arrayPostValue) && !empty($department_self_name)) {
            if($department_position == 2) {
                $role_id = trim($department_self_id, 'R');
                $department_parent_id = trim($department_parent_id, 'P');
                if($role_id > 0) {
                    $where = array('hotel_id'=>$hotel_id, 'role_id'=>$role_id);
                    RoleService::instance()->updateRole($where, array('role_name'=>$department_self_name));
                    return $this->successResponse('编辑成功');
                }
                $role_department_id = $objRequest -> role_department_id;
                if($department_parent_id >= 0) {
                    $id = RoleService::instance()->saveRole(array('role_name'=>$department_self_name,'department_position_id'=>$department_parent_id,
                        'hotel_id'=>$hotel_id, 'department_id'=>$role_department_id, 'company_id'=>$objResponse->arrayLoginEmployeeInfo['company_id']));
                    return $this->successResponse('保存成功', array('id'=>$id));
                }
            }
        }
        return $this->errorResponse('没有保存任何数据');
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
    }

}