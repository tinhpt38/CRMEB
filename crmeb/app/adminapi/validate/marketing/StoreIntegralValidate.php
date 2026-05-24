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

class StoreIntegralValidate extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'product_id' => 'require',
        'title' => 'require',
        'info' => 'require',
        'unit_name' => 'require',
        'image' => 'require',
        'images' => 'require',
        'description' => 'require',
        'attrs' => 'require',
        'items' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'product_id.require' => 'Vui lòng chọn sản phẩm',
        'title.require' => 'Vui lòng nhập tên sản phẩm',
        'info.require' => 'Vui lòng điền phần giới thiệu hoạt động',
        'unit_name.require' => 'Vui lòng điền vào Đơn vị',
        'image.require' => 'Vui lòng chọn hình ảnh băng chuyền sản phẩm',
        'images.require' => 'Vui lòng chọn hình ảnh băng chuyền sản phẩm',
        'description.require' => 'Vui lòng điền thông tin chi tiết sản phẩm',
        'attrs.require' => 'Vui lòng chọn thông số kỹ thuật',
    ];

    protected $scene = [
        'save' => ['product_id', 'title', 'unit_name', 'image', 'images',  'num', 'once_num', 'sort', 'description', 'attrs', 'items'],
    ];
}
