<?php
/*
 * auther: cooc
 * email:yemasky@msn.com
 */
declare(strict_types=1);
try {
	require_once ("config.php");
	$objAction = new \wise\Action();
	$objAction->execute();//
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
