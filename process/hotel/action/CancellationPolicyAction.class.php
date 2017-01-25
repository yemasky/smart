<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class CancellationPolicyAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        $objResponse -> back_lis_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSetting']['view'])));
    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'edit':
                $this->doEdit($objRequest, $objResponse);
                break;
            case 'add':
                $this->doAdd($objRequest, $objResponse);
                break;
            case 'delete':
                $this->doDelete($objRequest, $objResponse);
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

        $objResponse -> arrayData = '';

        //赋值
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['cancellationPolicy']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['cancellationPolicy']['edit'])));
        //设置类别
    }

   protected function view($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        $objResponse->view = 1;
        $objResponse->setTplName("hotel/modules_roomsSetting_edit");
    }

    protected function doAdd($objRequest, $objResponse) {
        $room_id = decode($objRequest -> room_id);
        if(!empty($room_id)) {
            throw new \Exception('系统异常！');
        }
        $this->doEdit($objRequest, $objResponse);
        //更改tpl
    }

    protected function doEdit($objRequest, $objResponse) {

    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();

        return $this->successResponse('删除成功');
    }

}