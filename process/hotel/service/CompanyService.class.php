<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class CompanyService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new CompanyService();
        return self::$objService;
    }

    public function getCompany($conditions, $field = '*', $hashKey = null) {
        return CompanyDao::instance()->getCompany($conditions, $field, $hashKey);
    }

    public function saveCompany($arrayData) {
        return CompanyDao::instance()->insert($arrayData);
    }

    public function updateCompany($where, $row) {
        return CompanyDao::instance()->update($where, $row);
    }

    public function deleteCompany($where) {
        return CompanyDao::instance()->delete($where);
    }

}