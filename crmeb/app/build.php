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
/**
 * Tạo cấu hình mô-đun
 * php think build model_name
 */return [
    // Các tập tin cần được tạo tự động
    '__file__'   => ['.htaccess','ExecptionHandle.php'],
    // Các thư mục cần được tạo tự động
    '__dir__'    => ['controller/v1','config','lang','validates/login/','route'],
    // Cần lớp điều khiển tự động
    'controller' => ['Index'],
    // Yêu cầu xác thực biểu mẫu được tạo tự động
    'validates' => ['Index'],
    // Các tuyến đường cần được tạo tự động
    'route' => ['route'],
    // Cần tự động tạo file cấu hình
    'config'      => ['route'],
    // Các tập tin cấu hình đa ngôn ngữ cần được tạo tự động
    'lang'      => ['zh-CN','en-US'],
    // Các mẫu cần được tạo tự động
//    'view'       => ['index/index'],
];
