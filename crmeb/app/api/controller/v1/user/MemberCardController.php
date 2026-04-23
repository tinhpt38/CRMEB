<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\api\controller\v1\user;

use app\Request;
use app\services\activity\coupon\StoreCouponUserServices;
use app\services\order\OtherOrderServices;
use app\services\other\AgreementServices;
use app\services\user\member\MemberCardServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/** thẻ thành viên
 * Class MemberCardController
 * @package app\api\controller\v1\user
 */
class MemberCardController
{
    protected $services = NUll;

    protected $channelType = ['weixin' => 'wechat', 'weixinh5' => 'weixinh5', 'routine' => 'routine', 'h5' => 'h5'];

    /** Khởi tạo xử lý lớp dịch vụ
     * MemberCardController constructor.
     * @param MemberCardServices $memberCardServices
     */
    public function __construct(MemberCardServices $memberCardServices)
    {
        $this->services = $memberCardServices;
    }

    /**
     * Giao diện dữ liệu trang chủ thẻ thành viên
     * @param Request $request
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(Request $request)
    {
        $member_rights = $this->services->getMemberRightsInfo();
        /** @var AgreementServices $agreementService */
        $agreementService = app()->make(AgreementServices::class);
        $member_explain = $agreementService->getAgreementBytype(1);
        $member_explain = (isset($member_explain['status']) && $member_explain['status'] == 1) ? $member_explain : "";
        $member_type = $this->services->DoMemberType();
        /** @var OtherOrderServices $OtherOrderServices */
        $OtherOrderServices = app()->make(OtherOrderServices::class);
        $is_get_free = $OtherOrderServices->isCanGetFree((int)$request->uid());
        /** @var StoreCouponUserServices $couponUserService */
        $couponUserService = app()->make(StoreCouponUserServices::class);
        $coupons = $couponUserService->getMemberCoupon($request->uid());
        return app('json')->success([
            'member_rights' => $member_rights['member_right'],
            'is_get_free' => $is_get_free,
            'member_explain' => $member_explain,
            'member_type' => $member_type,
            'member_coupons' => $coupons
        ]);
    }

    /**
     * Bí mật thẻ nhận thẻ thành viên
     * @param Request $request
     * @return mixed
     */
    public function draw_member_card(Request $request)
    {
        $data = $request->postMore([
            ['member_card_code', ''],
            ['member_card_pwd', ''],
            ['from', 'weixin'],
        ]);
        $data['from'] = strtolower(trim($data['from']));
        if (!array_key_exists($data['from'], $this->channelType)) return app('json')->fail('Hoạt động trái phép');
        $data['from'] = $this->channelType[$data['from']];
        $uid = (int)$request->uid();
        $this->services->drawMemberCard($data, $uid);
        return app('json')->success('Kích hoạt thành công');
    }

    /**
     * Giao diện phiếu giảm giá thành viên
     * @param Request $request
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function memberCouponList(Request $request)
    {
        /** @var StoreCouponUserServices $couponUserService */
        $couponUserService = app()->make(StoreCouponUserServices::class);
        $coupons = $couponUserService->getMemberCoupon($request->uid());
        return app('json')->success($coupons);
    }

    /**
     * Tính ngày thành viên
     * @param Request $request
     * @return mixed
     */
    public function getOverdueTime(Request $request)
    {
        $params = $request->getMore([
            ['member_type', ''],
            ['vip_day', ''],
            ['mc_id', 0]
        ]);
        $member_type = $params['member_type'];
        $vip_day = $params['vip_day'];
        /** @var \app\services\user\UserServices $userServices */
        $userServices = app()->make(\app\services\user\UserServices::class);
        $user_info = $userServices->getUserInfo($request->uid());
        if ($member_type == 'ever') {
            $overdue_time = 0;
            $is_ever_level = 1;
        } else {
            if ($user_info['is_money_level'] == 0) {
                $overdue_time = bcadd(bcmul($vip_day, 86400, 0), time(), 0);
            } else {
                $overdue_time = bcadd(bcmul($vip_day, 86400, 0), $user_info['overdue_time'], 0);
            }
            $is_ever_level = 0;
        }
        if ($is_ever_level == 1 || $user_info['is_ever_level']) {
            $res = "thành viên thường trực";
        } else {
            $res = date('Y-m-d', $overdue_time);
        }

        return app('json')->success(['data' => $res]);
    }


}
