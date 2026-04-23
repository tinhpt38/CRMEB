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

class MealValidata extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'meal_id' => 'require|number',
        'price' => 'require',
        'num' => 'require|number',
        'type' => 'require',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */
    protected $message = [
        'meal_id.require' => 'Vui lòng chuyển gói hàngid',
        'meal_id.number' => 'ID gói phải là một số',
        'price.require' => 'Vui lòng điền số tiền gói',
        'num.require' => 'Vui lòng điền số lượng mua hàng',
        'num.number' => 'Số lượng mua phải là số',
        'type.require' => 'Vui lòng điền loại gói mua hàng'
    ];

}
