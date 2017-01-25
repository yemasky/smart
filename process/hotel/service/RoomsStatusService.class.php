<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class RoomsStatusService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new RoomsStatusService();
        return self::$objService;
    }

    public function rollback() {
        RoomDao::instance()->rollback();
    }

    public function getBookRoomStatus($conditions, $hotel_id, $book_check_in, $book_check_out) {
        $conditions['where'] = array('hotel_id'=>$hotel_id,'');
        $conditions['where'] = "hotel_id = '".$hotel_id."' AND book_order_number_status >= 0 AND "
            ."(book_check_in <= '".$book_check_in."' AND '".$book_check_in."' < book_check_out) "
            ."OR ('".$book_check_in."' <= book_check_in AND book_check_in < '".$book_check_out."')";
        $field = 'book_id, room_id, room_layout_id layout_id, room_sell_layout_id sell_id, book_order_number_status `status`, book_check_in check_in, book_check_out check_out';
        $arrayISBookRoom = BookService::instance()->getBook($conditions, $field, 'room_id', true);
        return $arrayISBookRoom;
    }

    public function setRoomStatus($room_id, $status) {
        $updateData['room_status'] = '0';
        if($status == 'Dirty') $updateData['room_status'] = '1';
        if($status == 'Servicing') $updateData['room_status'] = '2';
        $where = array('room_id'=>$room_id);
        return RoomService::instance()->updateRoom($where, $updateData);
    }

}