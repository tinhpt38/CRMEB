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

class SystemCityValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'level' => 'number',
        'parent_id' => 'number',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'name.require' => 'Vui lòng điền tên thành phố',
        'level.number' => 'levelLỗi định dạng dữ liệu, dự kiến ​​là số nguyên',
        'parent_id.number' => 'parent_idLỗi định dạng dữ liệu, dự kiến ​​là số nguyên',
    ];

    protected $scene = [
        'save' => ['name', 'level', 'parent_id'],
    ];
}
