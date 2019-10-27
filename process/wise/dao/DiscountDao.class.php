<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class DiscountDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new DiscountDao();

		return self::$objDao;
	}
    //Role
    public function getDiscount(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_discount')->getList($whereCriteria, $field);
    }

    public function getChannelDiscountCount(\WhereCriteria $whereCriteria) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_discount')->getCount($whereCriteria,'discount_id');
    }

    public function saveDiscount($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_discount')->insert($arrayData, $insert_type);
    }

    public function updateDiscount(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_discount')->update($whereCriteria, $arrayUpdateData);
    }
    //

}