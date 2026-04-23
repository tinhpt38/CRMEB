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
// | Cookiecài đặt
// +----------------------------------------------------------------------
return [
    // cookie tiết kiệm thời gian
    'expire'    => 0,
    // cookie lưu đường dẫn
    'path'      => '/',
    // cookie Tên miền hợp lệ
    'domain'    => '',
    // cookie Cho phép chuyển giao an toàn
    'secure'    => false,
    // httponlycài đặt
    'httponly'  => false,
    // Có nên sử dụng không setcookie
    'setcookie' => true,
    // Tên miền chéoheader
    'header'    => [
        'Access-Control-Allow-Origin'       => '*',
        'Access-Control-Allow-Headers'      => 'Authori-zation,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, Form-type, Cb-lang, Invalid-zation',
        'Access-Control-Allow-Methods'      => 'GET,POST,PATCH,PUT,DELETE,OPTIONS,DELETE',
        'Access-Control-Max-Age'            =>  '1728000',
        'Access-Control-Allow-Credentials'  => 'true'
    ],
    // tokentên
    'token_name' => 'Authori-zation',
];
