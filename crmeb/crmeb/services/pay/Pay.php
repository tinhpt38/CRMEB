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

namespace crmeb\services\pay;


use crmeb\basic\BaseManager;
use crmeb\services\pay\storage\AliPay;
use crmeb\services\pay\storage\V3WechatPay;
use crmeb\services\pay\storage\WechatPay;
use think\facade\Config;

/**
 * Thanh toán của bên thứ ba
 * Class AllinPay
 * @package crmeb\services\pay
 * @mixin WechatPay
 */
class Pay extends BaseManager
{
    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\pay\\storage\\';

    /**
     * Trình điều khiển mặc định
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('pay.default', 'wechat_pay');
    }

}
