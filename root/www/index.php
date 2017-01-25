<?php
/*
 * auther: cooc
 * email:yemasky@msn.com
 */
try {
	require_once ("config.php");
	$app = '\www';//子系统
	if(isset($_REQUEST['app']))
		$app = '\\' . ucwords($_REQUEST['app']);
	$model = '\IndexAction';//模块
	if(isset($_REQUEST['model']))
		$model = '\\' . ucwords($_REQUEST['model']) . 'Action';
	$action = NULL;
	if(isset($_REQUEST['action']))
		$action = $_REQUEST['action'];
} catch(Exception $e) {
	echo ('error: ' . $e->getMessage() . "<br>");
	echo (str_replace("\n","\n<br>",$e->getTraceAsString()));
}

try {
	$app = $app . $model;
	$objAction = new $app();
	$objAction->execute($action);
} catch(Exception $e) {
	logError($e->getMessage(),__MODEL_EXCEPTION);
	logError($e->getTraceAsString(),__MODEL_EMPTY);
	if(__Debug) {
		echo ('error: ' . $e->getMessage() . "<br>");
		echo (str_replace("\n","\n<br>",$e->getTraceAsString()));
	} else {
		$objAction = new NotFound();
		$objAction->execute();
	}
}
?>