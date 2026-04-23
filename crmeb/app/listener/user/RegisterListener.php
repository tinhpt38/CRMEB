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
namespace app\listener\user;


use app\jobs\AgentJob;
use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\user\UserBillServices;
use app\services\user\UserServices;
use app\services\user\UserSpreadServices;
use crmeb\interfaces\ListenerInterface;

/**
 * Sự kiện sau đăng ký
 * Class RegisterListener
 * @package app\listener\user
 */
class RegisterListener implements ListenerInterface
{
    /**
     * Sự kiện sau đăng ký
     * @param $event
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle($event): void
    {
        [$spreadUid, $userType, $name, $uid, $isNew] = $event;

        if ($spreadUid) {
            if ($isNew) {
                //Mời người dùng mới để tăng trải nghiệm của họ
                /** @var UserBillServices $userBill */
                $userBill = app()->make(UserBillServices::class);
                $userBill->inviteUserIncExp((int)$spreadUid);
                //Tăng hoa hồng khuyến mãi
                /** @var UserServices $userServices */
                $userServices = app()->make(UserServices::class);
                $userServices->addBrokeragePrice($uid, $spreadUid);

                //Quảng bá người mới, tự xử lý và nâng cấp cấp độ phân phối vượt trội
                AgentJob::dispatch([$uid]);
            }
            //Ghi lại mối quan hệ ràng buộc khuyến mãi
            /** @var UserSpreadServices $userSpreadServices */
            $userSpreadServices = app()->make(UserSpreadServices::class);
            $res = $userSpreadServices->setSpread($uid, $spreadUid);

            //Liên kết người dùng cấp dưới thông báo tùy chỉnh thành công
            if ($res) {
                $phone = app()->make(UserServices::class)->value($spreadUid, 'phone');
                event('CustomNoticeListener', [$spreadUid, ['nickname' => $name, 'time' => date('Y-m-d H:i:s'), 'phone' => $phone], 'spread_success']);
            }
        }

        if ($isNew) {
            //Gửi phiếu giảm giá cho người mới
            /**@var StoreCouponIssueServices $storeCoupon */
            $storeCoupon = app()->make(StoreCouponIssueServices::class);
            $storeCoupon->userFirstSubGiveCoupon((int)$uid);

            //Renren Distribution mở quyền khuyến mãi
            if (sys_config('brokerage_func_status') && sys_config('store_brokerage_statu') == 2) {
                /** @var UserServices $userServices */
                $userServices = app()->make(UserServices::class);
                $userServices->update($uid, ['is_promoter' => 1]);
            }
        }
    }
}
