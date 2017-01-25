<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class RoomsLayoutAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        $objResponse -> back_lis_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['view'])));
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
            case 'saveAttrValue':
                $this->doSaveAttrValue($objRequest, $objResponse);
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
        if(decode($objRequest->room_layout_id) > 0) {
            $this->view($objRequest, $objResponse);
            return;
        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$objResponse->arrayLoginEmployeeInfo['hotel_id']);
        $conditions['order'] = 'room_layout_valid DESC';
        $arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions);
        if(!empty($arrayRoomLayout)) {
            foreach ($arrayRoomLayout as $i => $v) {
                $arrayRoomLayout[$i]['view_url'] =
                    \BaseUrlUtil::Url(array('module'=>$objRequest->module, 'room_layout_id'=>encode($v['room_layout_id'])));
                $arrayRoomLayout[$i]['edit_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['edit']),
                        'room_layout_id'=>encode($v['room_layout_id'])));
                $arrayRoomLayout[$i]['delete_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['delete']),
                        'room_layout_id'=>encode($v['room_layout_id'])));
            }
        }
        //赋值
        $objResponse -> arrayDataInfo = $arrayRoomLayout;
        $objResponse -> add_room_layout_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['add'])));
        $objResponse -> arayRoomType = ModulesConfig::$modulesConfig['roomsSetting']['room_type'];
        //设置类别
    }

    protected function view($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        $objResponse->view = '1';
        $objResponse->setTplName("hotel/modules_roomsLayout_edit");
    }

    protected function doAdd($objRequest, $objResponse) {
        $room_layout_id = decode($objRequest -> room_layout_id);
        if(!empty($room_layout_id)) {
            throw new \Exception('系统异常！');
        }
        $this->doEdit($objRequest, $objResponse);
        //
        $objResponse -> add_room_layout_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['add'])));
        $objResponse->view = 'add';
        //更改tpl
    }

    protected function doEdit($objRequest, $objResponse) {
        $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
        if($objRequest -> act == 'updateLayoutImages') {
            $this->setDisplay();
            $url = $objRequest->url;
            $room_layout_id = decode($objRequest->room_layout_id);
            if(empty($room_layout_id)) return $this->errorResponse('错误的ID号，请检查');

            $conditions = DbConfig::$db_query_conditions;
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                'room_layout_images_path'=>$url, 'room_layout_id'=>$room_layout_id);
            $arrayLayoutImage = RoomService::instance()->getRoomLayoutImages($conditions);
            if (!empty($arrayLayoutImage)) {
                return $this->errorResponse('此房型已经添加此图片');
            }
            $room_layout_images_id = RoomService::instance()->saveRoomLayoutImages(array('hotel_id'=>$hotel_id,
                'room_layout_images_path'=>$objRequest->url,
                'room_layout_id'=>$room_layout_id,
                'room_layout_images_filesize'=>0,
                'room_layout_images_add_date'=>getDay(),
                'room_layout_images_add_time'=>getTime()));
            return $this->successResponse('', array('room_layout_images_id'=>$room_layout_images_id));
        }

        if($objRequest -> act == 'setRoomLayoutRoom') {
            $this->setDisplay();
            $room_layout_id = decode($objRequest->room_layout_id);
            $room_id = decode($objRequest->room_id);
            $checked = $objRequest->checked;
            $extra_bed = $objRequest->extra_bed > 0 ? $objRequest->extra_bed : 0;
            $max_people = $objRequest->max_people > 0 ? $objRequest->max_people : 0;
            $max_children = $objRequest->max_children > 0 ? $objRequest->max_children : 0;

            if(empty($room_layout_id) || empty($room_id)) return $this->errorResponse('错误的ID号，请检查');

            $conditions = DbConfig::$db_query_conditions;
            $conditions['where'] = array('room_layout_id'=>$room_layout_id, 'room_id'=>$room_id, 'hotel_id'=>$hotel_id);
            if($checked == 'true') {
                $arrayRoomLayoutRoom = RoomService::instance()->getRoomLayoutRoom($conditions);
                $arrayRoomData['hotel_id'] = $hotel_id;
                $arrayRoomData['room_id'] = $room_id;
                $arrayRoomData['room_layout_id'] = $room_layout_id;
                $arrayRoomData['room_layout_room_max_people'] = $max_people;
                $arrayRoomData['room_layout_room_max_children'] = $max_children;
                $arrayRoomData['room_layout_room_extra_bed'] = $extra_bed;
                if(empty($arrayRoomLayoutRoom)) {
                    RoomService::instance()->saveRoomLayoutRoom($arrayRoomData);
                } else {
                    RoomService::instance()->updateRoomLayoutRoom($conditions['where'], $arrayRoomData);
                }
                $conditions['where'] = array('hotel_id'=>$hotel_id, 'room_id'=>$room_id);
                $arrayRoomInfo = RoomService::instance()->getRoom($conditions);
                if(empty($arrayRoomInfo[0]['temp_max_people'])) {
                    $where = array('hotel_id'=>$hotel_id,'room_id'=>$room_id);
                    $arrayUpdate['temp_max_people'] = $max_people;$arrayUpdate['temp_max_children'] = $max_children;$arrayUpdate['temp_extra_bed'] = $extra_bed;
                    RoomService::instance()->updateRoom($where, $arrayUpdate);
                }

            } elseif($checked == 'false') {
                RoomService::instance()->deleteRoomLayoutRoom($conditions['where']);
            }
            return $this->successResponse('设置成功');
        }


        $room_layout_id = decode($objRequest -> room_layout_id);
        $arrayPostValue= $objRequest->getPost();

        $conditions = DbConfig::$db_query_conditions;
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $this->setDisplay();
            $conditions['where'] = array('hotel_id'=>$hotel_id,
                'room_layout_name'=>$arrayPostValue['room_layout_name']);
            $arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions);
            if(!empty($arrayRoomLayout)) {
                if(empty($room_layout_id)) {
                    return $this->errorResponse('有重复的售卖房型名字，请检查！');
                } else {
                    if($arrayRoomLayout[0]['room_layout_id'] == $room_layout_id) {
                    } else {
                        return $this->errorResponse('有重复的售卖房型名字，请检查！');
                    }
                }
            }
            $arrayPostValue['room_bed_type_wide'] = json_encode($arrayPostValue['room_bed_type_wide']);
            if($arrayPostValue['room_bed_type'] != 'standard') $arrayPostValue['room_bed_type_wide'] = '';
            if ($room_layout_id > 0) {
                RoomService::instance()->updateRoomLayout(array('room_layout_id' => $room_layout_id), $arrayPostValue);
            } else {
                $arrayPostValue['hotel_id'] = $hotel_id;
                $arrayPostValue['room_layout_add_date'] = date("Y-m-d");
                $arrayPostValue['room_layout_add_time'] = getTime();
                $room_layout_id = RoomService::instance()->saveRoomLayout($arrayPostValue);
            }
            $redirect_url =
                \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['edit']),
                    'room_layout_id'=>encode($room_layout_id)));
            return $this->successResponse('保存售卖房型成功', array('room_layout_id'=>encode($room_layout_id)), $redirect_url);
        }

        if(empty($room_layout_id)) {
            $conditions['where'] = array('room_layout_id'=>0);
        } else {
            $conditions['where'] = array('room_layout_id'=>$room_layout_id, 'hotel_id'=>$hotel_id);
        }
        $arrayRoomLayout = RoomService::instance()->getRoomLayout($conditions);

        //房型图片
        $conditions['where'] = array('room_layout_id'=>$room_layout_id, 'hotel_id'=>$hotel_id);
        $objResponse -> arrayDataImages = RoomService::instance()->getRoomLayoutImages($conditions);
        //属性
        $arrayAttribute = RoomService::instance()->getAttribute($hotel_id, 'room');
        $arrayAttributeValue = RoomService::instance()->getRoomLayoutAttrValue($conditions, '*', 'room_layout_attribute_id', true);
        //print_r($arrayAttribute);
        //print_r($arrayAttributeValue);
        if(!empty($arrayAttribute)) {
            foreach($arrayAttribute as $attrKey => $arrayChild) {
                foreach($arrayChild['children'] as $i => $arrayAttr) {
                    $arrayAttribute[$attrKey]['children'][$i]['values'] = array();
                    if($arrayAttr['room_layout_attribute_is_appoint'] == '1') {
                        if($arrayAttr['room_layout_attribute_value_type'] == 'radio') {
                            $arrayAppoint = explode('|', $arrayAttr['room_layout_attribute_value_type_default']);
                            $arrayHash = '';
                            $room_layout_attribute_id = decode($arrayAttr['room_layout_attribute_id']);
                            if(isset($arrayAttributeValue[$room_layout_attribute_id])) {
                                $arrayValues = $arrayAttributeValue[$room_layout_attribute_id];
                                foreach($arrayValues as $attr => $attrVal) {
                                    $arrayHash[$attrVal['room_layout_attribute_value']] = 1;
                                }
                            }
                            foreach($arrayAppoint as $attr => $attrVal) {
                                $arrayAttribute[$attrKey]['children'][$i]['values'][$attr]['check'] = 0;
                                $arrayAttribute[$attrKey]['children'][$i]['values'][$attr]['name'] = $attrVal;
                                if(isset($arrayHash[$attrVal])) $arrayAttribute[$attrKey]['children'][$i]['values'][$attr]['check'] = 1;
                            }
                        }
                    } else {
                        if(isset($arrayAttributeValue[decode($arrayAttr['room_layout_attribute_id'])])) {
                            $arrayValues = $arrayAttributeValue[decode($arrayAttr['room_layout_attribute_id'])];
                            foreach($arrayValues as $attr => $attrVal) {
                                $arrayAttribute[$attrKey]['children'][$i]['values'][] = $attrVal;
                            }
                        }
                    }
                }
            }
        }
        sort($arrayAttribute, SORT_NUMERIC);
        //房型的房子
        $conditions['where'] = array('room_layout_id'=>$room_layout_id, 'hotel_id'=>$hotel_id);
        $arrayRoomLayoutRoom = RoomService::instance()->getRoomLayoutRoom($conditions, '*', 'room_id');
        //真实客房 房间状态 -1 删除 0 正常 1维修 2不进行使用
        $conditions['where'] = array('room_type'=>'1', 'hotel_id'=>$hotel_id);//'room_status'=>'0',
        $arrayRoom = RoomService::instance()->getRoom($conditions);
        if(!empty($arrayRoom)) {
            foreach($arrayRoom as $i => $arrayValue) {
                $arrayRoom[$i]['checked'] = 0;
                //$arrayRoom[$i]['room_layout_room_extra_bed'] = $arrayRoom[$i]['room_layout_room_max_people'] = $arrayRoom[$i]['room_layout_room_max_children'] = 0;
                $arrayRoom[$i]['room_layout_room_extra_bed'] = $arrayRoom[$i]['temp_extra_bed'];
                $arrayRoom[$i]['room_layout_room_max_people'] = $arrayRoom[$i]['temp_max_people'];
                $arrayRoom[$i]['room_layout_room_max_children'] = $arrayRoom[$i]['temp_max_children'];
                if(isset($arrayRoomLayoutRoom[$arrayValue['room_id']])){
                    $arrayRoom[$i]['checked'] = $room_layout_id;
                    $arrayRoom[$i]['room_layout_room_extra_bed'] = $arrayRoomLayoutRoom[$arrayValue['room_id']]['room_layout_room_extra_bed'];
                    $arrayRoom[$i]['room_layout_room_max_people'] = $arrayRoomLayoutRoom[$arrayValue['room_id']]['room_layout_room_max_people'];
                    $arrayRoom[$i]['room_layout_room_max_children'] = $arrayRoomLayoutRoom[$arrayValue['room_id']]['room_layout_room_max_children'];
                }
                $arrayRoom[$i]['room_id'] = str_replace('=', '',encode($arrayRoom[$i]['room_id']));
            }
        }
        //房型类别
        $conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$hotel_id)));
        $arrayRoomLayoutType = RoomService::instance()->getRoomLayoutType($conditions, '*', 'room_layout_type_id');

        //room type
        //$conditions['where'] = array('IN'=>array('hotel_id'=>array(0,$hotel_id)));
        //$arrayRoomType = RoomService::instance()->getRoomType($conditions, '*', 'room_type_id');
        //赋值
        $objResponse -> view = '0';
        $objResponse -> orientations = ModulesConfig::$modulesConfig['roomsLayout']['orientations'];
        $objResponse -> room_layout_id = encode($room_layout_id);
        $objResponse -> arrayAttribute = $arrayAttribute;
        $objResponse -> arrayDataInfo = $arrayRoomLayout[0];
        $objResponse -> arrayRoom = $arrayRoom;//真实房
        $objResponse -> arrayRoomLayoutType = $arrayRoomLayoutType;

        $objResponse -> layoutHouseConfig = json_encode(ModulesConfig::$roomLayout['layoutHouseConfig']);
        $objResponse -> add_room_layout_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['edit']),
                'room_layout_id'=>$objRequest->room_layout_id));
        $objResponse -> add_room_layout_attr_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['saveAttrValue'])));
        $objResponse -> upload_images_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['roomsLayout']['upload_type'],
                'room_layout_id'=>encode($room_layout_id)));
        $objResponse -> upload_manager_img_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['roomsLayout']['upload_type'],
                'room_layout_id'=>encode($room_layout_id),'act'=>'manager_img'));
        $objResponse -> room_attribute_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsAttribute']['view'])));
        $objResponse -> step = $objRequest -> step;
        //
        $objResponse -> setTplName("hotel/modules_roomsLayout_edit");
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
    }

    protected function doSaveAttrValue($objRequest, $objResponse) {
        $this->setDisplay();
        $room_layout_id = decode($objRequest->room_layout_id);
        $arrayPostValue= $objRequest->getPost();
        if($room_layout_id > 0) {
            if(!empty($arrayPostValue)) {
                $redirect_url =  \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['roomsLayout']['edit']),
                    'step'=>'upload_images','room_layout_id'=>encode($room_layout_id)));
                $hotel_id = $objResponse->arrayLoginEmployeeInfo['hotel_id'];
                RoomService::instance()->deleteRoomLayoutAttrValue(array('room_layout_id'=>$room_layout_id,
                    'hotel_id'=>$hotel_id));
                $arrayInsertValue = array();
                $i = 0;
                $arrarAttrHash = array();
                foreach ($arrayPostValue as $key => $val) {
                    //$key = decode($key);
                    foreach ($val as $k => $v) {
                        if(empty($v)) continue;
                        foreach($v as $attr => $attrValue) {
                            if(empty($attrValue)) continue;
                            if(isset($arrarAttrHash[$k][$attrValue])) continue;//消除相同属性的属性值
                            $arrayInsertValue[$i]['hotel_id'] = $hotel_id;
                            $arrayInsertValue[$i]['room_layout_id'] = $room_layout_id;
                            $arrayInsertValue[$i]['room_layout_attribute_father_id'] = decode($key);
                            $arrayInsertValue[$i]['room_layout_attribute_id'] = decode($k);
                            $arrayInsertValue[$i]['room_layout_attribute_value'] = $attrValue;
                            //消除相同属性的属性值
                            $arrarAttrHash[$k][$attrValue] = 0;
                            $i++;
                        }
                    }
                }
                if(!empty($arrayInsertValue)) {
                    //print_r($arrayInsertValue);
                    RoomService::instance()->batchSaveRoomLayoutAttrValue($arrayInsertValue);
                    return $this->successResponse('保存房型及房型属性成功！', '', $redirect_url);
                }
            }
            return $this->successResponse('保存房型成功！', '', $redirect_url);
        }
        return $this->errorResponse('保存失败！未识别');
    }

}