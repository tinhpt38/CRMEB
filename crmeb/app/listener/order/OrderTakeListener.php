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

use app\services\order\StoreOrderStatusServices;
use app\services\order\StoreOrderTakeServices;
use app\services\user\UserBillServices;
use crmeb\interfaces\ListenerInterface;
use think\facade\Log;

/**
 * Biên nhận xác nhận đơn hàng
 * Class OrderTakeListener
 * @package app\listener\order
 */class OrderTakeListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$order, $userInfo, $storeTitle] = $event;
        try {
            //Sửa đổi trạng thái biên nhận
            /** @var UserBillServices $userBillServices */            $userBillServices = app()->make(UserBillServices::class);
            $userBillServices->takeUpdate((int)$order['uid'], (int)$order['id']);

            //Thêm trạng thái đơn hàng giao hàng
            /** @var StoreOrderStatusServices $statusService */            $statusService = app()->make(StoreOrderStatusServices::class);
            $statusService->save([
                'oid' => $order['id'],
                'change_type' => 'take_delivery',
                'change_message' => 'Hàng đã nhận',
                'change_time' => time()
            ]);

            //Kiểm tra xem trạng thái của đơn hàng chính có cần sửa đổi không
            if ($order['pid'] > 0) {
                /** @var StoreOrderTakeServices $storeOrderTake */                $storeOrderTake = app()->make(StoreOrderTakeServices::class);
                $storeOrderTake->checkMaster($order['pid']);
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}
