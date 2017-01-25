<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class DepartmentAction extends \BaseAction {
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
    public function doDefault($objRequest, $objResponse) {
        $act = $objRequest -> act;
        if(!empty($act)) {
            if($act == 'getPosition') {
                return $this->getPosition($objRequest, $objResponse);
            }
            if($act == 'getRole') {
                return $this->getRole($objRequest, $objResponse);
            }
        }

        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        //$conditions['order'] = 'department_same_order ASC';
        $arrayDepartment = HotelService::instance()->getHotelDepartment($conditions);
        //
        $objResponse -> arrayDepartment = $arrayDepartment;
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['department']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['department']['edit'])));
        $objResponse -> delete_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['department']['delete'])));
        $objResponse -> view_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['department']['view'])));
        $objResponse -> role_add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['role']['add'])));
        $objResponse -> role_edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['role']['edit'])));
        $objResponse -> role_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['role']['view'])));

        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> department_self_id = '';
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

        if(!empty($arrayPostValue) && is_array($arrayPostValue) && !empty($department_self_name)) {
            $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
            if($department_position == 1) {
                $department_self_id = trim($department_self_id, 'P');
                if($department_self_id > 0) {
                    $where = array('hotel_id'=>$hotel_id, 'department_position_id'=>$department_self_id);
                    HotelService::instance()->updateHotelDepartmentPosition($where, array('department_position_name'=>$department_self_name));
                    return $this->successResponse('编辑成功');
                }
                if($department_parent_id >= 0) {
                    $id = HotelService::instance()->saveHotelDepartmentPosition(array('department_position_name'=>$department_self_name,'department_id'=>$department_parent_id,
                        'hotel_id'=>$hotel_id));
                    return $this->successResponse('保存成功', array('id'=>$id));
                }
            } elseif($department_position == 2) {
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
            } else {
                if($department_self_id > 0) {
                    $where = array('hotel_id'=>$hotel_id, 'department_id'=>$department_self_id);
                    HotelService::instance()->updateHotelDepartment($where, array('department_name'=>$department_self_name));
                    return $this->successResponse('编辑成功');
                }
                if($department_parent_id >= 0) {
                    $id = HotelService::instance()->saveHotelDepartment(array('department_name'=>$department_self_name,'department_father_id'=>$department_parent_id,
                        'hotel_id'=>$hotel_id));
                    return $this->successResponse('保存成功', array('id'=>$id));
                }
            }
        }
        return $this->errorResponse('没有保存任何数据');
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();

    }

    protected function getPosition($objRequest, $objResponse) {
        $this->setDisplay();
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayPosition = HotelService::instance()->getHotelDepartmentPosition($conditions);
        return $this->successResponse('', $arrayPosition);
    }

    protected function getRole($objRequest, $objResponse) {
        $this->setDisplay();
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayRole = RoleService::instance()->getRole($conditions);
        return $this->successResponse('', $arrayRole);
    }

}