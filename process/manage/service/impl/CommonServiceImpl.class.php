<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace manage;
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

}