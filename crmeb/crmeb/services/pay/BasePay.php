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

use EasyWeChat\Payment\Order;
use crmeb\basic\BaseStorage;

/**
 * Class BasePay
 * @package crmeb\services\pay
 */
abstract class BasePay extends BaseStorage
{
    /**
     * @var string
     */
    protected $payType;

    /**
     * Đặt loại thanh toán
     * @param string $type
     * @return $this
     */
    public function setPayType(string $type)
    {
        $this->payType = $type;
        return $this;
    }

    /**
     * Đặt loại thanh toán
     * @param string $type
     * @return $this
     */
    public function authSetPayType()
    {
        if (!$this->payType) {
            if (request()->isPc()) {
                $this->payType = Order::NATIVE;
            }
            if (request()->isApp()) {
                $this->payType = Order::APP;
            }
            if (request()->isRoutine() || request()->isWechat()) {
                $this->payType = Order::JSAPI;
            }
            if (request()->isH5()) {
                $this->payType = 'h5';
            }
        }
    }


}
