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
namespace app\api\middleware;

use app\services\kefu\service\StoreServiceServices;
use app\services\order\DeliveryServiceServices;
use app\services\system\store\SystemStoreStaffServices;
use crmeb\interfaces\MiddlewareInterface;
use app\Request;

class CustomerMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, \Closure $next)
    {
        $uid = $request->uid();
        /** @var StoreServiceServices $services */        $services = app()->make(StoreServiceServices::class);
        /** @var SystemStoreStaffServices $storeServices */        $storeServices = app()->make(SystemStoreStaffServices::class);
        $rule = trim(strtolower($request->rule()->getRule()));
        /** @var DeliveryServiceServices $deliveryService */        $deliveryService = app()->make(DeliveryServiceServices::class);
        $isDelivery = $deliveryService->checkoutIsService($uid);
        $withRule = ['/api/order/order_verific', "/api/admin/order/detail/<orderId>"];
        if (((!$services->checkoutIsService(['uid' => $uid, 'status' => 1, 'customer' => 1]) && !$storeServices->verifyStatus($uid)) && !$isDelivery) && !(in_array($rule, $withRule)))
            return app('json')->fail('Không đủ quyền');
        return $next($request);
    }
}
