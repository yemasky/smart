<?php
/*
	class.Utilities.php
	auther: 罗驰 
	email:yemasky@msn.com
*/

class Alipay {

	public static function payButtom($out_trade_no, $subject, $price, $body, $show_url) {
		require_once("alipay/alipay.config.php");
		require_once("alipay/lib/alipay_submit.class.php");

		/**************************请求参数**************************/

		//支付类型
		$payment_type = "1";
		//必填，不能修改
		//服务器异步通知页面路径
		$notify_url = "http://www.5dwww.com/create_partner_trade_by_buyer-PHP-UTF-8/notify_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = "http://www.5dwww.com/create_partner_trade_by_buyer-PHP-UTF-8/return_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//商户订单号
		//*$out_trade_no = $_POST['WIDout_trade_no'];
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		//*$subject = $_POST['WIDsubject'];
		//必填
		//付款金额
		//*$price = $_POST['WIDprice'];
		//必填
		//商品数量
		$quantity = "1";
		//必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
		//物流费用
		$logistics_fee = "0.00";
		//必填，即运费
		//物流类型
		$logistics_type = "EXPRESS";
		//必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		//物流支付方式
		$logistics_payment = "SELLER_PAY";
		//必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		//订单描述
		//*$body = $_POST['WIDbody'];
		//商品展示地址
		//*$show_url = $_POST['WIDshow_url'];
		//需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html
		//收货人姓名
		$receive_name = '客户';//$_POST['WIDreceive_name'];
		//如：张三
		//收货人地址
		$receive_address = '网络无需地址';//$_POST['WIDreceive_address'];
		//如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
		//收货人邮编
		$receive_zip = '100000';//$_POST['WIDreceive_zip'];
		//如：123456
		//收货人电话号码
		$receive_phone = '18000000000';//$_POST['WIDreceive_phone'];
		//如：0571-88158090
		//收货人手机号码
		$receive_mobile = '18000000000';//$_POST['WIDreceive_mobile'];
		//如：13312341234


		/************************************************************/

//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "create_partner_trade_by_buyer",
			"partner" => trim($alipay_config['partner']),
			"seller_email" => trim($alipay_config['seller_email']),
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"price"	=> $price,
			"quantity"	=> $quantity,
			"logistics_fee"	=> $logistics_fee,
			"logistics_type"	=> $logistics_type,
			"logistics_payment"	=> $logistics_payment,
			"body"	=> $body,
			"show_url"	=> $show_url,
			"receive_name"	=> $receive_name,
			"receive_address"	=> $receive_address,
			"receive_zip"	=> $receive_zip,
			"receive_phone"	=> $receive_phone,
			"receive_mobile"	=> $receive_mobile,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		return $html_text;

	}

	public static function payButtomsss($out_trade_no, $subject,$total_fee, $body, $show_url) {
		require_once("alipay/alipay.config.php");
		require_once("alipay/lib/alipay_submit.class.php");

		/**************************请求参数**************************/

		//支付类型
		$payment_type = "1";
		//必填，不能修改
		//服务器异步通知页面路径
		$notify_url = "http://www.5dwww.com/create_direct_pay_by_user-PHP-UTF-8/notify_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = "http://www.5dwww.com/create_direct_pay_by_user-PHP-UTF-8/return_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//商户订单号
		//*$out_trade_no = $_POST['WIDout_trade_no'];
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		//*$subject = $_POST['WIDsubject'];
		//必填
		//付款金额
		//*$total_fee = $_POST['WIDtotal_fee'];
		//必填
		//订单描述
		//*$body = $_POST['WIDbody'];
		//商品展示地址
		//*$show_url = $_POST['WIDshow_url'];
		//需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
		//防钓鱼时间戳
		$anti_phishing_key = "";
		//若要使用请调用类文件submit中的query_timestamp函数
		//客户端的IP地址
		$exter_invoke_ip = "";
		//非局域网的外网IP地址，如：221.0.0.1


		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" => trim($alipay_config['partner']),
			"seller_email" => trim($alipay_config['seller_email']),
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"total_fee"	=> $total_fee,
			"body"	=> $body,
			"show_url"	=> $show_url,
			"anti_phishing_key"	=> $anti_phishing_key,
			"exter_invoke_ip"	=> $exter_invoke_ip,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
		return $html_text;
	}
}
?>