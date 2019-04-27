<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace member;
interface MemberService extends \BaseService {
	//--------member//-----------//
	public function getMember(\HttpRequest $objRequest, $field = null);

	public function saveMember($arrayData, $insert_type = 'INSERT');

	public function updateMember(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '');
	//--------member//-----------//
    //--------member level//-----------//
    public function getMemberLevelByMemberId($member_id, $channel_father_id, $field = null);

    public function getMemberLevel(\WhereCriteria $whereCriteria, $field = null);

    public function saveMemberLevel($arrayData, $insert_type = 'INSERT');

    public function updateMemberLevel(\WhereCriteria $whereCriteria, $arrayUpdateData, $update_type = '');
    //--------member//-----------//
}