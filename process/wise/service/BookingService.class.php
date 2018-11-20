<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
interface BookingService extends \BaseService {

    public function getBooking(\WhereCriteria $whereCriteria, $field = null);

    public function checkBooking(\WhereCriteria $whereCriteria, $field = null);

    public function beginBooking(\HttpRequest $objRequest, \HttpResponse $objResponse): \SuccessService;

    public function saveBooking(BookingDataModel $BookingData): \SuccessService;

}
