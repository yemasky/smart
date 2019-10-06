<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class LogStatementsServiceImpl extends \BaseServiceImpl implements \BaseService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new LogStatementsServiceImpl();

        return self::$objService;
    }

    //收银报表
    public function getLogStatementsAccounts(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objLoginEmployee = LoginServiceImpl::instance()->checkLoginEmployee()->getEmployeeInfo();
        $company_id       = $objLoginEmployee->getCompanyId();
        $channel_id = $objRequest->channel_id;
        $employee_id = $objLoginEmployee->getEmployeeId();
        //

        //获取channel

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        //营业日收银报表
        $whereCriteria->EQ('employee_id', $employee_id);
        $field = 'accounts_id,booking_number,payment_id,payment_name,money,accounts_type,accounts_status,employee_name,business_day';
        $arrayLogStatementsAccounts = BookingHotelServiceImpl::instance()->getBookingAccounts($whereCriteria, $field);
        return $arrayLogStatementsAccounts;
    }


}