<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace hotel;


class HotelAction extends \BaseAction {
    protected function check($objRequest, $objResponse) {
        $objResponse -> navigation = 'hotelSetting';
        $objResponse -> setTplValue('navigation', 'hotelSetting');
        $objResponse -> back_lis_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['view'])));
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
        if(decode($objRequest->hotel_id) > 0) {
            $this->view($objRequest, $objResponse);
            return;
        }
        $pn = empty($objRequest->pn) ? 1 : $objRequest->pn;
        $pn_rows = $objRequest->pn_rows;

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('employee_id'=>$objResponse->arrayLoginEmployeeInfo['employee_id'],
            '!='=>array('hotel_id'=>0));
        $parameters['module'] = encode(decode($objRequest->module));
        $arrayPageHotelId = EmployeeService::instance()->pageEmployeeHotel($conditions, $pn, $pn_rows, $parameters);
        $arrayHotel = null;
        if(!empty($arrayPageHotelId['list_data'])) {
            $stringHotelId = '';
            foreach($arrayPageHotelId['list_data'] as $k => $v) {
                $stringHotelId .= $v['hotel_id'] . "','";
            }
            $stringHotelId = trim($stringHotelId, ",'");
            $conditions['where'] = array('IN'=>array('hotel_id'=>$stringHotelId));
            $arrayHotel = HotelService::instance()->getHotel($conditions);
            foreach ($arrayHotel as $k => $v) {
                //\BaseUrlUtil::Url(array('module'=>encode($arrayHotelModules[$i]['modules_id'])));
                $arrayHotel[$k]['view_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(decode($objRequest->module)), 'hotel_id'=>encode($arrayHotel[$k]['hotel_id'])));
                $arrayHotel[$k]['edit_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['edit']), 'hotel_id'=>encode($arrayHotel[$k]['hotel_id'])));
                $arrayHotel[$k]['delete_url'] =
                    \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['delete']), 'hotel_id'=>encode($arrayHotel[$k]['hotel_id'])));;
            }
        }

        //赋值
        $objResponse -> setTplValue("addHotelUrl",
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['add']))));
        $objResponse -> setTplValue("arrayHotel", $arrayHotel);
        $objResponse -> setTplValue("page", $arrayPageHotelId['page']);
        $objResponse -> setTplValue("pn", $pn);
        //设置类别
    }

    protected function doAdd($objRequest, $objResponse) {
        $objRequest -> hotel_id = encode(0);
        $this->doEdit($objRequest, $objResponse);
        $objResponse -> setTplValue("hotel_update_url",
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['add']))));
        $objResponse->view = 'add';
        $objResponse->setTplName("hotel/modules_hotel_edit");
        return;
        /*
        $arrayPostValue= $objRequest->getPost();
        $hotel_id = 0;
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {


        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>0);
        $arrayHotel = HotelService::instance()->DBcache(ModulesConfig::$cacheKey['hotel']['hotel_default_id'])->getHotel($conditions);

        $conditions['where'] = array('employee_id'=>$objResponse->arrayLoginEmployeeInfo['employee_id']);
        $arrayEmployeeCompany = EmployeeService::instance()->getEmployeeCompany($conditions);
        $arrayCompany = null;
        if(!empty($arrayEmployeeCompany)) {
            $stringCompanyId = '';
            foreach($arrayEmployeeCompany as $k => $v) {
                $stringCompanyId .= $v['company_id'] . "','";
            }
            $stringCompanyId = trim($stringCompanyId, ",'");
            $conditions['where'] = array('IN'=>array('company_id'=>$stringCompanyId));
            $arrayCompany = CompanyService::instance()->getCompany($conditions);
        }
        $arrarHotelAttribute = HotelService::instance()->getAttribute($hotel_id);
        sort($arrarHotelAttribute, SORT_NUMERIC);
        //赋值
        $objResponse -> update_success = 0;
        $objResponse -> setTplValue("arrayAttribute", $arrarHotelAttribute);
        $objResponse -> setTplValue("arrayEmployeeCompany", $arrayCompany);
        $objResponse -> setTplValue("arrayDataInfo", $arrayHotel[0]);

        //更改tpl*/
    }

    protected function view($objRequest, $objResponse) {
        $this->doEdit($objRequest, $objResponse);
        $objResponse->view = '1';
        $objResponse->setTplName("hotel/modules_hotel_edit");
    }

    protected function doEdit($objRequest, $objResponse) {
        $hotel_id = decode($objRequest->hotel_id);
        if($objRequest -> act == 'updateImages') {
            $this->setDisplay();
            if($hotel_id > 0) {
                $url = $objRequest->url;

                $conditions = DbConfig::$db_query_conditions;

                $conditions['where'] = array('hotel_id'=>$hotel_id,
                    'hotel_images_path'=>$url);
                $arrayHotelImage = HotelService::instance()->getHotelImages($conditions);
                if (!empty($arrayHotelImage)) {
                    return $this->errorResponse('已经添加此图片');
                }
                $hotel_images_id = HotelService::instance()->saveHotelImages(array('hotel_id'=>$hotel_id,
                    'hotel_images_path'=>$objRequest->url,
                    'hotel_images_filesize'=>0,
                    'hotel_images_add_date'=>getDay(),
                    'hotel_images_add_time'=>getTime()));
                return $this->successResponse('保存成功！', array('hotel_images_id'=>$hotel_images_id));
            } else {
                return $this->errorResponse('保存失败，ID无法识别！');

            }

        }

        $arrayPostValue= $objRequest->getPost();

        $objResponse->update_success = 0;
        if(!empty($arrayPostValue) && is_array($arrayPostValue)) {
            $this->setDisplay();
            if($hotel_id > 0) {
                if($arrayPostValue['hotel_wifi'] == '1') {
                    $arrayPostValue['hotel_wifi'] = true;
                } else {
                    $arrayPostValue['hotel_wifi'] = false;
                }
                HotelService::instance()->updateHotel(array('hotel_id' => $hotel_id), $arrayPostValue);
                $objResponse->update_success = 1;
                $this->setDisplay();
                //$hotel_id = encode($hotel_id);
                //HotelService::updateHotel(array('hotel_id'=>$hotel_id), array('hotel_is_delet'=>true));
                return $this->successResponse('保存酒店成功', array('hotel_id'=>encode($hotel_id)));
            } else {
                $arrayPostValue['hotel_add_date'] = date("Y-m-d");
                $arrayPostValue['hotel_add_time'] = getTime();
                $hotel_id = HotelService::instance()->saveHotel($arrayPostValue);
                if($hotel_id > 0) {
                    EmployeeService::instance()->saveEmployeeDepartment(array('company_id'=>$objResponse->arrayLoginEmployeeInfo['company_id'],
                        'employee_id'=>$objResponse->arrayLoginEmployeeInfo['employee_id'],
                        'hotel_id'=>$hotel_id));
                } else {
                    throw new \Exception('添加失败！');
                }
                if(empty($hotel_id)) {
                    return $this->errorResponse('保存失败，请检查数据！');
                }
                return $this->successResponse('保存酒店成功', array('hotel_id'=>encode($hotel_id)));
            }
        }

        $conditions = DbConfig::$db_query_conditions;
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $arrayHotel = HotelService::instance()->getHotel($conditions, '*');

        $conditions['where'] = array('employee_id'=>$objResponse->arrayLoginEmployeeInfo['employee_id']);
        $arrayEmployeeCompany = EmployeeService::instance()->getEmployeeCompany($conditions);
        $arrayCompany = null;

        if(!empty($arrayEmployeeCompany)) {
            $stringCompanyId = '';
            foreach($arrayEmployeeCompany as $k => $v) {
                $stringCompanyId .= $v['company_id'] . "','";
            }
            $stringCompanyId = trim($stringCompanyId, ",'");
            $conditions['where'] = array('IN'=>array('company_id'=>$stringCompanyId));
            $arrayCompany = CompanyService::instance()->getCompany($conditions);
        }

        $arrarHotelAttribute = HotelService::instance()->getAttribute($hotel_id);
        sort($arrarHotelAttribute, SORT_NUMERIC);
        //赋值
        //图片
        $conditions['where'] = array('hotel_id'=>$hotel_id);
        $objResponse -> arrayDataImages = HotelService::instance()->getHotelImages($conditions);
        $objResponse->view = '0';
        $objResponse -> setTplValue("arrayAttribute", $arrarHotelAttribute);
        $objResponse -> setTplValue("arrayDataInfo", $arrayHotel[0]);
        $objResponse -> setTplValue("location_province", $arrayHotel[0]['hotel_province']);
        $objResponse -> setTplValue("location_city", $arrayHotel[0]['hotel_city']);
        $objResponse -> setTplValue("location_town", $arrayHotel[0]['hotel_town']);
        $objResponse -> hotel_update_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['edit']), 'hotel_id'=>encode($hotel_id)));
        $objResponse -> upload_images_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['hotel']['upload_type'],'hotel_id'=>encode($hotel_id)));
        $objResponse -> upload_manager_img_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['upload']['uploadImages']),
                'upload_type'=>ModulesConfig::$modulesConfig['hotel']['upload_type'],'act'=>'manager_img','hotel_id'=>encode($hotel_id)));
        $objResponse -> add_hotel_attr_val_url =
            \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['saveAttrValue'])));
        $objResponse -> setTplValue("arrayEmployeeCompany", $arrayCompany);
        $objResponse -> hotel_id = empty($hotel_id) ? '' : encode($hotel_id);
        $objResponse -> step = $objRequest -> step;
        //
    }

    protected function doDelete($objRequest, $objResponse) {
        $this->setDisplay();
        $hotel_id = decode($objRequest->hotel_id);
        if(empty($hotel_id)) {
            return $this->errorResponse('操作失败，酒店ID不正确！');
        }
        HotelService::instance()->updateHotel(array('hotel_id'=>$hotel_id), array('hotel_is_delet'=>true));
        return $this->successResponse('删除酒店成功');
    }

    protected function doSaveAttrValue($objRequest, $objResponse) {
        $this->setDisplay();
        $hotel_id = decode($objRequest->hotel_id);
        $arrayPostValue= $objRequest->getPost();
        if($hotel_id > 0) {
            $redirect_url =  \BaseUrlUtil::Url(array('module'=>encode(ModulesConfig::$modulesConfig['hotel']['edit']),
                'step'=>'upload_images','hotel_id'=>encode($hotel_id)));
            if(!empty($arrayPostValue)) {
                HotelService::instance()->deleteHotelAttrValue(array('hotel_id'=>$hotel_id));
                $arrayInsertValue = array();
                $i = 0;
                $arrarAttrHash = array();
                foreach ($arrayPostValue as $key => $val) {
                    foreach ($val as $k => $v) {
                        if(empty($v)) continue;
                        foreach($v as $attr => $attrValue) {
                            if(empty($attrValue)) continue;
                            if(isset($arrarAttrHash[$k][$attrValue])) continue;//消除相同属性的属性值
                            $arrayInsertValue[$i]['hotel_id'] = $hotel_id;
                            $arrayInsertValue[$i]['hotel_attribute_father_id'] = $key;
                            $arrayInsertValue[$i]['hotel_attribute_id'] = $k;
                            $arrayInsertValue[$i]['hotel_attribute_value'] = $attrValue;
                            //消除相同属性的属性值
                            $arrarAttrHash[$k][$attrValue] = 0;
                            $i++;
                        }

                    }
                }
                if(!empty($arrayInsertValue)) {
                    HotelService::instance()->batchSaveHotelAttrValue($arrayInsertValue);
                    return $this->successResponse('保存酒店及属性成功', '', $redirect_url);
                }
            }
            return $this->successResponse('保存酒店成功。', '', $redirect_url);
        }
        return $this->errorResponse('保存失败，未识别ID');

    }

}