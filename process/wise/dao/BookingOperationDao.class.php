<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class BookingOperationDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new BookingOperationDao();

		return self::$objDao;
	}

    //--------BookingOperationDao//-----------//
    public function getBookingOperation(\WhereCriteria $whereCriteria, $field = '') {
        if(empty($field)) $field = '*';

        return $this->setDsnRead($this->getDsnRead())->setTable('booking_operation')->getList($whereCriteria, $field);//->DBCache($cacheId)
    }

    public function saveBookingOperation(Booking_operationEntity $Booking_operationEntity) {
        return $this->setDsnWrite($this->getDsnWrite())->insertEntity($Booking_operationEntity);
    }

    //--------BookingOperationDao//-----------//
}