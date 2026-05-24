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
namespace app\adminapi\validate\marketing;

use think\Validate;

class LiveRoomValidate extends Validate
{

    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'name' => 'require',
        'cover_img' => 'require',
        'share_img' => 'require',
        'anchor_wechat' => 'require',
        'start_time' => 'require|checkStartTime',
        'phone' => 'require|checkPhone',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'name.require' => 'Vui lòng nhập tên phòng phát sóng trực tiếp',
        'cover_img.require' => 'Vui lòng chọn hình nền',
        'share_img.require' => 'Hãy chọn chia sẻ hình ảnh',
        'anchor_wechat.require' => 'Vui lòng chọn mỏ neo',
        'start_time.require' => 'Vui lòng chọn thời gian bắt đầu và kết thúc của buổi phát sóng trực tiếp',
        'start_time.checkStartTime' => 'Vui lòng chọn thời gian bắt đầu và kết thúc của buổi phát sóng trực tiếp',
        'phone.require' => 'Vui lòng điền số điện thoại di động của bạn',
        'phone.checkPhone' => 'Lỗi định dạng số điện thoại di động',
    ];

    protected function checkPhone($value): bool
    {
        return check_phone($value) == true;
    }

    protected function checkStartTime($value): bool
    {
        return count($value) == 2;
    }

    protected $scene = [
        'save' => ['name', 'cover_img', 'share_img', 'anchor_wechat', 'start_time', 'phone'],
    ];
}
