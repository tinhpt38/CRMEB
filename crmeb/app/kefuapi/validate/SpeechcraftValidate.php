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

namespace app\kefuapi\validate;


use think\Validate;

class SpeechcraftValidate extends Validate
{
    /**
     * @var string[]
     */
    protected $rule = [
        'title' => 'chsAlphaNum|length:0,50',
        'cate_id' => 'require|number',
        'message' => 'require|length:0,500',
        'sort' => 'number',
    ];

    /**
     * @var string[]
     */
    protected $message = [
        'title.chsAlphaNum' => 'Vui lòng điền chữ hoặc số tiếng Trung',
        'title.length' => 'Độ dài tiêu đề không thể vượt quá 50 từ',
        'cate_id.require' => 'Vui lòng chọn một danh mục',
        'cate_id.number' => 'Danh mục phải là số',
        'message.require' => 'Hãy điền nội dung bài phát biểu',
        'message.length' => 'Độ dài bài phát biểu không quá 500 từ',
        'sort.number' => 'Sắp xếp phải là số',
    ];
}
