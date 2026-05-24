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

class StoreProductProtectionValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:32',
        'content' => 'require|max:5000',
        'image' => 'max:255',
        'sort' => 'integer|egt:0',
        'status' => 'in:0,1',
    ];

    protected $message = [
        'title.require' => 'Vui lòng nhập tên mục bảo vệ',
        'title.max' => 'Tên mục bảo vệ không được vượt quá 32 ký tự',
        'content.require' => 'Vui lòng nhập Nội dung bảo vệ',
        'content.max' => 'Nội dung bảo vệ không được vượt quá 5000 ký tự',
        'image.max' => 'Đường dẫn biểu tượng quá dài',
        'sort.integer' => 'Đơn hàng phải là số nguyên',
        'sort.egt' => 'Đơn hàng không được nhỏ hơn 0',
        'status.in' => 'Trạng thái hiển thị không hợp lệ',
    ];

    protected $scene = [
        'save' => ['title', 'content', 'image', 'sort', 'status'],
    ];
}
