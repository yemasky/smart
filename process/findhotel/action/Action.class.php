<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace findhotel;


class Action extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        $this->setDisplay();

        //default setting
        $action = $objRequest->action;
        $module = '\findhotel\IndexAction';
        $module_action_tpl = 'index';
        //$modules_module = 'index';

        //$arrayLaguage = CommonService::instance()->getPageModuleLaguage($modules_module);
        //$objResponse->setTplValue('arrayLaguage', $arrayLaguage);

        $objResponse -> setTplValue("__Meta", \BaseCommon::getMeta('index', '管理后台', '管理后台', '管理后台'));
        $objResponse->setTplName("findhotel/modules_" . $module_action_tpl);
        $objAction = new $module();
        $objAction->execute($action, $objRequest, $objResponse);//
    }
}