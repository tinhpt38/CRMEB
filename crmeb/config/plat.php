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
// +----------------------------------------------------------------------
// | cấu hình tin nhắn
// +----------------------------------------------------------------------

return [
    //Tài khoản nền tảng
    'account' => '',
    //Khóa nền tảng
    'secret' => '',
    //Chế độ lái xe
    'stores' => [
        'sms' => [
            //Giới hạn gửi hàng ngày cho một điện thoại di động
            'maxPhoneCount' => 10,
            //Mã xác minh được gửi trực tuyến mỗi phút
            'maxMinuteCount' => 20,
            //Giới hạn gửi hàng ngày cho một IP
            'maxIpCount' => 50,
            //mẫu tin nhắnid
            'template_id' => [
                //Mã xác minh tùy chỉnh giới hạn thời gian
                'VERIFICATION_CODE_TIME' => 538393,
                //Mã xác minh
                'VERIFICATION_CODE' => 518076,
                //Thanh toán thành công
                'PAY_SUCCESS_CODE' => 520268,
                //Nhắc nhở vận chuyển
                'DELIVER_GOODS_CODE' => 520269,
                //Xác nhận lời nhắc giao hàng
                'TAKE_DELIVERY_CODE' => 520271,
                //Nhắc nhở đặt hàng của quản trị viên
                'ADMIN_PLACE_ORDER_CODE' => 520272,
                //Lời nhắc trả lại của quản trị viên
                'ADMIN_RETURN_GOODS_CODE' => 520274,
                //Lời nhắc thanh toán thành công của quản trị viên
                'ADMIN_PAY_SUCCESS_CODE' => 520273,
                //Quản trị viên xác nhận đã nhận
                'ADMIN_TAKE_DELIVERY_CODE' => 520422,
                //Nhắc nhở thay đổi giá
                'PRICE_REVISION_CODE' => 528288,
                //Đơn hàng chưa được thanh toán
                'ORDER_PAY_FALSE' => 528116,
                //Nhắc nhở đặt hàng của quản trị viên
                'ADMIN_ORDER_UID' => 578254627,
            ]
        ]
    ]
];