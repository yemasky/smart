<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace hotel;
class BookService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookService();
        return self::$objService;
    }

    public function startTransaction() {
        BookDao::instance()->startTransaction();
    }
    public function commit() {
        BookDao::instance()->commit();
    }
    public function rollback() {
        BookDao::instance()->rollback();
    }

    public function getBook($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->getBook($conditions, $field, $hashKey, $multiple);
    }

    public function saveBook($arrayData) {
        return BookDao::instance()->setTable('book')->insert($arrayData);
    }

    public function updateBook($where, $row) {
        return BookDao::instance()->setTable('book')->update($where, $row);
    }

    public function deleteBook($where) {
        return BookDao::instance()->setTable('book')->delete($where);
    }
    //预定类别来源
    public function getBookType($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '', $childrenKey = '') {
        return BookDao::instance()->setTable('book_type')->getList($conditions, $field, $hashKey, $multiple, $fatherKey, $childrenKey);
    }

    public function saveBookType($arrayData) {
        return BookDao::instance()->setTable('book_type')->insert($arrayData);
    }

    public function updateBookType($where, $row) {
        return BookDao::instance()->setTable('book_type')->update($where, $row);
    }

    public function deleteBookType($where) {
        return BookDao::instance()->setTable('book_type')->delete($where);
    }

    //销售来源
    public function getBookSalesType($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '', $childrenKey = '') {
        return BookDao::instance()->setTable('book_sales_type')->getList($conditions, $field, $hashKey, $multiple, $fatherKey, $childrenKey);
    }

    public function saveBookSalesType($arrayData) {
        return BookDao::instance()->setTable('book_sales_type')->insert($arrayData);
    }

    public function updateBookSalesType($where, $row) {
        return BookDao::instance()->setTable('book_sales_type')->update($where, $row);
    }

    public function deleteBookSalesType($where) {
        return BookDao::instance()->setTable('book_sales_type')->delete($where);
    }
    //折扣
    public function getBookDiscount($conditions, $fieldid = '*', $hashKey = null) {
        return BookDao::instance()->setTable('book_discount')->getList($conditions, $fieldid, $hashKey);
    }

    public function saveBookDiscount($arrayData) {
        return BookDao::instance()->setTable('book_discount')->insert($arrayData);
    }

    public function updateBookDiscount($where, $row) {
        return BookDao::instance()->setTable('book_discount')->update($where, $row);
    }

    public function deleteBookDiscount($where) {
        return BookDao::instance()->setTable('book_discount')->delete($where);
    }


    public function getBookTypeDiscount($conditions) {
        $arrayBookTypeId = \web\UserService::instance()->getUserLogin($conditions, 'book_type_id, book_discount_id');
        $arrayBookType = null;
        if(!empty($arrayBookTypeId)) {
            $conditions['where'] = array('book_discount_id'=>$arrayBookTypeId[0]['book_discount_id']);
            $arrayBookTypeDiscount = $this->getBookDiscount($conditions, 'book_discount, book_discount_type, book_discount_name, agreement_company_name, room_layout_corp_id layout_corp');
        }
        if(!empty($arrayBookTypeDiscount)) {
            $arrayBookType['book_discount_id'] = $arrayBookTypeId[0]['book_discount_id'];
            $arrayBookType['book_type_id'] = $arrayBookTypeId[0]['book_type_id'];
            $arrayBookType['book_discount'] = $arrayBookTypeDiscount[0]['book_discount'];
            $arrayBookType['book_discount_name'] = $arrayBookTypeDiscount[0]['book_discount_name'];
            $arrayBookType['agreement_company_name'] = $arrayBookTypeDiscount[0]['agreement_company_name'];
            $arrayBookType['book_discount_type'] = $arrayBookTypeDiscount[0]['book_discount_type'];
            $arrayBookType['layout_corp'] = $arrayBookTypeDiscount[0]['layout_corp'];
        }
        return $arrayBookType;
    }

    //入住用户信息
    public function getBookUser($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_user')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookUser($arrayData) {
        return BookDao::instance()->setTable('book_user')->insert($arrayData);
    }

    public function updateBookUser($where, $row) {
        return BookDao::instance()->setTable('book_user')->update($where, $row);
    }

    public function deleteBookUser($where) {
        return BookDao::instance()->setTable('book_user')->delete($where);
    }

    //服务信息
    public function getBookHotelService($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_hotel_service')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookHotelService($arrayData) {
        return BookDao::instance()->setTable('book_hotel_service')->insert($arrayData);
    }

    public function updateBookHotelService($where, $row) {
        return BookDao::instance()->setTable('book_hotel_service')->update($where, $row);
    }

    public function deleteBookHotelService($where) {
        return BookDao::instance()->setTable('book_hotel_service')->delete($where);
    }
    //预定变化历史
    public function getBookChange($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_change')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookChange($arrayData) {
        return BookDao::instance()->setTable('book_change')->insert($arrayData);
    }

    public function updateBookChange($where, $row) {
        return BookDao::instance()->setTable('book_change')->update($where, $row);
    }

    public function deleteBookChange($where) {
        return BookDao::instance()->setTable('book_change')->delete($where);
    }
    //预定夜审
    public function getBookNightAudit($conditions, $field = '*', $hashKey = null, $multiple = false, $fatherKey = '', $childrenKey = '') {
        return BookDao::instance()->setTable('book_night_audit')->getList($conditions, $field, $hashKey, $multiple, $fatherKey, $childrenKey);
    }

    public function saveBookNightAudit($arrayData) {
        return BookDao::instance()->setTable('book_night_audit')->insert($arrayData);
    }

    public function updateBookNightAudit($where, $row) {
        return BookDao::instance()->setTable('book_night_audit')->update($where, $row);
    }

    public function deleteBookNightAudit($where) {
        return BookDao::instance()->setTable('book_night_audit')->delete($where);
    }

    //入住房价信息
    public function getBookRoomPrice($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_room_price')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookRoomPrice($arrayData) {
        return BookDao::instance()->setTable('book_room_price')->insert($arrayData);
    }

    public function updateBookRoomPrice($where, $row) {
        return BookDao::instance()->setTable('book_room_price')->update($where, $row);
    }

    public function deleteBookRoomPrice($where) {
        return BookDao::instance()->setTable('book_room_price')->delete($where);
    }
    //加床价格
    public function getBookRoomExtraBedPrice($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_room_extra_bed_price')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookRoomExtraBedPrice($arrayData) {
        return BookDao::instance()->setTable('book_room_extra_bed_price')->insert($arrayData);
    }

    public function updateBookRoomExtraBedPrice($where, $row) {
        return BookDao::instance()->setTable('book_room_extra_bed_price')->update($where, $row);
    }

    public function deleteBookRoomExtraBedPrice($where) {
        return BookDao::instance()->setTable('book_room_extra_bed_price')->delete($where);
    }

    //book_returns
    public function getBookReturns($conditions, $field = '*', $hashKey = null, $multiple = false) {
        return BookDao::instance()->setTable('book_returns')->getList($conditions, $field, $hashKey, $multiple);
    }

    public function saveBookReturns($arrayData) {
        return BookDao::instance()->setTable('book_returns')->insert($arrayData);
    }

    public function updateBookReturns($where, $row) {
        return BookDao::instance()->setTable('book_returns')->update($where, $row);
    }

    public function deleteBookReturns($where) {
        return BookDao::instance()->setTable('book_returns')->delete($where);
    }

}