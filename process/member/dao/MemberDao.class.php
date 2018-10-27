<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace member;
class MemberDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new MemberDao();

		return self::$objDao;
	}

	public function getMember($field = '', \WhereCriteria $whereCriteria) {
		return $this->setDsnRead($this->getDsnRead())->setTable('member')->getList($field, $whereCriteria);//DBCache($cacheId)->
	}

}