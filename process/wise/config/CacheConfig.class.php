<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace wise;

class CacheConfig extends \ModulesConfig {

	public static $cachePrefix = [
	    'role'=>'_role_', 'role_employee'=>'_role_employee_',
        'employee_module'=>'_employee_module_','module_company'=>'_module_company_',
        'module'=>'_module_','channel'=>'_channel_','attribute'=>'_attr_',
        'paymentType'=>'_pay_type_','customer_market'=>'_market_'
    ];

	public static function getCacheId($channel, $company_id, $employee_id = '') {
	    return self::$cachePrefix[$channel] . $company_id . '_' . $employee_id;
    }

}