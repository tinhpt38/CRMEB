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

namespace app\jobs;

use app\services\order\OtherOrderServices;
use app\services\order\StoreOrderEconomizeServices;
use app\services\user\member\MemberCardServices;
use app\services\user\UserServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Hàng đợi tin nhắn đặt hàng
 * Class OrderJob
 * @package crmeb\jobs
 */class OtherOrderJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Gửi tin nhắn khi thanh toán đơn hàng được thực hiện thành công
     * @param $order
     * @return bool
     */    public function doJob($order)
    {
        //Cập nhật số lượng đơn hàng thanh toán của Khách hàng
        try {
            $this->setUserPayCountAndPromoter($order);
        } catch (\Throwable $e) {
            Log::error('Không thể cập nhật số đơn đặt hàng của Khách hàng,Lý do thất bại:' . $e->getMessage());
        }

        // Tính toán mức tiết kiệm của Khách hàng
        try {
            $this->setEconomizeMoney($order);
        } catch (\Throwable $e) {
            Log::error('Tính toán tiết kiệm,Lý do thất bại:' . $e->getMessage());
        }

        //Điểm thưởng cho đơn hàng thu ngân
        try {
            $this->sendMemberIntegral($order);
        } catch (\Throwable $e) {
            Log::error('Hoàn trả điểm tiêu dùng không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }

    /**
     * Đặt số lượng Khách hàng mua hàng và thời gian phát hiện để trở thành người quảng bá
     * @param $order
     */    public function setUserPayCountAndPromoter($order)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->get($order['uid']);
        if ($userInfo) {
            $userInfo->pay_count = $userInfo->pay_count + 1;
            if (!$userInfo->is_promoter) {
                /** @var OtherOrderServices $orderServices */                $orderServices = app()->make(OtherOrderServices::class);
                $price = $orderServices->sum(['paid' => 1, 'uid' => $userInfo['uid']], 'pay_price');
                $status = is_brokerage_statu($price);
                if ($status) {
                    $userInfo->is_promoter = 1;
                }
            }
            $userInfo->save();
        }
    }

    /** Điểm thưởng thanh toán ngoại tuyến
     * @param $order
     * @return bool
     */    public function sendMemberIntegral($order)
    {
        //Phần thưởng chỉ có sẵn cho thanh toán ngoại tuyến
        if ($order['type'] == 3) {
            $order_give_integral = sys_config('order_give_integral');
            $order_integral = bcmul($order_give_integral, (string)$order['pay_price'], 0);
            /** @var UserServices $userService */            $userService = app()->make(UserServices::class);
            $userInfo = $userService->getUserInfo($order['uid']);
            if (!$userInfo) return false;
            if ($userInfo['is_money_level'] > 0) {
                //Kiểm tra xem phần thưởng nhân đôi điểm tiêu thụ có được kích hoạt hay không
                /** @var MemberCardServices $memberCardService */                $memberCardService = app()->make(MemberCardServices::class);
                $integral_rule_number = $memberCardService->isOpenMemberCard('integral');
                if ($integral_rule_number) {
                    $order_integral = bcadd($order_integral, $integral_rule_number, 2);
                }
            }
            if ($order_integral > 0) {
                $integral = bcadd(abs($userInfo['integral']), abs($order_integral), 2);
                $userService->update(['uid' => $order['uid']], ['integral' => $integral]);
            }
        }
    }

    /**
     * Tính toán tiết kiệm
     * @param $order
     */    public function setEconomizeMoney($order)
    {
        //Khoản tiết kiệm chỉ được tính cho thanh toán ngoại tuyến
        if ($order['type'] == 3) {
            /** @var StoreOrderEconomizeServices $economizeService */            $economizeService = app()->make(StoreOrderEconomizeServices::class);
            /** @var MemberCardServices $memberRightService */            $memberRightService = app()->make(MemberCardServices::class);
            $isOpenOfflin = $memberRightService->isOpenMemberCard('offline');
            if ($isOpenOfflin) {
                $save = [
                    'uid' => $order['uid'],
                    'order_id' => $order['order_id'],
                    'order_type' => 2,
                    'pay_price' => $order['pay_price'],
                    'offline_price' => bcsub($order['money'], $order['pay_price'], 2)
                ];
                $economizeService->addEconomize($save);
            }

        }
    }
}
