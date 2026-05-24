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

class LiveGoodsValidate extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'id' => 'require',
        'store_name' => 'require',
        'image' => 'require',
        'price' => 'require|gt:0',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'id.require' => 'Vui lòng chọn sản phẩm',
        'store_name.require' => 'Vui lòng nhập tên sản phẩm',
        'image.require' => 'Vui lòng chọn hình nền',
        'price.require' => 'Vui lòng nhập giá phát sóng trực tiếp',
        'price.gt' => 'Giá phát sóng trực tiếp phải lớn hơn0',
    ];

    protected $scene = [
        'save' => ['id', 'store_name', 'image', 'price'],
    ];
}
