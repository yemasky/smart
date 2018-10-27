<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace manage;

class ErrorCodeConfig extends \ModulesConfig {

	public static $errorCode = [
	    'common'=>['no_permission'=>['code'=>'000005','message'=>'没有权限'],'no_module'=>['code'=>'000006','message'=>'找不到模块'],
				   'success'=>['code'=>'000001','message'=>'操作成功！']
		]
    ];

	public static $successCode = ['success'=>'000001'];


}