<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class RoomsSellLayoutAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {

    }

    protected function service($objRequest, $objResponse) {
        switch($objRequest->getAction()) {
            case 'add':
                $this->doAdd($objRequest, $objResponse);
                break;
            case 'edit':
                $this->doEdit($objRequest, $objResponse);
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
        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'room_layout_valid'=>'1');
        $arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions, '*', 'room_layout_id');
        //售卖房型
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id']);
        $conditions['order'] = 'room_sell_layout_valid DESC';
        $arrayRoomSellLayout = RoomService::instance()->getRoomSellLayout($conditions, '*', 'room_layout_id', true);
        //赋值
        $objResponse -> arrayDataInfo = $arrayRoomSellLayout;
        $objResponse -> arrayRoomLayout = $arrayRoomLayout;
        $objResponse -> add_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSellLayout']['add'])));
        $objResponse -> edit_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSellLayout']['edit'])));
        $objResponse -> delete_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSellLayout']['delete'])));
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> room_sell_layout_id = 0;
        $this->doEdit($objRequest, $objResponse);
    }

    protected function doEdit($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $room_sell_layout_id = $objRequest -> room_sell_layout_id;
            $url = \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSellLayout']['view'])));
            if($room_sell_layout_id > 0) {
                $where = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'room_sell_layout_id'=>$room_sell_layout_id);
                RoomService::instance()->updateRoomSellLayout($where, $arrayPostValue);
                return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
            } else {
                unset($arrayPostValue['room_sell_layout_id']);
                $arrayPostValue['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                $arrayPostValue['room_sell_add_date'] = getDay();
                $arrayPostValue['room_sell_add_time']= getTime();
                //判断重名
                $conditions = DbConfig::$db_query_conditions;
                $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id'],'room_sell_layout_name'=>$arrayPostValue['room_sell_layout_name']);
                $arrayResult = RoomService::instance()->getRoomSellLayout($conditions);
                if(!empty($arrayResult)) {
                    return $this->errorResponse('不允许售卖房型重名，请检查！');
                }

                RoomService::instance()->saveRoomSellLayout($arrayPostValue);
                return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value'],'',$url);
            }
        }
        return $this->errorResponse($objResponse->arrayLaguage['save_nothings']['page_laguage_value']);
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
        $arrayPostValue= $objRequest->getPost();
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            return $this->successResponse($objResponse->arrayLaguage['save_success']['page_laguage_value']);
        }
        return $this->errorResponse($objResponse->arrayLaguage['save_nothings']['page_laguage_value']);
    }

}