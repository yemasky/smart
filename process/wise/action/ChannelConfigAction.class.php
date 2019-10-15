<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;
/*
//Channel 配置
*/
class ChannelConfigAction extends \BaseAction {
    protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {

    }

    protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            case 'room' :
                $this->doSetRoom($objRequest, $objResponse);
                break;
            case 'layout' :
                $this->doSetLayout($objRequest, $objResponse);
                break;
            case 'CuisineEditSave' :
                $this->doCuisineEditSave($objRequest, $objResponse);
                break;
            default:
                $this->doDefault($objRequest, $objResponse);
                break;
        }
    }

    protected function tryexecute(\HttpRequest $objRequest, \HttpResponse $objResponse) {//事务回滚
        CommonServiceImpl::instance()->rollback();
    }

    protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        // TODO: Implement method() method.
        $method = $objRequest->method;
        if (!empty($method)) {
            $method = 'doMethod' . ucfirst($method);
            $this->$method($objRequest, $objResponse);
            return true;
        }
        return false;
    }

    public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->check($objRequest, $objResponse);
        $this->service($objRequest, $objResponse);
    }

    /**
     * 首页显示
     */
    protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        if (empty($channel_id) || !is_numeric($channel_id)) {
            return $objResponse->setResponse('error', '000008');
        }
        $method = $objRequest->method;

        $arrayChannelList = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
        if (isset($arrayChannelList[$channel_id])) {
            $arrayChannel = $arrayChannelList[$channel_id];
        } else {
            return $objResponse->setResponse('error', '000009');
        }
        //
        $arrayConfig = ModulesConfig::$channel_config[$arrayChannel['channel']];
        //属性

        $channel_config = $arrayConfig[$method];
        $arrayAttribute = ChannelServiceImpl::instance()->getAttribute($company_id, $method);
        //默认属性
        $arrayDefaultAttr = isset(ModulesConfig::$attr_default_value[$method]) ? ModulesConfig::$attr_default_value[$method] : '';
        //赋值
        $objResponse->channel_id = encode($channel_id, getDay());
        //设置URL
        $action                          = empty($method) ? 'default' : $method;
        $objResponse->channel_config_url = ModuleServiceImpl::instance()->getEncodeModuleId('ChannelConfig', $action);
        $objResponse->imagesUploadUrl    = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'images');
        $objResponse->imagesManagerUrl   = ModuleServiceImpl::instance()->getEncodeModuleId('Upload', 'manager');

        //设置 nav 企业管理->配置企业->配置属性
        $arrayEmployeeModule = ModuleServiceImpl::instance()->getAllModuleCache();
        $objResponse->nav    = CommonServiceImpl::instance()->getNavbar($arrayEmployeeModule, $objResponse->getResponse('_self_module'));

        if ($arrayChannel['channel'] == 'Hotel') {
            //赋值
            $objResponse->channel_config_name = $channel_config;
            $objResponse->channel_config_key  = $method;
            //
            $objResponse->arrayDefaultAttr = json_encode($arrayDefaultAttr);
            $objResponse->arrayChannel     = json_encode($arrayChannel);
            $objResponse->arrayConfig      = json_encode($arrayConfig);
            $objResponse->arrayAttribute   = json_encode($arrayAttribute);
            $objResponse->room_url         = ModuleServiceImpl::instance()->getEncodeModuleId('ChannelConfig', 'room');
            $objResponse->setTplName('wise/ChannelConfig/' . $arrayChannel['channel']);
        } else {
            $this->setDisplay();
            //赋值
            $commonData['channel_config_name'] = $channel_config;
            $commonData['channel_config_key']  = $method;
            //
            $commonData['arrayDefaultAttr'] = $arrayDefaultAttr;
            $commonData['arrayChannel']     = $arrayChannel;
            $commonData['arrayConfig']      = $arrayConfig;
            $commonData['arrayAttribute']   = $arrayAttribute;
            //
            $commonData['channel_id']         = $objResponse->channel_id;
            $commonData['channel_config_url'] = $objResponse->channel_config_url;
            $commonData['imagesUploadUrl']    = $objResponse->imagesUploadUrl;
            $commonData['imagesManagerUrl']   = $objResponse->imagesManagerUrl;
            $objResponse->commonData          = $commonData;
        }
        $this->doMethod($objRequest, $objResponse);
    }

    protected function doMethodLayout(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objRequest->channel_config = 'layout';
        $objRequest->hashKey        = 'item_id';
        $arrayDataList              = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        $objResponse->itemList      = json_encode($arrayDataList);
    }

    protected function doMethodRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $objRequest->channel_config = 'room';
        $objRequest->hashKey        = 'item_attr2_value';
        $objRequest->childrenHash   = 'item_attr1_value';
        $objRequest->toHashArray    = true;
        $arrayDataList              = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
        $objResponse->itemList      = json_encode($arrayDataList);
    }

    protected function doSetRoom(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method                     = $objRequest->method;
        $objRequest->channel_config = 'room';
        if ($method == 'save') {
            $describe = $objRequest->getInput('describe');
            if (!empty($describe)) {
                $describe = implode(',', $describe);
                $objRequest->setInput('describe', $describe);
            }
            $item_id = $this->doSaveChannelItem($objRequest, $objResponse);
            if ($item_id == false) return false;
        }
        $objRequest->hashKey      = 'item_attr2_value';
        $objRequest->childrenHash = 'item_attr1_value';
        $objRequest->toHashArray  = true;
        $arrayDataList            = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);

        return $objResponse->successResponse('000001', $arrayDataList);
    }

    protected function doSetLayout(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method                     = $objRequest->method;
        $objRequest->channel_config = 'layout';
        if ($method == 'attribute') {
            $arrayAttribute = $this->getAttributeValue($objRequest, $objResponse);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayAttribute);
        }
        if ($method == 'save') {
            $arrayAttr       = $objRequest->getInput('attr_value');
            $arrayMultipe    = $objRequest->getInput('channel_item_multipe');//
            $arrayItemImages = $objRequest->getInput('item_images');
            $objRequest->unsetInput('attr_value');
            $objRequest->unsetInput('channel_item_multipe');
            $objRequest->unsetInput('item_images');
            CommonServiceImpl::instance()->startTransaction();
            $objRequest->setInput('item_type', 'category');
            $item_id = $this->doSaveChannelItem($objRequest, $objResponse);
            if ($item_id == false) return false;
            $objRequest->unsetInput(null);
            //add attr
            $objRequest->arrayAttr = $arrayAttr;
            //add images
            $objRequest->arrayItemImages = $arrayItemImages;
            //add multipe
            $objRequest->arrayMultipe = $arrayMultipe;
            $objRequest->item_id      = $item_id;
            $this->doSaveChannelItemExtend($objRequest, $objResponse);
            CommonServiceImpl::instance()->commit();
        }

        $objRequest->hashKey = 'item_id';
        $arrayDataList       = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);

        return $objResponse->successResponse(ErrorCodeConfig::$successCode, $arrayDataList);
    }

    public function doSaveChannelItem(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        $arrayData  = $objRequest->getInput();
        if (empty($arrayData)) {
            $objResponse->errorResponse('000009');

            return false;
        }
        if (is_numeric($arrayData['item_id']) && $arrayData['item_id']) {//update
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('item_id', $arrayData['item_id'])
                ->EQ('channel_config', $objRequest->channel_config);
            $item_id = $arrayData['item_id'];
            unset($arrayData['item_id']);
            ChannelServiceImpl::instance()->updateChannelItem($whereCriteria, $arrayData);

            return $item_id;
        } else {
            unset($arrayData['item_id']);
            $arrayData['channel_config'] = $objRequest->channel_config;
            $arrayData['channel_id']     = $channel_id;
            $arrayData['company_id']     = $company_id;
            //检查是否重复
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('item_name', $arrayData['item_name']);
            if (isset($arrayData['item_type'])) $whereCriteria->EQ('item_type', $arrayData['item_type']);
            $arrayDataList = ChannelServiceImpl::instance()->getChannelItem($whereCriteria, 'item_id');
            if (!empty($arrayDataList)) {
                $objResponse->errorResponse('000004', ['item_name', $arrayData['item_name']], '保存失败,重复数据,请检查!');
                return false;
            }
            $arrayData['add_datetime'] = getDateTime();
            return ChannelServiceImpl::instance()->saveChannelItem($arrayData);
        }
    }

    //保存属性值
    public function doSaveChannelItemExtend(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        $item_id    = $objRequest->item_id;

        $arrayAttr       = $objRequest->arrayAttr;
        $arrayMultipe    = $objRequest->arrayMultipe;
        $arrayItemImages = $objRequest->arrayItemImages;
        $arrayInsertData = [];
        $k               = 0;
        $sql_attr_type   = array();
        if (!empty($arrayAttr)) {//属性值
            foreach ($arrayAttr as $attribute_id => $value) {
                if (is_array($value)) {
                    foreach ($value as $j => $v) {
                        if (empty($v)) continue;
                        $arrayInsertData[$k]['attribute_id']     = $attribute_id;
                        $arrayInsertData[$k]['company_id']       = $company_id;
                        $arrayInsertData[$k]['channel_id']       = $channel_id;
                        $arrayInsertData[$k]['item_category_id'] = $item_id;
                        $arrayInsertData[$k]['item_id']          = $item_id;
                        $arrayInsertData[$k]['item_images_src']  = '';
                        $arrayInsertData[$k]['attr_value']       = $v;
                        $arrayInsertData[$k]['attr_en_value']    = '';
                        $arrayInsertData[$k]['attr_type']        = 'attr_value';
                        $k++;
                    }
                } else {
                    if (empty($value)) continue;
                    $arrayInsertData[$k]['attribute_id']     = $attribute_id;
                    $arrayInsertData[$k]['company_id']       = $company_id;
                    $arrayInsertData[$k]['channel_id']       = $channel_id;
                    $arrayInsertData[$k]['item_category_id'] = $item_id;
                    $arrayInsertData[$k]['item_id']          = $item_id;
                    $arrayInsertData[$k]['item_images_src']  = '';
                    $arrayInsertData[$k]['attr_value']       = $value;
                    $arrayInsertData[$k]['attr_en_value']    = '';
                    $arrayInsertData[$k]['attr_type']        = 'attr_value';
                    $k++;
                }
            }
            $sql_attr_type[] = 'attr_value';
        }
        if (!empty($arrayMultipe)) {//客房
            foreach ($arrayMultipe as $room_item_id => $value) {
                if ($room_item_id == 0) continue;
                $arrayInsertData[$k]['attribute_id']     = '0';
                $arrayInsertData[$k]['company_id']       = $company_id;
                $arrayInsertData[$k]['channel_id']       = $channel_id;
                $arrayInsertData[$k]['item_category_id'] = $item_id;
                $arrayInsertData[$k]['item_id']          = $value;
                $arrayInsertData[$k]['item_images_src']  = '';
                $arrayInsertData[$k]['attr_value']       = '';
                $arrayInsertData[$k]['attr_en_value']    = '';
                $arrayInsertData[$k]['attr_type']        = 'multipe_room';
                $k++;
            }
            $sql_attr_type[] = 'multipe_room';
        }
        if (!empty($arrayItemImages)) {//图片
            foreach ($arrayItemImages as $image_url => $value) {
                $arrayInsertData[$k]['attribute_id']     = '0';
                $arrayInsertData[$k]['company_id']       = $company_id;
                $arrayInsertData[$k]['channel_id']       = $channel_id;
                $arrayInsertData[$k]['item_category_id'] = $item_id;
                $arrayInsertData[$k]['item_id']          = $item_id;
                $arrayInsertData[$k]['item_images_src']  = $value['url'];
                $arrayInsertData[$k]['attr_value']       = $value['name'];
                $arrayInsertData[$k]['attr_en_value']    = '';
                $arrayInsertData[$k]['attr_type']        = 'images';
                $k++;
            }
            $sql_attr_type[] = 'images';
        }

        if (!empty($sql_attr_type)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('item_category_id', $item_id);
            $whereCriteria->ArrayIN('attr_type', $sql_attr_type);
            ChannelServiceImpl::instance()->deleteAttributeValue($whereCriteria);
            if (!empty($arrayInsertData)) ChannelServiceImpl::instance()->batchInsertAttrValue($arrayInsertData);
        }
    }

    protected function getAttributeValue(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id       = decode($objRequest->c_id, getDay());
        $item_id          = $objRequest->item_id;
        $item_category_id = $objRequest->item_category_id;
        $whereCriteria    = new \WhereCriteria();
        $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id);
        if (is_numeric($item_id) && $item_id > 0) $whereCriteria->EQ('item_id', $item_id);
        if (is_numeric($item_category_id) && $item_category_id > 0) $whereCriteria->EQ('item_category_id', $item_category_id);
        $field = 'item_category_id, item_id, item_images_src, attribute_id, attr_value, attr_en_value, attr_type';
        $whereCriteria->setHashKey('attr_type')->setMultiple(true);
        $arrayAttribute = ChannelServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
        return $arrayAttribute;
    }
    //**********************************************餐厅**********************************//
    //菜式类别
    protected function doMethodCuisineCategory(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id                 = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id                 = decode($objRequest->c_id, getDay());
        $objRequest->channel_config = 'cuisineCategory';//菜式类别

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        $arrayDataList = CuisineServiceImpl::instance()->getCuisine($whereCriteria);

        $successService         = new \SuccessService();
        $commonData             = $objResponse->commonData;
        $commonData['itemList'] = $arrayDataList;
        $successService->setData($commonData);
        return $objResponse->successServiceResponse($successService);
    }

    //菜式配置
    protected function doMethodCuisine(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = decode($objRequest->c_id, getDay());

        $objRequest->channel_config = 'cuisine';//菜式

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '0');
        $arrayDataList = CuisineServiceImpl::instance()->getCuisine($whereCriteria);
        //类别
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '1');
        $arrayCategoryDataList = CuisineServiceImpl::instance()->getCuisine($whereCriteria, 'cuisine_id,cuisine_name,cuisine_en_name');
        //
        $successService                 = new \SuccessService();
        $commonData                     = $objResponse->commonData;
        $commonData['itemList']         = $arrayDataList;
        $commonData['itemCategoryList'] = $arrayCategoryDataList;
        $successService->setData($commonData);
        return $objResponse->successServiceResponse($successService);
    }

    protected function doMethodTable(\HttpRequest $objRequest, \HttpResponse $objResponse) {

    }

    protected function doMethodDelivery(\HttpRequest $objRequest, \HttpResponse $objResponse) {

    }

    protected function doCuisineEditSave(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
        $method     = $objRequest->method;
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        if ($method == 'attribute') {
            $arrayAttribute = $this->getCuisineAttributeValue($objRequest, $objResponse);
            return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayAttribute);
        }
        $cuisine_category_id = $objRequest->cuisine_category_id;
        if ($method == 'save') {
            $cuisine_is_category = $objRequest->cuisine_is_category;
            $cuisine_id          = $objRequest->cuisine_id;
            $arrayAttr           = $objRequest->getInput('attr_value');
            $arrayItemImages     = $objRequest->getInput('item_images');
            CommonServiceImpl::instance()->startTransaction();
            if ($cuisine_is_category) {//类别
                $arrayCuisineCategory['cuisine_name']         = $objRequest->cuisine_name;
                $arrayCuisineCategory['cuisine_en_name']      = $objRequest->cuisine_en_name;
                $arrayCuisineCategory['cuisine_specialty']    = $objRequest->cuisine_specialty;
                $arrayCuisineCategory['cuisine_en_specialty'] = $objRequest->cuisine_en_specialty;
                $arrayCuisineCategory['image_src']            = $objRequest->image_src;
                $arrayCuisineCategory['valid']                = $objRequest->valid;
                if (empty($cuisine_id)) {//新数据
                    $arrayCuisineCategory['company_id']          = $company_id;
                    $arrayCuisineCategory['channel_id']          = $channel_id;
                    $arrayCuisineCategory['cuisine_category_id'] = '0';
                    $arrayCuisineCategory['cuisine_is_category'] = '1';
                    $cuisine_id                                  = CuisineServiceImpl::instance()->saveCuisine($arrayCuisineCategory);
                    $whereCriteria                               = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    CuisineServiceImpl::instance()->updateCuisine($whereCriteria, ['cuisine_category_id' => $cuisine_id]);
                } else {//修改
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    CuisineServiceImpl::instance()->updateCuisine($whereCriteria, $arrayCuisineCategory);
                }
                $cuisine_category_id = $cuisine_id;
            } else {//菜式
                $arrayCuisine['cuisine_name']         = $objRequest->cuisine_name;
                $arrayCuisine['cuisine_en_name']      = $objRequest->cuisine_en_name;
                $arrayCuisine['cuisine_specialty']    = $objRequest->cuisine_specialty;
                $arrayCuisine['cuisine_en_specialty'] = $objRequest->cuisine_en_specialty;
                $arrayCuisine['image_src']            = $objRequest->image_src;
                $arrayCuisine['valid']                = $objRequest->valid;
                if (empty($cuisine_id)) {//新数据
                    $arrayCuisine['company_id']          = $company_id;
                    $arrayCuisine['channel_id']          = $channel_id;
                    $arrayCuisine['cuisine_category_id'] = '0';
                    $arrayCuisine['cuisine_is_category'] = '1';
                    $cuisine_id                          = CuisineServiceImpl::instance()->saveCuisine($arrayCuisine);
                    $whereCriteria                       = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    CuisineServiceImpl::instance()->updateCuisine($whereCriteria, ['cuisine_category_id' => $cuisine_id]);
                } else {//修改
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    CuisineServiceImpl::instance()->updateCuisine($whereCriteria, $arrayCuisine);
                }
                $cuisine_category_id = $cuisine_id;
            }
            $sql_attr_type = array();
            $k             = 0;
            if (!empty($arrayAttr)) {//属性值
                foreach ($arrayAttr as $attribute_id => $value) {
                    if (is_array($value)) {
                        foreach ($value as $j => $v) {
                            if (empty($v)) continue;
                            $arrayInsertData[$k]['attribute_id']        = $attribute_id;
                            $arrayInsertData[$k]['company_id']          = $company_id;
                            $arrayInsertData[$k]['channel_id']          = $channel_id;
                            $arrayInsertData[$k]['cuisine_category_id'] = $cuisine_id;
                            $arrayInsertData[$k]['cuisine_id']          = $cuisine_id;
                            $arrayInsertData[$k]['cuisine_images_src']  = '';
                            $arrayInsertData[$k]['attr_value']          = $v;
                            $arrayInsertData[$k]['attr_en_value']       = '';
                            $arrayInsertData[$k]['attr_type']           = 'attr_value';
                            $k++;
                        }
                    } else {
                        if (empty($value)) continue;
                        $arrayInsertData[$k]['attribute_id']        = $attribute_id;
                        $arrayInsertData[$k]['company_id']          = $company_id;
                        $arrayInsertData[$k]['channel_id']          = $channel_id;
                        $arrayInsertData[$k]['cuisine_category_id'] = $cuisine_category_id;
                        $arrayInsertData[$k]['cuisine_id']          = $cuisine_id;
                        $arrayInsertData[$k]['cuisine_images_src']  = '';
                        $arrayInsertData[$k]['attr_value']          = $value;
                        $arrayInsertData[$k]['attr_en_value']       = '';
                        $arrayInsertData[$k]['attr_type']           = 'attr_value';
                        $k++;
                    }
                }
                $sql_attr_type[] = 'attr_value';
            }
            if (!empty($arrayItemImages)) {//图片
                foreach ($arrayItemImages as $image_url => $value) {
                    $arrayInsertData[$k]['attribute_id']        = '0';
                    $arrayInsertData[$k]['company_id']          = $company_id;
                    $arrayInsertData[$k]['channel_id']          = $channel_id;
                    $arrayInsertData[$k]['cuisine_category_id'] = $cuisine_category_id;
                    $arrayInsertData[$k]['cuisine_id']          = $cuisine_id;
                    $arrayInsertData[$k]['cuisine_images_src']  = $value['url'];
                    $arrayInsertData[$k]['attr_value']          = $value['name'];
                    $arrayInsertData[$k]['attr_en_value']       = '';
                    $arrayInsertData[$k]['attr_type']           = 'images';
                    $k++;
                }
                $sql_attr_type[] = 'images';
            }
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
            if ($cuisine_is_category) {
                $whereCriteria->EQ('cuisine_category_id', $cuisine_id);
            } else {
                $whereCriteria->EQ('cuisine_id', $cuisine_id);
            }
            //$whereCriteria->ArrayIN('attr_type', $sql_attr_type);
            CuisineServiceImpl::instance()->deleteAttributeValue($whereCriteria);
            if (!empty($arrayInsertData)) CuisineServiceImpl::instance()->batchInsertAttrValue($arrayInsertData);
            CommonServiceImpl::instance()->commit();
        }

        return $objResponse->successResponse(ErrorCodeConfig::$successCode, []);
    }

    protected function getCuisineAttributeValue(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id          = decode($objRequest->c_id, getDay());
        $cuisine_id          = $objRequest->cuisine_id;
        $cuisine_category_id = $objRequest->cuisine_category_id;
        $whereCriteria       = new \WhereCriteria();
        $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id);
        if (is_numeric($cuisine_id) && $cuisine_id > 0) $whereCriteria->EQ('cuisine_id', $cuisine_id);
        if (is_numeric($cuisine_category_id) && $cuisine_category_id > 0) $whereCriteria->EQ('cuisine_category_id', $cuisine_category_id);
        $field = 'cuisine_category_id, cuisine_id, cuisine_images_src, attribute_id, attr_value, attr_en_value, attr_type';
        $whereCriteria->setHashKey('attr_type')->setMultiple(true);
        $arrayAttribute = CuisineServiceImpl::instance()->getAttributeValue($whereCriteria, $field);
        return $arrayAttribute;
    }

}