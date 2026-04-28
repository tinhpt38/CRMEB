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

class StoreBargainValidate extends Validate
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
        'temp_id' => 'require',
        'description' => 'require',
        'attrs' => 'require',
        'items' => 'require',
        'bargain_num'=>'require|gt:0',
        'people_num'=>'require|gt:1',
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
        'unit_name.require' => 'Vui lòng điền vào Đơn vị',
        'images.require' => 'Vui lòng chọn hình ảnh băng chuyền sản phẩm',
        'section_time.require' => 'Vui lòng chọn khoảng thời gian sự kiện',
        'num.require' => 'Vui lòng điền giới hạn số lượng mua hàng',
        'num.gt' => 'Giới hạn số lượng mua phải lớn hơn0',
        'bargain_num.require' => 'Hãy điền số lần bạn đã giúp đỡ',
        'bargain_num.gt' => 'Số lần hack phải lớn hơn0',
        'people_num.require' => 'Hãy điền số lượng người thương lượng',
        'people_num.gt' => 'Số lượng người thương lượng phải lớn hơn1',
        'temp_id.require' => 'Vui lòng chọn mẫu vận chuyển sản phẩm',
        'description.require' => 'Vui lòng điền thông tin chi tiết sản phẩm',
        'attrs.require' => 'Vui lòng chọn thông số kỹ thuật',
    ];

    protected $scene = [
        'save' => ['product_id', 'title', 'info', 'unit_name', 'image', 'images', 'give_integral', 'section_time', 'is_hot', 'status', 'num', 'bargain_num', 'people_num', 'temp_id', 'sort', 'description', 'attrs', 'items'],
    ];
}
