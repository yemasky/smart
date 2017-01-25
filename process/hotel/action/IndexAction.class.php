<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class IndexAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'login':
                $this->employee_login($objRequest, $objResponse);
                break;
            case 'logout':
                $objRequest -> method = 'logout';
                $this->employee_login($objRequest, $objResponse);
                break;
            case 'noPermission':
                $this->doNoPermission($objRequest, $objResponse);
                break;
            case 'excute_success':
                $this->doExcuteSuccess($objRequest, $objResponse);
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
        //赋值
        //设置类别
        
        //设置Meta(共通)
        $objResponse -> setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
    }

    protected function doExcuteSuccess($objRequest, $objResponse) {
        //赋值
        //设置类别

        //设置Meta(共通)
        $objResponse -> setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
        $objResponse->setTplName("hotel/modules_excuteSuccess");
    }
    /**
     * 首页显示
     */
    protected function doNoPermission($objRequest, $objResponse) {
        //赋值
        //设置类别
    }

    protected function employee_login($objRequest, $objResponse) {
        $arrayLoginInfo['username'] = trim($objRequest->username);
        $arrayLoginInfo['employee_password'] = trim($objRequest->password);
        $remember_me = $objRequest->remember_me;
        $method = $objRequest->method;
        if($method == 'logout') {
            //$this->setDisplay();
            LoginService::instance()->logout();
            //$this->setRedirect(__WEB);
            //return;
        }
        $error_login = 0;
        if(!empty($arrayLoginInfo['username']) && !empty($arrayLoginInfo['employee_password'])) {
            $arrayEmployeeInfo = null;
            if(strpos($arrayLoginInfo['username'], '@') !== false) {
                $arrayEmployeeInfo = LoginService::instance()->loginEmployee(array('employee_email'=>$arrayLoginInfo['username']));
            } elseif (strlen($arrayLoginInfo['username']) == 11 && is_numeric($arrayLoginInfo['username'])) {
                $arrayEmployeeInfo = LoginService::instance()->loginEmployee(array('employee_mobile'=>$arrayLoginInfo['username']));
            } else {
                $arrayEmployeeInfo = LoginService::instance()->loginEmployee(array('employee_name'=>$arrayLoginInfo['username']));
            }
            if(!empty($arrayEmployeeInfo)) {
                $lenght = count($arrayEmployeeInfo);
                for($i = 0; $i < $lenght; $i++) {
                    if (md5(md5($arrayLoginInfo['employee_password']) . md5($arrayEmployeeInfo[$i]['employee_password_salt'])) == $arrayEmployeeInfo[$i]['employee_password']) {
                        $conditions = DbConfig::$db_query_conditions;
                        $conditions['where'] = array('hotel_id'=>$arrayEmployeeInfo[$i]['hotel_id']);
                        $arrayLoginHotel = HotelService::instance()->getHotel($conditions);
                        $arrayEmployeeInfo[$i]['hotel_name'] = $arrayLoginHotel[0]['hotel_name'];
                        LoginService::instance()->setLoginEmployeeCookie($arrayEmployeeInfo[$i], $remember_me);
                        $this->setDisplay();
                        $this->setRedirect(__WEB);
                        return;
                    }
                }
                $error_login = 1;
            } else {
                $error_login = 1;
            }
        }
        $objResponse -> setTplValue('error_login', $error_login);
        //
        $objResponse -> setTplName("hotel/employee_login");
    }
}