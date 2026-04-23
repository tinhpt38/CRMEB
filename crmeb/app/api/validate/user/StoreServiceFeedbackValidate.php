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

namespace app\api\validate\user;


use think\Validate;

class StoreServiceFeedbackValidate extends Validate
{
    protected $regex = ['phone' => '/^1[3456789]\d{9}$/'];

    protected $rule = [
        'phone' => 'require|regex:phone',
        'rela_name' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'phone.require' => 'Số điện thoại di động là bắt buộc',
        'phone.regex' => 'Lỗi định dạng số điện thoại di động',
        'content.require' => 'Vui lòng điền nội dung phản hồi',
        'rela_name.require' => 'Tên là bắt buộc',
    ];
}
