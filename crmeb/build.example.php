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

/**
 * php think build Ví dụ về tự động tạo cấu trúc thư mục của ứng dụng
 */
return [
    // Các tập tin cần được tạo tự động
    '__file__'   => [],
    // Các thư mục cần được tạo tự động
    '__dir__'    => ['controller', 'model', 'view'],
    // Bộ điều khiển cần được tạo tự động
    'controller' => ['Index'],
    // Các mô hình cần được tạo tự động
    'model'      => ['User'],
    // Các mẫu cần được tạo tự động
    'view'       => ['index/index'],
];
