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

// +----------------------------------------------------------------------
// | Áp dụng cài đặt
// +----------------------------------------------------------------------

use think\facade\Env;

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

return [
    // Địa chỉ ứng dụng
    'app_host'         => Env::get('app.host', ''),
    // không gian tên ứng dụng
    'app_namespace'    => '',
    // Có bật định tuyến hay không
    'with_route'       => true,
    // Có bật sự kiện hay không
    'with_event'       => true,
    // Chế độ đa ứng dụng tự động
    'auto_multi_app'   => true,
    // Ánh xạ ứng dụng (hợp lệ ở chế độ đa ứng dụng tự động）
    'app_map'          => [],
    // Liên kết tên miền (hợp lệ ở chế độ đa ứng dụng tự động)）
    'domain_bind'      => [],
    // Danh sách các ứng dụng bị cấm truy cập URL (hợp lệ ở chế độ đa ứng dụng tự động)）
    'deny_app_list'    => [],
    // Ứng dụng mặc định
    'default_app'      => '',

    'app_express'      => true,
    // Múi giờ mặc định
    'default_timezone' => Env::get('app.default_timezone', 'Asia/Ho_Chi_Minh'),
    // Tệp mẫu cho trang ngoại lệ
    'exception_tmpl'   => app()->getRootPath() . 'public/statics/exception.tpl',
    // thông báo lỗi,Hợp lệ ở chế độ không gỡ lỗi
    'error_message'    => 'Lỗi trang! Vui lòng thử lại sau～',
    // Hiển thị thông báo lỗi
    'show_error_msg'   => false,
    // Công tắc nhắc nhở cho lệnh xếp hàng tin nhắn hoặc lệnh tác vụ đã lên lịch không được bật.
    'console_remind'   => true,
    // admintiền tố định tuyến
    'admin_prefix'     => 'admin',
    //Hàm tạo mã tạo đường dẫn đến tệp giao diện người dùng
    'admin_template_path' => dirname(root_path()) . DS . 'template' . DS . 'admin' . DS . 'src' . DS,
    //Có tạo tệp trực tiếp khi lưu dữ liệu không
    'crud_make'        => true
];
