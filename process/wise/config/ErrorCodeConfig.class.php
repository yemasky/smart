<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace wise;

class ErrorCodeConfig extends \ModulesConfig {

	public static $errorCode = [
	    'common'=>['no_permission'=>['code'=>'000005','message'=>'没有权限'],'no_module'=>['code'=>'000006','message'=>'找不到模块'],
				   'duplicate_data'=>['code'=>'000004','message'=>'重复数据'],
				   'success'=>['code'=>'000001','message'=>'操作成功'],
				   'login_over_time'=>['code'=>'000011','message'=>'登录超时'],
			       'over_date'=>['code'=>'000012','message'=>'超過合理時間'],
                   'page_error'=>['code'=>'000013','message'=>'页面请求过时失效！请关闭页面重新请求。'],
		],
		'no_data_update'=>'000010',
        'no_data_found'=>'000009',
        'parameter_error'=>'000008',
        'page_error'=>'000013',
        'over_booking'=>'100002',
        'lock'=>'100003',
        'repair'=>'100004',
        'dirty'=>'100005',
        'live_in'=>'100006',
        'no_room_id_found'=>'100007'
    ];
    public static $notice  = ['no_equal_account'=>'200001'];
	public static $successCode = ['success'=>'000001'];


}