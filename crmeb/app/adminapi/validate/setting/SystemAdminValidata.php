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
namespace app\adminapi\validate\setting;

use think\Validate;

class SystemAdminValidata extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'account' => ['require', 'alphaDash'],
        'conf_pwd' => 'require',
        'pwd' => 'require',
        'real_name' => 'require',
        'roles' => ['require', 'array'],
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'account.require' => 'Vui lòng điền vào tài khoản quản trị viên',
        'account.alphaDash' => 'Tài khoản quản trị viên phải bằng chữ cái tiếng Anh',
        'conf_pwd.require' => 'Vui lòng nhập mật khẩu xác nhận',
        'pwd.require' => 'Vui lòng nhập mật khẩu',
        'real_name.require' => 'Vui lòng nhập tên quản trị viên',
        'roles.require' => 'Vui lòng chọn danh tính quản trị viên',
        'roles.array' => 'danh tính phải là một mảng',
    ];

    protected $scene = [
        'get' => ['account', 'pwd'],
        'update' => ['account', 'roles'],
    ];


}
