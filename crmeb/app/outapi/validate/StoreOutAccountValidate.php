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

class StoreOutAccountValidate extends Validate
{
    protected $regex = ['account' => '/^[a-zA-Z0-9]{4,30}$/'];

    /**
     * @var string[]
     */
    protected $rule = [
        'appid' => 'require|account',
        'appsecret' => 'min:6|max:32',
        'title' => 'max:120'
    ];

    /**
     * @var string[]
     */
    protected $message = [
        'appid.require' => 'Vui lòng điền số tài khoản',
        'appid.account' => 'Số tài khoản phải là sự kết hợp của các số hoặc chữ cái từ 4-30 chữ số',
        'appsecret.min' => 'Mật khẩu phải có từ 6 đến 16 ký tự',
        'appsecret.max' => 'Mật khẩu phải có từ 6 đến 16 ký tự',
        'title.max' => 'Mô tả không thể vượt quá 120 từ',
    ];

    protected $scene = [
        'save' => ['appid', 'appsecret', 'title'],
        'update' => ['appsecret', 'title'],
    ];
}
