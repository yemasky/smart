<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace member;
class MemberService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new MemberService();
		}
		
		return self::$objService;
	}

	//--------member//-----------//
	public function getMember(\WhereCriteria $whereCriteria, $field = null) {
        return MemberDao::instance()->getMember($field, $whereCriteria);
	}

	public function saveMember($arrayData, $insert_type = 'INSERT') {
		return MemberDao::instance()->setTable('member')->insert($arrayData, $insert_type);
	}

	public function updateMember(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return MemberDao::instance()->setTable('member')->update($arrayUpdateData, $whereCriteria, $update_type);
	}
	//--------member//-----------//
    //--------member level//-----------//
    public function getMemberLevel(\WhereCriteria $whereCriteria, $field = null) {
        return MemberDao::instance()->setTable('member_level')->getList($field, $whereCriteria);
    }

    public function saveMemberLevel($arrayData, $insert_type = 'INSERT') {
        return MemberDao::instance()->setTable('member_level')->insert($arrayData, $insert_type);
    }

    public function updateMemberLevel(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return MemberDao::instance()->setTable('member_level')->update($arrayUpdateData, $whereCriteria, $update_type);
    }
    //--------member//-----------//
}