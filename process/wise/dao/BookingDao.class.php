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

	public function getBooking($field = null, \WhereCriteria $whereCriteria){
		return $this->setDsnRead($this->getDsnRead())->setTable('booking')->getList($field, $whereCriteria);
	}

    public function getBookingDetail(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('booking_detail')->getList($field, $whereCriteria);
    }

    public function saveBooking(BookingEntity $BookingEntity) : int {
        return $this->setDsnRead($this->getDsnWrite())->insertEntity($BookingEntity);
    }

    public function saveBookingDetailList($bookDetailList) : array {
        return $this->setDsnRead($this->getDsnWrite())->batchInsertEntity($bookDetailList, 'item_id');
    }

    public function saveBookingDetailConsumeList($bookingDetailConsumeList) : array {
        return $this->setDsnRead($this->getDsnWrite())->batchInsertEntity($bookingDetailConsumeList);
    }
}