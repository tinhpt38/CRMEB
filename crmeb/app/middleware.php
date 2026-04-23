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
    // Bộ đệm yêu cầu toàn cầu
    // \think\middleware\CheckRequestCache::class,
    // Tải đa ngôn ngữ
    // \think\middleware\LoadLangPack::class,
    // Sessionkhởi tạo
    \think\middleware\SessionInit::class,
    //Khởi tạo đa ngôn ngữ
    \think\middleware\LoadLangPack::class,
    // Gỡ lỗi theo dõi trang
    // \think\middleware\TraceDebug::class,
    //Khởi tạo phần mềm trung gian cơ bản
    \app\http\middleware\BaseMiddleware::class,
    // Hỗ trợ đa ngôn ngữ
    \think\middleware\LoadLangPack::class,
];
