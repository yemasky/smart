<?php
/*
 * func.Common.php
 * auther: cooc
 * email:yemasky@msn.com
 */
if(!defined("INC_FUNC_COMMON")) {
	define("INC_FUNC_COMMON", "1");
	
	define("__MODEL_EMPTY", "");
	define("__MODEL_EXCEPTION", "Exception");
	
	date_default_timezone_set('PRC');
	if(isset($_SERVER['argc']) && $_SERVER['argc'] >= 0) {
		$arrVariables = $_SERVER['argv'];
		$_REQUEST = NULL;
		if(!empty($arrVariables[0])) {
			$arrParameter = null;
			foreach($arrVariables as $k => $v) {
				$arrVariable = explode('=', $v);
				if(!isset($arrVariable[1]))
					$arrVariable[1] = NULL;
					$arrParameter[$arrVariable[0]] = $arrVariable[1];
			}
			$_REQUEST = $arrParameter;
		}
	}
	//function __autoload($class){
	function process_autoload($class){
		$len = strlen($class) - 1;
		for($loop = $len; $loop >= 0; $loop--) {
			if($class[$loop] >= 'A' && $class[$loop] <= 'Z') {
				break;
			}
		}
		$execute_type = substr($class, $loop);
		$execute_dir = 'base/';
		$pos = strpos($class, '\\');
		if($pos != false) {
			$execute_dir = substr($class, 0, $pos) . '/';
			$class = '' . substr($class, $pos + 1);
		}

		switch($execute_type){
			case "Action" :
				$execute_dir = "process/" . $execute_dir . "action/";
				break;
			case "Dao" :
				$execute_dir = "process/" . $execute_dir . "dao/";
				break;
			case "Common" :
				$execute_dir = "process/" . $execute_dir . "common/";
				break;
			case "Service" :
				$execute_dir = "process/" . $execute_dir . "service/";
				break;
			case "Impl" :
				$execute_dir = "process/" . $execute_dir . "service/impl/";
				break;
			case "Util" :
				$execute_dir = "process/" . $execute_dir . "utils/";
				break;
			case "Tool" :
				$execute_dir = "process/" . $execute_dir . "tool/";
				break;
			case "Config" :
				$execute_dir = "process/" . $execute_dir . "config/";
				break;
			default :
				$execute_dir = 'libs/';
				break;
		}
		$classes_file = __ROOT_PATH . $execute_dir . $class . ".class.php";
		if(file_exists($classes_file)) {
			include_once ($classes_file);
		} else {
			//throw new Exception("unable to load class: $class in $classes_file");
			// trigger_error("unable to load class: $class", E_USER_ERROR);
			// class $class extends BaseAction; //{
			// }
		}
	}
	spl_autoload_register("process_autoload");

	function getDateTime($d = 0){
		return date("Y-m-d H:i:s", strtotime("$d HOUR")); // GMT+8
	}

	function getTime($d = 0){
		return date("H:i:s", strtotime("$d HOUR")); // GMT+8
	}
	function getYear() {
		return date("Y");
	}
	function getMonth() {
		return date("m");
	}
	function getDay($d = 0) {
		return date("Y-m-d", strtotime("$d HOUR"));
	}
    function getToDay($d = 0) {
        return date("j", strtotime("$d HOUR"));
    }

	function getHis(){
		return date("His");
	}

	function getDateTimeId($l = 6, $d = 8){
		$time = date("YmdHis", strtotime("+$d HOUR")); // GMT+8
		$time .= trim(substr(microtime(), 2, $l));
		return $time;
	}

	function logError($message, $model = "", $level = "ERROR"){
		writeLog("#error", $message);
	}

	function logDebug($message, $model = "", $level = "DEBUG"){
		writeLog("#debug", $message);
	}
	
	function sqlDebugLog($message, $model = "", $level = "DEBUG") {
		if(__Debug) writeLog("#sql.debug", $message);
	}

	function writeLog($filename, $msg){
		if(defined('__WWW_LOGS_PATH')) {
			$fp = fopen(__WWW_LOGS_PATH . $filename . '.' . date("z") . '.log', "a+");
		} else {
			$fp = fopen(__ROOT_LOGS_PATH . $filename . '.' . date("z") . '.log', "a+");
		}
		$uri = '';
		if(isset($_SERVER['REQUEST_URI']))
			$uri = $_SERVER['REQUEST_URI'];
		$msg = getDateTime() . " >>> " . $uri . ' >> ' . $msg;
		fwrite($fp, "$msg\r\n");
		fclose($fp);
	}

	function checkNum($id){
		if(!is_numeric($id)) {
			throw new Exception("parameter error: no the num -> " . $id);
		}
	}

	function keyED($txt, $encrypt_key){
		$encrypt_key = md5($encrypt_key);
		$ctr = 0;
		$tmp = "";
		for($i = 0; $i < strlen($txt); $i++) {
			if($ctr == strlen($encrypt_key))
				$ctr = 0;
			$tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
			$ctr++;
		}
		return $tmp;
	}

	function encode($txt, $key = __WEB){
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000));
		$ctr = 0;
		$tmp = "";
		for($i = 0; $i < strlen($txt); $i++) {
			if($ctr == strlen($encrypt_key))
				$ctr = 0;
			$tmp .= substr($encrypt_key, $ctr, 1) . (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
			$ctr++;
		}
		return str_replace('+', ' ', base64_encode(keyED($tmp, $key)));
	}

	function decode($txt, $key = __WEB){
		$txt = keyED(base64_decode(str_replace(' ', '+', $txt)), $key);
		$tmp = "";
		for($i = 0; $i < strlen($txt); $i++) {
			$md5 = substr($txt, $i, 1);
			$i++;
			$tmp .= (substr($txt, $i, 1) ^ $md5);
		}
		return $tmp;
	}

	function errorLog($msg){
		logError("[err]$msg;request ip:" . onLineIp() . ";url:" . getUrl() . ";ReferUrl:" . getReferUrl() . ";time:" . getDateTime());
		// throw new Exception("[err]$msg;request ip:".onLineIp().";url:".getUrl().";ReferUrl:".getReferUrl().";time:".getDateTime());
	}

	function getMicrotime(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function onLineIp(){
		if(isset($_SERVER['HTTP_CLIENT_IP'])) {
			$onlineip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}

	function getHost(){
		return $_SERVER['HTTP_HOST'];
	}

	function getUrl(){
		if($_SERVER["SERVER_PORT"] == 80) {
			return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		return 'http://' . $_SERVER['HTTP_HOST'] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER['REQUEST_URI'];
	}

	function getReferUrl(){
		if(!isset($_SERVER['HTTP_REFERER']))
			return NULL;
		if(($url = $_SERVER['HTTP_REFERER']) == NULL) {
			return '/';
		}
		return $url;
	}

	function conutf8($v, $e = 'GBK', $c = 'UTF-8'){
		return iconv($e, $c, $v);
	}

	function alert($mess, $go = -1){
		if(!headers_sent()) {
			header("Content-type: text/html; charset=" . __CHARSET);
		}
		$script = $go == 0 ? "" : "history.go(" . $go . ");";
		echo "<script>alert('" . $mess . "');$script</script>";
		flush();
		if(!empty($script)) {
			exit();
		}
	}

	function exception_handler($e){
		// echo "Uncaught exception: " , $exception->getMessage(), "\n";
		if(__Debug) {
			print_r($e->getMessage());
			print_r($e->getTraceAsString());
		}
		logError($e->getMessage(), __MODEL_EXCEPTION);
		logError($e->getTraceAsString(), __MODEL_EMPTY);
	}

	function ErrorHandler($errno, $errstr, $errfile, $errline){
		if(!(error_reporting() & $errno)) { // This error code is not included in error_reporting
			return;
		}
		$msg = '';
		switch($errno){
			case E_USER_ERROR :
				$msg .= "ERROR [$errno] $errstr ;";
				$msg .= "  Fatal error on line $errline in file $errfile";
				$msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ") ;";
				// $msg .= "Aborting..;";
				// exit(1);
				break;
			
			case E_USER_WARNING :
				$msg .= "WARNING [$errno] $errstr on line $errline in file $errfile;";
				break;
			
			case E_USER_NOTICE :
				$msg .= "NOTICE [$errno] $errstr on line $errline in file $errfile;";
				break;
			
			default :
				$msg .= "Unknown error type: [$errno] $errstr on line $errline in file $errfile;";
				break;
		}
		if(__Debug) {
			print_r($msg);
		}
		writeLog('#errorHandler', $msg);
		/* Don't execute PHP internal error handler */
		return true;
	}

	function cutString($str, $len, $start = 0){
		if(strlen($str) <= $len) {
			return $str;
		}
		for($loop = 0; $loop < $len; $loop++) {
			if(ord($str[$loop]) > 224) {
				$loop += 2;
				continue;
			}
			if(ord($str[$loop]) > 192) {
				$loop++;
			}
		}
		/*
		 * if($loop == $len + 1) {
		 * $len--;
		 * }
		 */
		return substr($str, 0, $loop);
	}

	function page($pn, $all_page_num, $list_data, $parameters, $show_pages = 5) {
		if(empty($all_page_num)) return array('page'=>0, 'list_data'=>'');
		$arrayResultPage = "";
		$mod_pn = $pn % $show_pages;
		if($mod_pn != 0) {
		} else {
			$mod_pn = $show_pages;
		}
		if($pn > $show_pages) {
			$arrayResultPage[0]['pn'] = $pn - $mod_pn - $show_pages + 1;
			$parameters['pn'] = $arrayResultPage[0]['pn'];
			$arrayResultPage[0]['url'] = \BaseUrlUtil::Url($parameters);
		} else {
			$arrayResultPage[0]['pn'] = '';
			$arrayResultPage[0]['url'] = '#no_page';
		}
		if(($all_page_num -  $pn) < $show_pages) {
			if($mod_pn <= ($all_page_num % $show_pages)) {
				$show_pages = $all_page_num % $show_pages;
			}
		}
		for($i = 1; $i <= $show_pages; $i++) {
			$arrayResultPage[$i]['pn'] = $pn - $mod_pn + $i;
			$parameters['pn'] = $arrayResultPage[$i]['pn'];
			$arrayResultPage[$i]['url'] = \BaseUrlUtil::Url($parameters);
		}
		if($pn < ($all_page_num - $show_pages)) {
			$arrayResultPage[$i]['pn'] = $arrayResultPage[$i -1]['pn'] + 1;
			$parameters['pn'] = $arrayResultPage[$i]['pn'];
			$arrayResultPage[$i]['url'] = \BaseUrlUtil::Url($parameters);
		} else {
			$arrayResultPage[$i]['pn'] = '';
			$arrayResultPage[$i]['url'] = '#no_page';
		}
		return array('page'=>$arrayResultPage, 'list_data'=>$list_data);
	}

	function getModelByUri(){
		$model = 'index'; // REDIRECT_URL
		if(isset($_SERVER['REDIRECT_URL'])) {
			$arrScript = explode('/', substr($_SERVER['REDIRECT_URL'], 0, -5));
			$model = $arrScript[count($arrScript) - 1];
		} elseif(isset($_SERVER['REQUEST_URI'])) {
			$arrScript = explode('?', substr($_SERVER['REQUEST_URI']));
			$script = $arrScript[0];
			$arrScript = explode('/', substr($script, 0, -5));
			$model = $arrScript[count($arrScript) - 1];
		}
		return $model;
	}
	// 排序
	function cmp_func($arrSort, $arrOrder){
		global $order;
		if($arrSort['is_dir'] && !$arrOrder['is_dir']) {
			return -1;
		} else if(!$arrSort['is_dir'] && $arrOrder['is_dir']) {
			return 1;
		} else {
			if($order == 'size') {
				if($arrSort['filesize'] > $arrOrder['filesize']) {
					return 1;
				} else if($arrSort['filesize'] < $arrOrder['filesize']) {
					return -1;
				} else {
					return 0;
				}
			} else if($order == 'type') {
				return strcmp($arrSort['filetype'], $arrOrder['filetype']);
			} else {
				return strcmp($arrSort['filename'], $arrOrder['filename']);
			}
		}
	}

	function uuid( $opt = true ){       //  Set to true/false as your default way to do this.
		if( function_exists('com_create_guid') ){
			if( $opt ){ return com_create_guid(); }
			else { return trim( com_create_guid(), '{}' ); }
		}
		else {
			mt_srand( (double)microtime() * 10000 );    // optional for php 4.2.0 and up.
			$charid = strtoupper( md5(uniqid(rand(), true)) );
			$hyphen = chr( 45 );    // "-"
			$left_curly = $opt ? chr(123) : "";     //  "{"
			$right_curly = $opt ? chr(125) : "";    //  "}"
			$uuid = $left_curly
					. substr( $charid, 0, 8 ) . $hyphen
					. substr( $charid, 8, 4 ) . $hyphen
					. substr( $charid, 12, 4 ) . $hyphen
					. substr( $charid, 16, 4 ) . $hyphen
					. substr( $charid, 20, 12 )
					. $right_curly;
			return $uuid;
		}
	}

	function order_number($order_number, $len = 12) {
		$order_number = '0' . $order_number;
		for($i = strlen($order_number) + 1; $i<=$len; $i++) {
			$order_number = rand(1, 9) . $order_number;
		}
		return $order_number;
	}
}
?>
