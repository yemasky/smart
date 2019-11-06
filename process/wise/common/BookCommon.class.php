<?php 
/*
	auther: cooc 
	email:yemasky@msn.com
*/
namespace wise;
class BookCommon implements \BaseService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new BookCommon();

        return self::$objService;
    }
    //查询会员数据
    public function doCheckMember(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $field       = 'member_id';
        $arrayMember = \member\MemberServiceImpl::instance()->getMember($objRequest, $field);
        if (!empty($arrayMember)) {
            $member_id         = $arrayMember[0]['member_id'];
            $channel_father_id = $objRequest->validInput('channel_father_id');
            $arrayMemberLevel  = \member\MemberServiceImpl::instance()->getMemberLevelByMemberId($member_id, $channel_father_id);
            if (!empty($arrayMemberLevel)) {
                $arrayMemberLevel[0]['member_id'] = $member_id;
                return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayMemberLevel[0]);
            }
        }
        return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_found']);
    }

    //查询协议公司数据
    public function doGetReceivable(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $company_id    = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id    = $objRequest->channel_id;
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->ArrayIN('channel_id', [0, $channel_id])->EQ('valid', '1');
        $arrayReceivable   = ChannelServiceImpl::instance()->getChannelReceivable($whereCriteria, 'receivable_id,receivable_name');
        $objSuccessService = new \SuccessService();
        $objSuccessService->setData(['receivableData' => $arrayReceivable]);
        return $objResponse->successServiceResponse($objSuccessService);
    }

}
?>