<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class HotelOrderServiceImpl extends \BaseServiceImpl {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
			return self::$objService;
		}
		self::$objService = new HotelOrderServiceImpl();

		return self::$objService;
	}

	public function getRoomStatus() {

	}


}