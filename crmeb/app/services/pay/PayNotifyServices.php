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

use app\services\order\OtherOrderServices;
use app\services\order\StoreOrderSuccessServices;
use app\services\user\UserRechargeServices;

/**
 * Gọi lại thanh toán thành công Tất cả các lệnh gọi lại thông báo không đồng bộ sẽ sử dụng ba phương thức sau:,Không còn nhận được cuộc gọi lại thanh toán WeChat/Alipay
 * Class PayNotifyServices
 * @package app\services\pay
 */
class PayNotifyServices
{

    /**
     * Sau khi thanh toán đơn hàng thành công
     * @param string|null $order_id Đặt hàngid
     * @param string|null $trade_no
     * @param string $payType
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function wechatProduct(string $order_id = null, string $trade_no = null, string $payType = PayServices::WEIXIN_PAY)
    {
        try {
            /** @var StoreOrderSuccessServices $services */
            $services = app()->make(StoreOrderSuccessServices::class);
            $orderInfo = $services->getOne(['order_id' => $order_id]);
            if (!$orderInfo) return true;
            if ($orderInfo->paid) return true;
            return $services->paySuccess($orderInfo->toArray(), $payType, ['trade_no' => $trade_no]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sau khi nạp tiền thành công
     * @param string|null $order_id Đặt hàngid
     * @return bool
     */
    public function wechatUserRecharge(string $order_id = null, string $trade_no = null, string $payType = PayServices::WEIXIN_PAY)
    {
        try {
            /** @var UserRechargeServices $userRecharge */
            $userRecharge = app()->make(UserRechargeServices::class);
            if ($userRecharge->be(['order_id' => $order_id, 'paid' => 1])) return true;
            return $userRecharge->rechargeSuccess($order_id, ['trade_no' => $trade_no, 'pay_type' => $payType]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Mua thành viên
     * @param string|null $order_id
     * @return bool
     */
    public function wechatMember(string $order_id = null, string $trade_no = null, string $payType = PayServices::WEIXIN_PAY)
    {
        try {
            /** @var OtherOrderServices $services */
            $services = app()->make(OtherOrderServices::class);
            $orderInfo = $services->getOne(['order_id' => $order_id]);
            if (!$orderInfo) return true;
            if ($orderInfo->paid) return true;
            return $services->paySuccess($orderInfo->toArray(), $payType, ['trade_no' => $trade_no]);
        } catch (\Exception $e) {
            return false;
        }
    }

}
