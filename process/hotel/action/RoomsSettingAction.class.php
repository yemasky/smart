<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class RoomsSettingAction extends \BaseAction {
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
        if(decode($objRequest->room_id) > 0) {
            $this->view($objRequest, $objResponse);
            return;
        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id']);
        $conditions['order'] = 'room_type ASC, room_mansion ASC, room_floor ASC, room_number ASC, room_name ASC, room_id ASC ';
        $arrayRoom = RoomService::instance()->getRoom($conditions);
        $conditions['order'] = '';
        $arrayRoomHash = null;
        if(!empty($arrayRoom)) {
            foreach ($arrayRoom as $i => $v) {
                $v['url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSetting']['view']), 'room_id'=>encode($v['room_id'])));
                $arrayRoomHash[$v['room_type']][] = $v;
            }
        }

        //room type
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$objResponse->arrayLoginEmployeeInfo['hotel_id'])));
        $arrayRoomType = RoomService::instance()->getRoomType($conditions, '*', 'room_type_id');
        //赋值
        $objResponse -> arrayRoomType = $arrayRoomType;
        $objResponse -> arrayDataInfo = $arrayRoomHash;
        $objResponse -> add_room_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSetting']['add']), 'room_id'=>$objRequest->room_id));
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
        $room_id = decode($objRequest -> room_id);
        $arrayPostValue= $objRequest->getPost();

        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            if ($room_id > 0) {
                RoomService::instance()->updateRoom(array('room_id' => $room_id), $arrayPostValue);
            } else {
                $arrayPostValue['hotel_id'] = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                $arrayPostValue['room_add_date'] = date("Y-m-d");
                $arrayPostValue['room_add_time'] = getTime();
                $room_id = RoomService::instance()->saveRoom($arrayPostValue);
            }
            $redirect_url =
                \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSetting']['view']), 'room_id'=>encode($room_id)));
            $this->setDisplay();
            return $this->successResponse('保存酒店房间成功', '', $redirect_url);
        }

        $conditions = DbConfig::$db_query_conditions;
        if(empty($room_id)) {
            $conditions['where'] = array('room_id'=>0);
        } else {
            $conditions['where'] = array('room_id'=>$room_id, 'hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id']);
        }
        $arrayRoom = RoomService::instance()->getRoom($conditions);
        //room type
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$objResponse->arrayLoginEmployeeInfo['hotel_id'])));
        $arrayRoomType = RoomService::instance()->getRoomType($conditions, '*', 'room_type_id');
        //
        $objResponse -> orientations = ModulesConfig::$modulesConfig['roomsLayout']['orientations'];
        $objResponse -> arrayDataInfo = $arrayRoom[0];
        $objResponse -> arrayRoomType = $arrayRoomType;
        $objResponse -> view = 0;
        $objResponse -> add_room_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsSetting']['edit']), 'room_id'=>$objRequest->room_id));
        $objResponse->setTplName("hotel/modules_roomsSetting_edit");
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
        $room_id = decode($objRequest->room_id);
        if(empty($room_id)) {
            logError('$room_id:' . $room_id . ',操作失败，参数不正确！');
            return $this->errorResponse('操作失败，参数不正确！');
        }
        RoomService::instance()->updateRoom(array('room_id'=>$room_id), array('room_status'=>'-1'));
        return $this->successResponse('删除成功');
    }

}