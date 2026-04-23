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
namespace app\adminapi\validate\order;

use think\Validate;

/**
 *
 * Class StoreOrderValidate
 * @package app\adminapi\validates
 */
class StoreOrderValidate extends Validate
{

    protected $rule = [
        'order_id'      => ['require','length'=>'1,32','alphaNum'],
        'total_price'   => ['require','float'],
        'total_postage' => ['require','float'],
        'pay_price'     => ['require','float'],
        'pay_postage'   => ['require','float'],
        'gain_integral' => ['float'],
    ];

    protected $message = [
        'order_id.require'      => 'Số đơn đặt hàng phải tồn tại',
        'order_id.length'       => 'Số đơn hàng sai',
        'order_id.alphaNum'     => 'Mã đơn hàng phải là chữ cái và số',
        'total_price.require'   => 'Số tiền đặt hàng phải được điền vào',
        'total_price.float'    => 'Số tiền đặt hàng phải là một con số',
        'pay_price.require'     => 'Số tiền đặt hàng phải được điền vào',
        'pay_price.float'      => 'Số tiền đặt hàng phải là một con số',
        'pay_postage.require'   => 'Bưu phí đặt hàng phải được điền vào',
        'pay_postage.float'    => 'Bưu phí đặt hàng phải là một con số',
        'gain_integral.float'  => 'Điểm quà tặng phải là số',
    ];

    protected $scene = [

    ];
}
