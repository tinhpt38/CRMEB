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

class StoreCategoryValidate extends Validate
{
    protected $rule = [
        'pid' => 'integer|egt:0',
        'cate_name' => 'require|max:32',
        'pic' => 'max:255',
        'big_pic' => 'max:255',
        'sort' => 'integer|egt:0',
        'is_show' => 'in:0,1',
    ];

    protected $message = [
        'pid.integer' => 'Danh mục cha không hợp lệ',
        'pid.egt' => 'Danh mục cha không hợp lệ',
        'cate_name.require' => 'Vui lòng nhập tên danh mục',
        'cate_name.max' => 'Tên danh mục không được vượt quá 32 ký tự',
        'pic.max' => 'Đường dẫn biểu tượng danh mục quá dài',
        'big_pic.max' => 'Đường dẫn ảnh danh mục lớn quá dài',
        'sort.integer' => 'Thứ tự phải là số nguyên',
        'sort.egt' => 'Thứ tự không được nhỏ hơn 0',
        'is_show.in' => 'Trạng thái hiển thị không hợp lệ',
    ];

    protected $scene = [
        'save' => ['pid', 'cate_name', 'pic', 'big_pic', 'sort', 'is_show'],
        'update' => ['pid', 'cate_name', 'pic', 'big_pic', 'sort', 'is_show'],
    ];
}
