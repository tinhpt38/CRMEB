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
use app\api\middleware\BlockerMiddleware;
use think\facade\Route;

/**
 * v1.1 định tuyến phiên bản
 */Route::group('v2', function () {
    //Không cần giao diện ủy quyền
    Route::group(function () {
        Route::group(function () {
            //Trang đăng nhập chương trình nhỏ tự động tải, trả về khóa bộ đệm của thông tin Khách hàng và trả về xem có buộc ràng buộc số điện thoại di động hay không.
            Route::get('routine/auth_type', 'v2.wechat.AuthController/authType')->option(['real_name' => 'Loại đăng nhập trang chương trình nhỏ']);
            //Chương trình nhỏ đăng nhập được ủy quyền, quay lạitoken
            Route::get('routine/auth_login', 'v2.wechat.AuthController/authLogin')->option(['real_name' => 'Đăng nhập được ủy quyền chương trình nhỏ']);
            //Chương trình nhỏ cho phép ràng buộc số điện thoại di động
            Route::post('routine/auth_binding_phone', 'v2.wechat.AuthController/authBindingPhone')->option(['real_name' => 'Chương trình nhỏ cho phép ràng buộc số điện thoại di động']);
            //Đăng nhập trực tiếp bằng số điện thoại di động của chương trình mini
            Route::post('routine/phone_login', 'v2.wechat.AuthController/phoneLogin')->option(['real_name' => 'Đăng nhập trực tiếp bằng số điện thoại di động của bạn']);
            //Liên kết số điện thoại di động sau khi được ủy quyền của chương trình mini
            Route::post('routine/binding_phone', 'v2.wechat.AuthController/BindingPhone')->option(['real_name' => 'Liên kết số điện thoại di động sau khi được ủy quyền của chương trình mini']);

            //Tài khoản chính thức được ủy quyền đăng nhập, quay lạitoken
            Route::get('wechat/auth_login', 'v2.wechat.WechatController/authLogin')->option(['real_name' => 'Tài khoản chính thức đăng nhập được ủy quyền']);
            //Tài khoản chính thức được ủy quyền để ràng buộc số điện thoại di động
            Route::post('wechat/auth_binding_phone', 'v2.wechat.WechatController/authBindingPhone')->option(['real_name' => 'Chương trình nhỏ cho phép ràng buộc số điện thoại di động']);

        })->option(['mark' => 'wechat_auto', 'mark_name' => 'Ủy quyền WeChat']);

        Route::group(function () {
            Route::get('diy/get_store_status', 'v2.PublicController/getStoreStatus')->option(['real_name' => 'Nhận trạng thái mở cửa hàng lấy hàng']);
            Route::get('diy/color_change/:name', 'v2.PublicController/colorChange')->option(['real_name' => 'Thay đổi màu bằng một cú nhấp chuột']);
            Route::get('diy/get_diy/[:name]', 'v2.PublicController/getDiy')->option(['real_name' => 'Nhận dữ liệu DIY']);
            Route::get('diy/get_version/[:name]', 'v2.PublicController/getVersion')->option(['real_name' => 'Nhận số phiên bản DIY']);
        })->option(['mark' => 'diy', 'mark_name' => 'DIY']);
    });
    //Cần có sự cho phép
    Route::group(function () {

        Route::post('reset_cart', 'v2.store.StoreCartController/resetCart')->name('resetCart')->option(['real_name' => 'Xóa giỏ hàng', 'mark' => 'cart', 'mark_name' => 'giỏ hàng']);
        Route::get('new_coupon', 'v2.store.StoreCouponsController/getNewCoupon')->name('getNewCoupon')->option(['real_name' => 'Nhận vé người mới', 'mark' => 'coupons', 'mark_name' => 'Mã giảm giá']);//Nhận vé người mới
        Route::post('order/product_coupon/:orderId', 'v2.store.StoreCouponsController/getOrderProductCoupon')->option(['real_name' => 'Nhận phiếu giảm giá được quản lý theo đơn đặt hàng', 'mark' => 'coupons', 'mark_name' => 'Mã giảm giá']);
        Route::get('user/service/record', 'v2.user.StoreService/record')->name('userServiceRecord')->option(['real_name' => 'Lịch sử trò chuyện CSKH', 'parent' => 'user', 'cate_name' => 'CSKH']);//Lịch sử trò chuyện CSKH
        Route::get('cart_list', 'v2.store.StoreCartController/getCartList')->option(['real_name' => 'Nhận danh sách giỏ hàng', 'mark' => 'cart', 'mark_name' => 'giỏ hàng']);
        Route::get('get_attr/:id/:type', 'v2.store.StoreProductController/getProductAttr')->option(['real_name' => 'Nhận thông số kỹ thuật sản phẩm', 'mark' => 'cart', 'mark_name' => 'giỏ hàng']);
        Route::post('set_cart_num', 'v2.store.StoreCartController/setCartNum')->option(['real_name' => 'Lấy số lượng giỏ hàng', 'mark' => 'cart', 'mark_name' => 'giỏ hàng']);

        Route::group(function () {
            // Đơn hàng — hóa đơn ứng dụng
            Route::post('order/make_up_invoice', 'v2.order.StoreOrderInvoiceController/makeUp')->name('orderMakeUpInvoice')->option(['real_name' => 'Đặt sản phẩm đơn Ứng dụng']);
            //Danh sách hóa đơn Khách hàng
            Route::get('invoice', 'v2.user.UserInvoiceController/invoiceList')->name('userInvoiceLIst')->option(['real_name' => 'Danh sách hóa đơn Khách hàng']);
            //Chi tiết hóa đơn riêng lẻ
            Route::get('invoice/detail/:id', 'v2.user.UserInvoiceController/invoice')->name('userInvoiceDetail')->option(['real_name' => 'Chi tiết hóa đơn riêng lẻ']);
            //Sửa|Thêm hóa đơn
            Route::post('invoice/save', 'v2.user.UserInvoiceController/saveInvoice')->name('userInvoiceSave')->option(['real_name' => 'Sửa|Thêm hóa đơn']);
            //Đặt hóa đơn mặc định
            Route::post('invoice/set_default/:id', 'v2.user.UserInvoiceController/setDefaultInvoice')->name('userInvoiceSetDefault')->option(['real_name' => 'Đặt hóa đơn mặc định']);
            //Nhận hóa đơn mặc định
            Route::get('invoice/get_default/:type', 'v2.user.UserInvoiceController/getDefaultInvoice')->name('userInvoiceGetDefault')->option(['real_name' => 'Nhận hóa đơn mặc định']);
            //Xóa hóa đơn
            Route::get('invoice/del/:id', 'v2.user.UserInvoiceController/delInvoice')->name('userInvoiceDel')->option(['real_name' => 'Xóa hóa đơn']);
            //Hồ sơ lập hóa đơn Ứng dụng đặt hàng
            Route::get('order/invoice_list', 'v2.order.StoreOrderInvoiceController/list')->name('orderInvoiceList')->option(['real_name' => 'Hồ sơ lập hóa đơn Ứng dụng đặt hàng']);
            //Chi tiết thanh toán đơn hàng
            Route::get('order/invoice_detail/:uni', 'v2.order.StoreOrderInvoiceController/detail')->name('orderInvoiceList')->option(['real_name' => 'Chi tiết thanh toán đơn hàng']);
            //Tải hóa đơn điện tử
            Route::get('order/down_invoice/:id', 'v2.order.StoreOrderInvoiceController/downInvoice')->name('downInvoice')->option(['real_name' => 'Tải hóa đơn điện tử']);
        })->option(['mark' => 'invoice', 'mark_name' => 'hóa đơn']);

        //Xóa lịch sử tìm kiếm
        Route::get('user/clean_search', 'v2.user.UserSearchController/cleanUserSearch')->name('cleanUserSearch')->option(['real_name' => 'Xóa lịch sử tìm kiếm']);

        //Chi tiết rút thăm may mắn
        Route::get('lottery/info/:factor/[:lottery_id]', 'v2.activity.LuckLotteryController/lotteryInfo')->name('lotteryInfo')->option(['real_name' => 'Chi tiết rút thăm may mắn']);
        //Tham gia xổ số
        Route::post('lottery', 'v2.activity.LuckLotteryController/luckLottery')->name('luckLottery')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Tham gia xổ số']);
        //Nhận giải thưởng
        Route::post('lottery/receive', 'v2.activity.LuckLotteryController/lotteryReceive')->name('lotteryReceive')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Nhận giải thưởng']);
        //Kỷ lục xổ số
        Route::get('lottery/record', 'v2.activity.LuckLotteryController/lotteryRecord')->name('lotteryRecord')->option(['real_name' => 'Kỷ lục xổ số']);

        //Nhận danh sách các cấp độ phân phối
        Route::get('agent/level_list', 'v2.agent.AgentLevel/levelList')->name('agentLevelList')->option(['real_name' => 'Nhận danh sách các cấp độ phân phối']);
        //Nhận danh sách nhiệm vụ cấp phân phối
        Route::get('agent/level_task_list', 'v2.agent.AgentLevel/levelTaskList')->name('agentLevelTaskList')->option(['real_name' => 'Nhận danh sách nhiệm vụ cấp phân phối']);

    })->middleware(\app\api\middleware\AuthTokenMiddleware::class, true);

    //Ủy quyền không thành công,Tiếp tục thực hiện mà không ném ngoại lệ
    Route::group(function () {
        Route::get('user/search_list', 'v2.user.UserSearchController/getUserSeachList')->name('userSearchList')->option(['real_name' => 'Lịch sử tìm kiếm của Khách hàng']);
        Route::get('get_today_coupon', 'v2.store.StoreCouponsController/getTodayCoupon')->option(['real_name' => 'Giao diện popup mã giảm giá mới']);//Popup mã giảm giá mới
        Route::get('subscribe', 'v2.PublicController/subscribe')->name('WechatSubscribe')->option(['real_name' => 'Người dùng tài khoản công cộng WeChat có chú ý không?']);// Người dùng tài khoản công cộng WeChat có chú ý không?
        Route::get('index', 'v2.PublicController/index')->name('index')->option(['real_name' => 'trang đầu']);//trang đầu
        Route::get('coupons', 'v2.store.StoreCouponsController/lst')->name('couponsList')->option(['real_name' => 'Danh sách phiếu giảm giá có sẵn']); //Danh sách phiếu giảm giá có sẵn
        Route::get('diy/sign', 'v2.PublicController/getDiySign')->name('getDiySign')->option(['real_name' => 'Nhận đăng ký DIY']);
    })->middleware(\app\api\middleware\AuthTokenMiddleware::class, false)
        ->option(['mark' => 'common', 'mark_name' => 'giao diện công cộng']);

})->middleware(\app\http\middleware\AllowOriginMiddleware::class)->middleware(\app\api\middleware\StationOpenMiddleware::class);
