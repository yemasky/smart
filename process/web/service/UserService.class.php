<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace web;
class UserService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new UserService();
        return self::$objService;
    }

    public function getUser($conditions, $fileid = '*') {
        return UserDao::instance()->getUser($conditions, $fileid);
    }

    public function saveUser($arrayData) {
        return BookDao::instance()->setTable('user')->insert($arrayData);
    }

    public function updateUser($where, $row) {
        return BookDao::instance()->setTable('user')->update($where, $row);
    }

    public function deleteUser($where) {
        return BookDao::instance()->setTable('user')->delete($where);
    }

    public function getUserLogin($conditions, $fileid = '*') {
        return UserDao::instance()->setTable('user_login')->getList($conditions, $fileid);
    }

    public function saveUserLogin($arrayData) {
        return BookDao::instance()->setTable('user_login')->insert($arrayData);
    }

    public function updateUserLogin($where, $row) {
        return BookDao::instance()->setTable('user_login')->update($where, $row);
    }

    public function deleteUserLogin($where) {
        return BookDao::instance()->setTable('user_login')->delete($where);
    }


}