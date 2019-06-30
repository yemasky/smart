<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class BookingOperationServiceImpl extends \BaseServiceImpl implements RoleService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
			return self::$objService;
		}
		self::$objService = new BookingOperationServiceImpl();

		return self::$objService;
	}

	public function getBookingOperation() {

	}

    public function saveBookingOperation(Booking_operationEntity $Booking_operationEntity) {
        return BookingOperationDao::instance()->saveBookingOperation($Booking_operationEntity);
    }

}