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

	public function getMember(\WhereCriteria $whereCriteria, $field = '') {
		return $this->setDsnRead($this->getDsnRead())->setTable('member')->getList($whereCriteria, $field);//DBCache($cacheId)->
	}

	public function saveMember($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnRead($this->getDsnWrite())->setTable('member')->insert($arrayData, $insert_type);
    }

    public function updateMember(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('member')->update($whereCriteria, $arrayUpdateData, $update_type);
    }

	public function getMemberLevel($whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('member_level')->getList($whereCriteria, $field);
    }

}