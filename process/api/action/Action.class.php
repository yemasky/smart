<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace api;


class Action extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        $this->setDisplay();
        $requestModule = $objRequest -> module;
        //default setting
        $action = $objRequest->action;
        $module = '\api\IndexAction';
        if(!empty($requestModule)) $module = '\api\\' . ucwords($requestModule) . 'Action';

        $objAction = new $module();
        $objAction->execute($action, $objRequest, $objResponse);//
    }
}