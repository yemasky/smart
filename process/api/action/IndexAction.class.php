<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace api;


class IndexAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        $this->setDisplay();
        switch($objRequest->getAction()) {
            case 'login':
                $this->login($objRequest, $objResponse);
                break;
            case 'logout':
                $objRequest -> method = 'logout';
                $this->login($objRequest, $objResponse);
                break;
            case 'noPermission':
                $this->doNoPermission($objRequest, $objResponse);
                break;
            case 'excute_success':
                $this->doExcuteSuccess($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    /**
     * 首页显示
     */
    protected function doDefault($objRequest, $objResponse) {
        //赋值
        //设置类别

    }

    protected function doExcuteSuccess($objRequest, $objResponse) {
        //赋值
        //设置类别


    }
    /**
     * 首页显示
     */
    protected function doNoPermission($objRequest, $objResponse) {
        //赋值
        //设置类别
    }

    protected function login($objRequest, $objResponse) {

    }
}