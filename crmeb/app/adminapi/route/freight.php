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
use think\facade\Route;

/**
 * Định tuyến liên quan đến quản lý người bán
 */
Route::group('freight', function () {
    //Định tuyến tài nguyên công ty hậu cần
    Route::resource('express', 'v1.freight.Express')->except(['read'])->name('ExpressResource')->option([
        'real_name' => [
            'index' => 'Nhận danh sách các công ty logistics',
            'create' => 'Nhận mẫu đơn công ty logistic',
            'save' => 'Công ty Logistics Lưu',
            'edit' => 'Nhận mẫu công ty hậu cần sửa đổi',
            'update' => 'Sửa đổi công ty hậu cần',
            'delete' => 'Xóa công ty hậu cần'
        ],
    ]);
    //Sửa đổi trạng thái
    Route::put('express/set_status/:id/:status', 'v1.freight.Express/set_status')->option(['real_name' => 'Sửa đổi trạng thái công ty hậu cần']);
    //Công ty Chuyển phát nhanh Logistics Đồng bộ
    Route::get('express/sync_express', 'v1.freight.Express/syncExpress')->option(['real_name' => 'Công ty Logistics đồng bộ']);
    //Mẫu chỉnh sửa cấu hình hậu cần
    Route::get('config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => 'Mẫu chỉnh sửa cấu hình hậu cần']);
    //Cấu hình hậu cần lưu dữ liệu
    Route::post('config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => 'Cấu hình hậu cần lưu dữ liệu']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'freight', 'mark_name' => 'Quản lý hậu cần']);
