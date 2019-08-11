<?php

var_dump(md5(1 . '`　-   `' . md5('14e1b600b1fd579f47433b88e8d85291') . md5('5483116858d36bd6d1f6c')));

//echo  json_encode($arr);
exit;
function getKey($bb = '') {
	static  $aa = '';
	if(!empty($bb)) $aa = $bb;
	return $aa;
}

echo  getKey() . "<br>";
echo  getKey('12345678') . "<br>";
echo  getKey();
exit;

echo date("Y-m-d", strtotime("2017-08-09") + 86400) . ' 12:00:00';

$arr = '"{"2017-09-08 10:46:59|2017-09-10 12:00:00":{"14-1":{"layout_id":"13","system_id":"1","order_amount":2,"room_id":{"0":"105","1":"107"},"corp_id":"0","discount_id":"0","discount_type":"0","discount":"100","date":{"2017-9-08":{"price":"480","discount_price":"480"},"2017-9-09":{"price":"480","discount_price":"480"}},"user":{"0":{"0":{"user_name":"","book_user_id_card_type":"id_card","book_user_id_card":"","book_user_sex":"1","book_user_mobile":"","user_comments":""},"room_id":"105"}}}}}"';
print_r(json_decode($arr, true));

exit;
 phpinfo();


echo md5(1 . '`　-   `' . md5('luochi') . md5('585568'));

exit;
//md5($arrayEmployeeInfo[$i]['hotel_id'] . '`　-   `' . md5($arrayLoginInfo['employee_password']) . md5($arrayEmployeeInfo[$i]['employee_password_salt']))
echo percent(0.000225455);


function percent($num, $xf = 3, $symbol = '%') {
	$xf = $xf + 2;
	return sprintf("%.".$xf."f", 0.000225455) * 100 . $symbol;
}
exit();

  function checkMobile($mobilephone) {
	return preg_match("/^(13|15|18|17)[0-9]{9}$/", $mobilephone);
}

var_dump(checkMobile(18500003333));
var_dump(checkMobile(15500003333));
var_dump(checkMobile(13500003333));
var_dump(checkMobile(17500003333));
var_dump(checkMobile(12500003333));
var_dump(checkMobile(1350003333));

function checkEmail($email) {
	$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9A-Za-z\\-_\\.]+[a-z]{2,3}(\\.[a-z]{2})?)$/i";
	return preg_match($pattern, $email);
}


var_dump(checkEmail('asdfaf@sss.com'));
var_dump(checkEmail('asdfaf@sss.com.cn'));
var_dump(checkEmail('asdsss.xxx.sss_ss.faf@ssss.xxx.sss.ww.ss.com'));
var_dump(checkEmail('asdfaf@sss.co'));
exit();

$arrayLayoutId = array(1=>2,3=>4,5=>6);
//echo implode(',', $arrayLayoutId);

echo date("Y-m-d") - date("Y-m-01");
return;
// phpinfo();
$key_date = date("Y-m-d H:i:00");
echo $key_date . "<br>";
$key_date = date("Y-m-d H:i:00", strtotime($key_date) - 60);
echo $key_date . "<br>";


echo date('Y-m-d', strtotime("2017-02-06") - 86400);
exit();
echo md5(2 . '`　-   `' . md5('985632147') . md5('5483116858d36bd6d1f6c')) . "<Br><br>\r\n";



$date1 = '2017-03-08';$date2 = '2017-07-08';
var_dump($date1 > $date2);var_dump($date2 > $date1);
phpinfo();
$aa = "sss";
$bb = "aaa_bbb";

$arrayAa = explode('_', $aa);
print_r($arrayAa);


$arrayAa = explode('_', $bb);
print_r($arrayAa);

echo md5(md5('123456') . md5('585568')) . "\r\n";

print_r(implode("'.'", $arrayAa));

//print_r(implode("'.'", $aa));