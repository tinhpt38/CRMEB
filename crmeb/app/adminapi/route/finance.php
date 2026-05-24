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
 * Định tuyến liên quan đến mô-đun Tài chính
 */Route::group('finance', function () {

    /** Rút tiền mặt */    Route::group(function () {
        //Danh sách Ứng dụng
        Route::get('extract', 'v1.finance.UserExtract/index')->option(['real_name' => 'Danh sách đơn xin rút tiền']);
        //chỉnh sửa biểu mẫu
        Route::get('extract/:id/edit', 'v1.finance.UserExtract/edit')->option(['real_name' => 'Mẫu sửa đổi hồ sơ rút tiền']);
        //Lưu thay đổi
        Route::put('extract/:id', 'v1.finance.UserExtract/update')->option(['real_name' => 'Sửa đổi hồ sơ rút tiền']);
        //từ chối đơn đăng ký
        Route::put('extract/refuse/:id', 'v1.finance.UserExtract/refuse')->option(['real_name' => 'Từ chối yêu cầu rút tiền']);
        //bằng cách áp dụng
        Route::put('extract/adopt/:id', 'v1.finance.UserExtract/adopt')->option(['real_name' => 'Áp dụng thông qua rút tiền']);
    })->option(['parent' => 'finance', 'cate_name' => 'Rút tiền mặt']);

    /** Hồ sơ tài trợ */    Route::group(function () {
        //Loại bộ lọc
        Route::get('finance/bill_type', 'v1.finance.Finance/bill_type')->option(['real_name' => 'Loại hồ sơ quỹ']);
        //Hồ sơ tài trợ
        Route::get('finance/list', 'v1.finance.Finance/list')->option(['real_name' => 'Danh sách ghi quỹ']);
        //Lịch sử hoa hồng
        Route::get('finance/commission_list', 'v1.finance.Finance/get_commission_list')->option(['real_name' => 'Danh sách hồ sơ hoa hồng']);
        //Hoa hồng chi tiết thông tin Khách hàng
        Route::get('finance/user_info/:id', 'v1.finance.Finance/user_info')->option(['real_name' => 'Hoa hồng chi tiết thông tin Khách hàng']);
        //Danh sách cá nhân hồ sơ rút tiền hoa hồng
        Route::get('finance/extract_list/:id', 'v1.finance.Finance/get_extract_list')->option(['real_name' => 'Danh sách cá nhân hồ sơ rút tiền hoa hồng']);
        /** Biến động số dư */        Route::get('balance/list', 'v1.finance.UserBalance/balanceList')->option(['real_name' => 'Danh sách ghi số dư']);
        Route::post('balance/set_mark/:id', 'v1.finance.UserBalance/balanceRecordRemark')->option(['real_name' => 'Ghi chú về số dư']);
    })->option(['parent' => 'finance', 'cate_name' => 'Hồ sơ tài trợ']);

    /** nạp tiền */    Route::group(function () {
        //Danh sách hồ sơ nạp tiền
        Route::get('recharge', 'v1.finance.UserRecharge/index')->option(['real_name' => 'Danh sách hồ sơ nạp tiền']);
        //xóa bản ghi
        Route::delete('recharge/:id', 'v1.finance.UserRecharge/delete')->option(['real_name' => 'Xóa hồ sơ nạp tiền']);
        //Nhận dữ liệu nạp tiền của Khách hàng
        Route::get('recharge/user_recharge', 'v1.finance.UserRecharge/user_recharge')->option(['real_name' => 'Nhận dữ liệu nạp tiền của Khách hàng']);
        //Hình thức hoàn tiền
        Route::get('recharge/:id/refund_edit', 'v1.finance.UserRecharge/refund_edit')->option(['real_name' => 'Hình thức nạp tiền và hoàn tiền']);
        //Đền bù
        Route::put('recharge/:id', 'v1.finance.UserRecharge/refund_update')->option(['real_name' => 'Nạp tiền và hoàn tiền']);
    })->option(['parent' => 'finance', 'cate_name' => 'nạp tiền']);


})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'finance', 'mark_name' => 'quản lý Tài chính']);
