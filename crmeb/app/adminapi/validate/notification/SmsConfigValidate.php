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
namespace app\adminapi\validate\notification;

use think\Validate;

/**
 *
 * Class SmsConfigValidate
 * @package app\adminapi\validates
 */class SmsConfigValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     * @var array
     */    protected $rule = [
        'sms_account' => ['require'],
        'sms_token' => ['require'],
    ];

    /**
     * Xác định thông báo lỗi
     * @var array
     */    protected $message = [
        'sms_account.require' => 'Tài khoản SMS phải được điền vào',
        'sms_token.require' => 'Cần có mật khẩu SMS',
    ];
}
