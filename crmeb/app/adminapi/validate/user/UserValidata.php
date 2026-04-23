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

namespace app\adminapi\validate\user;

use think\Validate;

class UserValidata extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'account' => 'require|alphaNum',
        'pwd' => 'require',
        'true_pwd' => 'require',
        'nickname' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'account.require' => 'Vui lòng nhập số tài khoản',
        'account.alphaNum' => 'Số tài khoản chỉ có thể là số và chữ',
        'pwd.require' => 'Vui lòng điền mật khẩu',
        'true_pwd.require' => 'Vui lòng nhập mật khẩu xác nhận',
        'nickname.number' => 'Vui lòng nhập tên'
    ];
}
