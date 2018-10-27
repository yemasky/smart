<?php

/*
	class.Utilities.php
	auther: 罗驰 
	email:yemasky@msn.com
*/

class Utilities {

	public static function &arrayTurnStr($data) {
		$str = '';
		if(is_array($data)) {
			$str .= "array(";
			foreach($data as $k => $v) {
				if(is_array($v)) {
					$str .= "'$k' => ";
					$str .= self::arrayTurnStr($v) . ',';
				} else {
					$str .= "'$k' => '$v',";
				}
			}
			$str = trim($str, ',') . ")";
		}

		return $str;
	}

	public static function &addslashesStr($data) {
		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($val)) {
					$data[$key] = self::addslashesStr($val);
				} else {
					$data[$key] = addslashes($val);
				}
			}
		} else {
			$data = addslashes($data);
		}

		return $data;
	}

	public static function toHtml($data) {//&
		if(empty($data)) return null;
		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($val)) {
					$data[$key] = self::toHtml($val);
				} else {
					//$data[$key] = nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($val)));
					$data[$key] = htmlspecialchars($val);
				}
			}
		} else {
			//$data = nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($data)));
			$data = htmlspecialchars($data);
		}

		return $data;
	}

	public static function formatXmlSpecialChar($str) {
		return str_replace("'", '&apos;', str_replace('"', '&quot;', str_replace('<', '&lt;', str_replace('>', '&gt;', str_replace('&', '&amp;', $str)))));
	}

	public static function checkIdentity($identity) {
		if(strlen($identity) < 18) return false;
		$weight        = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);  //十七位数字本体码权重
		$validate      = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');//mod11,对应校验码字符值
		$arrayIdentity = str_split($identity, 1);
		$sum           = 0;
		for($i = 0; $i < 17; $i++) {
			$sum = $sum + $arrayIdentity[$i] * $weight[$i];
		}
		$mode = $sum % 11;

		return $validate[$mode] == $arrayIdentity[17];
	}

	public static function getOrderNumber($billid, $length = 16, $type = 'DATE') {
		if($type == 'DATE') {
			$date    = date("Ymd");//20180205
			$str_num = '99999999999999999999';
			$str_num = substr($str_num, 0, $length - 8);
			$billno  = $date . ($billid % $str_num);//
			if(strlen($billno) == $length) return $billno;
			$billno    = $billno . '0';
			$id_lenght = strlen($billno);
			for($i = $id_lenght; $i < $length; $i++) {
				$billno .= rand(1, 9);
			}

			return $billno;
		}
		if(strlen($billid) >= $length) return $billid;
		$billno    = $billid . '0';
		$id_lenght = strlen($billno) + 1;
		for($i = $id_lenght; $i <= $length; $i++) {
			$billno .= rand(1, 9);
		}

		return $billno;
	}

	public static function checkEmail($email) {
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9A-Za-z\\-_\\.]+[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		return preg_match($pattern, $email);
	}

	public static function checkMobile($mobilephone) {
		return preg_match("/^(13|15|18|17)[0-9]{9}$/", $mobilephone);
	}
}

?>