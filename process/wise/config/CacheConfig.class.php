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
        'employee_module'=>'_employee_module_','employee_module_menu'=>'_employee_module_menu_','module_company'=>'_module_company_',
        'module'=>'_module_','channel'=>'_channel_','attribute'=>'_attr_',
        'paymentType'=>'_pay_type_','customer_market'=>'_market_','commision'=>'_Commision_'
    ];

	public static function getCacheId($channel, $company_id, $other_id = '') {
	    return self::$cachePrefix[$channel] . $company_id . '_' . $other_id;
    }

}