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
namespace app\adminapi\validate\product;

use think\Validate;

class StoreProductLabelValidate extends Validate
{
    protected $rule = [
        'name' => 'require|max:32',
        'sort' => 'integer|egt:0',
        'cate_id' => 'integer|egt:0',
        'type' => 'integer|in:0,1',
        'font_color' => 'max:20',
        'bg_color' => 'max:20',
        'border_color' => 'max:20',
        'image' => 'max:255',
        'status' => 'in:0,1',
        'is_show' => 'in:0,1',
    ];

    protected $message = [
        'name.require' => 'Vui lòng nhập tên',
        'name.max' => 'Tên không được vượt quá 32 ký tự',
        'sort.integer' => 'Thứ tự phải là số nguyên',
        'sort.egt' => 'Thứ tự không được nhỏ hơn 0',
        'cate_id.integer' => 'Danh mục nhãn không hợp lệ',
        'cate_id.egt' => 'Danh mục nhãn không hợp lệ',
        'type.integer' => 'Loại nhãn không hợp lệ',
        'type.in' => 'Loại nhãn không hợp lệ',
        'font_color.max' => 'Mã màu chữ không hợp lệ',
        'bg_color.max' => 'Mã màu nền không hợp lệ',
        'border_color.max' => 'Mã màu viền không hợp lệ',
        'image.max' => 'Đường dẫn ảnh nhãn quá dài',
        'status.in' => 'Trạng thái không hợp lệ',
        'is_show.in' => 'Trạng thái hiển thị không hợp lệ',
    ];

    protected $scene = [
        'save_cate' => ['name', 'sort'],
        'save_label' => ['name', 'cate_id', 'type', 'font_color', 'bg_color', 'border_color', 'image', 'sort', 'status', 'is_show'],
    ];
}
