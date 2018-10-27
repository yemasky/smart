<?php
/**
 * User: YEMASKY
 * Date: 2016/7/24
 * Time: 0:04
 */
namespace wise;
class PipeService extends \BaseService {
    private static $objService = null;
    public static function instance() {
        if(is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new PipeService();
        return self::$objService;
    }

    public function callWxDepartmentPipe($department_self_id, $hotel_id) {
		$postData = 'act=department&excute=All&department_id='.$department_self_id.'&hotel_id='.$hotel_id.'&company_id='.'&wx_corpid=';
		$phpFile = __WEIXIN_PIPE . 'index.php action=pipe type=employee postData='.urlencode($postData);
		phpCall($phpFile);
    }


}