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

/** tập tin định nghĩa sự kiện
 * Ví dụ về sự kiện cuộc gọi：
 * @param mixed $event Tên sự kiện (hoặc tên lớp）
 * @param mixed $args  tham số
 * event($event,$args);
 * event('OrderCreateAfterListener',$order);
*/

return [
    'bind' => [],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [\app\listener\http\HttpEndListener::class], //HTTPYêu cầu sự kiện gọi lại kết thúc
        'LogLevel' => [],
        'LogWrite' => [],
        'QueueStartListener' => [\app\listener\queue\QueueStartListener::class],
        'UserLoginListener' => [\app\listener\user\LoginListener::class],
        'AdminLoginListener' => [\app\listener\admin\AdminLoginListener::class],//Đăng nhập quản trị viên
        'UserRegisterListener' => [\app\listener\user\RegisterListener::class], //Đăng ký Khách hàng sau sự kiện
        'WechatAuthListener' => [\app\listener\wechat\AuthListener::class], //Ủy quyền Khách hàng sau sự kiện
        'OrderCreateAfterListener' => [\app\listener\order\OrderCreateAfterListener::class], //Tạo đơn hàng sau sự kiện
        'OrderPaySuccessListener' => [\app\listener\order\OrderPaySuccessListener::class], //Sự kiện sau khi thanh toán đơn hàng thành công
        'OrderDeliveryListener' => [\app\listener\order\OrderDeliveryListener::class], //Đơn hàng giao hàng sau sự kiện
        'OrderTakeListener' => [\app\listener\order\OrderTakeListener::class], //Nhận đơn hàng sau sự kiện
        'OrderRefundCreateAfterListener' => [\app\listener\order\OrderRefundCreateAfterListener::class], //Yêu cầu trả hàng / hoàn tiền tạo ra sự kiện sau
        'OrderRefundCancelAfterListener' => [\app\listener\order\OrderRefundCancelAfterListener::class], //Hủy đơn hàng sau bán hàng sau sự kiện
        'OutPushListener' => [\app\listener\out\OutPushListener::class], //Sự kiện đẩy bên ngoài
        'UserLevelListener' => [\app\listener\user\UserLevelListener::class], //Sự kiện nâng cấp Khách hàng
        'UserVisitListener' => [\app\listener\user\UserVisitListener::class], //Sự kiện truy cập của Khách hàng
        'NoticeListener' => [\app\listener\notice\NoticeListener::class], //thông báo->sự kiện tin nhắn
        'CustomNoticeListener' => [\app\listener\notice\CustomNoticeListener::class], //thông báo->Sự kiện gửi tin nhắn tùy chỉnh
        'NotifyListener' => [\app\listener\pay\NotifyListener::class],//Trả tiền gọi lại không đồng bộ
        'OrderShippingListener' => [\app\listener\order\OrderShippingListener::class],//Quản lý phân phối chương trình nhỏ
        'CustomEventListener' => [\app\listener\CustomEventListener::class],//Sự kiện tùy chỉnh
    ],
];


