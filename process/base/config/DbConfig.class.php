<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
abstract class DbConfig{
    const page_rows = 2;

	public static $db_query_conditions = array('order'=>NULL, 'limit'=>NULL, 'group'=>NULL, 'where'=>NULL);

    abstract static function dsnRead();

    abstract static function dsnWrite();

}