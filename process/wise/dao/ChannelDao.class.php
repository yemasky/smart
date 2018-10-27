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

	public function getChannel(string $field = '*', \WhereCriteria $whereCriteria) {
		if($field == '') $field = '*';
		if(empty($whereCriteria->getHashKey())) $whereCriteria->setHashKey('channel_id');

		return $this->setDsnRead($this->getDsnRead())->setTable('channel')->getList($field, $whereCriteria);//DBCache($cacheId)->
	}

}