<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class ChannelDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new ChannelDao();

		return self::$objDao;
	}

	public function getChannel(\WhereCriteria $whereCriteria, string $field = '*') {
		if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('channel_id');

		return $this->setDsnRead($this->getDsnRead())->setTable('channel')->getList($whereCriteria, $field);//DBCache($cacheId)->
	}

    //--------Channel//-----------//
    public function saveChannel($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel')->insert($arrayData, $insert_type);
    }

    public function updateChannel(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //--------Channel//-----------//

    //Attribute
    public function getAttribute(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_attribute')->getList($whereCriteria, $field);
    }

    public function saveAttribute($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_attribute')->insert($arrayData, $insert_type);
    }

    public function updateAttribute(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_attribute')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //channel_item_attr_value
    public function getAttributeValue(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_item_attribute_value')->getList($whereCriteria, $field);
    }

    public function deleteAttributeValue(\WhereCriteria $whereCriteria) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_item_attribute_value')->delete($whereCriteria);
    }

    public function batchInsertAttributeValue($arrayValues, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_item_attribute_value')->batchInsert($arrayValues, $insert_type);
    }

    //upload images
    public function getUploadImages(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_upload_images')->getList($whereCriteria, $field);
    }

    public function getUploadImagesCount(\WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_upload_images')->getCount($whereCriteria, 'images_id');
    }

    public function saveUploadImages($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_upload_images')->insert($arrayData, $insert_type);
    }

    //save channel_item
    public function getChannelItem(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_item')->getList($whereCriteria, $field);
    }

    public function getChannelItemCount(\WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_item')->getCount($whereCriteria,'item_id');
    }

    public function saveChannelItem($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_item')->insert($arrayData, $insert_type);
    }

    public function updateChannelItem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_item')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    //payment_type
    public function getPaymentType(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('payment_type')->getList($whereCriteria, $field);
    }

    public function savePaymentType($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('payment_type')->insert($arrayData, $insert_type);
    }

    public function updatePaymentType(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('payment_type')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    //customer_market
    public function getCustomerMarket(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('customer_market')->getList($whereCriteria, $field);
    }

    public function saveCustomerMarket($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('customer_market')->insert($arrayData, $insert_type);
    }

    public function updateCustomerMarket(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('customer_market')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    //channel_layout_price_system
    public function getLayoutPriceSystem(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_layout_price_system')->getList($whereCriteria, $field);
    }

    public function saveLayoutPriceSystem($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price_system')->insert($arrayData, $insert_type);
    }

    public function updateLayoutPriceSystem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price_system')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function batchInsertPriceSystemLayout($arrayData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price_system_layout')->batchInsert($arrayData);
    }

    public function getLayoutPriceSystemLayout(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_layout_price_system_layout')->getList($whereCriteria, $field);
    }

    public function deletePriceSystemLayout(\WhereCriteria $whereCriteria) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price_system_layout')->delete($whereCriteria);
    }
    //channel_layout_price
    public function getLayoutPrice(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_layout_price')->getList($whereCriteria, $field);
    }

    public function saveLayoutPrice($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price')->insert($arrayData, $insert_type);
    }

    public function batchInsertLayoutPrice($arrayValues, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price')->batchInsert($arrayValues, $insert_type);
    }

    public function updateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('channel_layout_price')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

    public function batchUpdateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdate, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_layout_price')->batchUpdateByKey($whereCriteria, $arrayUpdate, $update_type);
    }

    //channel_cancellation_policy
    public function getCancellationPolicy(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_cancellation_policy')->getList($whereCriteria, $field);
    }

    public function saveCancellationPolicy($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_cancellation_policy')->insert($arrayData, $insert_type);
    }

    public function updateCancellationPolicy(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_cancellation_policy')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //channel_setting
    public function getChannelSettingEntity(\WhereCriteria $whereCriteria, $field = null) : Channel_settingEntity {
        return $this->setDsnRead($this->getDsnRead())->setEntity('\wise\Channel_settingEntity')->getEntity($whereCriteria, $field);
    }

    public function getChannelSettingList(\WhereCriteria $whereCriteria, $field = null)  {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_setting')->getList($whereCriteria, $field);
    }

    public function saveChannelSetting($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_setting')->insert($arrayData, $insert_type);
    }

    public function updateChannelSetting(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_setting')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
    //business_day
    public function getBusinessDay(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_business_day')->getList($whereCriteria, $field);
    }

    public function saveBusinessDay($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_business_day')->insert($arrayData, $insert_type);
    }

    public function updateBusinessDay(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_business_day')->update($whereCriteria, $arrayUpdateData, $update_type);
    }
}