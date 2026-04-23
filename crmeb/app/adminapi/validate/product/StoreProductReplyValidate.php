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
namespace app\adminapi\validate\product;

use think\Validate;

class StoreProductReplyValidate extends Validate
{
    public function __construct()
    {
        parent::__construct();

        /**
         * Xác định thông báo lỗi
         *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
         *
         * @var array
         */
        $this->message = [
            'product_id.require' => 'Vui lòng chọn sản phẩm',
            'avatar.require' => 'Vui lòng chọn hình đại diện của người dùng',
            'nickname.require' => 'Vui lòng điền tên người dùng',
            'comment.require' => 'Vui lòng điền nội dung bình luận',
            'product_score.require' => 'Vui lòng chọn điểm sản phẩm',
            'service_score.require' => 'Vui lòng chọn điểm dịch vụ',
            'product_score.In' => 'Điểm sản phẩm phải là số nguyên trong khoảng 1-5',
            'service_score.In' => 'Điểm dịch vụ phải là số nguyên trong khoảng 1-5',
        ];
    }

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */
    protected $rule = [
        'product_id' => 'require',
        'avatar' => 'require',
        'nickname' => 'require',
        'comment' => 'require',
        'product_score' => ['require','In:1,2,3,4,5'],
        'service_score' => ['require','In:1,2,3,4,5'],
    ];

    protected $scene = [
        'save' => ['product_id', 'nickname', 'comment', 'avatar', 'product_score', 'service_score'],
    ];
}
