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
         */        $this->message = [
            'product_id.require' => 'Vui lòng chọn sản phẩm',
            'product_id.integer' => 'Sản phẩm không hợp lệ',
            'product_id.gt' => 'Sản phẩm không hợp lệ',
            'avatar.require' => 'Vui lòng chọn hình đại diện của Khách hàng',
            'avatar.max' => 'Đường dẫn hình đại diện quá dài',
            'nickname.require' => 'Vui lòng nhập tên Khách hàng',
            'nickname.max' => 'Tên Khách hàng không được vượt quá 32 ký tự',
            'comment.require' => 'Vui lòng nhập Nội dung đánh giá',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 500 ký tự',
            'pics.array' => 'Danh sách hình ảnh đánh giá không hợp lệ',
            'add_time.dateFormat' => 'Thời gian đánh giá không đúng định dạng',
            'product_score.require' => 'Vui lòng chọn điểm sản phẩm',
            'service_score.require' => 'Vui lòng chọn điểm dịch vụ',
            'product_score.in' => 'Điểm sản phẩm phải là số nguyên Trong khoảng 1-5',
            'service_score.in' => 'Điểm dịch vụ phải là số nguyên Trong khoảng 1-5',
        ];
    }

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'product_id' => 'require|integer|gt:0',
        'avatar' => 'require|max:255',
        'nickname' => 'require|max:32',
        'comment' => 'require|max:500',
        'pics' => 'array',
        'add_time' => 'dateFormat:Y-m-d H:i:s',
        'product_score' => ['require', 'in:1,2,3,4,5'],
        'service_score' => ['require', 'in:1,2,3,4,5'],
    ];

    protected $scene = [
        'save' => ['product_id', 'nickname', 'comment', 'avatar', 'product_score', 'service_score'],
    ];
}
