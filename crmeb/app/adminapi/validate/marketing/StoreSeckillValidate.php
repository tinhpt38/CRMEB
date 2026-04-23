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

class StoreSeckillValidate extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'product_id' => 'require',
        'title' => 'require',
        'info' => 'require',
        'unit_name' => 'require',
        'images' => 'require',
        'section_time' => 'require',
        'num' => 'require|gt:0',
        'once_num' => 'require|gt:0',
        'time_id' => 'require',
        'temp_id' => 'require',
        'description' => 'require',
        'attrs' => 'require',
        'items' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'product_id.require' => 'Vui lòng chọn sản phẩm',
        'title.require' => 'Vui lòng nhập tên sản phẩm',
        'info.require' => 'Vui lòng điền phần giới thiệu hoạt động',
        'unit_name.require' => 'Vui lòng điền vào đơn vị',
        'images.require' => 'Vui lòng chọn hình ảnh băng chuyền sản phẩm',
        'section_time.require' => 'Vui lòng chọn khoảng thời gian sự kiện',
        'num.require' => 'Vui lòng điền giới hạn số lượng mua hàng',
        'num.gt' => 'Giới hạn số lượng mua phải lớn hơn0',
        'once_num.require' => 'Vui lòng điền số lượng mua một lần',
        'once_num.gt' => 'Số lượng mua một lần phải lớn hơn0',
        'time_id.require' => 'Vui lòng chọn khoảng thời gian flash sale',
        'temp_id.require' => 'Vui lòng chọn mẫu vận chuyển hàng hóa',
        'description.require' => 'Vui lòng điền thông tin chi tiết sản phẩm',
        'attrs.require' => 'Vui lòng chọn thông số kỹ thuật',
    ];

    protected $scene = [
        'save' => ['product_id', 'title', 'info', 'unit_name', 'image', 'images', 'give_integral', 'section_time', 'is_hot', 'status', 'num', 'once_num', 'time_id', 'temp_id', 'sort', 'description', 'attrs', 'items'],
    ];
}
