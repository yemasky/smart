<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace wise;
class CommonDao extends \BaseDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new CommonDao();
        return self::$objDao;
    }

    public function getDsnRead() {//default
		// TODO: Implement getDsnRead() method.
		return DbConfig::instance()->dsnRead();
    }

    public function getDsnWrite() {//default
		// TODO: Implement getDsnWrite() method.
		return DbConfig::instance()->dsnWrite();
    }

}