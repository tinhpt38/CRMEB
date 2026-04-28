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

namespace app\api\controller\v1;


use app\Request;
use crmeb\services\app\MiniProgramService;
use crmeb\services\pay\Pay;

/**
 * Hoàn vốn
 * Class PayController
 * @package app\api\controller\v1
 */
class PayController
{

    /**
     * Hoàn vốn
     * @param string $type
     * @return string|void
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function notify(string $type)
    {
        switch (urldecode($type)) {
            case 'alipay':
                /** @var Pay $pay */
                $pay = app()->make(Pay::class, ['ali_pay']);
                return $pay->handleNotify();
            case 'v3wechat':
                return app()->make(Pay::class, ['v3_wechat_pay'])->handleNotify()->getContent();
            case 'routine':
                return MiniProgramService::handleNotify();
            case 'wechat':
                if (sys_config('pay_wechat_type')) {
                    /** @var Pay $pay */
                    $pay = app()->make(Pay::class, ['v3_wechat_pay']);
                } else {
                    /** @var Pay $pay */
                    $pay = app()->make(Pay::class);
                }
                return $pay->handleNotify()->getContent();
            default:
                if (strstr($type, 'allin') !== false) {
                    /** @var Pay $pay */
                    $pay = app()->make(Pay::class, ['allin_pay']);
                    return $pay->handleNotify($type);
                }
        }
    }

    /**
     * Cấu hình thanh toán
     * @param Request $request
     * @return mixed
     */
    public function config(Request $request)
    {
        $config = [
            [
                'icon' => 'icon-weixinzhifu',
                'name' => 'Thanh toán WeChat',
                'value' => 'weixin',
                'title' => 'Sử dụng Thanh toán nhanh WeChat',
                'number' => null,
                'payStatus' => !!sys_config('pay_weixin_open', 0),
            ],
            [
                'icon' => 'icon-zhifubao',
                'name' => 'thanh toán Alipay',
                'value' => 'alipay',
                'title' => 'Thanh toán trực tuyến bằng Alipay',
                'number' => null,
                'payStatus' => !!sys_config('ali_pay_status', 0),
            ],
            [
                'icon' => 'icon-yuezhifu',
                'name' => 'thanh toán số dư',
                'value' => 'yue',
                'title' => 'Số dư hiện có',
                'number' => $request->user('now_money'),
                'payStatus' => (int)sys_config('yue_pay_status', 0) === 1,
            ],
            [
                'icon' => 'icon-yuezhifu1',
                'name' => 'Thanh toán ngoại tuyến',
                'value' => 'offline',
                'title' => 'Chọn phương thức thanh toán ngoại tuyến',
                'number' => null,
                'payStatus' => (int)sys_config('offline_pay_status', 0) === 1,
            ],
            [
                'icon' => 'icon-haoyoudaizhifu',
                'name' => 'Bạn bè trả tiền thay mặt',
                'value' => 'friend',
                'title' => 'Thanh toán với bạn bè WeChat',
                'number' => null,
                'payStatus' => !!sys_config('friend_pay_status', 0),
            ]
        ];

        return app('json')->success($config);
    }

    public function transferNotify()
    {
        return app()->make(Pay::class, ['v3_wechat_pay'])->handleTransferNotify()->getContent();
    }
}
