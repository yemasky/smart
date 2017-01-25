<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class RoomService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new RoomService();
        return self::$objService;
    }

    public function rollback() {
        RoomDao::instance()->rollback();
    }

    public function getRoom($conditions, $field = '', $hashKey = null, $multiple = false, $fatherKey = '', $childrenKey = '') {
        return RoomDao::instance()->getRoom($conditions, $field, $hashKey, $multiple, $fatherKey, $childrenKey);
    }

    public function saveRoom($arrayData) {
        return RoomDao::instance()->setTable('room')->insert($arrayData);
    }

    public function updateRoom($where, $row) {
        return RoomDao::instance()->setTable('room')->update($where, $row);
    }

    public function deleteRoom($where) {
        return RoomDao::instance()->setTable('room')->delete($where);
    }

    public function getAttribute($hotel_id, $room_type = 'room') {
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0, $hotel_id)), 'room_type'=>$room_type);
        //$cache_id = ModulesConfig::$cacheKey['hotel']['room_attribute'] . $hotel_id;
        $conditions['order'] = 'room_layout_attribute_father_id ASC, room_layout_attribute_order ASC, room_layout_attribute_id ASC';
        $arrayAttr =  RoomDao::instance()->setTable('room_layout_attribute')->getList($conditions);//DBCache($cache_id)->
        $arrarResult = array();
        foreach ($arrayAttr as $k => $v) {
            if($v['room_layout_attribute_id'] == $v['room_layout_attribute_father_id'] || empty($v['room_layout_attribute_father_id'])) {
                //$v['room_layout_attribute_id'] = encode($v['room_layout_attribute_id']);
                $arrarResult[$v['room_layout_attribute_father_id']] = $v;
                $arrarResult[$v['room_layout_attribute_father_id']]['room_layout_attribute_id'] = encode($v['room_layout_attribute_id']);
                $arrarResult[$v['room_layout_attribute_father_id']]['children'] = array();
            } else {
                //$v['room_layout_attribute_id'] = encode($v['room_layout_attribute_id']);
                $encodeV = $v;
                $encodeV['room_layout_attribute_id'] = encode($v['room_layout_attribute_id']);
                $arrarResult[$v['room_layout_attribute_father_id']]['children'][] = $encodeV;
            }
        }
        return $arrarResult;
    }

    public function saveRoomLayoutAttr($arrayData) {
        return RoomDao::instance()->setTable('room_layout_attribute')->insert($arrayData);
    }

    public function updateRoomLayoutAttr($where, $row) {
        return RoomDao::instance()->setTable('room_layout_attribute')->update($where, $row);
    }

    public function deleteRoomLayoutAttr($where) {
        return RoomDao::instance()->setTable('room_layout_attribute')->delete($where);
    }
    //物理房型类型
    public function getRoomType($conditions, $field = '*', $hashKey = null) {
        return RoomDao::instance()->setTable('room_type')->getList($conditions, $field, $hashKey);
    }

    public function saveRoomType($arrayData) {
        return RoomDao::instance()->setTable('room_type')->insert($arrayData);
    }

    public function updateRoomType($where, $row) {
        return RoomDao::instance()->setTable('room_type')->update($where, $row);
    }

    public function deleteRoomType($where) {
        return RoomDao::instance()->setTable('room_type')->delete($where);
    }

    //基本房型
    public function getRoomLayout($conditions, $field = '*', $hashKey = null) {
        return RoomDao::instance()->setTable('room_layout')->getList($conditions, $field, $hashKey);
    }

    public function saveRoomLayout($arrayData) {
        return RoomDao::instance()->setTable('room_layout')->insert($arrayData);
    }

    public function updateRoomLayout($where, $row) {
        return RoomDao::instance()->setTable('room_layout')->update($where, $row);
    }

    public function deleteRoomLayout($where) {
        return RoomDao::instance()->setTable('room_layout')->delete($where);
    }

    //基本房型属性
    public function getRoomLayoutAttrValue($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_attribute_value')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutAttrValue($arrayData) {
        return RoomDao::instance()->setTable('room_layout_attribute_value')->insert($arrayData);
    }

    public function batchSaveRoomLayoutAttrValue($arrayData, $insert_type = 'INSERT') {
        return RoomDao::instance()->setTable('room_layout_attribute_value')->batchInsert($arrayData, $insert_type);
    }

    public function updateRoomLayoutAttrValue($where, $row) {
        return RoomDao::instance()->setTable('room_layout_attribute_value')->update($where, $row);
    }

    public function deleteRoomLayoutAttrValue($where) {
        return RoomDao::instance()->setTable('room_layout_attribute_value')->delete($where);
    }

    public function getRoomLayoutImages($conditions, $field = '*', $hashKey = null) {
        return RoomDao::instance()->setTable('room_layout_images')->getList($conditions, $field, $hashKey);
    }

    public function saveRoomLayoutImages($arrayData) {
        return RoomDao::instance()->setTable('room_layout_images')->insert($arrayData);
    }

    public function deleteRoomLayoutImages($where) {
        return RoomDao::instance()->setTable('room_layout_images')->delete($where);
    }

    public function getRoomLayoutRoomDetailed($conditions, $hashKey = null) {
        $table = '`room` r INNER JOIN `room_layout_room` rlr ON r.room_id = rlr.room_id';
        $field = 'r.room_id, r.room_type, r.room_on_sell, r.room_status, r.room_name, r.room_describe, r.room_mansion, r.room_number, r.room_floor,r.room_area,r.room_orientations,'
            .'rlr.room_layout_id,rlr.room_layout_room_max_people max_people,rlr.room_layout_room_max_children max_children,rlr.room_layout_room_extra_bed extra_bed';
        return RoomDao::instance()->setTable($table)->getList($conditions, $field, $hashKey);
    }

    public function getRoomLayoutRoom($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_room')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutRoom($arrayData) {
        return RoomDao::instance()->setTable('room_layout_room')->insert($arrayData);
    }

    public function updateRoomLayoutRoom($where, $row) {
        return RoomDao::instance()->setTable('room_layout_room')->update($where, $row);
    }

    public function deleteRoomLayoutRoom($where) {
        return RoomDao::instance()->setTable('room_layout_room')->delete($where);
    }

    //房型价格
    public function getRoomLayoutPrice($conditions, $field = null, $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_price')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutPrice($arrayData) {
        return RoomDao::instance()->setTable('room_layout_price')->insert($arrayData);
    }

    public function updateRoomLayoutPrice($where, $row) {
        return RoomDao::instance()->setTable('room_layout_price')->update($where, $row);
    }

    //加床
    public function getRoomLayoutExtraBedPrice($conditions, $field = null, $hashKey = null) {
        return RoomDao::instance()->setTable('room_layout_price_extra_bed')->getList($conditions, $field, $hashKey);
    }

    public function saveRoomLayoutExtraBedPrice($arrayData) {
        return RoomDao::instance()->setTable('room_layout_price_extra_bed')->insert($arrayData);
    }

    public function updateRoomLayoutExtraBedPrice($where, $row) {
        return RoomDao::instance()->setTable('room_layout_price_extra_bed')->update($where, $row);
    }
    //价格体系
    public function getRoomLayoutPriceSystem($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_price_system')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutPriceSystem($arrayData) {
        return RoomDao::instance()->setTable('room_layout_price_system')->insert($arrayData);
    }

    public function updateRoomLayoutPriceSystem($where, $row) {
        return RoomDao::instance()->setTable('room_layout_price_system')->update($where, $row);
    }

    public function deleteRoomLayoutPriceSystem($where) {
        return RoomDao::instance()->setTable('room_layout_price_system')->delete($where);
    }

    public function getRoomLayoutPriceSystemFilter($conditions) {
        //EXPLAIN SELECT rlps.room_layout_price_system_id, rlps.room_layout_id, rlps.room_layout_price_system_name, hs.hotel_service_id
        //FROM `room_layout_price_system` rlps LEFT JOIN `room_layout_price_system_filter` rlpsf ON rlps.room_layout_price_system_id = rlpsf.room_layout_price_system_id
        //LEFT JOIN `hotel_service` hs ON hs.hotel_service_id = rlpsf.hotel_service_id WHERE rlps.hotel_id IN(0,1)
        $table = '`room_layout_price_system` rlps LEFT JOIN `room_layout_price_system_filter` rlpsf '
                .'ON rlps.room_layout_price_system_id = rlpsf.room_layout_price_system_id LEFT JOIN `hotel_service` hs '
                .'ON hs.hotel_service_id = rlpsf.hotel_service_id';
        $field = 'rlps.room_layout_price_system_id,rlps.room_sell_layout_id, rlps.room_layout_id, rlps.room_layout_price_system_name, hs.hotel_service_id,hs.hotel_service_name';
        $arrayPriceSystemFilter = RoomDao::instance()->setTable($table)->getList($conditions, $field);
        $arrayResule = array();
        if(!empty($arrayPriceSystemFilter)) {
            $k = 0;
            foreach($arrayPriceSystemFilter as $i => $arrayValues) {
                $id = $arrayValues['room_layout_price_system_id'];
                $arrayResule[$id]['room_layout_price_system_id'] = $id;
                $arrayResule[$id]['room_layout_id'] = $arrayValues['room_layout_id'];
                $arrayResule[$id]['room_sell_layout_id'] = $arrayValues['room_sell_layout_id'];
                $arrayResule[$id]['room_layout_price_system_name'] = $arrayValues['room_layout_price_system_name'];
                if(empty($arrayValues['hotel_service_id'])) {
                    $arrayResule[$id]['hotel_service_id'] = '';
                    $arrayResule[$id]['hotel_service_name'] = '';
                } else {
                    $arrayResule[$id]['hotel_service_id'][] = $arrayValues['hotel_service_id'];
                    $arrayResule[$id]['hotel_service_name'][] = $arrayValues['hotel_service_name'];
                }
            }
            sort($arrayResule);
        }
        return $arrayResule;
    }
    //事务保存价格体系
    public function saveRoomLayoutPriceSystemAndFilter($arrayPostValue, $hotel_id) {
        $update_id = $arrayPostValue['update_system_id'];
        if($update_id == 1) return $update_id;//系统id 不能修改
        //事务开启
        RoomDao::instance()->startTransaction();
        $arrayRoomLayoutPriceSystem['room_layout_price_system_name'] = $arrayPostValue['price_system_name'];
        if(!empty($update_id)) {
            $room_layout_price_system_id = $update_id;
            $where = array('hotel_id'=>$hotel_id, 'room_layout_price_system_id'=>$update_id);
            $this->updateRoomLayoutPriceSystem($where, $arrayRoomLayoutPriceSystem);
            RoomDao::instance()->setTable('room_layout_price_system_filter')->delete($where);
        } else {
            $arrayRoomLayoutPriceSystem['hotel_id'] = $hotel_id;
            $arrayRoomLayoutPriceSystem['room_layout_price_system_add_date'] = getDay();
            $arrayRoomLayoutPriceSystem['room_layout_price_system_add_time'] = getTime();
            //$arrayRoomLayoutPriceSystem['room_layout_id'] = $arrayPostValue['room_layout_id'];
            $arrayRoomLayoutPriceSystem['room_sell_layout_id'] = $arrayPostValue['sell_layout_id'];
            $arrayRoomLayoutPriceSystem['room_layout_corp_id'] = $arrayPostValue['room_layout_corp_id'];
            $room_layout_price_system_id = $this->saveRoomLayoutPriceSystem($arrayRoomLayoutPriceSystem);
        }
        if(isset($arrayPostValue['hotel_service_id']) && !empty($arrayPostValue['hotel_service_id'])) {
            foreach($arrayPostValue['hotel_service_id'] as $i => $hotel_service_id) {
                $arraySystemFilter[$i]['room_layout_price_system_id'] = $room_layout_price_system_id;
                $arraySystemFilter[$i]['hotel_id'] = $hotel_id;
                $arraySystemFilter[$i]['hotel_service_id'] = $hotel_service_id;
            }
            RoomDao::instance()->setTable('room_layout_price_system_filter')->batchInsert($arraySystemFilter);
        }
        RoomDao::instance()->commit();
        return $room_layout_price_system_id;
    }

    //基础房型类别 RoomLayoutType
    public function getRoomLayoutType($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_type')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutType($arrayData) {
        return RoomDao::instance()->setTable('room_layout_type')->insert($arrayData);
    }

    public function updateRoomLayoutType($where, $row) {
        return RoomDao::instance()->setTable('room_layout_type')->update($where, $row);
    }

    public function deleteRoomLayoutType($where) {
        return RoomDao::instance()->setTable('room_layout_type')->delete($where);
    }
    //售卖房型 room_sell_layout
    public function getRoomSellLayout($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_sell_layout')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomSellLayout($arrayData) {
        return RoomDao::instance()->setTable('room_sell_layout')->insert($arrayData);
    }

    public function updateRoomSellLayout($where, $row) {
        return RoomDao::instance()->setTable('room_sell_layout')->update($where, $row);
    }

    public function deleteRoomSellLayout($where) {
        return RoomDao::instance()->setTable('room_sell_layout')->delete($where);
    }
    //
    //售卖房型 room_sell_layout
    public function getRoomLayoutCorp($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return RoomDao::instance()->setTable('room_layout_corp')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveRoomLayoutCorp($arrayData) {
        return RoomDao::instance()->setTable('room_layout_corp')->insert($arrayData);
    }

    public function updateRoomLayoutCorp($where, $row) {
        return RoomDao::instance()->setTable('room_layout_corp')->update($where, $row);
    }

    public function deleteRoomLayoutCorp($where) {
        return RoomDao::instance()->setTable('room_layout_corp')->delete($where);
    }
}