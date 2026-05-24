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
 * Các tuyến liên quan đến tải xuống và xuất tệp
 */Route::group(function () {
    //Tải xuống bảng ghi bản sao lưu
    Route::get('backup/download', 'v1.system.SystemDatabackup/downloadFile')->option(['real_name' => 'Tải xuống bản ghi sao lưu bảng']);
    //Thống kê trang chủ
    Route::get('home/header', 'Common/homeStatics')->option(['real_name' => 'Thống kê trang chủ']);
    //Biểu đồ đặt hàng tại nhà
    Route::get('home/order', 'Common/orderChart')->option(['real_name' => 'Biểu đồ đặt hàng tại nhà']);
    //Biểu đồ Khách hàng gia đình
    Route::get('home/user', 'Common/userChart')->option(['real_name' => 'Biểu đồ Khách hàng gia đình']);
    //Xếp hạng khối lượng giao dịch trang chủ
    Route::get('home/rank', 'Common/purchaseRanking')->option(['real_name' => 'Xếp hạng khối lượng giao dịch trang chủ']);
    //Lời nhắc tin nhắn
    Route::get('jnotice', 'Common/jnotice')->option(['real_name' => 'Lời nhắc tin nhắn']);
    //Xác minh ủy quyền
    Route::get('check_auth', 'Common/auth')->option(['real_name' => 'Xác minh ủy quyền']);
    //Nộp đơn xin ủy quyền
    Route::post('auth_apply', 'Common/auth_apply')->option(['real_name' => 'Nộp đơn xin ủy quyền']);
    //Ủy quyền
    Route::get('auth', 'Common/auth')->option(['real_name' => 'Thông tin ủy quyền']);
    //Nhận menu bên trái
    Route::get('menus', 'v1.setting.SystemMenus/menus')->option(['real_name' => 'menu bên trái']);
    //Nhận danh sách menu tìm kiếm
    Route::get('menusList', 'Common/menusList')->option(['real_name' => 'Danh sách thực đơn tìm kiếm']);
    //lấylogo
    Route::get('logo', 'Common/getLogo')->option(['real_name' => 'lấylogo']);
    //Kiểm tra bản quyền
    Route::get('copyright', 'Common/copyright')->option(['real_name' => 'Xin bản quyền']);
    //lưu bản quyền
    Route::post('copyright', 'Common/saveCopyright')->option(['real_name' => 'lưu bản quyền']);
    //Tìm kiếm menu phụ trợ
    Route::post('menusSearch', 'Common/menusSearch')->option(['real_name' => 'Tìm kiếm menu phụ trợ']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'common', 'mark_name' => 'Dữ liệu hệ thống']);

