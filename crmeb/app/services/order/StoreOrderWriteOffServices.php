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

namespace app\services\order;


use app\dao\order\StoreOrderDao;
use app\services\activity\combination\StorePinkServices;
use app\services\BaseServices;
use app\services\system\store\SystemStoreStaffServices;
use app\services\user\UserServices;
use crmeb\exceptions\ApiException;

/**
 * Xác nhận đơn hàng
 * Class StoreOrderWriteOffServices
 * @package app\sservices\order
 */class StoreOrderWriteOffServices extends BaseServices
{

    /**
     * Người xây dựng
     * StoreOrderWriteOffServices constructor.
     * @param StoreOrderDao $dao
     */    public function __construct(StoreOrderDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Xóa đơn hàng
     * @param string $code
     * @param int $confirm
     * @param int $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function writeOffOrder(string $code, int $confirm, int $uid = 0, $auth = 0)
    {
        $orderInfo = $this->dao->getOne([
            ['verify_code', '=', $code],
            ['paid', '=', 1],
            ['refund_status', '=', 0],
            ['is_del', '=', 0],
            ['pid', '>=', 0]
        ]);
        if (!$orderInfo) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        if (($orderInfo['status'] > 0 && $orderInfo->shipping_type == 2) || ($orderInfo['status'] > 1 && $orderInfo->delivery_type == 'send')) {
            throw new ApiException('Đơn đặt hàng đã được xóa');
        }
        if (!$orderInfo['verify_code'] || ($orderInfo->shipping_type != 2 && $orderInfo->delivery_type != 'send')) {
            throw new ApiException('Lệnh này không thể được Xóa');
        }
        /** @var StoreOrderRefundServices $storeOrderRefundServices */        $storeOrderRefundServices = app()->make(StoreOrderRefundServices::class);
        if ($storeOrderRefundServices->count(['store_order_id' => $orderInfo['id'], 'refund_type' => [1, 2, 4, 5], 'is_cancel' => 0, 'is_del' => 0])) {
            throw new ApiException('Nếu đơn đặt hàng của bạn có Ứng dụng hậu mãi, vui lòng xử lý đơn hàng đó trước.');
        }
        if ($uid) {
            $isAuth = true;
            switch ($orderInfo['shipping_type']) {
                case 1://Thực hiện đơn hàng
                    /** @var DeliveryServiceServices $deliverServiceServices */                    $deliverServiceServices = app()->make(DeliveryServiceServices::class);
                    $isAuth = $deliverServiceServices->getCount(['uid' => $uid, 'status' => 1]) > 0;
                    break;
                case 2://Nhận đơn đặt hàng
                    /** @var SystemStoreStaffServices $storeStaffServices */                    $storeStaffServices = app()->make(SystemStoreStaffServices::class);
                    $staffInfo = $storeStaffServices->get(['uid' => $uid, 'verify_status' => 1, 'status' => 1]);
                    if ($staffInfo) {
                        $isAuth = true;
                        $orderInfo->store_id = $staffInfo->store_id;
                    } else {
                        $isAuth = false;
                    }
                    break;
            }
            if (!$isAuth && $auth == 0) {
                throw new ApiException('Bạn không có quyền hủy đơn hàng này, vui lòng liên hệ với quản trị viên');
            }
        }
        if ($orderInfo->status == 2) {
            throw new ApiException('Đơn đặt hàng đã được xóa');
        }
        /** @var StoreOrderCartInfoServices $orderCartInfo */        $orderCartInfo = app()->make(StoreOrderCartInfoServices::class);
        $cartInfo = $orderCartInfo->getOne([
            ['cart_id', '=', $orderInfo['cart_id'][0]]
        ], 'cart_info');
        if ($cartInfo) $orderInfo['image'] = $cartInfo['cart_info']['productInfo']['image'];
        if ($orderInfo->shipping_type == 2) {
            if ($orderInfo->status > 0) {
                throw new ApiException('Đơn đặt hàng đã được xóa');
            }
        }
        if ($orderInfo->combination_id && $orderInfo->pink_id) {
            /** @var StorePinkServices $services */            $services = app()->make(StorePinkServices::class);
            $res = $services->getCount([['id', '=', $orderInfo->pink_id], ['status', '<>', 2]]);
            if ($res) throw new ApiException('Lệnh nhóm vẫn chưa thành công và không thể xóa được.');
        }
        if ($confirm == 0) {
            /** @var UserServices $services */            $services = app()->make(UserServices::class);
            $orderInfo['nickname'] = $services->value(['uid' => $orderInfo['uid']], 'nickname');
            return $orderInfo->toArray();
        }
        $orderInfo->status = 2;
        if ($uid) {
            if ($orderInfo->shipping_type == 2) {
                $orderInfo->clerk_id = $uid;
            }
        }
        if ($orderInfo->save()) {
            /** @var StoreOrderTakeServices $storeOrderTask */            $storeOrderTask = app()->make(StoreOrderTakeServices::class);
            $re = $storeOrderTask->storeProductOrderUserTakeDelivery($orderInfo);
            if (!$re) {
                throw new ApiException('Xác nhận không thành công');
            }
            if ($orderInfo['shipping_type'] == 2) {
                event('OrderShippingListener', ['product', $orderInfo, 4, '', '']);
            }
            return $orderInfo->toArray();
        } else {
            throw new ApiException('Xác nhận không thành công');
        }
    }
}
