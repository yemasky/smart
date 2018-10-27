<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */

namespace member;

class DbConfig extends \DbConfig {
    protected static $objConfig = null;
	const page_rows = 15;

    private static $member_dsn_read  = "mysqli:mysql://127.0.0.1:3306/wise_member?user=root&password=root&characterEncoding=UTF8";
    private static $member_dsn_write = "mysqli:mysql://127.0.0.1:3306/wise_member?user=root&password=root&characterEncoding=UTF8";

    public static function instance() {
        if(is_object(self::$objConfig)) {
            return self::$objConfig;
        }
        self::$objConfig = new DbConfig();
        return self::$objConfig;
    }

	public function dsnRead() {
		return self::$member_dsn_read;
	}

	public function dsnWrite() {
		return self::$member_dsn_write;
	}


}