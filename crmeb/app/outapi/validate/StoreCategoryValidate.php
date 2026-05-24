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
namespace app\outapi\validate;

use think\Validate;

class StoreCategoryValidate extends Validate
{
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'pid' => 'number|egt:0',
        'cate_name' => 'require|max:25',
        'pic' => 'max:128',
        'big_pic' => 'max:200',
        'sort' => 'number|egt:0',
        'is_show' => 'in:0,1'
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'pid.number' => 'Loại tham số ID gốc không đúng',
        'pid.egt' => 'Loại tham số ID gốc không đúng',
        'cate_name.require' => 'Tên danh mục không được để trống',
        'cate_name.max' => 'Tên danh mục không được dài quá 25 ký tự',
        'pic.max' => 'Độ dài biểu tượng danh mục không được vượt quá 128 ký tự',
        'big_pic.max' => 'Độ dài của ảnh phân loại không được vượt quá 200 ký tự.',
        'sort.number' => 'Lỗi loại tham số sắp xếp',
        'sort.egt' => 'Sắp xếp không thể nhỏ hơn0',
        'is_show.in' => 'Trạng thái phải là số nguyên Trong khoảng 0-1',
    ];

    protected $scene = [
        'save' => ['pid', 'cate_name', 'pic', 'big_pic', 'sort', 'is_show'],
    ];
}