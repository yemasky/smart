<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace hotel;

class DbConfig extends \DbConfig{
    private static $hotel_dsn_read = "mysqli:mysql://127.0.0.1:3306/hotel?user=root&password=root&characterEncoding=UTF8";
    private static $hotel_dsn_write = "mysqli:mysql://127.0.0.1:3306/hotel?user=root&password=root&characterEncoding=UTF8";

    public static function dsnRead() {
        return self::$hotel_dsn_read;
    }

    public static function dsnWrite() {
        return self::$hotel_dsn_write;
    }


}