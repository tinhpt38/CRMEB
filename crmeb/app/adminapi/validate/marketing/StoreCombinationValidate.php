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

class StoreCombinationValidate extends Validate
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
        'temp_id' => 'require',
        'description' => 'require',
        'attrs' => 'require',
        'items' => 'require',
        'people' => 'require|gt:1',
        'effective_time' => 'require|gt:0',
        'virtual' => 'require|gt:0|elt:100',
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
        'virtual.require' => 'Vui lòng điền tỷ lệ chia sẻ nhóm ảo',
        'virtual.gt' => 'Số lượng người tham gia ảo không được lớn hơn số lượng người tham gia trong nhóm',
        'virtual.elt' => 'Số lượng người tham gia ảo không được lớn hơn số lượng người tham gia trong nhóm',
        'once_num.require' => 'Vui lòng điền số lượng mua một lần',
        'once_num.gt' => 'Số lượng mua một lần phải lớn hơn0',
        'temp_id.require' => 'Vui lòng chọn mẫu vận chuyển sản phẩm',
        'description.require' => 'Vui lòng điền thông tin chi tiết sản phẩm',
        'attrs.require' => 'Vui lòng chọn thông số kỹ thuật',
        'people.require' => 'Vui lòng điền số người trong nhóm',
        'people.gt' => 'Số người trong nhóm không thể ít hơn 2 người',
        'effective_time.require' => 'Vui lòng điền thời hạn hiệu lực của nhóm',
        'effective_time.gt' => 'Thời hạn hiệu lực của nhóm phải lớn hơn0',
    ];

    protected $scene = [
        'save' => ['product_id', 'title', 'info', 'unit_name', 'image', 'images', 'section_time', 'is_host', 'is_show', 'num', 'people', 'once_num', 'virtual', 'temp_id', 'sort', 'description', 'attrs', 'items', 'people', 'effective_time'],
    ];
}
