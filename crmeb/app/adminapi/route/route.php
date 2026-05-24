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
use think\facade\Config;
use think\Response;
use app\http\middleware\AllowOriginMiddleware;

/**
 * Giao diện không có giấy phép
 */Route::group(function () {
    //Thủ tục nâng cấp
    Route::get('upgrade', 'UpgradeController/index');
    Route::get('upgrade/run', 'UpgradeController/upgrade');
    //Đăng nhập bằng tên Khách hàng và mật khẩu
    Route::post('login', 'Login/login')->name('AdminLogin')->option(['real_name' => 'Tải xuống bản ghi sao lưu bảng']);
    //Dữ liệu trang đăng nhập phụ trợ
    Route::get('login/info', 'Login/info')->option(['real_name' => 'Thông tin đăng nhập']);
    //Mã xác minh
    Route::get('captcha_pro', 'Login/captcha')->name('')->option(['real_name' => 'Nhận mã xác minh']);
    //Nhận mã xác minh
    Route::get('ajcaptcha', 'Login/ajcaptcha')->name('ajcaptcha')->option(['real_name' => 'Nhận mã xác minh']);
    //Một lần xác minh
    Route::post('ajcheck', 'Login/ajcheck')->name('ajcheck')->option(['real_name' => 'Một lần xác minh']);
    //Nhận dữ liệu CSKH
    Route::get('get_workerman_url', 'PublicController/getWorkerManUrl')->option(['real_name' => 'Nhận dữ liệu CSKH']);
    //Bài kiểm tra
    Route::get('index', 'Test/index')->option(['real_name' => 'Địa chỉ kiểm tra']);
    //Quét mã QR để tải ảnh lên
    Route::post('image/scan_upload', 'PublicController/scanUpload')->option(['real_name' => 'Quét mã QR để tải ảnh lên']);
    Route::get('custom_admin_js', 'PublicController/customAdminJs')->option(['real_name' => 'Địa chỉ kiểm tra']);

})->middleware(AllowOriginMiddleware::class)->option(['mark' => 'login', 'mark_name' => 'Đăng nhập liên quan']);


/**
 * Giao diện yêu cầu ủy quyền
 */Route::group(function () {
    //Thông tin máy chủ
    Route::get('system/info', 'PublicController/getSystemInfo')->option(['real_name' => 'Thông tin máy chủ']);
    //Nhập tuyến đường
    Route::get('route/import_api', 'PublicController/import')->option(['real_name' => 'Nhập tuyến đường']);
    //Tải tập tin xuống
    Route::get('download/[:key]', 'PublicController/download')->option(['real_name' => 'Tải tập tin xuống']);
})->middleware([
    AllowOriginMiddleware::class,
   \app\adminapi\middleware\AdminAuthTokenMiddleware::class
])->option(['mark' => 'system', 'mark_name' => 'Liên quan đến hệ thống']);

/**
 * miss lộ trình
 */Route::miss(function () {
    if (app()->request->isOptions()) {
        $header = Config::get('cookie.header');
        $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
