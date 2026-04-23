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
return [

    // Chương trình hệ thống nhận tin nhắn đã gửi và đẩy nó đến khách hàng hoặc bộ phận dịch vụ khách hàng tương ứng thông qua cổng này, cũng như lời nhắc bật lên nền cho các đơn hàng mới.
    'channel' => [
        //Cổng nghe giao tiếp nội bộ
        'port' => 40003,
        //Địa chỉ thư từ nội bộ
        'ip' => '127.0.0.1',
    ],

    // notice Đơn hàng mới và đơn hàng hoàn tiền mới gửi tin nhắn đến chương trình và thông báo tin nhắn nền
    'admin' => [
        //giao thức
        'protocol' => 'websocket',
        //địa chỉ nghe
        'ip' => '0.0.0.0',
        //cổng nghe
        'port' => 40001,
        //Đặt số lượng quy trình được bắt đầu bởi phiên bản Worker hiện tại
        'serverCount' => 1,
    ],

    // msg Khách hàng hoặc bộ phận chăm sóc khách hàng gửi tin nhắn vào chương trình, tin nhắn chăm sóc khách hàng được thông báo
    'chat' => [
        //giao thức
        'protocol' => 'websocket',
        //địa chỉ nghe
        'ip' => '0.0.0.0',
        //cổng nghe
        'port' => 40002,
        //Đặt số lượng quy trình được bắt đầu bởi phiên bản Worker hiện tại
        'serverCount' => 1,
    ],
];
