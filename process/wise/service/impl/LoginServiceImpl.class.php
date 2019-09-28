<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class LoginServiceImpl extends \BaseServiceImpl implements LoginService {
    private static $loginKey = 'employee';
    private static $objService = null;
    private static $objEmployee;
    private static $business_day = '';

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new LoginServiceImpl();

        return self::$objService;
    }

    public function setLoginInfo(Employee $employee) {
        self::$objEmployee = $employee;
    }

    public function getLoginInfo(): Employee {
        return self::$objEmployee;
    }

    /**
     * @return string
     */
    public static function getBusinessDay(): string {
        return self::$business_day;
    }

    /**
     * @param string $business_day
     */
    public static function setBusinessDay(string $business_day) {
        self::$business_day = $business_day;
    }

    public function doLoginEmployee(\HttpRequest $objRequest, \HttpResponse $objResponse): LoginEmployeeModel {
        $password = $objRequest->password;
        $username = $objRequest->email;

        $whereCriteria           = new \WhereCriteria();
        $field                   = 'employee_id,company_id,employee_name,photo,`password`,password_salt,default_channel_id';
        $arrayEmployeeList       = array();
        if (strpos($username, '@') !== false) {
            $whereCriteria->EQ('email', $username);
        } elseif(is_numeric($username) && strlen($username) == 11) {
            $whereCriteria->EQ('mobile', $username);
        }
        $whereCriteria->EQ('valid', '1');
        $arrayEmployeeList = EmployeeDao::instance()->getEmployee($whereCriteria, $field);
        $loginEmployeeModel = new LoginEmployeeModel();
        $lenght             = count($arrayEmployeeList);
        for ($i = 0; $i < $lenght; $i++) {
            //md5(1 . '`　-   `' . md5('14e1b600b1fd579f47433b88e8d85291') . md5('5483116858d36bd6d1f6c'))
            if (md5($arrayEmployeeList[$i]['company_id'] . '`　-   `' . md5($password) . md5($arrayEmployeeList[$i]['password_salt'])) == $arrayEmployeeList[$i]['password']) {//找到登录者
                //查找权限
                $company_id          = $arrayEmployeeList[$i]['company_id'];
                $employee_id         = $arrayEmployeeList[$i]['employee_id'];
                $default_channel_id  = $arrayEmployeeList[$i]['default_channel_id'];//默认切换的channel
                $arrayEmployeeModule = $this->getEmployeeModule($company_id, $employee_id, $default_channel_id);
                //set cookie
                unset($arrayEmployeeList[$i]['password_salt']);
                unset($arrayEmployeeList[$i]['password']);

                $employeeChannel = EmployeeServiceImpl::instance()->getEmployeeChannel($company_id, $employee_id);
                $Employee        = new Employee();
                $Employee->setEmployeeId($arrayEmployeeList[$i]['employee_id']);
                $Employee->setCompanyId($arrayEmployeeList[$i]['company_id']);
                $Employee->setDefaultChannelId($default_channel_id);
                $Employee->setEmployeeName($arrayEmployeeList[$i]['employee_name']);
                $Employee->setPhoto($arrayEmployeeList[$i]['photo']);
                self::$objEmployee = $Employee;
                $loginEmployeeModel->setEmployeeInfo($Employee);
                $loginEmployeeModel->setEmployeeMenu($arrayEmployeeModule);
                $loginEmployeeModel->setEmployeeChannel($employeeChannel);
                //channelSettingList
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->setHashKey('channel_id');
                $channelSettingList = ChannelDao::instance()->getChannelSettingList($whereCriteria->EQ('company_id', $Employee->getCompanyId()));
                $loginEmployeeModel->setChannelSettingList($channelSettingList);
                $this->setLoginEmployeeCookie($loginEmployeeModel);
                break;
            }
        }

        return $loginEmployeeModel;
    }

    public function getEmployeeModule($company_id, $employee_id, $channel_id) {
        $arrayEmployeeRoleModule = RoleServiceImpl::instance()->getEmployeeRoleModuleCache($company_id, $employee_id, $channel_id);
        $arrayEmployeeModule     = '';
        if (!empty($arrayEmployeeRoleModule)) {
            $arrayEmployeeModule = ModuleServiceImpl::instance()->getModuleInModuleId($arrayEmployeeRoleModule, $company_id, $employee_id);
        }

        return $arrayEmployeeModule;
    }

    public function getLoginEmployee($objCookie = null, $isSession = false): LoginEmployeeModel {
        if (!is_object($objCookie) && $isSession == false) {
            $objCookie = new \Cookie;
        }
        $loginKey = self::$loginKey . date("z");
        //$loginKeyHotelList = self::$loginKey . '_hotelList';$objCookie->setCookie(, $hotelList, $time);
        if ($isSession == false) {
            $loginEmployee = $objCookie->$loginKey;
            if (empty($loginEmployee)) {//只针对cookie用户 session保存1个月占服务器太长时间
                $loginKey      = self::$loginKey . 2592000;//一个月
                $loginEmployee = $objCookie->$loginKey;
            }
        } else {
            $objSession    = new \Session();
            $loginEmployee = $objSession->$loginKey;
        }

        $loginEmployeeModel = new LoginEmployeeModel();
        if (!empty($loginEmployee)) {
            $arrayCookieEmployee                = explode('`--`', $loginEmployee);
            $arrayEmployee['employee_id']       = $arrayCookieEmployee[0];
            $arrayEmployee['company_id']        = $arrayCookieEmployee[1];
            $arrayEmployee['default_channel_id']= $arrayCookieEmployee[2];
            $arrayEmployee['employee_name']     = $arrayCookieEmployee[3];
            $arrayEmployee['photo']             = $arrayCookieEmployee[4];
            $objSession                         = new \Session();
            $arrayLoginEmployee['employeeInfo'] = $arrayEmployee;
            $employeeMenu                       = json_decode($objSession->employeeMenu, true);
            $employeeChannel                    = json_decode($objSession->employeeChannel, true);
            $channelSettingList                 = $objSession->channelSettingList;
            $setCookie = false;
            if (empty($employeeMenu) || empty($channelSettingList)) {
                $employeeMenu    = $this->getEmployeeModule($arrayEmployee['company_id'], $arrayEmployee['employee_id'], $arrayEmployee['default_channel_id']);
                $employeeChannel = EmployeeServiceImpl::instance()->getEmployeeChannel($arrayEmployee['company_id'], $arrayEmployee['employee_id']);
                //channelSettingList
                $whereCriteria = new \WhereCriteria();
                $whereCriteria->setHashKey('channel_id');
                $channelSettingList = ChannelDao::instance()->getChannelSettingList($whereCriteria->EQ('company_id', $arrayEmployee['company_id']));
                $setCookie = true;
            }
            $arrayLoginEmployee['employeeMenu']    = $employeeMenu;
            $arrayLoginEmployee['employeeChannel'] = $employeeChannel;
            $Employee                              = new Employee();
            $Employee->setEmployeeId($arrayCookieEmployee[0]);
            $Employee->setCompanyId($arrayCookieEmployee[1]);
            $Employee->setDefaultChannelId($arrayCookieEmployee[2]);
            $Employee->setEmployeeName($arrayCookieEmployee[3]);
            $Employee->setPhoto($arrayCookieEmployee[4]);
            $loginEmployeeModel->setEmployeeInfo($Employee);
            $loginEmployeeModel->setEmployeeMenu($employeeMenu);
            $loginEmployeeModel->setEmployeeChannel($employeeChannel);
            $loginEmployeeModel->setChannelSettingList($channelSettingList);
            if($setCookie) $this->setLoginEmployeeCookie($loginEmployeeModel);
        }
        return $loginEmployeeModel;
    }

    public function checkLoginEmployee($objCookie = null, $isSession = false): LoginEmployeeModel {
        if (!is_object($objCookie) && $isSession == false) {
            $objCookie = new \Cookie();
        }
        if ($isSession == false) {
            $loginEmployeeModel = $this->getLoginEmployee($objCookie);
        } else {
            $loginEmployeeModel = $this->getLoginEmployee(null, true);
        }
        self::$objEmployee = $loginEmployeeModel->getEmployeeInfo();
        return $loginEmployeeModel;
    }

    public function setLoginEmployeeCookie(LoginEmployeeModel $loginEmployeeModel, $remember_me = false) {
        $objCookie = new \Cookie();
        $time      = null;
        $key       = date("z");
        if ($remember_me) {
            $time = 2592000;//一个月
            $key  = $time;
        }
        $objEmployee    = $loginEmployeeModel->getEmployeeInfo();
        $cookieEmployee = $objEmployee->getEmployeeId() . '`--`' . $objEmployee->getCompanyId() . '`--`' . $objEmployee->getDefaultChannelId()
            . '`--`' . $objEmployee->getEmployeeName() . '`--`' . $objEmployee->getPhoto();
        $objCookie->setCookie(self::$loginKey . $key, $cookieEmployee, $time);
        $objSession                     = new \Session();
        $objSession->employeeMenu       = json_encode($loginEmployeeModel->getEmployeeMenu());
        $objSession->employeeChannel    = json_encode($loginEmployeeModel->getEmployeeChannel());
        $objSession->channelSettingList = $loginEmployeeModel->getChannelSettingList();
    }

    public function logout() {
        $objCookie = new \Cookie();
        $loginKey  = self::$loginKey . 2592000;//一个月
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