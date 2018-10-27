<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class ChannelService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new ChannelService();
		}
		
		return self::$objService;
	}

	//--------Channel//-----------//
	public function getChannel(\WhereCriteria $whereCriteria, $field = '') {
        return ChannelDao::instance()->getChannel($field, $whereCriteria);
	}

	public function saveChannel($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel')->insert($arrayData, $insert_type);
	}

	public function updateChannel(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('channel')->update($arrayUpdateData, $whereCriteria, $update_type);
	}
	//--------Channel//-----------//

    //Attribute
    public function getAttribute(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->setTable('channel_attribute')->getList($field, $whereCriteria);
    }

    public function saveAttribute($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->setTable('channel_attribute')->insert($arrayData, $insert_type);
    }

    public function updateAttribute(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->setTable('channel_attribute')->update($arrayUpdateData, $whereCriteria, $update_type);
    }
	//channel_item_attr_value
	public function getAttributeValue(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_item_attribute_value')->getList($field, $whereCriteria);
	}

	public function deleteAttributeValue(\WhereCriteria $whereCriteria) {
		return ChannelDao::instance()->setTable('channel_item_attribute_value')->delete($whereCriteria);
	}

    public function batchInsertAttributeValue($arrayValues, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_item_attribute_value')->batchInsert($arrayValues, $insert_type);
	}

	//upload images
	public function getUploadImages(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_upload_images')->getList($field, $whereCriteria);
	}

	public function getUploadImagesCount(\WhereCriteria $whereCriteria) {
		return ChannelDao::instance()->setTable('channel_upload_images')->getCount($whereCriteria, 'images_id');
	}

	public function saveUploadImages($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_upload_images')->insert($arrayData, $insert_type);
	}

	//save channel_item
	public function getChannelItem(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_item')->getList($field, $whereCriteria);
	}

	public function getChannelItemCount(\WhereCriteria $whereCriteria) {
		return ChannelDao::instance()->setTable('channel_item')->getCount($whereCriteria,'item_id');
	}

	public function saveChannelItem($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_item')->insert($arrayData, $insert_type);
	}

	public function updateChannelItem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('channel_item')->update($arrayUpdateData, $whereCriteria, $update_type);
	}

	//payment_type
    public function getPaymentType(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->setTable('payment_type')->getList($field, $whereCriteria);
    }

    public function savePaymentType($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->setTable('payment_type')->insert($arrayData, $insert_type);
    }

    public function updatePaymentType(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->setTable('payment_type')->update($arrayUpdateData, $whereCriteria, $update_type);
    }

    //customer_market
	public function getCustomerMarket(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('customer_market')->getList($field, $whereCriteria);
	}

	public function saveCustomerMarket($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('customer_market')->insert($arrayData, $insert_type);
	}

	public function updateCustomerMarket(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('customer_market')->update($arrayUpdateData, $whereCriteria, $update_type);
	}

	//channel_layout_price_system
	public function getLayoutPriceSystem(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_layout_price_system')->getList($field, $whereCriteria);
	}

	public function saveLayoutPriceSystem($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_layout_price_system')->insert($arrayData, $insert_type);
	}

	public function updateLayoutPriceSystem(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('channel_layout_price_system')->update($arrayUpdateData, $whereCriteria, $update_type);
	}

	public function batchInsertPriceSystemLayout($arrayData) {
		return ChannelDao::instance()->setTable('channel_layout_price_system_layout')->batchInsert($arrayData);
	}

	public function getLayoutPriceSystemLayout(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_layout_price_system_layout')->getList($field, $whereCriteria);
	}

	public function deletePriceSystemLayout(\WhereCriteria $whereCriteria) {
		return ChannelDao::instance()->setTable('channel_layout_price_system_layout')->delete($whereCriteria);
	}
	//channel_layout_price
	public function getLayoutPrice(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_layout_price')->getList($field, $whereCriteria);
	}

	public function saveLayoutPrice($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_layout_price')->insert($arrayData, $insert_type);
	}

	public function batchInsertLayoutPrice($arrayValues, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_layout_price')->batchInsert($arrayValues, $insert_type);
	}

	public function updateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('channel_layout_price')->update($arrayUpdateData, $whereCriteria, $update_type);
	}

	public function batchUpdateLayoutPrice(\WhereCriteria $whereCriteria, $arrayUpdate, $update_type = '') {
		return ChannelDao::instance()->setTable('channel_layout_price')->batchUpdateByKey($arrayUpdate, $whereCriteria, $update_type);
	}

	//channel_cancellation_policy
	public function getCancellationPolicy(\WhereCriteria $whereCriteria, $field = null) {
		return ChannelDao::instance()->setTable('channel_cancellation_policy')->getList($field, $whereCriteria);
	}

	public function saveCancellationPolicy($arrayData, $insert_type = 'INSERT') {
		return ChannelDao::instance()->setTable('channel_cancellation_policy')->insert($arrayData, $insert_type);
	}

	public function updateCancellationPolicy(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return ChannelDao::instance()->setTable('channel_cancellation_policy')->update($arrayUpdateData, $whereCriteria, $update_type);
	}
	//channel_setting
    public function getChannelSetting(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->setTable('channel_setting')->getList($field, $whereCriteria);
    }

    public function saveChannelSetting($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->setTable('channel_setting')->insert($arrayData, $insert_type);
    }

    public function updateChannelSetting(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->setTable('channel_setting')->update($arrayUpdateData, $whereCriteria, $update_type);
    }
    //business_day
    public function getBusinessDay(\WhereCriteria $whereCriteria, $field = null) {
        return ChannelDao::instance()->setTable('channel_business_day')->getList($field, $whereCriteria);
    }

    public function saveBusinessDay($arrayData, $insert_type = 'INSERT') {
        return ChannelDao::instance()->setTable('channel_business_day')->insert($arrayData, $insert_type);
    }

    public function updateBusinessDay(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return ChannelDao::instance()->setTable('channel_business_day')->update($arrayUpdateData, $whereCriteria, $update_type);
    }

}