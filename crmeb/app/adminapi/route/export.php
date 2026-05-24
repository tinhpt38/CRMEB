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
 * Xuất các tuyến đường liên quan đến excel
 */Route::group('export', function () {
    //Danh sách Khách hàng
    Route::get('user_list', 'v1.export.ExportExcel/userList')->option(['real_name' => 'Xuất danh sách Khách hàng']);
    //danh sách đặt hàng
    Route::get('order_list', 'v1.export.ExportExcel/orderList')->option(['real_name' => 'Xuất danh sách đơn hàng']);
    //Danh sách đơn hàng vận chuyển
    Route::get('order_delivery_list', 'v1.export.ExportExcel/orderDeliveryList')->option(['real_name' => 'Xuất danh sách đơn hàng vận chuyển']);
    //Danh sách sản phẩm
    Route::get('product_list', 'v1.export.ExportExcel/productList')->option(['real_name' => 'Xuất danh sách sản phẩm']);
    //Lịch sử trả giá
    Route::get('bargain_list', 'v1.export.ExportExcel/bargainList')->option(['real_name' => 'Danh mục sản phẩm xuất khẩu giá hời']);
    //Đơn hàng mua chung
    Route::get('combination_list', 'v1.export.ExportExcel/combinationList')->option(['real_name' => 'Xuất danh sách sản phẩm nhóm']);
    //Sản phẩm Flash Sale
    Route::get('seckill_list', 'v1.export.ExportExcel/seckillList')->option(['real_name' => 'Xuất danh sách sản phẩm flash sale']);
    //Xuất thẻ thành viên
    Route::get('member_card/:id', 'v1.export.ExportExcel/memberCardList')->option(['real_name' => 'Xuất thẻ thành viên']);
    //Danh sách khuyến mãi Khách hàng phân phối
    Route::get('userAgent', 'v1.export.ExportExcel/userAgent')->option(['real_name' => 'Danh sách khuyến mãi nhà phân phối xuất khẩu']);
    //Giám sát quỹ Khách hàng
    Route::get('userFinance', 'v1.export.ExportExcel/userFinance')->option(['real_name' => 'Xuất tiền của Khách hàng']);
    //Hoa hồng Khách hàng
    Route::get('userCommission', 'v1.export.ExportExcel/userCommission')->option(['real_name' => 'Xuất hoa hồng Khách hàng']);
    //Điểm Khách hàng
    Route::get('userPoint', 'v1.export.ExportExcel/userPoint')->option(['real_name' => 'Xuất điểm Khách hàng']);
    //Nạp tiền vào ví
    Route::get('userRecharge', 'v1.export.ExportExcel/userRecharge')->option(['real_name' => 'Xuất nạp tiền Khách hàng']);
    //Xác nhận đơn hàng
    Route::get('verify_order', 'v1.export.ExportExcel/verifyOrder')->option(['real_name' => 'Xác nhận đơn hàng']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'export', 'mark_name' => 'Xuất dữ liệu']);
