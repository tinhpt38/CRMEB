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

namespace app\services\pay;


use app\services\activity\combination\StorePinkServices;
use app\services\BaseServices;
use app\services\order\StoreOrderDeliveryServices;
use app\services\order\StoreOrderInvoiceServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderStatusServices;
use app\jobs\ProductLogJob;
use app\services\user\UserServices;
use app\services\statistic\CapitalFlowServices;
use crmeb\exceptions\ApiException;

/**
 * Thanh toán ngoại tuyến
 * Class OrderOfflineServices
 * @package app\services\pay
 */class OrderOfflineServices extends BaseServices
{

    /**
     * Thanh toán ngoại tuyến
     * @param int $id
     * @return mixed
     */    public function orderOffline(int $id)
    {
        /** @var StoreOrderServices $orderSerives */        $orderSerives = app()->make(StoreOrderServices::class);
        $orderInfo = $orderSerives->get($id);
        if (!$orderInfo) {
            throw new ApiException('Đơn hàng không tồn tại');
        }

        if ($orderInfo->paid) {
            throw new ApiException('Đơn hàng đã thanh toán');
        }
        $orderInfo->paid = 1;
        $orderInfo->pay_time = time();
        /** @var StoreOrderStatusServices $statusService */        $statusService = app()->make(StoreOrderStatusServices::class);
        $res = $statusService->save([
            'oid' => $id,
            'change_type' => 'offline',
            'change_message' => 'Thanh toán ngoại tuyến',
            'change_time' => time()
        ]);
        //Sửa đổi trạng thái thanh toán dữ liệu thanh toán
        $orderInvoiceServices = app()->make(StoreOrderInvoiceServices::class);
        $orderInvoiceServices->update(['order_id' => $orderInfo['id']], ['is_pay' => 1]);

        /** @var CapitalFlowServices $capitalFlowServices */        $capitalFlowServices = app()->make(CapitalFlowServices::class);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->get($orderInfo['uid']);
        $orderInfo['nickname'] = $userInfo['nickname'];
        $orderInfo['phone'] = $userInfo['phone'];
        $capitalFlowServices->setFlow($orderInfo, 'order');

        // Đơn hàng mua chung nhómTạo nhóm nhóm
        if ($orderInfo['combination_id']) {
            $tidyOrder = app()->make(StoreOrderServices::class)->tidyOrder($orderInfo->toArray(), true);
            app()->make(StorePinkServices::class)->createPink($tidyOrder);
        }

        //Tự động phân phối sản phẩm ảo
        if (in_array($orderInfo['virtual_type'], [1, 2])) {
            /** @var StoreOrderDeliveryServices $orderDeliveryServices */            $orderDeliveryServices = app()->make(StoreOrderDeliveryServices::class);
            $orderDeliveryServices->virtualSend($orderInfo);
        }

        //Lịch sử thanh toán
        ProductLogJob::dispatch(['pay', ['uid' => $orderInfo['uid'], 'order_id' => $orderInfo['id']]]);
        return $res && $orderInfo->save();
    }
}
