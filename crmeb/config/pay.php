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
    //Chế độ thanh toán mặc định
    'default' => 'wechat_pay',
    //Phương thức thanh toán
    'payType' => ['weixin' => 'WeChat trả tiền', 'yue' => 'thanh toán số dư', 'offline' => 'Thanh toán ngoại tuyến'],
    //Phương thức rút tiền
    'extractType' => ['alipay', 'bank', 'weixin'],
    //Phương thức giao hàng
    'deliveryType' => ['send' => 'giao hàng của người bán', 'express' => 'chuyển phát nhanh'],
    //Chế độ lái xe
    'stores' => [
        //WeChat trả tiền
        'wechat_pay' => [],
        //thanh toán Alipay
        'ali_pay' => [],
        //thanh toán số dư
        'yue' => [],
    ]
];
