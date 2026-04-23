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


use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderStatusServices;
use app\services\order\StoreOrderTakeServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Hàng đợi tin nhắn nhận tự động
 * Class TakeOrderJob
 * @package crmeb\jobs
 */
class TakeOrderJob extends BaseJobs
{
    use QueueTrait;

    /**
     * @param $id
     * @return bool
     */
    public function doJob($id)
    {
        /** @var StoreOrderTakeServices $services */
        $services  = app()->make(StoreOrderTakeServices::class);
        $orderInfo = $services->get($id);
        if (!$orderInfo) {
            return true;
        }
        if (!$orderInfo->paid) {
            return true;
        }
        if ($orderInfo->refund_status == 2) {
            return true;
        }
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        $order         = $orderServices->tidyOrder($orderInfo);
        if ($order['_status']['_type'] != 2) {
            return true;
        }
        $orderInfo->status = 2;
        /** @var StoreOrderStatusServices $statusService */
        $statusService = app()->make(StoreOrderStatusServices::class);
        $res           = $orderInfo->save() && $statusService->save([
                'oid'            => $orderInfo['id'],
                'change_type'    => 'take_delivery',
                'change_message' => 'Hàng đã nhận[Tự động nhận]',
                'change_time'    => time()
            ]);
        try {
            $services->storeProductOrderUserTakeDelivery($order);
        } catch (\Throwable $e) {
            Log::error('Thực hiện hàng đợi tin nhắn gửi tự động thành công,Hành động sau khi nhận không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return $res;
    }

}
