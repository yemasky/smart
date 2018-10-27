<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class BookingService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new BookingService();
		}
		
		return self::$objService;
	}

	public function getBooking(\WhereCriteria $whereCriteria, $field = null) {
		return BookingDao::instance()->getBooking($field, $whereCriteria);
	}

	public function getBookingDetail(\WhereCriteria $whereCriteria, $field = null) {
		return BookingDao::instance()->setTable('booking_detail')->getList($field, $whereCriteria);
	}
}