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

namespace app\kefuapi\validate;


use think\Validate;

class LoginValidate extends Validate
{
    protected $regex = ['account' => '/^[a-zA-Z0-9]{4,30}$/'];
    /**
     * @var string[]
     */    protected $rule = [
        'account' => 'require|account',
        'password' => 'require',
    ];

    /**
     * @var string[]
     */    protected $message = [
        'account.require' => 'Vui lòng nhập số tài khoản và mật khẩu của bạn',
        'account.account' => 'Vui lòng nhập số tài khoản và mật khẩu của bạn',
        'password.regex' => 'Vui lòng nhập số tài khoản và mật khẩu của bạn',
    ];
}
