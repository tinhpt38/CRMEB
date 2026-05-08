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
        'total_price'   => ['require', 'float', 'egt:0'],
        'total_postage' => ['require', 'float', 'egt:0'],
        'pay_price'     => ['require', 'float', 'egt:0'],
        'pay_postage'   => ['require', 'float', 'egt:0'],
        'gain_integral' => ['float', 'egt:0'],
    ];

    protected $message = [
        'order_id.require'      => 'Vui lòng nhập mã đơn hàng',
        'order_id.length'       => 'Mã đơn hàng không hợp lệ',
        'order_id.alphaNum'     => 'Mã đơn hàng phải là chữ cái và số',
        'total_price.require'   => 'Vui lòng nhập tổng tiền đơn hàng',
        'total_price.float'    => 'Tổng tiền đơn hàng phải là số',
        'total_price.egt'      => 'Tổng tiền đơn hàng phải lớn hơn hoặc bằng 0',
        'total_postage.require' => 'Vui lòng nhập tổng phí vận chuyển',
        'total_postage.float'   => 'Tổng phí vận chuyển phải là số',
        'total_postage.egt'     => 'Tổng phí vận chuyển phải lớn hơn hoặc bằng 0',
        'pay_price.require'     => 'Vui lòng nhập số tiền thanh toán',
        'pay_price.float'       => 'Số tiền thanh toán phải là số',
        'pay_price.egt'         => 'Số tiền thanh toán phải lớn hơn hoặc bằng 0',
        'pay_postage.require'   => 'Vui lòng nhập phí vận chuyển',
        'pay_postage.float'     => 'Phí vận chuyển phải là số',
        'pay_postage.egt'       => 'Phí vận chuyển phải lớn hơn hoặc bằng 0',
        'gain_integral.float'   => 'Điểm quà tặng phải là số',
        'gain_integral.egt'     => 'Điểm quà tặng phải lớn hơn hoặc bằng 0',
    ];

    protected $scene = [

    ];
}
