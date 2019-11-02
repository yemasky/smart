<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class CuisineServiceImpl extends \BaseServiceImpl implements \BaseService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new CuisineServiceImpl();

        return self::$objService;
    }

    public function cuisineEditSave(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $method     = $objRequest->method;
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
        $channel_id = decode($objRequest->c_id, getDay());
        //
        $cuisine_category_id = $objRequest->cuisine_category_id;
        $cuisine_is_category = $objRequest->cuisine_is_category;
        $cuisine_id          = $objRequest->cuisine_id;
        if ($method == 'save') {
            $arrayAttr       = $objRequest->getInput('attr_value');
            $arrayItemImages = $objRequest->getInput('item_images');
            CommonServiceImpl::instance()->startTransaction();
            if ($cuisine_is_category) {//类别
                $arrayCuisineCategory['cuisine_name']         = trim($objRequest->cuisine_name);
                $arrayCuisineCategory['cuisine_en_name']      = trim($objRequest->cuisine_en_name);
                $arrayCuisineCategory['cuisine_specialty']    = $objRequest->cuisine_specialty;
                $arrayCuisineCategory['cuisine_en_specialty'] = $objRequest->cuisine_en_specialty;
                $arrayCuisineCategory['image_src']            = $objRequest->image_src;
                $arrayCuisineCategory['valid']                = $objRequest->valid;
                if (empty($cuisine_id)) {//新数据
                    $arrayCuisineCategory['company_id']          = $company_id;
                    $arrayCuisineCategory['channel_id']          = $channel_id;
                    $arrayCuisineCategory['cuisine_category_id'] = '0';
                    $arrayCuisineCategory['cuisine_is_category'] = '1';
                    $cuisine_id                                  = $this->saveCuisine($arrayCuisineCategory);
                    $whereCriteria                               = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    $this->updateCuisine($whereCriteria, ['cuisine_category_id' => $cuisine_id]);
                } else {//修改
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    $this->updateCuisine($whereCriteria, $arrayCuisineCategory);
                }
                $cuisine_category_id = $cuisine_id;
            } else {//菜式
                $sku_key                              = $objRequest->sku_key;
                $arrayCuisine['cuisine_name']         = trim($objRequest->cuisine_name);
                $arrayCuisine['cuisine_en_name']      = trim($objRequest->cuisine_en_name);
                $arrayCuisine['sku_complete_dinner']  = $objRequest->sku_complete_dinner;//套菜
                $arrayCuisine['cuisine_specialty']    = $objRequest->cuisine_specialty;
                $arrayCuisine['cuisine_en_specialty'] = $objRequest->cuisine_en_specialty;
                $arrayCuisine['image_src']            = $objRequest->image_src;
                $arrayCuisine['valid']                = $objRequest->valid;
                //
                $arrayCc_id             = $objRequest->cc_id;//判断是否新数据
                $arrayCuisineInventory  = $objRequest->cuisine_inventory;//库存
                $arrayCuisinePrice      = $objRequest->cuisine_price;//价格
                $arraySelectSkuAttr     = $objRequest->select_SkuAttr;
                $arraySkuAttrValue      = $objRequest->sku_attr_value;
                $skuCompleteDinnerIds   = $objRequest->sku_complete_dinner_ids;
                $arrayDeleteSKU         = $objRequest->deleteSKU;
                $arrayCuisineList       = [];
                $arrayCuisineUpdateList = [];
                if (!empty($arrayCc_id)) {
                    if (empty($sku_key)) $sku_key = $channel_id . '-' . getDateTimeId();
                    $arrayCuisine['sku_key'] = $sku_key;
                    $k                       = 0;
                    foreach ($arrayCc_id as $key => $cc_id) {
                        $arrayCuisine['cuisine_inventory'] = $arrayCuisineInventory[$key];//库存
                        if ($arrayCuisine['sku_complete_dinner']) {
                            $arrayCuisine['cuisine_inventory']       = -9999;//套菜库存
                            $arrayCuisine['sku_complete_dinner_ids'] = implode(',', array_keys($skuCompleteDinnerIds));
                        }
                        $arrayCuisine['cuisine_price'] = $arrayCuisinePrice[$key];
                        if (!empty($arraySelectSkuAttr)) {
                            foreach ($arraySelectSkuAttr as $sku_attr_key => $skuAttr) {
                                $arrayCuisine['sku_attr' . $sku_attr_key] = $skuAttr['cn'];
                            }
                        }
                        if (!empty($arraySkuAttrValue)) {
                            foreach ($arraySkuAttrValue as $sku_attr_key => $skuAttrValue) {
                                $arrayCuisine['sku_attr' . $sku_attr_key . '_value'] = $skuAttrValue[$key];
                            }
                        }
                        if (empty($cc_id)) {//add
                            $arrayCuisine['company_id']          = $company_id;
                            $arrayCuisine['channel_id']          = $channel_id;
                            $arrayCuisine['cuisine_category_id'] = $cuisine_category_id;
                            $arrayCuisine['cuisine_is_category'] = '0';
                            $arrayCuisine['sku_cuisine_id']      = '0';
                            $arrayCuisineList[$k]                = $arrayCuisine;
                            $k++;
                        } else {//edit
                            $arrayUpdateCc_id[$key]       = decode($cc_id);
                            $arrayCuisineUpdateList[$key] = $arrayCuisine;
                        }
                    }
                    if (!empty($arrayCuisineList)) {//add
                        $this->batchInsertCuisine($arrayCuisineList);
                        if (empty($cuisine_id)) {//全新插入
                            //取得第一个cuisine_id id
                            $whereCriteria = new \WhereCriteria();
                            $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('sku_key', $sku_key);
                            $arrayCuisine_id = $this->getCuisine($whereCriteria, 'MIN(cuisine_id) cuisine_id');
                            $cuisine_id      = $arrayCuisine_id[0]['cuisine_id'];
                            CuisineDao::instance()->updateCuisine($whereCriteria, ['sku_cuisine_id' => $cuisine_id]);
                            $whereCriteria->EQ('cuisine_id', $cuisine_id);
                            CuisineDao::instance()->updateCuisine($whereCriteria, ['sku' => '1']);
                        } else {
                            $whereCriteria = new \WhereCriteria();
                            $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('sku_key', $sku_key);
                            CuisineDao::instance()->updateCuisine($whereCriteria, ['sku_cuisine_id' => $cuisine_id]);
                        }
                    }
                    if (!empty($arrayCuisineUpdateList)) {
                        $arrayUpdateCuisine        = [];
                        $arrayUpdateCuisine['key'] = 'cuisine_id';
                        foreach ($arrayCuisineUpdateList as $j => $updataData) {
                            foreach ($updataData as $field => $value) {
                                $arrayUpdateCuisine['field'][$field][$arrayUpdateCc_id[$j]] = $value;
                            }
                        }
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->ArrayIN('cuisine_id', $arrayUpdateCc_id);
                        CuisineDao::instance()->batchUpdateCuisine($whereCriteria, $arrayUpdateCuisine);
                    }

                }
                if (!empty($arrayDeleteSKU)) {
                    $arrayActiveUpdateCc_id = $arrayDeleteUpdateCc_id = [];
                    foreach ($arrayDeleteSKU as $cc_id => $valid) {
                        if ($valid == '+') {
                            $arrayActiveUpdateCc_id[$cuisine_id] = decode($cc_id);
                        } else {
                            $arrayDeleteUpdateCc_id[$cuisine_id] = decode($cc_id);
                        }
                    }
                    if (!empty($arrayDeleteUpdateCc_id)) {
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id);
                        $whereCriteria->ArrayIN('cuisine_id', $arrayDeleteUpdateCc_id);
                        CuisineDao::instance()->updateCuisine($whereCriteria, ['valid' => '0']);
                    }
                    if (!empty($arrayActiveUpdateCc_id)) {
                        $whereCriteria = new \WhereCriteria();
                        $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id);
                        $whereCriteria->ArrayIN('cuisine_id', $arrayActiveUpdateCc_id);
                        CuisineDao::instance()->updateCuisine($whereCriteria, ['valid' => '1']);
                    }
                }
                /*if (empty($cuisine_id)) {//新数据
                    $arrayCuisine['company_id']          = $company_id;
                    $arrayCuisine['channel_id']          = $channel_id;
                    $arrayCuisine['cuisine_category_id'] = $cuisine_category_id;
                    $arrayCuisine['cuisine_is_category'] = '0';
                    $cuisine_id                          = $this->saveCuisine($arrayCuisine);
                } else {//修改
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    $this->updateCuisine($whereCriteria, $arrayCuisine);
                }*/
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
                            $arrayInsertData[$k]['cuisine_category_id'] = $cuisine_category_id;
                            $arrayInsertData[$k]['cuisine_id']          = $cuisine_id;
                            $arrayInsertData[$k]['cuisine_images_src']  = '';
                            $arrayInsertData[$k]['attr_value']          = trim($v);
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
                        $arrayInsertData[$k]['attr_value']          = trim($value);
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
                    $arrayInsertData[$k]['attr_value']          = trim($value['name']);
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
            if (!empty($arrayInsertData)) $this->batchInsertAttrValue($arrayInsertData);
            CommonServiceImpl::instance()->commit();
            return $objResponse->successResponse(ErrorCodeConfig::$successCode, []);
        }
        return $objResponse->successResponse(ErrorCodeConfig::$errorCode['no_data_update'], []);
    }

    //Cuisine
    public function getChannelCuisinePage(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $tableState = $objRequest->tableState;
        if (empty($tableState)) $tableState = array();
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        if (!empty($objRequest->c_id)) {
            $channel_id = decode($objRequest->c_id, getDay());
        }
        $tableStateModel = new TableStateModel($tableState);
        $objPagination   = $tableStateModel->getPagination();
        $objSearch       = $tableStateModel->getSearch();
        $objSort         = $tableStateModel->getSort();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '0')->EQ('sku', '1');
        if (!empty($searchValue = $objSearch->getPredicateObject())) {
            //$whereCriteria->MATCH('receivable_name', $searchValue['$']);
            $whereCriteria->LIKE('cuisine_name', '%' . $searchValue['$'] . '%');
        }
        $cuisineCount = CuisineDao::instance()->getChannelCuisineCount($whereCriteria, 'cuisine_id');
        $objPagination->setTotalItemCount($cuisineCount);
        $number        = $objPagination->getNumber();
        $numberOfPages = ceil($cuisineCount / $number);
        $objPagination->setNumberOfPages($numberOfPages);
        $start = $objPagination->getStart();

        $arrayData = [];
        if ($numberOfPages > 0) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '0')->EQ('sku', '1')
                ->LIMIT($start, $number);
            if (!empty($searchValue = $objSearch->getPredicateObject())) {
                //$whereCriteria->MATCH('receivable_name', $searchValue['$']);
                $whereCriteria->LIKE('cuisine_name', '%' . $searchValue['$'] . '%');
            }
            if (!empty($predicate = $objSort->getPredicate())) {
                $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
                $whereCriteria->ORDER($predicate, $reverse);
            }
            $arrayCuisineId = $this->getCuisine($whereCriteria, 'cuisine_id');
            $arrayCuisineId = array_column($arrayCuisineId, 'cuisine_id');
            //
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->setHashKey('sku_cuisine_id')->setChildrenKey('cuisine_id')->ArrayIN('sku_cuisine_id', $arrayCuisineId);
            $arrayData = $this->getCuisine($whereCriteria);
            if (!empty($arrayData)) {
                foreach ($arrayData as $sku_cuisine_id => $data) {
                    foreach ($data as $cuisine_id => $cuisine) {
                        $arrayData[$sku_cuisine_id][$cuisine_id]['cc_id'] = encode($cuisine['cuisine_id']);
                    }
                }
            }
        }
        //$tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }

    public function getCuisine(\WhereCriteria $whereCriteria, $field = '') {
        return CuisineDao::instance()->getCuisine($whereCriteria, $field);
    }

    public function getCuisineSKUList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        if (!empty($objRequest->c_id)) {
            $channel_id = decode($objRequest->c_id, getDay());
        }
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '0')
            ->setHashKey('sku_cuisine_id')->setChildrenKey('cuisine_id');
        $arrayData = $this->getCuisine($whereCriteria);
        if (!empty($arrayData)) {
            foreach ($arrayData as $sku_cuisine_id => $data) {
                foreach ($data as $cuisine_id => $cuisine) {
                    $arrayData[$sku_cuisine_id][$cuisine_id]['cc_id'] = encode($cuisine['cuisine_id']);
                }
            }
        }
        return $arrayData;
    }

    public function getCuisineList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = $objRequest->channel_id;
        if (!empty($objRequest->c_id)) $channel_id = decode($objRequest->c_id, getDay());

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('valid', '1');
        $field = 'cuisine_id,cuisine_category_id,cuisine_name,cuisine_en_name,image_src,sku,sku_cuisine_id,sku_attr1,sku_attr1_value,sku_attr2,'
            . 'sku_attr2_value,sku_attr3,sku_attr3_value,sku_attr4,sku_attr4_value,sku_attr5,sku_attr5_value,cuisine_inventory,cuisine_price,'
            . 'cuisine_sell_clear,cuisine_specialty,cuisine_en_specialty,cuisine_is_category';
        return CuisineServiceImpl::instance()->getCuisine($whereCriteria, $field);
    }

    public function batchInsertCuisine($arrayData, $insert_type = 'INSERT') {
        return CuisineDao::instance()->batchInsertCuisine($arrayData, $insert_type);
    }

    public function saveCuisine($arrayData, $insert_type = 'INSERT') {
        return CuisineDao::instance()->saveCuisine($arrayData, $insert_type);
    }

    public function updateCuisine(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return CuisineDao::instance()->updateCuisine($whereCriteria, $arrayUpdateData);
    }

    public function getCuisineCategory($company_id, $channel_id, $field = '*', $hashKey = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [0, $company_id])->ArrayIN('channel_id', [0, $channel_id])->EQ('cuisine_is_category', '1');
        if (!empty($hashKey)) $whereCriteria->setHashKey($hashKey);
        return CuisineServiceImpl::instance()->getCuisine($whereCriteria, $field);
    }

    //
    public function deleteAttributeValue(\WhereCriteria $whereCriteria) {
        return CuisineDao::instance()->deleteAttributeValue($whereCriteria);
    }

    public function batchInsertAttrValue($arrayData, $insert_type = 'INSERT') {
        return CuisineDao::instance()->batchInsertAttributeValue($arrayData, $insert_type);
    }

    public function getAttributeValue($whereCriteria, $field = '') {
        return CuisineDao::instance()->getAttributeValue($whereCriteria, $field);
    }

    //table
    public function getChannelTablePage(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $tableState = $objRequest->tableState;
        if (empty($tableState)) $tableState = array();
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id      = decode($objRequest->c_id, getDay());
        $tableStateModel = new TableStateModel($tableState);
        $objPagination   = $tableStateModel->getPagination();
        $objSearch       = $tableStateModel->getSearch();
        $objSort         = $tableStateModel->getSort();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('channel_config', 'table');
        if (!empty($searchValue = $objSearch->getPredicateObject())) {
            //$whereCriteria->MATCH('receivable_name', $searchValue['$']);
            $whereCriteria->LIKE('item_name', '%' . $searchValue['$'] . '%');
        }
        $tableCount = ChannelDao::instance()->getChannelItemCount($whereCriteria, 'item_id');
        $objPagination->setTotalItemCount($tableCount);
        $number        = $objPagination->getNumber();
        $numberOfPages = ceil($tableCount / $number);
        $objPagination->setNumberOfPages($numberOfPages);
        if (!empty($predicate = $objSort->getPredicate())) {
            $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
            $whereCriteria->ORDER($predicate, $reverse);
        }
        $start = $objPagination->getStart();
        $whereCriteria->LIMIT($start, $number);
        $arrayData = ChannelDao::instance()->getChannelItem($whereCriteria);
        if (!empty($arrayData)) {
            foreach ($arrayData as $i => $data) {
                $arrayData[$i]['ci_id'] = encode($data['item_id']);

            }
        }
        $tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }
}