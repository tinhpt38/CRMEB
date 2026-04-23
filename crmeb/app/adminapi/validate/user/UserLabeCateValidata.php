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

/**
 * Class UserLabeCateValidata
 * @package app\adminapi\validate\user
 */
class UserLabeCateValidata extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'sort' => 'require|number'
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'name.require' => 'Vui lòng điền tên danh mục nhãn',
        'sort.require' => 'Hãy điền vào nhãn phân loại phân loại',
        'sort.number' => 'Danh mục thẻ phải là số'
    ];
}
