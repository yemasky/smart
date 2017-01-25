<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace hotel;
class RoleDao extends \BaseDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new RoleDao();
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

    public function getRoleEmployee($conditions){
        $cacheId = md5('getRoleEmployee' . json_encode($conditions));
        $fileid = 'role_id, employee_id, hotel_id';
        return $this->setDsnRead($this->getDsnRead())->setTable('role_employee')->getList($conditions, $fileid);//->DBCache($cacheId)
    }
}