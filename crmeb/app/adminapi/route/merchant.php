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
 * Các tuyến đường liên quan đến mẫu vận chuyển sản phẩm
 */Route::group('merchant', function () {

    /** cửa hàng */    Route::group(function () {
        //Chi tiết thiết lập cửa hàng
        Route::get('store', 'v1.merchant.SystemStore/index')->option(['real_name' => 'Danh sách cửa hàng']);
        //Số lượng danh sách cửa hàng
        Route::get('store/get_header', 'v1.merchant.SystemStore/get_header')->option(['real_name' => 'Lưu trữ dữ liệu tiêu đề danh sách']);
        //Số lượng danh sách cửa hàng
        Route::put('store/set_show/:id/:is_show', 'v1.merchant.SystemStore/set_show')->option(['real_name' => 'kệ cửa hàng']);
        //Số lượng danh sách cửa hàng
        Route::delete('store/del/:id', 'v1.merchant.SystemStore/delete')->option(['real_name' => 'xóa cửa hàng']);
        //Lựa chọn vị trí
        Route::get('store/address', 'v1.merchant.SystemStore/select_address')->option(['real_name' => 'Lựa chọn vị trí cửa hàng']);
        //Chi tiết thiết lập cửa hàng
        Route::get('store/get_info/:id', 'v1.merchant.SystemStore/get_info')->option(['real_name' => 'Chi tiết cửa hàng']);
        //Lưu và sửa đổi thông tin cửa hàng
        Route::post('store/:id', 'v1.merchant.SystemStore/save')->option(['real_name' => 'Lưu và sửa đổi thông tin cửa hàng']);
    })->option(['parent' => 'merchant', 'cate_name' => 'cửa hàng']);

    /** nhân viên văn phòng */    Route::group(function () {
        //Lấy danh sách nhân viên
        Route::get('store_staff', 'v1.merchant.SystemStoreStaff/index')->option(['real_name' => 'Lấy danh sách nhân viên cửa hàng']);
        //Thêm biểu mẫu nhân viên cửa hàng
        Route::get('store_staff/create', 'v1.merchant.SystemStoreStaff/create')->option(['real_name' => 'Thêm biểu mẫu nhân viên cửa hàng']);
        //Danh sách tìm kiếm cửa hàng
        Route::get('store_list', 'v1.merchant.SystemStoreStaff/store_list')->option(['real_name' => 'Danh sách tìm kiếm cửa hàng']);
        //Sửa đổi trạng thái nhân viên cửa hàng
        Route::put('store_staff/set_show/:id/:is_show', 'v1.merchant.SystemStoreStaff/set_show')->option(['real_name' => 'Sửa đổi trạng thái nhân viên cửa hàng']);
        //Sửa đổi mẫu thư ký
        Route::get('store_staff/:id/edit', 'v1.merchant.SystemStoreStaff/edit')->option(['real_name' => 'Sửa đổi mẫu thư ký']);
        //Lưu thư ký
        Route::post('store_staff/save/:id', 'v1.merchant.SystemStoreStaff/save')->option(['real_name' => 'Lưu thư ký']);
        //Xóa thư ký
        Route::delete('store_staff/del/:id', 'v1.merchant.SystemStoreStaff/delete')->option(['real_name' => 'Xóa thư ký']);
    })->option(['parent' => 'merchant', 'cate_name' => 'nhân viên văn phòng']);

    /** Xác nhận đơn hàng */    Route::group(function () {
        //Nhận danh sách các lệnh xóa nợ
        Route::get('verify_order', 'v1.merchant.SystemVerifyOrder/list')->option(['real_name' => 'Nhận danh sách các lệnh xóa nợ']);
        //Nhận người đứng đầu lệnh xóa nợ
        Route::get('verify_badge', 'v1.merchant.SystemVerifyOrder/getVerifyBadge')->option(['real_name' => 'Nhận người đứng đầu lệnh xóa nợ']);
        //Nhận người đứng đầu lệnh xóa nợ
        Route::get('verify/spread_info/:uid', 'v1.merchant.SystemVerifyOrder/order_spread_user')->option(['real_name' => 'Thông tin đề xuất lệnh xác nhận']);
    })->option(['parent' => 'merchant', 'cate_name' => 'Xác nhận đơn hàng']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'merchant', 'mark_name' => 'Xác nhận cửa hàng']);
