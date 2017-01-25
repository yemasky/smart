<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class EmployeeAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        $objResponse -> navigation = 'roomsManagement';
        $objResponse -> setTplValue('navigation', 'roomsManagement');
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
            case 'personnelFile':
                $this->doPersonnelFile($objRequest, $objResponse);
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
        $employee_id = $objRequest -> employee_id;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $conditions = DbConfig::$db_query_conditions;
        if($employee_id > 0) {
            $this->setDisplay();
            $conditions['where'] = array('hotel_id'=>$hotel_id, 'employee_id'=>$employee_id);
            $arrayEmployee = EmployeeService::instance()->getEmployee($conditions);
            if(!empty($arrayEmployee)) {
                unset($arrayEmployee[0]['employee_password']);
                unset($arrayEmployee[0]['employee_password_salt']);
            }
            return $this->successResponse('', $arrayEmployee);
        }

        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayDepartment = HotelService::instance()->getHotelDepartment($conditions, '*', 'department_id');

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        //$arrayPosition = HotelService::instance()->getHotelDepartmentPosition($conditions);
        $arrayPosition = HotelService::instance()->getHotelDepartmentPosition($conditions, '*', 'department_position_id');

        $arrayPageEmployee = $this->getPageEmployee($objRequest, $objResponse);
        //role
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayRole = RoleService::instance()->getRole($conditions);
        //人事信息权限
        $arrayMyRole = $objResponse->arrayRoleModulesEmployeePermissions;
        $personnelRole = isset($arrayMyRole[ModulesConfig::$modulesConfig['employee']['personnelFile']]) ? 1 : 0;
        //
        $objResponse -> yearEnd = date("Y") - 14;
        $objResponse -> yearBegin = date("Y") - 14 . '-' . date("m") . '-' .date("d");
        $objResponse -> arrayDepartment = $arrayDepartment;
        $objResponse -> arrayPosition = $arrayPosition;
        $objResponse -> arrayPageEmployee = $arrayPageEmployee;
        $objResponse -> arrayRole = $arrayRole;
        $objResponse -> personnelRole = $personnelRole;
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['employee']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['employee']['edit'])));
        $objResponse -> delete_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['employee']['delete'])));
        $objResponse -> view_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['employee']['view'])));
        $objResponse -> personnelFile_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['employee']['personnelFile'])));
        $objResponse -> upload_images_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['hotel']['upload_type'],'hotel_id'=>encode($hotel_id),'type'=>'employee'));
        $objResponse -> upload_manager_img_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['hotel']['upload_type'],'act'=>'manager_img','hotel_id'=>encode($hotel_id),'type'=>'employee'));
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        //更改tpl
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPost = $objRequest->getPost();
        $employee_id = $objRequest -> employee_id;
        if(!empty($arrayPost)) {
            unset($arrayPost['employee_id']);
            $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
            $company_id = $objResponse->arrayLoginEmployeeInfo['company_id'];
            $saveData = $arrayPost;
            unset($saveData['upload_images_url']);
            $saveData['employee_photo'] = str_replace('/data/images/', '', $arrayPost['upload_images_url']);
            if(empty($employee_id)) {
                $saveData['company_id'] = $company_id;
                $saveData['hotel_id'] = $hotel_id;
                $saveData['employee_password_salt'] = rand(10000, 900000000);
                //
                $saveData['employee_password'] = md5(md5('985632147') . md5($saveData['employee_password_salt']));
                $saveData['employee_add_date'] = getDay();
                $saveData['employee_add_time'] = getTime();
                $employee_id = EmployeeService::instance()->saveEmployee($saveData);
                //
                EmployeeService::instance()->saveEmployeeDepartment(array('company_id'=>$company_id,'hotel_id'=>$hotel_id,
                    'employee_id'=>$employee_id,'department_id'=>$saveData['department_id'],'department_position_id'=>$saveData['department_position_id']));
            } else {
                $where = array('employee_id'=>$employee_id, 'hotel_id'=>$hotel_id);
                EmployeeService::instance()->updateEmployee($where, $saveData);
                RoleService::instance()->deleteRoleEmployee($where);
            }
            //保存权限
            if($saveData['role_id'] > 0) {
                RoleService::instance()->saveRoleEmployee(array('hotel_id'=>$hotel_id,'role_id'=>$saveData['role_id'],'employee_id'=>$employee_id));
            }

            return $this->successResponse('成功保存数据', $employee_id);
        }
        return $this->errorResponse('没有保存任数据');
        //$objResponse -> add_room_attribute_url =
        //    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsAttribute']['edit'])));
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();

    }

    protected function getPageEmployee($objRequest, $objResponse) {
        $pn = $objRequest -> pn;
        $pn = $pn > 0 ? $pn : 1;
        $pn_rows = $objRequest -> pn_rows;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        $parameters['module'] = encode(decode($objRequest->module));
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayEmplayee = EmployeeService::instance()->pageEmployee($conditions, $pn, $pn_rows, $parameters);
        $objResponse -> pn = $pn;
        $objResponse -> page = $arrayEmplayee['page'];
        return $arrayEmplayee;
    }

    protected function doPersonnelFile($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPost = $objRequest->getPost();
        $employee_id = $objRequest -> employee_id;
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        if(!empty($arrayPost)) {
            $arrayPost['employee_id'] = $employee_id;
            $arrayPost['hotel_id'] = $hotel_id;
            if(empty($arrayPost['employee_entry_date'])) unset($arrayPost['employee_entry_date']);
            if(empty($arrayPost['employee_probation_date'])) unset($arrayPost['employee_probation_date']);
            EmployeeService::instance()->insertEmployeePersonnelFile($arrayPost);
            return $this->successResponse('保存成功！');
        }
        if($employee_id > 0) {
            $conditions = DbConfig::$db_query_conditions;
            $conditions['where'] = array('employee_id'=>$employee_id, 'hotel_id'=>$hotel_id);
            $arrayResult = EmployeeService::instance()->getEmployeePersonnelFile($conditions);
            if(!empty($arrayResult)) {
                return $this->successResponse('', $arrayResult[0]);
            }
            return $this->errorResponse('没有取得任何数据');
        }
        return $this->errorResponse('没有保存任何数据！');
    }

}