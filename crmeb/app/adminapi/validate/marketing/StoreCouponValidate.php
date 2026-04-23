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
namespace app\adminapi\validate\marketing;

use think\Validate;

class StoreCouponValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'title' => 'require',
        'image' => 'require',
        'category_id' => 'require',
        'coupon_price' => 'require',
        'use_min_price' => 'require',
        'coupon_time' => 'require',
        'status' => 'In:0,1',
        'type' => ['require', 'In:0,1,2'],
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'title.require' => 'Vui lòng điền tên phiếu giảm giá',
        'image.require' => 'Vui lòng chọn sản phẩm',
        'category_id.require' => 'Vui lòng chọn danh mục sản phẩm',
        'coupon_price.require' => 'Vui lòng điền số tiền phiếu giảm giá',
        'use_min_price.require' => 'Vui lòng điền số tiền sử dụng tối thiểu của phiếu giảm giá',
        'coupon_time.require' => 'Vui lòng điền thời hạn hiệu lực của phiếu giảm giá',
    ];

    protected $scene = [
        'save' => ['title', 'coupon_price', 'use_min_price', 'coupon_time'],
        'type' => ['title', 'category_id', 'coupon_price', 'use_min_price', 'coupon_time'],
        'product' => ['title', 'image', 'coupon_price', 'use_min_price', 'coupon_time'],
    ];
}
