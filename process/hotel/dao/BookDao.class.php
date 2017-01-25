<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace hotel;
class BookDao extends \BaseDao {
    protected $table = 'book';
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new BookDao();
        return self::$objDao;
    }

    public function getDsnRead() {
        return DbConfig::dsnRead();
    }

    public function getDsnWrite() {
        return DbConfig::dsnWrite();
    }

    public function getBook($conditions, $fieldid = null, $hashKey = null, $multiple = false){
        return $this->setDsnRead($this->getDsnRead())->setTable('book')->getList($conditions, $fieldid, $hashKey, $multiple);//->DBCache($cacheId)
    }

}