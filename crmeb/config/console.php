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
// | Cấu hình bảng điều khiển
// +----------------------------------------------------------------------
return [
    // Người dùng thực thi (không hợp lệ trong Windows)）
    'user' => null,
    // Định nghĩa lệnh
    'commands' => [
        'workerman' => \crmeb\command\Workerman::class,
        'timer' => \crmeb\command\Timer::class,
        'util' => \crmeb\command\Util::class,
        'npm' => \crmeb\command\Npm::class,
        'localize:vn-menus' => \crmeb\command\LocalizeVnMenus::class,
    ],
];
