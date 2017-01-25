<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */
namespace hotel;
class LoginService extends \BaseService {
    private static $loginKey = 'employee';
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new LoginService();
        return self::$objService;
    }

    public function loginEmployee($arrayLoginInfo){
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = $arrayLoginInfo;
        return EmployeeDao::instance()->getEmployee($conditions);
    }
    
    public function getLoginEmployee($objCookie = NULL, $isSession = false) {
        if(!is_object($objCookie) && $isSession == false){
            $objCookie = new \Cookie;
        }
        $loginKey = self::$loginKey . date("z");
        if($isSession == false) {
            $loginuser = $objCookie -> $loginKey;
            if(empty($loginuser)) {//只针对cookie用户 session保存1个月占服务器太长时间
                $loginKey = self::$loginKey . 2592000;//一个月
                $loginuser = $objCookie -> $loginKey;
            }
        } else {
            $objSession = new \Session();
            $loginuser = $objSession -> $loginKey;
        }

        if(!empty($loginuser)) {
            $arrEmployee = explode("\t", $loginuser);
            $arrEmployeeInfo['employee_id'] = $arrEmployee[0];
            $arrEmployeeInfo['company_id'] = $arrEmployee[1];
            $arrEmployeeInfo['hotel_id'] = $arrEmployee[2];
            $arrEmployeeInfo['employee_name'] = $arrEmployee[3];
            $arrEmployeeInfo['hotel_name'] = $arrEmployee[4];
            return $arrEmployeeInfo;
        }
        return NULL;
    }

    public function checkLoginEmployee($objCookie = NULL, $isSession = false) {
        $arrEmployeeInfo = null;
        if(!is_object($objCookie) && $isSession == false){
            $objCookie = new \Cookie();
        }
        if($isSession == false) {
            $arrEmployeeInfo = $this->getLoginEmployee($objCookie);
        } else {
            $arrEmployeeInfo = $this->getLoginEmployee(NULL, true);
        }
        //if(empty($arrEmployeeInfo)) redirect(__WEB . 'index.php?action=login');
        return $arrEmployeeInfo;
    }

    public function setLoginEmployeeCookie($arrayLoginEmployeeInfo, $remember_me = false) {
        $cookieUser = $arrayLoginEmployeeInfo['employee_id'] . "\t" . $arrayLoginEmployeeInfo['company_id'] . "\t" . $arrayLoginEmployeeInfo['hotel_id']
            . "\t" . $arrayLoginEmployeeInfo['employee_name']. "\t" . $arrayLoginEmployeeInfo['hotel_name'];
        $objCookie = new \Cookie();
        $time = NULL;
        $key = date("z");
        if($remember_me) {
            $time = 2592000;//一个月
            $key = $time;
        }
        $objCookie->setCookie(self::$loginKey . $key, $cookieUser, $time);
    }

    public function logout() {
        $objCookie = new \Cookie();
        $loginKey = self::$loginKey . 2592000;//一个月
        unset($objCookie->$loginKey);
        $loginKey = self::$loginKey . date("z");
        unset($objCookie->$loginKey);
    }

    /**
     * @return string
     */
    public function getLoginKey() {
        return self::$loginKey;
    }

    /**
     * @param string $loginKey
     */
    public function setLoginKey($loginKey) {
        self::$loginKey = $loginKey;
    }

}