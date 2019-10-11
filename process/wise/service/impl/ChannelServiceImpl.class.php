<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ChannelServiceImpl extends \BaseServiceImpl implements ChannelService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new ChannelServiceImpl();

        return self::$objService;
    }

    public function getCompanyChannelCache($company_id, $channel = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        $cacheChannelId = CacheConfig::getCacheId('channel', $company_id);
        $whereCriteria->setHashKey('channel_id');
        $arrayChannelList = ChannelDao::instance()->DBCache($cacheChannelId)->getChannel($whereCriteria);
        if (!empty($arrayChannelList)) {
            if (empty($channel)) {
                foreach ($arrayChannelList as $channel_id => $value) {
                    $arrayChannelList[$channel_id]['company_id'] = 0;
                    $arrayChannelList[$channel_id]['id']         = encode($arrayChannelList[$channel_id]['channel_id'], getDay());
                }

                return $arrayChannelList;
            } else {
                $arrayChannel = [];
                foreach ($arrayChannelList as $channel_id => $value) {
                    if ($value['channel'] == $channel) {
                        $arrayChannel[$channel_id]               = $value;
                        $arrayChannel[$channel_id]['company_id'] = 0;
                        $arrayChannel[$channel_id]['id']         = encode($arrayChannelList[$channel_id]['channel_id'], getDay());
                    }
                }

                return $arrayChannel;
            }
        }

        return null;
    }

    public function getEmployeeChannel($arrayChannelId, $channel = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('channel_id', $arrayChannelId);
        $arrayChannelList = ChannelDao::instance()->getChannel($whereCriteria);
        if (!empty($arrayChannelList)) {
            if (empty($channel)) {
                foreach ($arrayChannelList as $channel_id => $value) {
                    $arrayChannelList[$channel_id]['company_id'] = 0;
                    $arrayChannelList[$channel_id]['id']         = encode($arrayChannelList[$channel_id]['channel_id'], getDay());
                }

                return $arrayChannelList;
            } else {
                $arrayChannel = [];
                foreach ($arrayChannelList as $channel_id => $value) {
                    if ($value['channel'] == $channel) {
                        $arrayChannel[$channel_id]               = $value;
                        $arrayChannel[$channel_id]['company_id'] = 0;
                        $arrayChannel[$channel_id]['id']         = encode($arrayChannelList[$channel_id]['channel_id'], getDay());
                    }
                }

                return $arrayChannel;
            }
        }

        return null;
    }

    //* return channel_id *//
    public function saveChannel($arrayData, $insert_type = 'INSERT') {
        $cacheChannelId = CacheConfig::getCacheId('channel', $arrayData['company_id']);

        return ChannelDao::instance()->DBCache($cacheChannelId, -1)->saveChannel($arrayData, $insert_type);
    }

    public function updateChannel(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        $cacheChannelId = CacheConfig::getCacheId('channel', $arrayUpdateData['company_id']);

        return ChannelDao::instance()->DBCache($cacheChannelId, -1)->updateChannel($whereCriteria, $arrayUpdateData);
    }

    //arrtibute
    public function getAttribute($company_id, $channel_config, $channel_id = 0, $item_id = 0) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('channel_config', $channel_config)->ArrayIN('company_id', [$company_id, '0']);
        if ($channel_id > 0) $whereCriteria->ArrayIN('channel_id', [$channel_id, '0']);
        if (!is_array($item_id) && $item_id > 0) $whereCriteria->ArrayIN('item_id', [$item_id, '0']);
        if (is_array($item_id) && !empty($item_id)) $whereCriteria->ArrayIN('item_id', $item_id);
        $cacheAttributeId = $company_id . '_' . $channel_config . '_' . $channel_id . '_' . $item_id;
        $cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);
        $whereCriteria->setHashKey('attribute_id');

        return ChannelDao::instance()->DBCache($cacheAttributeId)->getAttribute($whereCriteria);
    }

    public function saveAttribute($arrayData, $insert_type = 'INSERT') {
        $channel_id       = isset($arrayData['channel_id']) ? $arrayData['channel_id'] : '0';
        $item_id          = isset($arrayData['item_id']) ? $arrayData['item_id'] : '0';
        $cacheAttributeId = $arrayData['company_id'] . '_' . $arrayData['channel_config'] . '_' . $channel_id . '_' . $item_id;
        $cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);

        return ChannelDao::instance()->DBCache($cacheAttributeId, -1)->saveAttribute($arrayData, $insert_type);
    }

    public function updateAttribute(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        //$channel_id       = isset($where['channel_id']) ? $where['channel_id'] : '0';
        //$item_id          = isset($where['item_id']) ? $where['item_id'] : '0';
        //$cacheAttributeId = $where['company_id'] . '_' . $where['channel_config'] . '_' . $channel_id . '_' . $item_id;
        $cacheAttributeId = $whereCriteria->getWhere();
        $cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);

        return ChannelDao::instance()->DBCache($cacheAttributeId, -1)->updateAttribute($whereCriteria, $arrayUpdateData);
    }

    //AttrValue
    public function getAttributeValue(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->getAttributeValue($whereCriteria, $field);
    }

    public function deleteAttributeValue(\WhereCriteria $whereCriteria) {
        return ChannelDao::instance()->deleteAttributeValue($whereCriteria);
    }

    public function batchInsertAttrValue($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->batchInsertAttributeValue($arrayData, $insert_type);
    }

    //upload images
    public function getUploadImages(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->getUploadImages($whereCriteria, $field);
    }

    public function getUploadImagesCount(\WhereCriteria $whereCriteria) {
        return ChannelDao::instance()->getUploadImagesCount($whereCriteria);
    }

    public function saveUploadImages($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveUploadImages($arrayData, $insert_type);
    }

    //get channel_item
    public function getChannelItemHash(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id     = $objRequest->channel_id;
        $channel_config = $objRequest->channel_config;

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        if (!empty($channel_id) && $channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        if (!empty($channel_config)) $whereCriteria->EQ('channel_config', $channel_config);;
        //
        $field       = $objRequest->field;
        $toHashArray = $objRequest->toHashArray == true ? true : false;
        $whereCriteria->setMultiple($toHashArray);
        $hashKey = $objRequest->hashKey;
        if (!empty($hashKey)) $whereCriteria->setHashKey($hashKey);
        $fatherHash = $objRequest->fatherHash;
        if (!empty($fatherHash)) $whereCriteria->setFatherKey($fatherHash);
        $childrenHash = $objRequest->childrenHash;
        if (!empty($childrenHash)) $whereCriteria->setChildrenKey($childrenHash);
        $arrayDataList = ChannelDao::instance()->getChannelItem($whereCriteria, $field);

        return $arrayDataList;
    }

    public function getChannelItem(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->getChannelItem($whereCriteria, $field);
    }

    public function getChannelItemCount(\WhereCriteria $whereCriteria) {
        return ChannelDao::instance()->getChannelItemCount($whereCriteria);
    }

    public function saveChannelItem($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveChannelItem($arrayData, $insert_type);
    }

    public function updateChannelItem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->updateChannelItem($whereCriteria, $arrayUpdateData, $update_type);
    }

    //payment_type
    public function checkSameNamePaymentType($company_id, $payment_name = '', $payment_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($payment_name)) $whereCriteria->EQ('payment_name', $payment_name);
        //if(!empty($payment_en_name)) $arrayCondition['where']['payment_en_name'] = $payment_en_name;
        $arrayResult = ChannelDao::instance()->getPaymentType($whereCriteria, 'payment_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function getPaymentTypeHash($company_id) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        $field              = 'payment_id,company_id,payment_father_id,payment_name,payment_en_name,valid';
        $cachePaymentTypeId = CacheConfig::getCacheId('paymentType', $company_id);
        $whereCriteria->setHashKey('payment_id')->setMultiple(false)->setFatherKey('payment_father_id');

        return ChannelDao::instance()->DBCache($cachePaymentTypeId)->getPaymentType($whereCriteria, $field);
    }

    public function savePaymentType($arrayData, $insert_type = 'INSERT') {
        $cachePaymentTypeId = CacheConfig::getCacheId('paymentType', $arrayData['company_id']);

        return ChannelDao::instance()->DBCache($cachePaymentTypeId, -1)->savePaymentType($arrayData, $insert_type);
    }

    public function updatePaymentType($company_id, $payment_id, $arrayUpdateData, $update_type = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('payment_id', $payment_id);
        $cachePaymentTypeId = CacheConfig::getCacheId('paymentType', $company_id);

        return ChannelDao::instance()->DBCache($cachePaymentTypeId, -1)->updatePaymentType($whereCriteria, $arrayUpdateData, $update_type);
    }
    //
    //CustomerMarket
    public function checkSameNameCustomerMarket($company_id, $market_name = '', $market_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($market_name)) $whereCriteria->EQ('market_name', $market_name);
        //if(!empty($market_name)) $arrayCondition['where']['market_en_name'] = $market_en_name;
        $arrayResult = ChannelDao::instance()->getCustomerMarket($whereCriteria, 'market_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function getCustomerMarketHash($company_id) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        $whereCriteria->ORDER('marketing')->ORDER('market_father_id', 'ASC')->ORDER('market_id', 'ASC');
        $field                 = 'market_id,company_id,market_father_id,market_name,market_en_name,marketing,valid';
        $cacheCustomerMarketId = CacheConfig::getCacheId('customer_market', $company_id);
        $whereCriteria->setHashKey('market_id')->setMultiple(false)->setFatherKey('market_father_id');

        return ChannelDao::instance()->DBCache($cacheCustomerMarketId)->getCustomerMarket($whereCriteria, $field);
    }

    public function saveCustomerMarket($arrayData, $insert_type = 'INSERT') {
        $cacheCustomerMarketId = CacheConfig::getCacheId('customer_market', $arrayData['company_id']);

        return ChannelDao::instance()->DBCache($cacheCustomerMarketId, -1)->saveCustomerMarket($arrayData, $insert_type);
    }

    public function updateCustomerMarket($company_id, $market_id, $arrayUpdateData, $update_type = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('market_id', $market_id);
        $cacheCustomerMarketId = CacheConfig::getCacheId('customer_market', $company_id);

        return ChannelDao::instance()->DBCache($cacheCustomerMarketId, -1)->updateCustomerMarket($whereCriteria, $arrayUpdateData, $update_type);
    }

    //channel_commision
    public function getChannelCommisionHash($company_id) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        $field = 'channel_commision_id,company_id,channel_id,market_id,price_system_id,commision_type,commision_form,commision_form_value,valid';
        $whereCriteria->setHashKey('channel_id')->setMultiple(false)->setFatherKey('market_id')->setChildrenKey('price_system_id');
        return ChannelDao::instance()->getChannelCommision($whereCriteria, $field);
    }

    public function getChannelCommisionCache($company_id, $channel_id = '', $market_id = '', $price_system_id = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('valid', '1');
        $field   = 'channel_commision_id,company_id,channel_id,market_id,price_system_id,commision_type,commision_form,commision_form_value,valid';
        $cacheId = CacheConfig::getCacheId('commision', $company_id);
        $whereCriteria->setHashKey('channel_id')->setFatherKey('market_id')
            ->setChildrenKey('price_system_id')->setMultiple(false);
        $arrayCommision = ChannelDao::instance()->DBCache($cacheId)->getChannelCommision($whereCriteria, $field);
        if (!empty($channel_id) && !empty($market_id)) {
            if(isset($arrayCommision[$channel_id]) && isset($arrayCommision[$channel_id][$market_id])) {
                return $arrayCommision[$channel_id][$market_id];
            }
            return [];
        } elseif (!empty($channel_id)) {
            if(isset($arrayCommision[$channel_id]))
                return $arrayCommision[$channel_id];
            return [];
        }
        return $arrayCommision;
    }

    public function saveChannelCommision($arrayData) {
        $cacheId = CacheConfig::getCacheId('commision', $arrayData['company_id']);
        return ChannelDao::instance()->DBCache($cacheId, -1)->saveChannelCommision($arrayData);
    }

    public function updateChannelCommision($arrayData, $company_id, $cc_id) {
        $whereCriteria = new \WhereCriteria ();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_commision_id', $cc_id);
        $cacheId = CacheConfig::getCacheId('commision', $company_id);
        return ChannelDao::instance()->DBCache($cacheId, -1)->updateChannelCommision($whereCriteria, $arrayData);
    }

    //channel_layout_price_system
    public function checkSameNamePriceSystem($company_id, $price_system_name = '', $price_system_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        if (!empty($price_system_name)) $whereCriteria->EQ('price_system_name', $price_system_name);
        //if(!empty($price_system_en_name)) $arrayCondition['where']['price_system_en_name'] = $price_system_en_name;
        $arrayResult = ChannelDao::instance()->getLayoutPriceSystem($whereCriteria, 'price_system_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function getLayoutPriceSystem(\WhereCriteria $whereCriteria, $field = '') {
        if (empty($field))
            $field = 'price_system_id, price_system_father_id, channel_ids, price_system_name, market_ids, layout_item, '
                . 'formula,price_system_type, valid';

        return ChannelDao::instance()->getLayoutPriceSystem($whereCriteria, $field);
    }

    public function getLayoutPriceSystemHash($company_id, $valid = '', $channel_id = '', $market_id = '', $item_category_id = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);
        if (!empty($channel_id) && $channel_id > 0) $whereCriteria->MATCH('channel_ids', $channel_id);
        if (!empty($market_id) && $market_id > 0) $whereCriteria->MATCH('market_ids', $market_id);
        if (!empty($item_category_id) && $item_category_id > 0) $whereCriteria->MATCH('layout_item', $item_category_id);
        if ($valid !== '') $whereCriteria->EQ('valid', $valid);
        $whereCriteria->setHashKey('price_system_id');
        $field = 'price_system_id, price_system_father_id, channel_ids, price_system_name,price_system_en_name, market_ids, layout_item, book_min_day, cancellation_policy,'
            . 'formula,price_system_type, valid';

        return ChannelDao::instance()->getLayoutPriceSystem($whereCriteria, $field);
    }

    public function saveLayoutPriceSystem($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveLayoutPriceSystem($arrayData, $insert_type);
    }

    public function updateLayoutPriceSystem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->updateLayoutPriceSystem($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function batchInsertPriceSystemLayout($arrayData) {
        return ChannelDao::instance()->batchInsertPriceSystemLayout($arrayData);
    }

    public function getLayoutPriceSystemLayout(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->getLayoutPriceSystemLayout($whereCriteria, $field);
    }

    public function getPriceSystemLayout($market_id, $price_system_id = '', $channel_id = '', $item_category_id = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('market_id', $market_id);
        if ($price_system_id > 0) $whereCriteria->EQ('price_system_id', $price_system_id);
        if ($price_system_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        if ($price_system_id > 0) $whereCriteria->EQ('item_category_id', $item_category_id);

        return ChannelDao::instance()->getLayoutPriceSystem($whereCriteria, 'price_system_id,channel_id,market_id,item_category_id');
    }

    public function deletePriceSystemLayout(\WhereCriteria $whereCriteria) {
        return ChannelDao::instance()->deletePriceSystemLayout($whereCriteria);
    }

    //channel_layout_price
    public function getDateLayoutPrice($price_system_id, $betinDate, $endDate, $arrayItemId, $company_id, $field = null) {
        $whereCriteria = new \WhereCriteria();

        $whereCriteria->EQ('company_id', $company_id);
        if (is_array($price_system_id)) {
            $whereCriteria->ArrayIN('price_system_id', $price_system_id);
        } else {
            $whereCriteria->EQ('price_system_id', $price_system_id);
        }
        $whereCriteria->GE('layout_price_date', $betinDate);
        $whereCriteria->LE('layout_price_date', $endDate);
        $arrayCondition['where']['<='] = [];
        if (is_array($arrayItemId)) {
            $whereCriteria->ArrayIN('item_id', $arrayItemId);
        } else {
            $whereCriteria->EQ('item_id', $arrayItemId);
        }
        if (is_array($price_system_id)) {
            $whereCriteria->setHashKey('item_id')->setMultiple(false)->setFatherKey('price_system_id')
                ->setChildrenKey('layout_price_date');

            return ChannelDao::instance()->getLayoutPrice($whereCriteria, $field);
        }
        $whereCriteria->setHashKey('item_id')->setMultiple(false)->setChildrenKey('layout_price_date');

        return ChannelDao::instance()->getLayoutPrice($whereCriteria, $field);
    }

    public function getLayoutPrice($company_id, $channel_id, $arraySystemId = array(), $item_category_id = null, $in_date, $out_date, $sql = '',
        $arrayHashKey = array(), $field = null) {
        $whereCriteria = new \WhereCriteria();
        if ($company_id > 0) $whereCriteria->EQ('company_id', $company_id);
        if ($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        if (!empty($arraySystemId)) {
            $whereCriteria->ArrayIN('price_system_id', $arraySystemId);
        }
        if (!empty($item_category_id)) {
            $whereCriteria->EQ('item_id', $item_category_id);
        }
        if (isset($arrayHashKey['hashKey'])) $whereCriteria->setHashKey($arrayHashKey['hashKey']);
        if (isset($arrayHashKey['fatherKey'])) $whereCriteria->setFatherKey($arrayHashKey['fatherKey']);
        if (isset($arrayHashKey['childrenKey'])) $whereCriteria->setChildrenKey($arrayHashKey['childrenKey']);
        if (!empty($sql)) $whereCriteria->SQL($sql);

        $whereCriteria->GE('layout_price_date', substr($in_date, 0, 8) . '01');
        $whereCriteria->LE('layout_price_date', substr($out_date, 0, 8) . '01');

        return ChannelDao::instance()->getLayoutPrice($whereCriteria, $field);
    }

    public function saveLayoutPrice($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveLayoutPrice($arrayData, $insert_type);
    }

    public function batchInsertLayoutPrice($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->batchInsertLayoutPrice($arrayData, $insert_type);
    }

    public function updateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->updateLayoutPrice($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function batchUpdateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->batchUpdateLayoutPrice($whereCriteria, $arrayUpdateData, $update_type);
    }

    //channel_cancellation_policy
    public function getCancellationPolicy($company_id, $channel_id = '', $policy_id = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($channel_id) && $channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
        if (!empty($policy_id) && $policy_id > 0) $whereCriteria->EQ('policy_id', $policy_id);
        $field = 'policy_id,policy_name,policy_en_name,channel_id,rules,rules_value,rules_days,rules_time,begin_datetime,end_datetime,policy_type,valid';

        return ChannelDao::instance()->getCancellationPolicy($whereCriteria, $field);
    }

    public function getCancellationPolicyCache($company_id) {
        $cacheChannelId = CacheConfig::getCacheId('policy', $company_id);
        $whereCriteria  = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0'])->setHashKey('policy_id')->EQ('valid', '1');
        $arrayCancellationPolicy = ChannelDao::instance()->DBCache($cacheChannelId)->getCancellationPolicy($whereCriteria);

        return $arrayCancellationPolicy;

    }

    public function checkSameCancellationPolicy($company_id, $policy_name = '', $policy_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($policy_name)) $whereCriteria->EQ('policy_name', $policy_name);
        //if(!empty($policy_en_name)) $arrayCondition['where']['policy_en_name'] = $policy_en_name;
        $arrayResult = ChannelDao::instance()->getCancellationPolicy($whereCriteria, 'policy_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function saveCancellationPolicy($arrayData, $insert_type = 'INSERT') {
        $cacheChannelId = CacheConfig::getCacheId('policy', $arrayData['company_id']);

        return ChannelDao::instance()->DBCache($cacheChannelId, -1)->saveCancellationPolicy($arrayData, $insert_type);
    }

    public function updateCancellationPolicy($company_id, $policy_id, $arrayUpdateData, $update_type = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('policy_id', $policy_id);
        $cacheChannelId = CacheConfig::getCacheId('policy', $company_id);
        return ChannelDao::instance()->DBCache($cacheChannelId, -1)->updateCancellationPolicy($whereCriteria, $arrayUpdateData, $update_type);
    }
    //
    //channel_consume
    public function getChannelConsume($company_id, $channel_id = '', $channel_consume_id = '', $field = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0'])->ORDER('channel_consume_father_id', 'ASC')->NE('valid', '-1');
        if (!empty($channel_id) && $channel_id > 0) $whereCriteria->ArrayIN('channel_id', [$channel_id, 0]);
        if (!empty($channel_consume_id) && $channel_consume_id > 0) $whereCriteria->EQ('channel_consume_id', $channel_consume_id);
        if (empty($field)) $field = 'channel_consume_id,channel_consume_father_id,channel,company_id,channel_id,consume_title,consume_title_en,consume_code,consume_price,consume_unit,valid';

        return ChannelDao::instance()->getChannelConsume($whereCriteria, $field);
    }

    public function checkSameChannelConsume($company_id, $consume_title = '', $policy_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($policy_name)) $whereCriteria->EQ('consume_title', $consume_title);
        //if(!empty($policy_en_name)) $arrayCondition['where']['policy_en_name'] = $policy_en_name;
        $arrayResult = ChannelDao::instance()->getChannelConsume($whereCriteria, 'channel_consume_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function saveChannelConsume($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveChannelConsume($arrayData, $insert_type);
    }

    public function updateChannelConsume($company_id, $channel_consume_id, $arrayUpdateData, $update_type = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_consume_id', $channel_consume_id);

        return ChannelDao::instance()->updateChannelConsume($whereCriteria, $arrayUpdateData, $update_type);
    }

    //getChannelBorrowing
    public function getChannelBorrowing($company_id, $channel_id = '', $borrowing_id = '', $field = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0'])->ORDER('channel_id', 'ASC')->ORDER('borrowing_tag')->NE('valid', '-1');
        if (!empty($channel_id) && $channel_id > 0) $whereCriteria->ArrayIN('channel_id', [$channel_id, 0]);
        if (!empty($borrowing_id) && $borrowing_id > 0) $whereCriteria->EQ('borrowing_id', $borrowing_id);
        if (empty($field)) $field = 'borrowing_id,channel,company_id,channel_id,borrowing_name,borrowing_en_name,borrowing_price,borrowing_tag,borrowing_stock,borrowing_have,borrowing_describe,valid';

        return ChannelDao::instance()->getChannelBorrowing($whereCriteria, $field);
    }

    public function getChannelBorrowingCache($company_id, $channel_id = '', $policy_id = '') {

    }

    public function checkSameChannelBorrowing($company_id, $borrowing_name = '', $borrowing_en_name = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->ArrayIN('company_id', [$company_id, '0']);
        if (!empty($policy_name)) $whereCriteria->EQ('borrowing_name', $borrowing_name);
        //if(!empty($borrowing_en_name)) $arrayCondition['where']['borrowing_en_name'] = $borrowing_en_name;
        $arrayResult = ChannelDao::instance()->getChannelBorrowing($whereCriteria, 'borrowing_id');
        if (!empty($arrayResult)) {
            return false;
        }

        return true;
    }

    public function saveChannelBorrowing($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveChannelBorrowing($arrayData, $insert_type);
    }

    public function updateChannelBorrowing($company_id, $borrowing_id, $arrayUpdateData, $update_type = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('borrowing_id', $borrowing_id);

        return ChannelDao::instance()->updateChannelBorrowing($whereCriteria, $arrayUpdateData, $update_type);
    }

    //BusinessDay
    public function saveBusinessDay($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->saveBusinessDay($arrayData, $insert_type);
    }

    public function getBusinessDay($channel_id) {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('channel_id', $channel_id)->ORDER('business_day_id')->LIMIT(0, 1);
        $arrayBusinessDay = ChannelDao::instance()->getBusinessDay($whereCriteria, 'business_day');
        if (empty($arrayBusinessDay)) {
            return null;
        }
        return $arrayBusinessDay[0]['business_day'];
    }

    //channel_receivable
    public function saveChannelReceivable(Channel_receivableEntity $Channel_receivableEntity) {
        return ChannelDao::instance()->saveChannelReceivable($Channel_receivableEntity);
    }

    public function getChannelReceivable(\WhereCriteria $whereCriteria, $field = '') {
        return ChannelDao::instance()->getChannelReceivable($whereCriteria, $field);
    }

    public function getChannelReceivablePage(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $tableState = $objRequest->tableState;
        if (empty($tableState)) $tableState = array();
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id      = $objRequest->channel_id;
        $tableStateModel = new TableStateModel($tableState);
        $objPagination   = $tableStateModel->getPagination();
        $objSearch       = $tableStateModel->getSearch();
        $objSort         = $tableStateModel->getSort();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->ArrayIN('channel_id', [0, $channel_id]);
        if (!empty($searchValue = $objSearch->getPredicateObject())) {
            //$whereCriteria->MATCH('receivable_name', $searchValue['$']);
            $whereCriteria->LIKE('receivable_name', '%' . $searchValue['$'] . '%');
        }
        $receivableCount = ChannelDao::instance()->getChannelReceivableCount($whereCriteria, 'receivable_id');
        $objPagination->setTotalItemCount($receivableCount);
        $number        = $objPagination->getNumber();
        $numberOfPages = ceil($receivableCount / $number);
        $objPagination->setNumberOfPages($numberOfPages);
        if (!empty($predicate = $objSort->getPredicate())) {
            $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
            $whereCriteria->ORDER($predicate, $reverse);
        }
        $start = $objPagination->getStart();
        $whereCriteria->LIMIT($start, $number);

        $arrayData = ChannelDao::instance()->getChannelReceivable($whereCriteria);
        if (!empty($arrayData)) {
            foreach ($arrayData as $i => $data) {
                $arrayData[$i]['cr_id'] = encode($data['receivable_id']);
            }
        }
        $tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }

    public function updateChannelReceivable(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->updateChannelReceivable($whereCriteria, $arrayUpdateData, $update_type);
    }
}