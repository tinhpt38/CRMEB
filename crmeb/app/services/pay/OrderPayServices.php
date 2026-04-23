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
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\pay\extend\allinpay\AllinPay;
use crmeb\utils\Str;
use think\exception\ValidateException;

/**
 * Thanh toán bắt đầu đặt hàng
 * Class OrderPayServices
 * @package app\services\pay
 */
class OrderPayServices
{
    /**
     * chi trả
     * @var PayServices
     */
    protected $payServices;

    public function __construct(PayServices $services)
    {
        $this->payServices = $services;
    }

    /**
     * Nhận phương thức thanh toán
     * @param string $payType
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/15
     */
    public function getPayType(string $payType)
    {
        //Thanh toán WeChat không được bật, thanh toán Tonglian được bật và người dùng sử dụng thanh toán Tonglian WeChat H5 khi truy cập chương trình nhỏ hoặc tài khoản chính thức.
        if ($payType == PayServices::WEIXIN_PAY && !request()->isH5() && !request()->isApp()) {
            $payType = sys_config('pay_weixin_open', 0);
        }

        //Alipay chưa được bật nhưng Tonglian Pay đã được bật. Người dùng sử dụng Alipay để thanh toán và khi truy cập ứng dụng, hãy sử dụng ứng dụng Tonglian Alipay để thanh toán.
        if ($payType == PayServices::ALIAPY_PAY && request()->isApp()) {
            $payType = sys_config('ali_pay_status', 0);
        }

        return $payType;
    }

    /**
     * Nhận kiểu trả về
     * @param string $payType
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/15
     */
    public function payStatus(string $payType)
    {
        if ($payType == PayServices::WEIXIN_PAY) {
            if (request()->isH5()) {
                $payStstus = 'wechat_h5_pay';
            } else if (request()->isPc()) {
                $payStstus = 'wechat_pc_pay';
            } else {
                $payStstus = 'wechat_pay';
            }
        } else if ($payType == PayServices::ALIAPY_PAY) {
            $payStstus = 'alipay_pay';
        } else if ($payType == PayServices::ALLIN_PAY) {
            $payStstus = 'allinpay_pay';
        } else {
            throw new ValidateException('Không thể lấy được loại trả lại thanh toán');
        }
        return $payStstus;
    }

    /**
     * Trước khi bắt đầu thanh toán
     * @param array $orderInfo
     * @param string $payType
     * @param array $options
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/15
     */
    public function beforePay(array $orderInfo, string $payType, array $options = [])
    {
        $wechat = $payType == PayServices::WEIXIN_PAY;

        $payType = $this->getPayType($payType);

        if ($orderInfo['paid']) {
            throw new ApiException('Đơn hàng đã thanh toán');
        }
        if ($orderInfo['pay_price'] <= 0) {
            throw new ApiException('Không cần phải trả tiền');
        }

        switch ($payType) {
            case PayServices::WEIXIN_PAY:
                $openid = '';
                if (request()->isWechat() || request()->isRoutine()) {
                    if (request()->isWechat()) {
                        $userType = 'wechat';
                    } else {
                        $userType = 'routine';
                    }
                    /** @var WechatUserServices $services */
                    $services = app()->make(WechatUserServices::class);
                    $openid = $services->uidToOpenid($orderInfo['pay_uid'] ?? $orderInfo['uid'], $userType);
                    if (!$openid) {
                        throw new ApiException('Không thể lấy openid người dùng,Không thể thanh toán');
                    }
                }
                $options['openid'] = $openid;
                break;
            case PayServices::ALLIN_PAY:
                if ($wechat) {
                    $options['wechat'] = $wechat;
                }
                break;
            case PayServices::ALIAPY_PAY:
                if ($wechat) {
                    $options['returnUrl'] = sys_config('site_url') . '/pages/goods/order_pay_status/index?order_id=' . $orderInfo['order_id'];
                }
                break;
        }


        $site_name = sys_config('site_name');
        if (isset($orderInfo['member_type'])) {
            $body = Str::substrUTf8($site_name . '--' . $orderInfo['member_type'], 20);
            $successAction = "member";
            /** @var OtherOrderServices $otherOrderServices */
            $otherOrderServices = app()->make(OtherOrderServices::class);
            $otherOrderServices->update($orderInfo['id'], ['pay_type' => $payType]);
        } else {
            /** @var StoreOrderCartInfoServices $orderInfoServices */
            $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
            $body = $orderInfoServices->getCarIdByProductTitle((int)$orderInfo['id']);
            $body = Str::substrUTf8($site_name . '--' . $body, 20);
            $successAction = "product";
            /** @var StoreOrderServices $orderServices */
            $orderServices = app()->make(StoreOrderServices::class);
            $orderServices->update($orderInfo['id'], ['pay_type' => $payType]);
        }

        if (!$body) {
            throw new ApiException('Cấu hình tên trang web chưa được điền,Không thể thanh toán');
        }

        //Bắt đầu thanh toán
        $jsConfig = $this->payServices->pay($payType, $orderInfo['order_id'], $orderInfo['pay_price'], $successAction, $body, $options);

        //Xử lý các tham số trả về sau khi bắt đầu thanh toán
        $payInfo = $this->afterPay($orderInfo, $jsConfig, $payType);
        $statusType = $this->payStatus($payType);

        return [
            'status' => $statusType,
            'payInfo' => $payInfo,
        ];
    }

    /**
     * Xử lý các tham số trả về sau khi thanh toán được bắt đầu
     * @param $order
     * @param $jsConfig
     * @param string $payType
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/15
     */
    public function afterPay($order, $jsConfig, string $payType)
    {
        $payKey = md5($order['order_id']);
        switch ($payType) {
            case PayServices::ALIAPY_PAY:
                if (request()->isPc()) $jsConfig->invalid = time() + 60;
                CacheService::set($payKey, ['order_id' => $order['order_id'], 'other_pay_type' => false], 300);
                break;
            case PayServices::ALLIN_PAY:
                if (request()->isWechat()) {
                    $payUrl = AllinPay::UNITODER_H5UNIONPAY;
                }
                break;
            case PayServices::WEIXIN_PAY:
                if (isset($jsConfig['mweb_url'])) {
                    $jsConfig['h5_url'] = $jsConfig['mweb_url'];
                }
        }

        return ['jsConfig' => $jsConfig, 'oid' => $order['id'], 'order_id' => $order['order_id'], 'pay_key' => $payKey, 'pay_url' => $payUrl ?? ''];
    }
}
