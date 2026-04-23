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
    //Tiện ích mở rộng mặc định
    'default' => 'yihaotong',
    //Giới hạn gửi hàng ngày cho một điện thoại di động
    'maxPhoneCount' => 20,
    //Mã xác minh được gửi trực tuyến mỗi phút
    'maxMinuteCount' => 5,
    //Giới hạn gửi hàng ngày cho một IP
    'maxIpCount' => 50,
    //Chế độ lái xe
    'stores' => [
        //Thẻ một số
        'yihaotong' => [
            'sms_account' => '',
            'sms_token' => ''
        ],
        //Đám mây của Alibaba
        'aliyun' => [
            'aliyun_SignName' => '',
            'aliyun_AccessKeyId' => '',
            'aliyun_AccessKeySecret' => '',
            'aliyun_RegionId' => '',
        ],
        //Đám mây Tencent
        'tencent' => [
            'tencent_sms_app_id' => '',
            'tencent_sms_secret_id' => '',
            'tencent_sms_secret_key' => '',
            'tencent_sms_sign_name' => '',
            'tencent_sms_region' => '',
        ]
    ]
];
