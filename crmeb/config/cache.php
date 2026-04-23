<?php
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
// | Cài đặt bộ đệm
// +----------------------------------------------------------------------

return [
    // Trình điều khiển bộ đệm mặc định
    'default' => Env::get('cache.driver', 'file'),

    // Cấu hình chế độ kết nối bộ đệm
    'stores'  => [
        'file' => [
            // Chế độ lái xe
            'type'       => 'File',
            // Thư mục lưu bộ đệm
            'path'       => app()->getRuntimePath() . 'cache' . DIRECTORY_SEPARATOR,
            // tiền tố bộ đệm
            'prefix'     => '',
            // Thời hạn hiệu lực của bộ đệm 0 có nghĩa là bộ đệm vĩnh viễn
            'expire'     => 0,
            // tiền tố thẻ bộ nhớ cache
            'tag_prefix' => 'tag:',
            // Cơ chế tuần tự hóa, ví dụ: ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        // Nhiều kết nối được lưu trong bộ nhớ đệm hơn
        // làm lại bộ đệm
        'redis'   =>  [
            // Chế độ lái xe
            'type'          => 'redis',
            // Địa chỉ máy chủ
            'host'          => Env::get('redis.redis_hostname', '127.0.0.1'),
            // hải cảng
            'port'          => Env::get('redis.port', '6379'),
            // mật khẩu
            'password'      => Env::get('redis.redis_password', ''),
            // Thời hạn hiệu lực của bộ đệm 0 có nghĩa là bộ đệm vĩnh viễn
            'expire'        => 0 ,
            // tiền tố bộ đệm
            'prefix'     => Env::get('cache.cache_prefix', 'c:'),
            // tiền tố thẻ bộ nhớ cache
            'tag_prefix'    => Env::get('cache.cache_tag_prefix', 'CRMEB:'),
            // Cơ sở dữ liệu số 0 Cơ sở dữ liệu
            'select'        => intval(Env::get('redis.select', 0)),
            // Cơ chế tuần tự hóa, ví dụ: ['serialize', 'unserialize']
            'serialize'     => [],
            // Máy chủ chủ động tắt
            'timeout'       => 0
        ],
    ],
];
