<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class HotelService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new HotelService();
        return self::$objService;
    }

    public function rollback() {
        HotelDao::instance()->rollback();
    }

    public function startTransaction() {
        HotelDao::instance()->startTransaction();
    }

    public function commit() {
        HotelDao::instance()->commit();
    }

    public function getHotelModules($hotel_id, $hashKey = null, $multiple = false) {
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        return HotelDao::instance()->getHotelModules($conditions, '*', $hashKey, $multiple);
    }

    public function getHotel($conditions, $field = '*', $hashKey = null) {
        if(empty($conditions['order'])) $conditions['order'] = 'hotel_id DESC';
        return HotelDao::instance()->getHotel($conditions, $field, $hashKey);
    }

    public function saveHotel($arrayData) {
        return HotelDao::instance()->setTable('hotel')->insert($arrayData);
    }

    public function updateHotel($where, $row) {
        return HotelDao::instance()->setTable('hotel')->update($where, $row);
    }

    public function deleteHotel($where) {
        return HotelDao::instance()->setTable('hotel')->delete($where);
    }

    public function getAttribute($hotel_id) {
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0, $hotel_id)));
        $cache_id = ModulesConfig::$cacheKey['hotel']['hotel_attribute'] . $hotel_id;
        $conditions['order'] = 'hotel_attribute_father_id ASC, hotel_attribute_order ASC, hotel_attribute_id ASC';
        $arrayAttr =  HotelDao::instance()->setTable('hotel_attribute')->getList($conditions);//->DBCache($cache_id)
        $arrayResult = array();
        foreach ($arrayAttr as $k => $v) {
            if($v['hotel_attribute_id'] == $v['hotel_attribute_father_id']) {
                $arrayResult[$v['hotel_attribute_father_id']] = $v;
                $arrayResult[$v['hotel_attribute_father_id']]['hotel_attribute_id'] = encode($v['hotel_attribute_id']);
                $arrayResult[$v['hotel_attribute_father_id']]['children'] = array();
            } else {
                $encodeV = $v;
                $encodeV['hotel_attribute_id'] = encode($v['hotel_attribute_id']);
                $arrayResult[$v['hotel_attribute_father_id']]['children'][] = $encodeV;
            }
        }
        return $arrayResult;
    }

    public function saveHotelAttr($arrayData) {
        return HotelDao::instance()->setTable('hotel_attribute')->insert($arrayData);
    }

    public function updateHotelAttr($where, $row) {
        return HotelDao::instance()->setTable('hotel_attribute')->update($where, $row);
    }

    public function deleteHotelAttr($where) {
        return HotelDao::instance()->setTable('hotel_attribute')->delete($where);
    }

    public function getHotelImages($conditions, $hashKey = null) {
        return HotelDao::instance()->setTable('hotel_images')->getList($conditions, '', $hashKey);
    }

    public function saveHotelImages($arrayData) {
        return HotelDao::instance()->setTable('hotel_images')->insert($arrayData);
    }

    public function deleteHotelImages($where) {
        return HotelDao::instance()->setTable('hotel_images')->delete($where);
    }

    public function getHotelAttrValue($conditions, $hashKey = null, $multiple = false) {
        return HotelDao::instance()->setTable('hotel_attribute_value')->getList($conditions, '', $hashKey, $multiple);
    }

    public function saveHotelAttrValue($arrayData) {
        return HotelDao::instance()->setTable('hotel_attribute_value')->insert($arrayData);
    }

    public function batchSaveHotelAttrValue($arrayData, $insert_type = 'INSERT') {
        return HotelDao::instance()->setTable('hotel_attribute_value')->batchInsert($arrayData, $insert_type);
    }

    public function updateHotelAttrValue($where, $row) {
        return HotelDao::instance()->setTable('hotel_attribute_value')->update($where, $row);
    }

    public function deleteHotelAttrValue($where) {
        return HotelDao::instance()->setTable('hotel_attribute_value')->delete($where);
    }
    //hotel_service
    public function getHotelService($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return HotelDao::instance()->setTable('hotel_service')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveHotelService($arrayData) {
        return HotelDao::instance()->setTable('hotel_service')->insert($arrayData);
    }

    public function updateHotelService($where, $row) {
        return HotelDao::instance()->setTable('hotel_service')->update($where, $row);
    }

    public function deleteHotelService($where) {
        return HotelDao::instance()->setTable('hotel_service')->delete($where);
    }

    //hotel_department
    public function getHotelDepartment($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return HotelDao::instance()->setTable('department')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveHotelDepartment($arrayData) {
        return HotelDao::instance()->setTable('department')->insert($arrayData);
    }

    public function updateHotelDepartment($where, $row) {
        return HotelDao::instance()->setTable('department')->update($where, $row);
    }

    public function deleteHotelDepartment($where) {
        return HotelDao::instance()->setTable('department')->delete($where);
    }

    public function depthDepartment($arrayData) {
        $arrayDepth = '';
        if(!empty($arrayData)) {
            foreach($arrayData as $i => $department) {
                //father
                if($department['department_id'] == $department['department_father_id']) {

                } else {//is children
                    $arrayDepth[$department['department_father_id']][$department['department_same_order']] = $department;
                }
            }
        }
    }
    //department_position
    public function getHotelDepartmentPosition($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return HotelDao::instance()->setTable('department_position')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveHotelDepartmentPosition($arrayData) {
        return HotelDao::instance()->setTable('department_position')->insert($arrayData);
    }

    public function updateHotelDepartmentPosition($where, $row) {
        return HotelDao::instance()->setTable('department_position')->update($where, $row);
    }

    public function deleteHotelDepartmentPosition($where) {
        return HotelDao::instance()->setTable('department_position')->delete($where);
    }
    //payment_type
    public function getHotelPaymentType($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return HotelDao::instance()->setTable('payment_type')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveHotelPaymentType($arrayData) {
        return HotelDao::instance()->setTable('payment_type')->insert($arrayData);
    }

    public function updateHotelPaymentType($where, $row) {
        return HotelDao::instance()->setTable('payment_type')->update($where, $row);
    }

    public function deleteHotelPaymentType($where) {
        return HotelDao::instance()->setTable('payment_type')->delete($where);
    }
    //payment_type
    public function getHotelServiceSetting($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '') {
        return HotelDao::instance()->setTable('hotel_service_setting')->getList($conditions, $field, $hashKey, $multiple, $fatherKey);
    }

    public function saveHotelServiceSetting($arrayData) {
        return HotelDao::instance()->setTable('hotel_service_setting')->insert($arrayData);
    }

    public function updateHotelServiceSetting($where, $row) {
        return HotelDao::instance()->setTable('hotel_service_setting')->update($where, $row);
    }

    public function deleteHotelServiceSetting($where) {
        return HotelDao::instance()->setTable('hotel_service_setting')->delete($where);
    }
}