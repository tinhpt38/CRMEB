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


use crmeb\utils\PhoneValidate;
use think\Validate;

/**
 * Xác minh đăng ký
 * Class RegisterValidates
 * @package app\http\validates\user
 */class RegisterValidates extends Validate
{
    protected $regex = ['phone' => PhoneValidate::VN_MOBILE_PATTERN];

    protected $rule = [
        'phone' => 'require|regex:phone',
        'account' => 'require|regex:phone',
        'captcha' => 'require|length:6',
        'password' => 'require',
    ];

    protected $message = [
        'phone.require' => 'Vui lòng nhập số điện thoại di động',
        'phone.regex' => 'Định dạng số điện thoại di động không chính xác',
        'account.require' => 'Vui lòng nhập số điện thoại di động',
        'account.regex' => 'Định dạng số điện thoại di động không chính xác',
        'captcha.require' => 'Vui lòng nhập mã xác minh',
        'captcha.length' => 'Lỗi mã xác minh',
        'password.require' => 'Mật khẩu phải có từ 6 đến 16 ký tự',
    ];


    public function sceneCode()
    {
        return $this->only(['phone']);
    }


    public function sceneRegister()
    {
        return $this->only(['account', 'captcha', 'password']);
    }
}
