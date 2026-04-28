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
 * Định tuyến nền tảng dịch vụ
 */
Route::group('serve', function () {
    //Đăng nhập nền tảng
    Route::post('login', 'v1.serve.Login/login')->option(['real_name' => 'Đăng nhập nền tảng một số']);
    //Mã xác minh
    Route::post('captcha', 'v1.serve.Login/captcha')->option(['real_name' => 'Nhận mã xác minh thông qua One Number Pass']);
    //Xác minh mã xác minh
    Route::post('checkCode', 'v1.serve.Login/checkCode')->option(['real_name' => 'Mã xác minh vượt qua một số']);
    //đăng ký
    Route::post('register', 'v1.serve.Login/register')->option(['real_name' => 'Đăng ký một số Pass']);
    //Mở biểu mẫu điện tử
    Route::post('opn_express', 'v1.serve.Serve/openExpress')->option(['real_name' => 'Số 1 mở mẫu điện tử']);
    //Lấy thông tin người dùng
    Route::get('info', 'v1.serve.Serve/getUserInfo')->option(['real_name' => 'Thông tin tài khoản một số']);
    //Lấy mẫu danh sách
    Route::get('meal_list', 'v1.serve.Serve/mealList')->option(['real_name' => 'Danh sách gói thanh toán một số']);
    //Được trả tiền
    Route::post('pay_meal', 'v1.serve.Serve/payMeal')->option(['real_name' => 'Mã QR thanh toán một số']);
    //Kích hoạt dịch vụ SMS
    Route::get('sms/open', 'v1.serve.Sms/openServe')->option(['real_name' => 'Mở dịch vụ SMS qua One Number Tong']);
    //Kích hoạt các dịch vụ khác
    Route::get('open', 'v1.serve.Serve/openServe')->option(['real_name' => 'One-Hawtong mở thêm dịch vụ khác']);
    //Sửa đổi chữ ký
    Route::put('sms/sign', 'v1.serve.Sms/editSign')->option(['real_name' => 'Một số sửa đổi chữ ký']);
    //Nhận mẫu SMS
    Route::get('sms/temps', 'v1.serve.Sms/temps')->option(['real_name' => 'Nhận mẫu SMS từ One Number Pass']);
    //Mẫu đơn đăng ký
    Route::post('sms/apply', 'v1.serve.Sms/apply')->option(['real_name' => 'Mẫu đơn xin cấp thẻ một số']);
    //Nhận hồ sơ ứng dụng
    Route::get('sms/apply_record', 'v1.serve.Sms/applyRecord')->option(['real_name' => 'Nhận hồ sơ ứng dụng thông qua One Number']);
    //Ghi
    Route::get('record', 'v1.serve.Serve/getRecord')->option(['real_name' => 'Lịch sử mua hàng một số']);
    //Có bật tính năng in biểu mẫu điện tử hay không
    Route::get('dump_open', 'v1.serve.Export/dumpIsOpen')->name('dumpIsOpen')->option(['real_name' => 'Số 1 có cho phép in biểu mẫu điện tử không?']);
    //Nhận tất cả các công ty hậu cần
    Route::get('export_all', 'v1.serve.Export/getExportAll')->option(['real_name' => 'Nhận Tất cả các công ty hậu cần với One Number']);
    //Nhận mẫu công ty hậu cần
    Route::get('export_temp', 'v1.serve.Export/getExportTemp')->option(['real_name' => 'Nhận mẫu công ty hậu cần thông qua One Number']);
    //Thay đổi mật khẩu
    Route::post('modify', 'v1.serve.Serve/modify')->option(['real_name' => 'Mật khẩu một số Đổi mật khẩu']);
    //Sửa đổi số điện thoại di động
    Route::post('update_phone', 'v1.serve.Serve/updatePhone')->option(['real_name' => 'Đổi số điện thoại di động bằng One Number Pass']);
    //Mẫu chỉnh sửa cấu hình SMS
    Route::get('sms_config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => 'Mẫu chỉnh sửa cấu hình SMS một số']);
    //Cấu hình SMS lưu dữ liệu
    Route::post('sms_config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => 'Cấu hình SMS một số lưu dữ liệu']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'serve', 'mark_name' => 'Thẻ một số']);
