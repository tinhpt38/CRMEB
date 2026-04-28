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

use app\services\order\OutStoreOrderRefundServices;
use app\services\order\OutStoreOrderServices;
use app\services\user\UserServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class OutPushJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Đẩy lệnh
     * @param int $oid
     * @param string $pushUrl
     * @param int $step
     * @return bool
     */
    public function orderCreate(int $oid, string $pushUrl, int $step = 0): bool
    {
        if ($step > 2) {
            Log::error('Đặt hàng' . $oid . 'Đẩy không thành công');
            return true;
        }

        try {
            /** @var OutStoreOrderServices $services */
            $services = app()->make(OutStoreOrderServices::class);
            if (!$services->orderCreatePush($oid, $pushUrl)) {
                OutPushJob::dispatchSecs(($step + 1) * 5, 'orderCreate', [$oid, $pushUrl, $step + 1]);
            }
        } catch (\Exception $e) {
            Log::error('Đặt hàng' . $oid . 'Đẩy không thành công,Lý do thất bại:' . $e->getMessage());
            OutPushJob::dispatchSecs(($step + 1) * 5, 'orderCreate', [$oid, $pushUrl, $step + 1]);
        }

        return true;
    }

    /**
     * Đẩy thanh toán đơn hàng
     * @param int $oid
     * @param string $pushUrl
     * @param int $step
     * @return bool
     */
    public function paySuccess(int $oid, string $pushUrl, int $step = 0): bool
    {
        if ($step > 2) {
            Log::error('Thanh toán đơn hàng' . $oid . 'Đẩy không thành công');
            return true;
        }

        try {
            /** @var OutStoreOrderServices $services */
            $services = app()->make(OutStoreOrderServices::class);
            if (!$services->paySuccessPush($oid, $pushUrl)) {
                OutPushJob::dispatchSecs(($step + 1) * 5, 'paySuccess', [$oid, $pushUrl, $step + 1]);
            }
        } catch (\Exception $e) {
            Log::error('Thanh toán đơn hàng' . $oid . 'Đẩy không thành công,Lý do thất bại:' . $e->getMessage());
            OutPushJob::dispatchSecs(($step + 1) * 5, 'paySuccess', [$oid, $pushUrl, $step + 1]);
        }

        return true;
    }

    /**
     * Tạo đơn hàng sau bán hàng
     * @param int $oid
     * @param string $pushUrl
     * @param int $step
     * @return bool
     */
    public function refundCreate(int $oid, string $pushUrl, int $step = 0): bool
    {
        if ($step > 2) {
            Log::error('Yêu cầu trả hàng / hoàn tiền' . $oid . 'Đẩy không thành công');
            return true;
        }

        try {
            /** @var OutStoreOrderRefundServices $services */
            $services = app()->make(OutStoreOrderRefundServices::class);
            if (!$services->refundCreatePush($oid, $pushUrl)) {
                OutPushJob::dispatchSecs(($step + 1) * 5, 'refundCreate', [$oid, $pushUrl, $step + 1]);
            }
        } catch (\Exception $e) {
            Log::error('Yêu cầu trả hàng / hoàn tiền' . $oid . 'Đẩy không thành công,Lý do thất bại:' . $e->getMessage());
            OutPushJob::dispatchSecs(($step + 1) * 5, 'refundCreate', [$oid, $pushUrl, $step + 1]);
        }
        return true;
    }

    /**
     * Hủy đơn đăng ký
     * @param int $oid
     * @param string $pushUrl
     * @param int $step
     * @return bool
     */
    public function refundCancel(int $oid, string $pushUrl, int $step = 0): bool
    {
        if ($step > 2) {
            Log::error('Hủy đơn hàng sau bán hàng' . $oid . 'Đẩy không thành công');
            return true;
        }

        try {
            /** @var OutStoreOrderRefundServices $services */
            $services = app()->make(OutStoreOrderRefundServices::class);
            if (!$services->cancelApplyPush($oid, $pushUrl)) {
                OutPushJob::dispatchSecs(($step + 1) * 5, 'refundCancel', [$oid, $pushUrl, $step + 1]);
            }
        } catch (\Exception $e) {
            Log::error('Hủy đơn hàng sau bán hàng' . $oid . 'Đẩy không thành công,Lý do thất bại:' . $e->getMessage());
            OutPushJob::dispatchSecs(($step + 1) * 5, 'refundCancel', [$oid, $pushUrl, $step + 1]);
        }
        return true;
    }

    /**
     * Sự thay đổi số dư, điểm, hoa hồng và kinh nghiệm
     * @param array $data
     * @param string $pushUrl
     * @param int $step
     * @return bool
     */
    public function userUpdate(array $data, string $pushUrl, int $step = 0): bool
    {
        if ($step > 2) {
            Log::error('Đẩy thay đổi người dùng không thành công');
            return true;
        }

        try {
            /** @var UserServices $services */
            $services = app()->make(UserServices::class);
            if (!$services->userUpdate($data, $pushUrl)) {
                OutPushJob::dispatchSecs(($step + 1) * 5, 'userUpdate', [$data, $pushUrl, $step + 1]);
            }
        } catch (\Exception $e) {
            OutPushJob::dispatchSecs(($step + 1) * 5, 'userUpdate', [$data, $pushUrl, $step + 1]);
        }
        return true;
    }
}