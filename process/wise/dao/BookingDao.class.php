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
		return $this->setDsnRead($this->getDsnRead())->setTable('booking')->getList($field, $whereCriteria);
	}

    public function saveBooking(BookingEntity $BookingEntity) : int {
        return $this->setDsnRead($this->getDsnWrite())->insertEntity($BookingEntity);
    }
    
    public function updateBooking(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('booking')->update($arrayUpdateData, $whereCriteria, $update_type);
    }
    
    public function getBookingDetailEntity(\WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnRead())->setEntity("\wise\Booking_detailEntity")->getEntityList(null, $whereCriteria);
    }
    
    public function getBookingDetailList(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_detail')->getList($field, $whereCriteria);
    }

    public function saveBookingDetailList($bookDetailList) : array {
        return $this->setDsnRead($this->getDsnWrite())->batchInsertEntity($bookDetailList, 'item_id');
    }

    public function updateBookingDetail(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('booking_detail')->update($arrayUpdateData, $whereCriteria, $update_type);
    }
    //

    public function saveBookingDetailConsumeList($bookingDetailConsumeList) : array {
        return $this->setDsnRead($this->getDsnWrite())->batchInsertEntity($bookingDetailConsumeList);
    }
    //获取消费
    public function getBookingConsume(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_consume')->getList($field, $whereCriteria);
    }
    //取得账务
    public function getBookingAccounts(\WhereCriteria $whereCriteria, $field = null) : array {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_accounts')->getList($field, $whereCriteria);
    }
    //入住客人
    public function saveGuestLiveIn(Booking_live_inEntity $Booking_live_inEntity) : int {
        return $this->setDsnRead($this->getDsnWrite())->insertEntity($Booking_live_inEntity);
    }
}