<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace web;
class UserDao extends \BaseDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new UserDao();
        return self::$objDao;
    }

    public function getDsnRead() {
        // TODO: Implement getDsnRead() method.
        return DbConfig::dsnRead();
    }

    public function getDsnWrite() {
        // TODO: Implement getDsnWrite() method.
        return DbConfig::dsnWrite();
    }

    public function getUser($conditions, $fileid = '*') {
        return $this->setDsnRead($this->getDsnRead())->setTable('user')->getList($conditions, $fileid);//->DBCache($cacheId)
    }

}