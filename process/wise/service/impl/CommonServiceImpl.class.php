<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class CommonServiceImpl extends \BaseServiceImpl implements CommonService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new CommonServiceImpl();

        return self::$objService;
    }

    public function startTransaction() {
        CommonDao::instance()->startTransaction();
    }

    public function commit() {
        CommonDao::instance()->commit();
    }

    public function rollback() {
        CommonDao::instance()->rollback();
    }

    public function getNavbar($arrayEmployeeModule, $thisModule, $nav = '') {
        $href = 'href="/#!/app/' . $thisModule['module_channel'] . '//'.\Encrypt::instance()->encode($thisModule['module_id'], getDay()).'"';
        //'<a ' . $href . '>' .  </a>
        $nav = $thisModule['module_name'] . ' <i class="fa fa-angle-double-right"></i> ' . $nav;
        if(isset($arrayEmployeeModule[$thisModule['module_father_id']]) && $thisModule['module_father_id'] > 0) {
            $nav = $this->getNavbar($arrayEmployeeModule, $arrayEmployeeModule[$thisModule['module_father_id']], $nav);
        }
        return $nav;
    }

}