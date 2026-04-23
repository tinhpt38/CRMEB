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

namespace app\adminapi\validate\serve;


use think\Validate;

class ServeValidata extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'phone' => 'require|number|mobile',
        'password' => 'require',
        'verify_code' => 'require|number',
        'account' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'phone.require' => 'Vui lòng điền số điện thoại di động của bạn',
        'phone.number' => 'Số điện thoại di động bạn nhập phải là số',
        'phone.mobile' => 'Lỗi định dạng số điện thoại di động',
        'password.require' => 'Cần có mật khẩu',
        'verify_code.require' => 'Vui lòng điền mã xác minh',
        'verify_code.number' => 'Mã xác minh SMS phải là số',
        'account.require' => 'Vui lòng điền số tài khoản',
    ];

    protected $scene = [
        'login' => ['password', 'account'],
        'phone' => ['phone']
    ];
}
