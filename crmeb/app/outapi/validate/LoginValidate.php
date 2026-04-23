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

namespace app\outapi\validate;


use think\Validate;

class LoginValidate extends Validate
{
    protected $regex = ['account' => '/^[a-zA-Z0-9]{4,30}$/'];
    /**
     * @var string[]
     */
    protected $rule = [
        'appid' => 'require|account',
        'appsecret' => 'require',
    ];

    /**
     * @var string[]
     */
    protected $message = [
        'appid.require' => 'Vui lòng điền số tài khoản',
        'appid.account' => 'Tài khoản hoặc mật khẩu không chính xác',
        'appsecret.regex' => 'Vui lòng điền mật khẩu',
    ];
}
