<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace member;

class Action {
	protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		//common setting//变量:index_url arrayXxxXxx; function : doXxxx getXxxx
		$objResponse->thisDateTime = getDateTime();
	}

	protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		//default setting
		$module = empty($module) ? 'Index' : ucwords($module);
		//common setting
		$objResponse->home_channel = \Encrypt::instance()->encode('home', getDay());

		$objResponse->setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
		//
		$action_tpl = empty($action) ? 'default' : $action;
		$objResponse->setTplName("wise/" . $module . "/" . $action_tpl);

		$module    = '\member\\' . $module . 'Action';
		$objAction = new $module();
		//
		$objAction->execute($action, $objRequest, $objResponse);//
	}

	public function execute() {
		try {
			set_error_handler("ErrorHandler");//$error_handler =
			$objRequest  = new \HttpRequest();
			$objResponse = new \HttpResponse();

			// 入力检查
			$this->check($objRequest, $objResponse);
			//接入服务
			$this->service($objRequest, $objResponse);
		} catch(\Exception $e) {
			if(__Debug) {
				echo('错误信息: ' . $e->getMessage() . "<br>");
				echo(str_replace("\n", "\n<br>", $e->getTraceAsString()));
			}
			// 错误日志
			logError($e->getMessage(), __MODEL_EXCEPTION);
			logError($e->getTraceAsString(), __MODEL_EMPTY);
		}
	}
}