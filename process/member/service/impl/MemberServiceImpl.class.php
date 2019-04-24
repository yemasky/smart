<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace member;
class MemberServiceImpl extends \BaseServiceImpl {
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
		return MemberDao::instance()->getMember($field, $whereCriteria);
	}

	public function saveMember($arrayData, $insert_type = 'INSERT') {
        return MemberService::instance()->saveMember($arrayData, $insert_type);
	}

	public function updateMember(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		return MemberService::instance()->updateMember($whereCriteria, $arrayUpdateData);
	}
	//
    public function getMemberLevel($member_id, $channel_father_id, $field = 'market_id') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('member_id', $member_id)->EQ('channel_father_id', $channel_father_id);
        return MemberService::instance()->getMemberLevel($whereCriteria, $field);
    }


}