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

class LiveAnchorValidate extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'wechat' => 'require',
        'phone' => 'require|checkPhone',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'name.require' => 'Vui lòng nhập tên',
        'wechat.require' => 'Vui lòng nhập tài khoản WeChat của bạn',
        'phone.require' => 'Vui lòng điền số điện thoại di động của bạn',
        'phone.checkPhone' => 'Lỗi định dạng số điện thoại di động',
    ];

    protected function checkPhone($value): bool
    {
        return check_phone($value) == true;
    }

    protected $scene = [
        'save' => ['name', 'wechat', 'phone'],
    ];
}
