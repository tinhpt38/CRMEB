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
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Env;

// +----------------------------------------------------------------------
// | Cài đặt nhật ký
// +----------------------------------------------------------------------
return [
    // Kênh ghi nhật ký mặc định
    'default'      => Env::get('log.channel', 'file'),
    // mức độ ghi nhật ký
    'level'        => ['error', 'warning', 'fail', 'success', 'info', 'notice', 'crontab', 'crmeb', 'listener'],
    // Kênh được ghi theo loại nhật ký ['error'=>'email',...]
    'type_channel' => [],
    //Có bật nhật ký thành công của doanh nghiệp hay không
    'success_log'  => false,
    //Có bật nhật ký lỗi kinh doanh hay không
    'fail_log'     => false,
    //Có bật nhật ký tác vụ theo lịch trình hay không
    'timer_log'    => false,
    //Có bật nhật ký sự kiện tùy chỉnh hay không
    'listener_log'    => false,
    // Đăng nhập danh sách kênh
    'channels'     => [
        'file' => [
            // Phương pháp ghi nhật ký
            'type'        => 'File',
            // Thư mục lưu nhật ký
            'path'        => app()->getRuntimePath() . 'log' . DIRECTORY_SEPARATOR,
            // Ghi nhật ký tập tin duy nhất
            'single'      => false,
            // cấp độ nhật ký độc lập
            'apart_level' => ['error', 'fail', 'success', 'crontab', 'crmeb', 'listener'],
            // Số lượng tệp nhật ký tối đa
            'max_files'   => 60,
            'time_format' => 'Y-m-d H:i:s',
            'format'      => '%s|%s|%s'
        ],
        // Các cấu hình kênh nhật ký khác
    ],
];
