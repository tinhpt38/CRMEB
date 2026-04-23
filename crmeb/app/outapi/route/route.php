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
use app\http\middleware\AllowOriginMiddleware;
use app\outapi\middleware\AuthTokenMiddleware;
use think\facade\Config;
use think\facade\Route;
use think\Response;

Route::group(function () {

    Route::group(function () {
        //lấytoken
        Route::post('access_token', 'Login/getToken')->name('getToken')->option(['real_name' => 'Đăng nhập để nhậntoken']);
        //làm cho khỏe lạitoken
        Route::post('refresh_token', 'Login/refreshToken')->name('refreshToken')->option(['real_name' => 'làm cho khỏe lạitoken']);
    })->option(['mark' => 'common', 'mark_name' => 'giao diện công cộng']);

    Route::group(function () {
        Route::group(function () {
            //Phân loại sản phẩm
            Route::get('category/list', 'StoreCategory/index')->option(['real_name' => 'Danh sách danh mục']);
            Route::get('category/:id', 'StoreCategory/read')->option(['real_name' => 'Nhận danh mục']);
            Route::post('category', 'StoreCategory/save')->option(['real_name' => 'Thêm danh mục mới']);
            Route::put('category/:id', 'StoreCategory/update')->option(['real_name' => 'Sửa đổi phân loại']);
            Route::delete('category/:id', 'StoreCategory/delete')->option(['real_name' => 'Xóa danh mục']);
            Route::put('category/set_show/:id/:is_show', 'StoreCategory/set_show')->option(['real_name' => 'Sửa đổi trạng thái phân loại']);
        })->option(['mark' => 'category', 'mark_name' => 'Phân loại sản phẩm']);

        Route::group(function () {
            //hàng hóa
            Route::get('product/list', 'StoreProduct/index')->option(['real_name' => 'Danh sách sản phẩm']);
            Route::post('product', 'StoreProduct/save')->option(['real_name' => 'Thêm sản phẩm mới']);
            Route::put('product/:id', 'StoreProduct/update')->option(['real_name' => 'Sửa đổi sản phẩm']);
            Route::get('product/:id', 'StoreProduct/read')->option(['real_name' => 'Nhận sản phẩm']);
            Route::put('product/set_show/:id/:is_show', 'StoreProduct/set_show')->option(['real_name' => 'Sửa đổi trạng thái sản phẩm']);
            Route::put('product/stock/upload', 'StoreProduct/uploadStock')->option(['real_name' => 'Đồng bộ hóa kho sản phẩm']);
        })->option(['mark' => 'product', 'mark_name' => 'hàng hóa']);

        Route::group(function () {
            //Đặt hàng
            Route::get('order/list', 'StoreOrder/lst')->name('StoreOrderList')->option(['real_name' => 'danh sách đặt hàng']);
            Route::get('order/:order_id', 'StoreOrder/read')->name('StoreOrderInfo')->option(['real_name' => 'Chi tiết đặt hàng']);
            Route::put('order/remark/:order_id', 'StoreOrder/remark')->name('StoreOrderRemark')->option(['real_name' => 'Sửa đổi thông tin nhận xét']);
            Route::put('order/receive/:order_id', 'StoreOrder/receive')->name('StoreOrderReceive')->option(['real_name' => 'xác nhận đã nhận hàng']);
            Route::get('order/express_list', 'StoreOrder/express')->name('StoreOrderExpress')->option(['real_name' => 'Nhận công ty hậu cần']);
            Route::put('order/delivery/:order_id', 'StoreOrder/delivery')->name('StoreOrderDelivery')->option(['real_name' => 'Đơn hàng đã được vận chuyển']);
            Route::put('order/distribution/:order_id', 'StoreOrder/updateDistribution')->name('StoreOrderDistribution')->option(['real_name' => 'Sửa đổi thông tin vận chuyển']);
            Route::get('order/split_cart_info/:order_id', 'StoreOrder/splitCartInfo')->name('StoreOrderSplitCartInfo')->option(['real_name' => 'Lấy danh sách các mặt hàng có thể chia nhỏ trong một đơn hàng']);
            Route::put('order/split_delivery/:order_id', 'StoreOrder/splitDelivery')->name('StoreOrderSplitDelivery')->option(['real_name' => 'Chia đơn hàng và gửi hàng']);
            Route::put('order/invoice/:order_id', 'StoreOrder/setInvoice')->option(['real_name' => 'Sửa hóa đơn đặt hàng']);
            Route::put('order/invoice_status/:order_id', 'StoreOrder/setInvoiceStatus')->option(['real_name' => 'Sửa đổi trạng thái hóa đơn đơn hàng']);
        })->option(['mark' => 'order', 'mark_name' => 'Đặt hàng']);

        Route::group(function () {
            //Đơn hàng sau bán hàng
            Route::get('refund/list', 'RefundOrder/lst')->option(['real_name' => 'Danh sách đơn hàng sau bán hàng']);
            Route::put('refund/remark/:order_id', 'RefundOrder/remark')->option(['real_name' => 'Ghi chú đơn hàng sau bán hàng']);
            Route::put('refund/:order_id', 'RefundOrder/refundPrice')->option(['real_name' => 'Hoàn tiền đơn hàng sau bán hàng']);
            Route::put('refund/agree/:order_id', 'RefundOrder/agree')->option(['real_name' => 'Người bán đồng ý hoàn tiền']);
            Route::put('refund/refuse/:order_id', 'RefundOrder/refuse')->option(['real_name' => 'Người bán từ chối hoàn tiền']);
            Route::get('refund/:order_id', 'RefundOrder/read')->option(['real_name' => 'Chi tiết đơn hàng sau bán hàng']);
        })->option(['mark' => 'refund', 'mark_name' => 'Hậu mãi']);

        Route::group(function () {
            //Phiếu giảm giá
            Route::get('coupon/list', 'StoreCoupon/lst')->option(['real_name' => 'Danh sách phiếu giảm giá']);
            Route::post('coupon', 'StoreCoupon/save')->option(['real_name' => 'Thêm phiếu giảm giá']);
            Route::put('coupon/status/:id/:status', 'StoreCoupon/status')->option(['real_name' => 'Sửa đổi trạng thái phiếu giảm giá']);
            Route::delete('coupon/:id', 'StoreCoupon/delete')->option(['real_name' => 'Xóa phiếu giảm giá']);
        })->option(['mark' => 'coupon', 'mark_name' => 'Phiếu giảm giá']);

        Route::group(function () {
            //Cấp độ người dùng
            Route::get('user_level/list', 'UserLevel/lst')->option(['real_name' => 'Danh sách cấp độ người dùng']);

            //người dùng
            Route::get('user/list', 'User/lst')->option(['real_name' => 'Danh sách người dùng']);
            Route::get('user/info/:uid', 'User/info')->option(['real_name' => 'Chi tiết người dùng']);
            Route::post('user', 'User/save')->option(['real_name' => 'Thêm người dùng mới']);
            Route::put('user/:uid', 'User/update')->option(['real_name' => 'Sửa đổi người dùng']);
            Route::put('user/give_balance/:uid', 'User/giveBalance')->option(['real_name' => 'Số dư quà tặng']);
            Route::put('user/give_point/:uid', 'User/givePoint')->option(['real_name' => 'Tặng điểm']);
            Route::put('user/change_balance/:uid', 'User/changeBalance')->option(['real_name' => 'Sửa đổi số dư']);
            Route::put('user/change_point/:uid', 'User/changePoint')->option(['real_name' => 'Sửa đổi điểm']);
        })->option(['mark' => 'user', 'mark_name' => 'người dùng']);

    })->middleware(AuthTokenMiddleware::class);

    // MCP Giao diện (hỗ trợ xác thực appid + appsecret, không sử dụng Token middleware）
    Route::group(function () {
        Route::post('mcp', 'Mcp/index')->option(['real_name' => 'MCPgiao diện']);
    })->option(['mark' => 'mcp', 'mark_name' => 'MCPgiao diện']);

})->middleware(AllowOriginMiddleware::class);

Route::miss(function () {
    if (app()->request->isOptions()) {
        $header = Config::get('cookie.header');
        $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
