<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ChannelServiceImpl implements \BaseServiceImpl {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
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
		$arrayChannelList = ChannelService::instance()->DBCache($cacheChannelId)->getChannel($whereCriteria);
		if(!empty($arrayChannelList)) {
			if(empty($channel)) {
				foreach($arrayChannelList as $channel_id => $value) {
					$arrayChannelList[$channel_id]['company_id'] = 0;
					$arrayChannelList[$channel_id]['id']         = encode($arrayChannelList[$channel_id]['channel_id'], getDay());
				}

				return $arrayChannelList;
			} else {
				$arrayChannel = [];
				foreach($arrayChannelList as $channel_id => $value) {
					if($value['channel'] == $channel) {
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

		return ChannelService::instance()->DBCache($cacheChannelId, -1)->saveChannel($arrayData, $insert_type);
	}

	public function updateChannel(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		$cacheChannelId = CacheConfig::getCacheId('channel', $arrayUpdateData['company_id']);

		return ChannelService::instance()->DBCache($cacheChannelId, -1)->updateChannel($whereCriteria, $arrayUpdateData);
	}

	//arrtibute
	public function getAttribute($company_id, $channel_config, $channel_id = 0, $item_id = 0) {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('channel_config', $channel_config)->ArrayIN('company_id', [$company_id, '0']);
		if($channel_id > 0) $whereCriteria->ArrayIN('channel_id', [$channel_id, '0']);
		if($item_id > 0) $whereCriteria->ArrayIN('item_id', [$item_id, '0']);
		$cacheAttributeId = $company_id . '_' . $channel_config . '_' . $channel_id . '_' . $item_id;
		$cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);
		$whereCriteria->setHashKey('attribute_id');

		return ChannelService::instance()->DBCache($cacheAttributeId)->getAttribute($whereCriteria);
	}

	public function saveAttribute($arrayData, $insert_type = 'INSERT') {
		$channel_id       = isset($arrayData['channel_id']) ? $arrayData['channel_id'] : '0';
		$item_id          = isset($arrayData['item_id']) ? $arrayData['item_id'] : '0';
		$cacheAttributeId = $arrayData['company_id'] . '_' . $arrayData['channel_config'] . '_' . $channel_id . '_' . $item_id;
		$cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);

		return ChannelService::instance()->DBCache($cacheAttributeId, -1)->saveAttribute($arrayData, $insert_type);
	}

	public function updateAttribute(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		//$channel_id       = isset($where['channel_id']) ? $where['channel_id'] : '0';
		//$item_id          = isset($where['item_id']) ? $where['item_id'] : '0';
		//$cacheAttributeId = $where['company_id'] . '_' . $where['channel_config'] . '_' . $channel_id . '_' . $item_id;
		$cacheAttributeId = $whereCriteria->getWhere();
		$cacheAttributeId = CacheConfig::getCacheId('attribute', $cacheAttributeId);

		return ChannelService::instance()->DBCache($cacheAttributeId, -1)->updateAttribute($whereCriteria, $arrayUpdateData);
	}

	//AttrValue
	public function getAttributeValue(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelService::instance()->getAttributeValue($whereCriteria, $field);
	}

	public function deleteAttributeValue(\WhereCriteria $whereCriteria) {
		return ChannelService::instance()->deleteAttributeValue($whereCriteria);
	}

	public function batchInsertAttrValue($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->batchInsertAttributeValue($arrayData, $insert_type);
	}

	//upload images
	public function getUploadImages(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelService::instance()->getUploadImages($whereCriteria, $field);
	}

	public function getUploadImagesCount(\WhereCriteria $whereCriteria) {
		return ChannelService::instance()->getUploadImagesCount($whereCriteria);
	}

	public function saveUploadImages($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->saveUploadImages($arrayData, $insert_type);
	}

	//save channel_item
	public function getChannelItemHash(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id     = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
		$channel_id     = $objRequest->id;
		$channel_id     = !empty($channel_id) ? decode($channel_id, getDay()) : '';
		$channel_config = $objRequest->channel_config;

		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id);
		if(!empty($channel_id) && $channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
		if(!empty($channel_config)) $whereCriteria->EQ('channel_config', $channel_config);;
		//
		$field       = $objRequest->field;
		$toHashArray = $objRequest->toHashArray == true ? true : false;
		$whereCriteria->setMultiple($toHashArray);
		$hashKey = $objRequest->hashKey;
		if(!empty($hashKey)) $whereCriteria->setHashKey($hashKey);
		$fatherHash = $objRequest->fatherHash;
		if(!empty($fatherHash)) $whereCriteria->setFatherKey($fatherHash);
		$childrenHash = $objRequest->childrenHash;
		if(!empty($childrenHash)) $whereCriteria->setChildrenKey($childrenHash);
		$arrayDataList = ChannelService::instance()->getChannelItem($whereCriteria, $field);

		return $arrayDataList;
	}

	public function getChannelItem(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelService::instance()->getChannelItem($whereCriteria, $field);
	}

	public function getChannelItemCount(\WhereCriteria $whereCriteria) {
		return ChannelService::instance()->getChannelItemCount($whereCriteria);
	}

	public function saveChannelItem($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->saveChannelItem($arrayData, $insert_type);
	}

	public function updateChannelItem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelService::instance()->updateChannelItem($whereCriteria, $arrayUpdateData, $update_type);
	}

	//payment_type
	public function checkSameNamePaymentType($company_id, $payment_name = '', $payment_en_name = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->ArrayIN('company_id', [$company_id, '0']);
		if(!empty($payment_name)) $whereCriteria->EQ('payment_name', $payment_name);
		//if(!empty($payment_en_name)) $arrayCondition['where']['payment_en_name'] = $payment_en_name;
		$arrayResult = ChannelService::instance()->getPaymentType($whereCriteria, 'payment_id');
		if(!empty($arrayResult)) {
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

		return ChannelService::instance()->DBCache($cachePaymentTypeId)->getPaymentType($whereCriteria, $field);
	}

	public function savePaymentType($arrayData, $insert_type = 'INSERT') {
		$cachePaymentTypeId = CacheConfig::getCacheId('paymentType', $arrayData['company_id']);

		return ChannelService::instance()->DBCache($cachePaymentTypeId, -1)->savePaymentType($arrayData, $insert_type);
	}

	public function updatePaymentType($company_id, $payment_id, $arrayUpdateData, $update_type = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id)->EQ('payment_id', $payment_id);
		$cachePaymentTypeId = CacheConfig::getCacheId('paymentType', $company_id);

		return ChannelService::instance()->DBCache($cachePaymentTypeId, -1)->updatePaymentType($whereCriteria, $arrayUpdateData, $update_type);
	}
	//
	//CustomerMarket
	public function checkSameNameCustomerMarket($company_id, $market_name = '', $market_en_name = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->ArrayIN('company_id', [$company_id, '0']);
		if(!empty($market_name)) $whereCriteria->EQ('market_name', $market_name);
		//if(!empty($market_name)) $arrayCondition['where']['market_en_name'] = $market_en_name;
		$arrayResult = ChannelService::instance()->getCustomerMarket($whereCriteria, 'market_id');
		if(!empty($arrayResult)) {
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

		return ChannelService::instance()->DBCache($cacheCustomerMarketId)->getCustomerMarket($whereCriteria, $field);
	}

	public function saveCustomerMarket($arrayData, $insert_type = 'INSERT') {
		$cacheCustomerMarketId = CacheConfig::getCacheId('customer_market', $arrayData['company_id']);

		return ChannelService::instance()->DBCache($cacheCustomerMarketId, -1)->saveCustomerMarket($arrayData, $insert_type);
	}

	public function updateCustomerMarket($company_id, $market_id, $arrayUpdateData, $update_type = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id)->EQ('market_id', $market_id);
		$cacheCustomerMarketId = CacheConfig::getCacheId('customer_market', $company_id);

		return ChannelService::instance()->DBCache($cacheCustomerMarketId, -1)->updateCustomerMarket($whereCriteria, $arrayUpdateData, $update_type);
	}

	//channel_layout_price_system
	public function checkSameNamePriceSystem($company_id, $price_system_name = '', $price_system_en_name = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id);
		if(!empty($price_system_name)) $whereCriteria->EQ('price_system_name', $price_system_name);
		//if(!empty($price_system_en_name)) $arrayCondition['where']['price_system_en_name'] = $price_system_en_name;
		$arrayResult = ChannelService::instance()->getLayoutPriceSystem($whereCriteria, 'price_system_id');
		if(!empty($arrayResult)) {
			return false;
		}

		return true;
	}

	public function getLayoutPriceSystem(\WhereCriteria $whereCriteria, $field = '') {
		if(empty($field))
			$field = 'price_system_id, price_system_father_id, channel_ids, price_system_name, market_ids, layout_item, '
				. 'formula,price_system_type, valid';

		return ChannelService::instance()->getLayoutPriceSystem($whereCriteria, $field);
	}

	public function getLayoutPriceSystemHash($company_id, $valid = '', $channel_id = '', $market_id = '', $layout_item_id = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id);
		if(!empty($channel_id) && $channel_id > 0) $whereCriteria->MATCH('channel_ids', $channel_id);
		if(!empty($market_id) && $market_id > 0) $whereCriteria->MATCH('market_ids', $market_id);
		if(!empty($layout_item_id) && $layout_item_id > 0) $whereCriteria->MATCH('layout_item', $layout_item_id);
		if($valid !== '') $whereCriteria->EQ('valid', $valid);
		$whereCriteria->setHashKey('price_system_id');
		$field = 'price_system_id, price_system_father_id, channel_ids, price_system_name,price_system_en_name, market_ids, layout_item, book_min_day, cancellation_policy,' . 'formula,price_system_type, valid';

		return ChannelService::instance()->getLayoutPriceSystem($whereCriteria, $field);
	}

	public function saveLayoutPriceSystem($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->saveLayoutPriceSystem($arrayData, $insert_type);
	}

	public function updateLayoutPriceSystem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelService::instance()->updateLayoutPriceSystem($whereCriteria, $arrayUpdateData, $update_type);
	}

	public function batchInsertPriceSystemLayout($arrayData) {
		return ChannelService::instance()->batchInsertPriceSystemLayout($arrayData);
	}

	public function getLayoutPriceSystemLayout($market_id, $field = null) {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('market_id', $market_id);

		return ChannelService::instance()->getLayoutPriceSystemLayout($whereCriteria, $field);
	}

	public function getPriceSystemLayout($market_id, $price_system_id = '', $channel_id = '', $layout_item_id = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('market_id', $market_id);
		if($price_system_id > 0) $whereCriteria->EQ('price_system_id', $price_system_id);
		if($price_system_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
		if($price_system_id > 0) $whereCriteria->EQ('layout_item_id', $layout_item_id);

		return ChannelService::instance()->getLayoutPriceSystem($whereCriteria, 'price_system_id,channel_id,market_id,layout_item_id');
	}

	public function deletePriceSystemLayout(\WhereCriteria $whereCriteria) {
		return ChannelService::instance()->deletePriceSystemLayout($whereCriteria);
	}

	//channel_layout_price
	public function getDateLayoutPrice($price_system_id, $betinDate, $endDate, $arrayItemId, $company_id, $field = null) {
		$whereCriteria = new \WhereCriteria();

		$whereCriteria->EQ('company_id', $company_id);
		if(is_array($price_system_id)) {
			$whereCriteria->ArrayIN('price_system_id', $price_system_id);
		} else {
			$whereCriteria->EQ('price_system_id', $price_system_id);
		}
		$whereCriteria->GE('layout_price_date', $betinDate);
		$whereCriteria->LE('layout_price_date', $endDate);
		$arrayCondition['where']['<='] = [];
		if(is_array($arrayItemId)) {
			$whereCriteria->ArrayIN('item_id', $arrayItemId);
		} else {
			$whereCriteria->EQ('item_id', $arrayItemId);
		}
		if(is_array($price_system_id)) {
			$whereCriteria->setHashKey('item_id')->setMultiple(false)->setFatherKey('price_system_id')
				->setChildrenKey('layout_price_date');

			return ChannelService::instance()->getLayoutPrice($whereCriteria, $field);
		}
		$whereCriteria->setHashKey('item_id')->setMultiple(false)->setChildrenKey('layout_price_date');

		return ChannelService::instance()->getLayoutPrice($whereCriteria, $field);
	}

	public function getLayoutPrice($company_id, $channel_id, $arraySystemId = array(), $in_date, $out_date, $sql = '',
		$arrayHashKey = array(), $field = null) {
		$whereCriteria = new \WhereCriteria();
		if($company_id > 0) $whereCriteria->EQ('company_id', $company_id);
		if($channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
		if(!empty($arraySystemId)) {
			$whereCriteria->ArrayIN('price_system_id', $arraySystemId);
		}
		if(isset($arrayHashKey['hashKey'])) $whereCriteria->setHashKey($arrayHashKey['hashKey']);
		if(isset($arrayHashKey['fatherKey'])) $whereCriteria->setFatherKey($arrayHashKey['fatherKey']);
		if(isset($arrayHashKey['childrenKey'])) $whereCriteria->setChildrenKey($arrayHashKey['childrenKey']);
		if(!empty($sql)) $whereCriteria->SQL($sql);

		$whereCriteria->GE('layout_price_date', substr($in_date, 0, 8) . '01');
		$whereCriteria->LE('layout_price_date', substr($out_date, 0, 8) . '01');

		return ChannelService::instance()->getLayoutPrice($whereCriteria, $field);
	}

	public function saveLayoutPrice($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->saveLayoutPrice($arrayData, $insert_type);
	}

	public function batchInsertLayoutPrice($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->batchInsertLayoutPrice($arrayData, $insert_type);
	}

	public function updateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelService::instance()->updateLayoutPrice($whereCriteria, $arrayUpdateData, $update_type);
	}

	public function batchUpdateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelService::instance()->batchUpdateLayoutPrice($whereCriteria, $arrayUpdateData, $update_type);
	}

	//channel_cancellation_policy
	public function getCancellationPolicy($company_id, $channel_id = '', $policy_id = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->ArrayIN('company_id', [$company_id, '0']);
		if(!empty($channel_id) && $channel_id > 0) $whereCriteria->EQ('channel_id', $channel_id);
		if(!empty($policy_id) && $policy_id > 0) $whereCriteria->EQ('policy_id', $policy_id);
		$field = 'policy_id,policy_name,policy_en_name,channel_id,rules,rules_value,rules_days,rules_time,begin_datetime,end_datetime,policy_type,valid';

		return ChannelService::instance()->getCancellationPolicy($whereCriteria, $field);
	}

	public function getCancellationPolicyCache($company_id, $channel_id = '', $policy_id = '') {

	}

	public function checkSameCancellationPolicy($company_id, $policy_name = '', $policy_en_name = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->ArrayIN('company_id', [$company_id, '0']);
		if(!empty($policy_name)) $whereCriteria->EQ('policy_name', $policy_name);
		//if(!empty($policy_en_name)) $arrayCondition['where']['policy_en_name'] = $policy_en_name;
		$arrayResult = ChannelService::instance()->getCancellationPolicy($whereCriteria, 'policy_id');
		if(!empty($arrayResult)) {
			return false;
		}

		return true;
	}

	public function saveCancellationPolicy($arrayData, $insert_type = 'INSERT') {
		return ChannelService::instance()->saveCancellationPolicy($arrayData, $insert_type);
	}

	public function updateCancellationPolicy($company_id, $policy_id, $arrayUpdateData, $update_type = '') {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('company_id', $company_id)->EQ('policy_id', $policy_id);

		return ChannelService::instance()->updateCancellationPolicy($whereCriteria, $arrayUpdateData, $update_type);
	}

	//
	public function getBusinessDay($channel_id) {
		$whereCriteria = new \WhereCriteria();
		$whereCriteria->EQ('channel_id', $channel_id)->ORDER('business_day_id')->LIMIT(0, 1);
		$arrayBusinessDay = ChannelService::instance()->getBusinessDay($whereCriteria, 'business_day');
		if(empty($arrayBusinessDay)) {
			return getToDay();
		}

		return $arrayBusinessDay[0]['business_day'];
	}

}