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
namespace app\adminapi\validate\merchant;

use think\Validate;

class SystemStoreValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'introduction' => 'require',
        'phone' => 'require',
        'address' => 'require',
        'image' => 'require',
        'oblong_image' => 'require',
        'detailed_address' => 'require',
        'latlng' => 'require',
        'day_time' => 'require',
    ];
    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'name.require' => 'Vui lòng điền tên cửa hàng',
        'introduction.require' => 'Vui lòng điền vào hồ sơ cửa hàng',
        'phone.require' => 'Vui lòng điền số điện thoại của cửa hàng',
        'image.require' => 'Vui lòng chọn điểm đónlogo',
        'oblong_image.require' => 'Vui lòng chọn hình ảnh lớn hơn của điểm đón',
        'address.require' => 'Vui lòng chọn một địa chỉ',
        'detailed_address.require' => 'Vui lòng điền địa chỉ chi tiết',
        'latlng.require' => 'Vui lòng chọn vĩ độ và kinh độ',
        'day_time.require' => 'Vui lòng chọn giờ làm việc',
    ];

    protected $scene = [
        'save' => ['name', 'phone', 'address', 'detailed_address', 'latlng', 'day_time', 'image', 'oblong_image'],
    ];
}
