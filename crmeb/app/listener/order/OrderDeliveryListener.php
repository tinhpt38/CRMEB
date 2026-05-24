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
namespace app\listener\order;


use app\jobs\TakeOrderJob;
use crmeb\interfaces\ListenerInterface;

/**
 * Tự động giao hàng khi hết đơn hàng
 * Class OrderDeliveryListener
 * @package app\listener\order
 */class OrderDeliveryListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$orderInfo, $storeTitle, $data, $type] = $event;

        //Tự động nhận hàng khi hết hạn
        $time = sys_config('system_delivery_time') ?? 0;
        if ($time != 0) {
            $sevenDay = 24 * 3600 * $time;
            $sevenDay = $sevenDay + 180;
            TakeOrderJob::dispatchSecs((int)$sevenDay, [$orderInfo->id]);
        }
    }
}
