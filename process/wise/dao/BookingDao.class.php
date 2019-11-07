<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class BookingDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new BookingDao();

		return self::$objDao;
	}

	public function getBooking(\WhereCriteria $whereCriteria, $field = null){
		return $this->setDsnRead($this->getDsnRead())->setTable('booking')->getList($whereCriteria, $field);
	}

    public function saveBooking(BookingEntity $BookingEntity) : int {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($BookingEntity);
    }
    
    public function updateBooking(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    
    public function getBookingDetailEntity(\WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnRead())->setEntity("\wise\Booking_detailEntity")->getEntityList($whereCriteria);
    }
    
    public function getBookingDetailList(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_detail')->getList($whereCriteria, $field);
    }

    public function saveBookingDetailList($bookDetailList) : array {
        return $this->setDsnWrite($this->getDsnWrite())->batchInsertEntity($bookDetailList, 'item_id');
    }

    public function updateBookingDetail(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_detail')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //
    public function saveBookingDetailConsumeList($bookingDetailConsumeList) : array {
        return $this->setDsnWrite($this->getDsnWrite())->batchInsertEntity($bookingDetailConsumeList);
    }
    //获取消费
    public function getBookingConsume(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_consume')->getList($whereCriteria, $field);
    }
    public function saveBookingConsume(Booking_consumeEntity $Booking_consumeEntity) {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($Booking_consumeEntity);
    }
    public function updateBookingConsume($whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_consume')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //取得借物
    public function getBookingBorrowing(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_borrowing')->getList($whereCriteria, $field);
    }
    public function saveBookingBorrowing(Booking_borrowingEntity $Booking_borrowingEntity) {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($Booking_borrowingEntity);
    }
    public function updateBookingBorrowing($whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_borrowing')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //取得账务
    public function getBookingAccounts(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_accounts')->getList($whereCriteria, $field);
    }

    public function updateBookingAccounts($whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_accounts')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function saveBookingAccounts(Booking_accountsEntity $Booking_accountsEntity) {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($Booking_accountsEntity);
    }
    //入住客人
    public function saveGuestLiveIn(Booking_live_inEntity $Booking_live_inEntity) : int {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($Booking_live_inEntity);
    }

    public function updateGuestLiveIn(\WhereCriteria $whereCriteria, $row) : int {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_live_in')->update($whereCriteria, $row);
    }

    public function getGuestLiveIn(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_live_in')->getList($whereCriteria, $field);
    }
    //房间操作
    public function saveRoomEven(Booking_evenEntity $booking_evenEntity) {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($booking_evenEntity);
    }
    public function updateRoomEven($whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_even')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //**MEAL******************************************************************************//
    public function getBookingCuisine(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_cuisine')->getList($whereCriteria, $field);
    }

    public function saveBookingCuisineList($bookingCuisineList) {
        return $this->setDsnWrite($this->getDsnWrite())->batchInsertEntity($bookingCuisineList, 'booking_cuisine_id');
    }

    public function saveBookingCuisine(Booking_cuisineEntity $bookingCuisineList) : int {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($bookingCuisineList);
    }

    public function updateBookingCuisine(\WhereCriteria $whereCriteria, $row) : int {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_cuisine')->update($whereCriteria, $row);
    }
    //
    public function getBookingDiscount(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_discount')->getList($whereCriteria, $field);
    }

    public function saveBookingDiscountList($bookingCuisineList) {
        return $this->setDsnWrite($this->getDsnWrite())->batchInsertEntity($bookingCuisineList, 'booking_discount_id');
    }

    public function saveBookingDiscount(Booking_cuisineEntity $bookingCuisineList) : int {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($bookingCuisineList);
    }

    public function updateBookingDiscount(\WhereCriteria $whereCriteria, $row) : int {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('booking_discount')->update($whereCriteria, $row);
    }

}