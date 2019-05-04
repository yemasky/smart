<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace member;
class MemberServiceImpl extends \BaseServiceImpl implements MemberService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
			return self::$objService;
		}
		self::$objService = new MemberServiceImpl();

		return self::$objService;
	}

	//* return member_id *//
	public function getMember(\HttpRequest $objRequest, $field = null) {
        $member_email  = $objRequest->getInput('member_email');
        $member_mobile = $objRequest->getInput('member_mobile');
        $member_idcard_type = $objRequest->getInput('member_idcard_type');
        $member_idcard_number = $objRequest->getInput('member_idcard_number');

        $whereCriteria = new \WhereCriteria();
        if(!empty($email)) $whereCriteria->EQ('member_email', $member_email);
        if(!empty($email)) $whereCriteria->EQ('member_mobile', $member_mobile);
        if(!empty($member_idcard_type)) $whereCriteria->EQ('id_type', $member_idcard_type);
        if(!empty($member_idcard_number)) $whereCriteria->EQ('id_number', $member_idcard_number);
		return MemberDao::instance()->getMember($whereCriteria, $field);
	}

	public function saveMember($arrayData, $insert_type = 'INSERT') {
        return MemberDao::instance()->saveMember($arrayData, $insert_type);
	}

	public function updateMember(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
		return MemberDao::instance()->updateMember($whereCriteria, $arrayUpdateData);
	}
	//
    public function getMemberLevelByMemberId($member_id, $channel_father_id, $field = 'market_id') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('member_id', $member_id)->EQ('channel_father_id', $channel_father_id);
        return MemberDao::instance()->getMemberLevel($whereCriteria, $field);
    }

    public function getMemberLevel(\WhereCriteria $whereCriteria, $field = null) {
        // TODO: Implement getMemberLevel() method.
        return MemberDao::instance()->getMemberLevel($whereCriteria, $field);
    }

    public function saveMemberLevel($arrayData, $insert_type = 'INSERT') {
        return MemberDao::instance()->setTable('member_level')->insert($arrayData, $insert_type);
    }

    public function updateMemberLevel(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '') {
        return MemberDao::instance()->setTable('member_level')->update($arrayUpdateData, $whereCriteria, $update_type);
    }


}