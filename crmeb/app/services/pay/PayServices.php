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
declare (strict_types=1);

namespace app\services\pay;

use crmeb\exceptions\ApiException;
use crmeb\services\pay\Pay;

/**
 * Cổng thanh toán thống nhất
 * Class PayServices
 * @package app\services\pay
 */class PayServices
{
    //Loại thanh toán WeChat
    const WEIXIN_PAY = 'weixin';

    //Thanh toán bằng số dư
    const YUE_PAY = 'yue';

    //Thanh toán ngoại tuyến
    const OFFLINE_PAY = 'offline';

    //Alipay
    const ALIAPY_PAY = 'alipay';

    //Thanh toán Tonglian
    const ALLIN_PAY = 'allinpay';

    //Bạn bè trả tiền thay mặt
    const FRIEND = 'friend';

    //chuyển khoản ngân hàng
    const BANK = 'bank';

    /** Thanh toán khi nhận hàng (COD) — thị trường Việt Nam */
    const VN_COD = 'vn_cod';

    /** Chuyển khoản ngân hàng / VietQR — xác nhận thủ công */
    const VN_BANK = 'vn_bank';

    /** VNPay — cổng thẻ / QR / ví liên kết */
    const VN_VNPAY = 'vnpay';

    /** MoMo — ví điện tử */
    const VN_MOMO = 'momo';

    /** ZaloPay — ví điện tử */
    const VN_ZALOPAY = 'zalopay';

    //Phương thức thanh toán
    const PAY_TYPE = [
        PayServices::WEIXIN_PAY => 'Thanh toán WeChat',
        PayServices::YUE_PAY => 'Thanh toán bằng số dư',
        PayServices::OFFLINE_PAY => 'Thanh toán ngoại tuyến',
        PayServices::ALIAPY_PAY => 'Alipay',
        PayServices::FRIEND => 'Bạn bè trả tiền thay mặt',
        PayServices::ALLIN_PAY => 'Thanh toán Tonglian',
        PayServices::BANK => 'chuyển khoản ngân hàng',
        PayServices::VN_COD => 'Thanh toán khi nhận hàng (COD)',
        PayServices::VN_BANK => 'Chuyển khoản ngân hàng / VietQR',
        PayServices::VN_VNPAY => 'VNPay',
        PayServices::VN_MOMO => 'MoMo',
        PayServices::VN_ZALOPAY => 'ZaloPay',
    ];

    /**
     * @var array
     */    protected $options = [];

    /**
     * @param string $key
     * @param $value
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/1/16
     */    public function setOption(string $key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param array $value
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/1/16
     */    public function setOptions(array $value)
    {
        $this->options = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/1/16
     */    protected function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * Bắt đầu thanh toán
     * @param string $payType
     * @param string $openid
     * @param string $orderId
     * @param string $price
     * @param string $successAction
     * @param string $body
     * @return array|string
     */    public function pay(string $payType, string $orderId, string $price, string $successAction, string $body, array $options = [])
    {
        try {

            //Đây là Tất cả các khoản thanh toán WeChat
            if (in_array($payType, ['routine', 'weixinh5', 'weixin', 'pc', 'store'])) {
                $payType = 'wechat_pay';
                //Xác định xem có nên sử dụngv3
                if (sys_config('pay_wechat_type') == 1) {
                    $payType = 'v3_wechat_pay';
                }
            } else {
                if ($payType == 'alipay') {
                    $payType = 'ali_pay';
                } elseif ($payType == 'allinpay') {
                    $payType = 'allin_pay';
                } elseif ($payType === self::VN_VNPAY) {
                    $payType = 'vnpay_pay';
                } elseif ($payType === self::VN_MOMO) {
                    $payType = 'momo_pay';
                } elseif ($payType === self::VN_ZALOPAY) {
                    $payType = 'zalopay_pay';
                }
            }

            /** @var Pay $pay */            $pay = app()->make(Pay::class, [$payType]);


            return $pay->create($orderId, $price, $successAction, $body, '', ['pay_new_weixin_open' => (bool)sys_config('pay_new_weixin_open')] + $options);

        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'api unauthorized rid') !== false) {
                throw new ApiException('Vui lòng thay đổi lựa chọn tài khoản người bán trong Chương trình nhỏ thành ràng buộc tài khoản người bán trong cấu hình thanh toán WeChat');
            }
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * TODO Bắt đầu thanh toán Không được dùng nữa
     * @param string $payType
     * @param string $openid
     * @param string $orderId
     * @param string $price
     * @param string $successAction
     * @param string $body
     * @return array|string
     *///    public function pay(string $payType, string $openid, string $orderId, string $price, string $successAction, string $body, bool $isCode = false)
//    {
//        try {
//
//            //Đây là Tất cả các khoản thanh toán WeChat
//            if (in_array($payType, ['routine', 'weixinh5', 'weixin', 'pc', 'store'])) {
//                $payType = 'wechat_pay';
//                //Xác định xem có nên sử dụngv3
//                if (sys_config('pay_wechat_type') == 1) {
//                    $payType = 'v3_wechat_pay';
//                }
//            }
//
//            if ($payType == 'alipay') {
//                $payType = 'ali_pay';
//            }
//
//
//            $options = [];
//            if (self::ALLIN_PAY === $payType) {
//                $options['returl'] = $this->getOption('returl');
//                if ($options['returl']) {
//                    $options['returl'] = str_replace('http://', 'https://', $options['returl']);
//                }
//                $options['is_wechat'] = $this->getOption('is_wechat', false);
//                $options['appid'] = sys_config('routine_appId');
//                $payType = 'allin_pay';
//            }
//
//            /** @var Pay $pay */
//            $pay = app()->make(Pay::class, [$payType]);
//
//
//            return $pay->create($orderId, $price, $successAction, $body, '', ['openid' => $openid, 'isCode' => $isCode, 'pay_new_weixin_open' => (bool)sys_config('pay_new_weixin_open')] + $options);
//
//        } catch (\Exception $e) {
//            if (strpos($e->getMessage(), 'api unauthorized rid') !== false) {
//                throw new ApiException('Vui lòng thay đổi lựa chọn tài khoản người bán trong Chương trình nhỏ thành ràng buộc tài khoản người bán trong cấu hình thanh toán WeChat');
//            }
//            throw new ApiException($e->getMessage());
//        }
//    }
}
