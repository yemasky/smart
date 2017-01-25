<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace hotel;
class LaguageDao extends \BaseDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new LaguageDao();
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
    
    public function getPageModuleLaguage($conditions){
        $cacheId = md5('getPageModuleLaguage' . json_encode($conditions));
        $fileid = 'laguage, page_module, page_laguage_key, page_laguage_value';
        return $this->setDsnRead($this->getDsnRead())->setTable('multi_laguage_page')->getList($conditions, $fileid, 'page_laguage_key');//->DBCache($cacheId)
    }
}