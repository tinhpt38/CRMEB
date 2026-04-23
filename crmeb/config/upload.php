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
    //Chế độ tải lên mặc định,Cấu hình phụ trợ được ưu tiên,Khi thêm loại, chỉ mục phải nhất quán với tên trình điều khiển và sử dụng chữ cái viết thường.
    'default' => 'local',
    //Kích thước tệp tải lên 50M
    'filesize' => 52428800,
    //Loại hậu tố tệp tải lên
    'fileExt' => ['jpg', 'jpeg', 'png', 'gif', 'pem', 'mp3', 'wma', 'wav', 'amr', 'mp4', 'key', 'xlsx', 'xls', 'txt', 'ico', 'crt', 'webp', 'zip'],
    //Tải lên loại tệp
    'fileMime' => [
        'image/jpg',
        'image/jpeg',
        'image/gif',
        'image/png',
        'text/plain',
        'audio/mpeg',
        'video/mp4',
        'application/octet-stream',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-works',
        'application/vnd.ms-excel',
        'application/zip',
        'text/xml',
        'image/x-icon',
        'image/vnd.microsoft.icon',
        'application/x-x509-ca-cert',
        'image/webp',
        'application/x-zip-compressed',
        // bổ sung còn thiếu
        'audio/x-ms-wma',              // wma
        'audio/wav',                   // wav
        'audio/amr',                   // amr
        'application/x-pem-file',      // pem
        // Windows tương thích
        'audio/mp3',                   // mp3 Windows
        'audio/wave',                  // wav Windows Chrome
        'audio/x-wav',                 // wav Windows IE/Edge
        'application/msexcel',         // xls Windows
    ],
    //Chế độ driver, cấu hình này được ưu tiên với cấu hình nền. Vui lòng thêm tiền tố khi thêm cấu hình ở chế độ nền. Ví dụ: thêm cấu hình Qiniu Cloud: accessKey. Thêm tên biến trong nền. qiniu_accessKey
    'stores' => [
        //Cấu hình tải lên cục bộ
        'local' => [],
        //Cấu hình tải lên đám mây Qiniu
        'qiniu' => [
            'AccessKeyId' => '', // sys_config('qiniu_accessKey')
            'AccessKeySecret' => '', // sys_config('qiniu_secretKey')
        ],
        //oss Cấu hình tải lên đám mây của Alibaba
        'oss' => [
            'AccessKeyId' => '', // sys_config('accessKey')
            'AccessKeySecret' => '', // sys_config('secretKey')
        ],
        //cos Cấu hình tải lên của Tencent Cloud
        'cos' => [
            'AccessKeyId' => '', //sys_config('tengxun_accessKey')
            'AccessKeySecret' => '', //sys_config('tengxun_secretKey')
            'APPID' => '', //sys_config('tengxun_appid')
        ],
        //oss Đám mây JD
        'jdoss' => [
            'AccessKeyId' => '', // sys_config('accessKey')
            'AccessKeySecret' => '', // sys_config('secretKey')
        ],
        //oss Đám mây Huawei
        'obs' => [
            'AccessKeyId' => '', // sys_config('accessKey')
            'AccessKeySecret' => '', // sys_config('secretKey')
        ],
        //oss Đám mây Thiên Nhất
        'tyoss' => [
            'AccessKeyId' => '', // sys_config('accessKey')
            'AccessKeySecret' => '', // sys_config('secretKey')
        ],
    ]
];
