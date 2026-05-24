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


class ExpressValidata extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'com' => 'require',
        'temp_id' => 'require',
        'to_name' => 'require',
        'to_tel' => 'require|mobile',
        'to_address' => 'require',
        'siid' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'com.require' => 'Hãy chọn công ty chuyển phát nhanh',
        'temp_id.number' => 'Vui lòng chọn mẫu vận chuyển sản phẩm',
        'to_name.require' => 'Vui lòng điền tên người gửi',
        'to_tel.require' => 'Vui lòng nhập số điện thoại di động của người gửi',
        'to_tel.mobile' => 'Số điện thoại di động của người gửi không chính xác',
        'to_address.require' => 'Vui lòng điền địa chỉ chi tiết của người gửi',
        'siid.require' => 'Vui lòng điền số máy in trên đám mây',
    ];
}
