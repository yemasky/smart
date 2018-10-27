<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class CommonServiceImpl implements \BaseServiceImpl {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new CommonServiceImpl();

        return self::$objService;
    }

    public function startTransaction() {
        CommonService::instance()->startTransaction();
    }

    public function commit() {
        CommonService::instance()->commit();
    }

    public function rollback() {
        CommonService::instance()->rollback();
    }

}