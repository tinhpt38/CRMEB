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

return [
    // Cấu hình kết nối cơ sở dữ liệu được sử dụng theo mặc định
    'default'         => Env::get('database.driver', 'mysql'),

    // Thông tin cấu hình kết nối cơ sở dữ liệu
    'connections'     => [
        'mysql' => [
            // Loại cơ sở dữ liệu
            'type'            => Env::get('database.type', 'mysql'),
            // Địa chỉ máy chủ
            'hostname'        => Env::get('database.hostname', '127.0.0.1'),
            // Tên cơ sở dữ liệu
            'database'        => Env::get('database.database', 'crmeb31'),
            // tên người dùng
            'username'        => Env::get('database.username', 'root'),
            // mật khẩu
            'password'        => Env::get('database.password', 'root'),
            // hải cảng
            'hostport'        => Env::get('database.hostport', '3306'),
            // kết nốidsn
            'dsn'             => '',
            // Thông số kết nối cơ sở dữ liệu
            'params'          => [],
            // Mã hóa cơ sở dữ liệu mặc định làutf8
            'charset'         => Env::get('database.charset', 'utf8'),
            // Tiền tố bảng cơ sở dữ liệu
            'prefix'          => Env::get('database.prefix', 'eb_'),
            // Chế độ gỡ lỗi cơ sở dữ liệu
            'debug'           => Env::get('database.debug', true),
            // Phương pháp triển khai cơ sở dữ liệu:0 Tập trung(máy chủ duy nhất),1 phân phối(Máy chủ chủ-nô lệ)
            'deploy'          => 0,
            // Việc đọc và ghi cơ sở dữ liệu có được tách biệt không? Chế độ chủ-nô lệ có hiệu lực
            'rw_separate'     => false,
            // Số lượng máy chủ chính sau khi tách đọc và ghi
            'master_num'      => 1,
            // Chỉ định số sê-ri máy chủ nô lệ
            'slave_no'        => '',
            // Có nên kiểm tra nghiêm ngặt xem trường đó có tồn tại hay không
            'fields_strict'   => true,
            // Phân tích hiệu suất SQL có cần thiết không?
            'sql_explain'     => false,
            // Builderloại
            'builder'         => '',
            // Queryloại
            'query'           => '',
            // Bạn có cần ngắt kết nối và kết nối lại không?
            'break_reconnect' => true,
        ],

        // Thông tin thêm về cấu hình cơ sở dữ liệu
    ],

    // Quy tắc truy vấn thời gian tùy chỉnh
    'time_query_rule' => [],
    // Tự động ghi các trường dấu thời gian
    'auto_timestamp'  => 'timestamp',
    // Định dạng thời gian mặc định sau khi trường thời gian bị loại bỏ
    'datetime_format' => 'Y-m-d H:i:s',
    //Cấu hình phân trang dữ liệu
    'page' => [
        //số trangkey
        'pageKey' => 'page',
        //Chặn trên mỗi trangkey
        'limitKey' => 'limit',
        //Giá trị tối đa bị chặn trên mỗi trang
        'limitMax' => 100,
        //Số mục mặc định
        'defaultLimit' => 10,
    ]
];
