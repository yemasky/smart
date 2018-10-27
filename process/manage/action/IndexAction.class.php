<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace manage;

class IndexAction extends \BaseAction {
	protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
	}

	protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		switch($objRequest->getAction()) {
			case 'login':
				$this->doLogin($objRequest, $objResponse);
			break;
			case 'logout':
				$this->doLogout($objRequest, $objResponse);
			break;
			case 'noPermission':
				$this->doNoPermission($objRequest, $objResponse);
			break;
			case 'setModule':
				$this->doSetModule($objRequest, $objResponse);
			break;
			default:
				$this->doDefault($objRequest, $objResponse);
			break;
		}
	}

	protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		// TODO: Implement method() method.
		$method = $objRequest->method;
		if(!empty($method)) {
			$method = 'doMethod' . ucfirst($method);

			return $this->$method($objRequest, $objResponse);
		}
	}

	public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->check($objRequest, $objResponse);
		$this->service($objRequest, $objResponse);
	}

	/**
	 * 首页显示
	 */
	protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		//赋值
		//设置类别
	}

	/**
	 * 首页显示
	 */
	protected function doNoPermission(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		//赋值
		//设置类别
	}

	protected function doLogin(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$method = $objRequest->method;
		if(!empty($method)) {
			$method = 'doMethod' . ucfirst($method);

			return $this->$method($objRequest, $objResponse);
		}
		//
		$objResponse->setTplName("manage/index/default");
	}

	protected function doLogout(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();

		return $objResponse->successResponse('000001');
	}

	protected function doMethodCheckLogin(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
		//sleep(31);

	}

	protected function doSetModule($objRequest, $objResponse) {
		$method = $objRequest->method;
		if (!empty($method)) {
			return $this->doMethod($objRequest, $objResponse);
		}
		$arrayModule      = \wise\ModuleServiceImpl::instance()->getAllModuleCache();

		$objResponse->arrayModule = $arrayModule;
	}

	protected function doMethodUpdateModule(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayModule = $objRequest->getPost();
        $arrayUpdateModule = $arrayModuleId = '';
        if(!empty($arrayModule)) {
	        $arrayBatchUpdate['key'] = 'module_id';
            foreach ($arrayModule as $field => $arrayData) {
                foreach ($arrayData as $module_id => $value) {
                    if($field == '_module') $field = 'module';
                    if($field == '_action') $field = 'action';
					$arrayModuleId[$module_id] = $module_id;
	                $arrayUpdateModule['field'][$field][$module_id] = $value;
                }
            }
        }
        if(!empty($arrayUpdateModule)) {
	        $whereCriteria = new \WhereCriteria();
	        $whereCriteria->ArrayIN('module_id', $arrayModuleId);
            \wise\ModuleServiceImpl::instance()->batchUpdateModule($arrayUpdateModule, $whereCriteria);
        }

        $arrayModule = \wise\ModuleServiceImpl::instance()->getAllModuleCache(true);
        $objResponse->arrayModule = $arrayModule;
	}
}