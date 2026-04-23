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
 * Quản lý thông báo tin nhắn, tin nhắn mẫu (danh sách, thông báo, thêm, chỉnh sửa), định tuyến liên quan đến SMS
 */
Route::group('notify', function () {
    //Lưu cấu hình Đăng nhập
    Route::post('sms/config', 'v1.notification.sms.SmsConfig/save_basics')->option(['real_name' => 'Lưu cấu hình SMS']);
    //Bản ghi gửi SMS
    Route::get('sms/record', 'v1.notification.sms.SmsConfig/record')->option(['real_name' => 'Bản ghi gửi SMS']);
    //Dữ liệu tài khoản SMS
    Route::get('sms/data', 'v1.notification.sms.SmsConfig/data')->option(['real_name' => 'Dữ liệu tài khoản SMS']);
    //Kiểm tra xem bạn đã đăng nhập chưa
    Route::get('sms/is_login', 'v1.notification.sms.SmsConfig/is_login')->option(['real_name' => 'Kiểm tra xem tài khoản SMS đã được đăng nhập chưa']);
    //Kiểm tra xem bạn đã đăng nhập chưa
    Route::get('sms/logout', 'v1.notification.sms.SmsConfig/logout')->option(['real_name' => 'Đăng xuất khỏi tài khoản SMS']);
    //Gửi mã xác minh qua SMS
    Route::post('sms/captcha', 'v1.notification.sms.SmsAdmin/captcha')->option(['real_name' => 'Gửi mã xác minh qua SMS']);
    //Sửa đổi/đăng ký tài khoản nền tảng SMS
    Route::post('sms/register', 'v1.notification.sms.SmsAdmin/save')->option(['real_name' => 'Sửa đổi hoặc đăng ký tài khoản nền tảng SMS']);
    //Danh sách mẫu SMS
    Route::get('sms/temp', 'v1.notification.sms.SmsTemplateApply/index')->option(['real_name' => 'Danh sách mẫu SMS']);
    //Mẫu đơn xin việc mẫu SMS
    Route::get('sms/temp/create', 'v1.notification.sms.SmsTemplateApply/create')->option(['real_name' => 'Mẫu đơn xin việc mẫu SMS']);
    //Ứng dụng mẫu SMS
    Route::post('sms/temp', 'v1.notification.sms.SmsTemplateApply/save')->option(['real_name' => 'Ứng dụng mẫu SMS']);
    //Danh sách các mẫu SMS công khai
    Route::get('sms/public_temp', 'v1.notification.sms.SmsPublicTemp/index')->option(['real_name' => 'Danh sách các mẫu SMS công khai']);
    //Số lượng mặt hàng còn lại
    Route::get('sms/number', 'v1.notification.sms.SmsPay/number')->option(['real_name' => 'Số tin nhắn văn bản còn lại']);
    //Nhận kế hoạch thanh toán
    Route::get('sms/price', 'v1.notification.sms.SmsPay/price')->option(['real_name' => 'Nhận gói mua SMS']);
    //Nhận mã thanh toán
    Route::post('sms/pay_code', 'v1.notification.sms.SmsPay/pay')->option(['real_name' => 'Nhận mã thanh toán mua hàng SMS']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'notify', 'mark_name' => 'Thông báo tin nhắn']);
