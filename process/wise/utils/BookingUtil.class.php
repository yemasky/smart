<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/12/12
 * Time: 14:32
 */

namespace wise;

class BookingUtil {
    private static $objUtil;

    public static function instanct() {
        if (is_object(self::$objUtil)) {
            return self::$objUtil;
        }
        self::$objUtil = new BookingUtil();

        return self::$objUtil;
    }

    public function getBookingNumber($booking_id, $len = 7) {
        $order_number = '0' . $booking_id;
        $length       = strlen($order_number) + 1;
        if ($length > $len) {
            $order_number = substr($order_number, $length - $len);
        } else {
            for ($i = $length; $i <= $len; $i++) {
                $order_number = rand(1, 9) . $order_number;
            }
        }

        return date("ymd") . $order_number;
    }
}