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


use app\services\activity\seckill\StoreSeckillServices;
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderRefundServices;
use app\services\order\StoreOrderServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Hủy đơn hàng chưa thanh toán đến hạn
 * Class UnpaidOrderCancelJob
 * @package crmeb\jobs
 */
class UnpaidOrderCancelJob extends BaseJobs
{

    use QueueTrait;

    public function doJob($orderId)
    {
        /** @var StoreOrderServices $services */
        $services = app()->make(StoreOrderServices::class);
        $orderInfo = $services->get($orderId);
        if (!$orderInfo) {
            return true;
        }
        if ($orderInfo->paid) {
            return true;
        }
        if ($orderInfo->is_del) {
            return true;
        }
        if ($orderInfo->pay_type == 'offline') {
            return true;
        }
        if ($orderInfo->is_cancel == 1) {
            return true;
        }
        /** @var StoreOrderCartInfoServices $cartServices */
        $cartServices = app()->make(StoreOrderCartInfoServices::class);
        $cartInfo = $cartServices->getOrderCartInfo($orderId);
        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);

        try {
            $res = $refundServices->transaction(function () use ($orderInfo, $refundServices) {
                //Trả lại điểm và phiếu giảm giá
                $refundServices->integralAndCouponBack($orderInfo, 'cancel');
                //Khôi phục hàng tồn kho và doanh số bán hàng
                $refundServices->regressionStock($orderInfo);
                return true;
            });
            if ($res) {
                $orderInfo->is_cancel = 1;
                $orderInfo->mark = 'Đơn hàng chưa được thanh toán quá thời gian quy định của hệ thống';
                $orderInfo->save();
            }
            return $res;
        } catch (\Throwable $e) {
            Log::error('Tự động hủy đơn hàng không thành công,Lý do thất bại:' . $e->getMessage());
            return false;
        }
    }
}
