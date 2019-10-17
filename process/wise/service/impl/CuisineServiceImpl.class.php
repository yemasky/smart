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
                $arrayCuisine['cuisine_name']         = trim($objRequest->cuisine_name);
                $arrayCuisine['cuisine_en_name']      = trim($objRequest->cuisine_en_name);
                $arrayCuisine['cuisine_specialty']    = $objRequest->cuisine_specialty;
                $arrayCuisine['cuisine_en_specialty'] = $objRequest->cuisine_en_specialty;
                $arrayCuisine['cuisine_inventory']    = $objRequest->cuisine_inventory;
                $arrayCuisine['cuisine_price']        = $objRequest->cuisine_price;
                $arrayCuisine['image_src']            = $objRequest->image_src;
                $arrayCuisine['valid']                = $objRequest->valid;
                if (empty($cuisine_id)) {//新数据
                    $arrayCuisine['company_id']          = $company_id;
                    $arrayCuisine['channel_id']          = $channel_id;
                    $arrayCuisine['cuisine_category_id'] = $cuisine_category_id;
                    $arrayCuisine['cuisine_is_category'] = '0';
                    $cuisine_id                          = $this->saveCuisine($arrayCuisine);
                } else {//修改
                    $whereCriteria = new \WhereCriteria();
                    $whereCriteria->EQ('channel_id', $channel_id)->EQ('company_id', $company_id)->EQ('cuisine_id', $cuisine_id);
                    $this->updateCuisine($whereCriteria, $arrayCuisine);
                }
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
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id = decode($objRequest->c_id, getDay());
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
        if (!empty($predicate = $objSort->getPredicate())) {
            $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
            $whereCriteria->ORDER($predicate, $reverse);
        }
        $start = $objPagination->getStart();
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id)->EQ('cuisine_is_category', '0')
            ->setHashKey('sku_cuisine_id')->setChildrenKey('cuisine_id');
        $whereCriteria->LIMIT($start, $number);
        $arrayData = $this->getCuisine($whereCriteria);
        if (!empty($arrayData)) {
            foreach ($arrayData as $sku_cuisine_id => $data) {
                foreach ($data as $cuisine_id => $cuisine) {
                    $arrayData[$sku_cuisine_id][$cuisine_id]['cc_id'] = encode($cuisine['cuisine_id']);
                }
            }
        }
        $tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }

    public function getCuisine(\WhereCriteria $whereCriteria, $field = '') {
        return CuisineDao::instance()->getCuisine($whereCriteria, $field);
    }

    public function saveCuisine($arrayData, $insert_type = 'INSERT') {
        return CuisineDao::instance()->saveCuisine($arrayData, $insert_type);
    }

    public function updateCuisine(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return CuisineDao::instance()->updateCuisine($whereCriteria, $arrayUpdateData);
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
}