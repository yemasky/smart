<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */

namespace wise;

class DbConfig extends \DbConfig {
    protected static $objConfig = null;
	const page_rows = 15;
	private static $hotel_dsn_read  = "mysqli:mysql://127.0.0.1:3306/wise_dev?user=root&password=root&characterEncoding=UTF8";
	private static $hotel_dsn_write = "mysqli:mysql://127.0.0.1:3306/wise_dev?user=root&password=root&characterEncoding=UTF8";

	private static $wxBook_dsn_read  = "mysqli:mysql://127.0.0.1:3306/weixin_book?user=root&password=root&characterEncoding=UTF8";
	private static $wxBook_dsn_write = "mysqli:mysql://127.0.0.1:3306/weixin_book?user=root&password=root&characterEncoding=UTF8";

    public static function instance() {
        if(is_object(self::$objConfig)) {
            return self::$objConfig;
        }
        self::$objConfig = new DbConfig();
        return self::$objConfig;
    }

	public function dsnRead() {
		return self::$hotel_dsn_read;
	}

	public function dsnWrite() {
		return self::$hotel_dsn_write;
	}

	public static function wxBookRead() {
		return self::$wxBook_dsn_read;
	}

	public static function wxBookWrite() {
		return self::$wxBook_dsn_write;
	}


}