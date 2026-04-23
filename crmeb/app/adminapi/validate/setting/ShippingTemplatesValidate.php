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

class ShippingTemplatesValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'region_info' => 'array',
        'appoint_info' => 'array',
        'no_delivery_info' => 'array',
        'type' => 'number',
        'appoint' => 'number',
        'no_delivery' => 'number',
        'sort' => 'number'
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'name.require' => 'Vui lòng điền tên mẫu vận chuyển hàng hóa',
        'region_info.array' => 'Thông tin vận chuyển phải là một mảng',
        'appoint_info.array' => 'Thông tin miễn phí vận chuyển phải là một mảng',
        'no_delivery_info.array' => 'Tin nhắn không gửi được phải là một mảng',
        'type.number' => 'typeLỗi định dạng dữ liệu, phải là 1 hoặc 2 hoặc3',
        'appoint.number' => 'appointLỗi định dạng dữ liệu, phải là 0 hoặc1',
        'no_delivery.number' => 'no_deliveryLỗi định dạng dữ liệu, phải là 0 hoặc1',
        'sort.number' => 'sortLỗi định dạng dữ liệu, dự kiến ​​là số nguyên',
    ];

    protected $scene = [
        'save' => ['name', 'type', 'appoint', 'sort', 'region_info', 'appoint_info', 'no_delivery_info', 'no_delivery'],
    ];
}
