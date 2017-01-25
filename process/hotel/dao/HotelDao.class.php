<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace hotel;
class HotelDao extends \BaseDao {
    protected $table = 'hotel';
    protected $table_key = 'hotel_id';
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new HotelDao();
        return self::$objDao;
    }

    public function getDsnRead() {
        return DbConfig::dsnRead();
    }

    public function getDsnWrite() {
        return DbConfig::dsnWrite();
    }

    public function getHotelModules($conditions, $field = '*', $hashKey = null, $multiple = false){
        if(empty($field) || $field == '*') 
            $fileid = 'hotel_id, modules_id, hotel_modules_father_id, hotel_modules_name, hotel_modules_navigation, hotel_modules_order, hotel_modules_ico, hotel_modules_show';
        $conditions['order'] = 'hotel_modules_father_id ASC, hotel_modules_order ASC, modules_id ASC';
        return $this->setDsnRead($this->getDsnRead())->setTable('hotel_modules')->getList($conditions, $fileid, $hashKey, $multiple);
    }

    public function getHotel($conditions, $field = null , $hashKey = null, $multiple = false){
        $field =  !empty($field) ? $field : 'hotel_id, company_id, company_group, hotel_group, hotel_name, hotel_address, hotel_phone, hotel_mobile, hotel_fax, hotel_email, '
                 .'hotel_longitude, '
                 .'hotel_latitude, hotel_country, hotel_province, hotel_city, hotel_town, hotel_introduce_short, hotel_introduce, hotel_booking_notes, '
				 .'hotel_type, hotel_star, '
                 .'hotel_brand, hotel_wifi,hotel_checkout,hotel_checkin, hotel_add_date';
        return $this->setDsnRead($this->getDsnRead())->setTable('hotel')->getList($conditions, $field, $hashKey, $multiple);
    }
}