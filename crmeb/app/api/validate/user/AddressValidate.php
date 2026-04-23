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

/**
 * Lớp xác minh địa chỉ người dùng
 * Class AddressValidate
 * @package app\http\validates\user
 */
class AddressValidate extends Validate
{
    //di chuyển
    protected $regex = ['phone' => '/^1[3456789]\d{9}|([0-9]{3,4}-)?[0-9]{7,8}$/'];

    protected $rule = [
        'real_name' => 'require|max:25',
        'phone' => 'require|regex:phone',
        'province' => 'require',
        'city' => 'require',
        'district' => 'require',
        'detail' => 'require',
    ];

    protected $message = [
        'real_name.require' => 'Tên là bắt buộc',
        'real_name.max' => 'Tên không thể vượt quá 25 ký tự',
        'phone.require' => 'Số điện thoại di động là bắt buộc',
        'phone.regex' => 'Lỗi định dạng số điện thoại di động',
        'province.require' => 'Tỉnh là bắt buộc',
        'city.require' => 'Thành phố là bắt buộc',
        'district.require' => 'Quận/quận phải được điền vào',
        'detail.require' => 'Cần có địa chỉ chi tiết',
    ];
}
