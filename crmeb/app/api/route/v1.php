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
use think\facade\Config;
use think\Response;

Route::group(function () {
    Route::any('wechat/serve', 'v1.wechat.WechatController/serve')->option(['real_name' => 'Dịch vụ tài khoản công cộng']);//Dịch vụ tài khoản công cộng
    Route::any('wechat/miniServe', 'v1.wechat.WechatController/miniServe')->option(['real_name' => 'Dịch vụ chương trình nhỏ']);//Dịch vụ tài khoản công cộng
    Route::any('pay/notify/:type', 'v1.PayController/notify')->option(['real_name' => 'Hoàn vốn']);//Hoàn vốn
    Route::any('transfer/notify/:type', 'v1.PayController/transferNotify')->option(['real_name' => 'Gọi lại chuyển nhượng người bán']);//Gọi lại chuyển nhượng người bán
    Route::any('order_call_back', 'v1.order.StoreOrderController/callBack')->option(['real_name' => 'Cuộc gọi lại vận chuyển của người bán']);//Cuộc gọi lại vận chuyển của người bán
    Route::get('get_script', 'v1.PublicController/getScript')->option(['real_name' => 'Tùy chỉnh di độngJS']);//Tùy chỉnh di độngJS
    Route::get('custom_pc_js', 'v1.PublicController/customPcJs')->option(['real_name' => 'PCKết thúc tùy chỉnhJS']);//PCKết thúc tùy chỉnhJS
    Route::get('version', 'v1.PublicController/getVersion')->option(['real_name' => 'Nhận số phiên bản mã']);
    Route::get('service_pay_result', 'v1.PublicController/servicePayResult')->option(['real_name' => 'Giao diện biên nhận thanh toán của nhà cung cấp dịch vụ']);
})->middleware(\app\http\middleware\AllowOriginMiddleware::class)->option(['mark' => 'serve', 'mark_name' => 'Giao diện dịch vụ']);

Route::group(function () {
    //appleĐăng nhập nhanh
    Route::post('apple_login', 'v1.LoginController/appleLogin')->name('appleLogin')->option(['real_name' => 'Ủy quyền ứng dụng WeChat']);//Ủy quyền ứng dụng WeChat
    // Đăng nhập tài khoản và mật khẩu
    Route::post('login', 'v1.LoginController/login')->name('login')->option(['real_name' => 'Đăng nhập bằng tài khoản và mật khẩu']);
    // Nhận tin nhắn văn bảnkey
    Route::get('verify_code', 'v1.LoginController/verifyCode')->name('verifyCode')->option(['real_name' => 'Nhận tin nhắn văn bảnkey']);
    //Đăng nhập số điện thoại di động
    Route::post('login/mobile', 'v1.LoginController/mobile')->name('loginMobile')->option(['real_name' => 'Đăng nhập số điện thoại di động']);
    //Mã xác minh hình ảnh
    Route::get('sms_captcha', 'v1.LoginController/captcha')->name('captcha')->option(['real_name' => 'Mã xác minh hình ảnh']);
    //Mã xác minh đồ họa
    Route::get('ajcaptcha', 'v1.LoginController/ajcaptcha')->name('ajcaptcha')->option(['real_name' => 'Mã xác minh đồ họa']);
    //Xác minh hình ảnh xác thực bằng đồ họa
    Route::post('ajcheck', 'v1.LoginController/ajcheck')->name('ajcheck')->option(['real_name' => 'Xác minh hình ảnh xác thực bằng đồ họa']);
    //Gửi mã xác minh qua điện thoại di động
    Route::post('register/verify', 'v1.LoginController/verify')->name('registerVerify')->option(['real_name' => 'Gửi mã xác minh qua điện thoại di động']);
    //Đăng ký số điện thoại di động
    Route::post('register', 'v1.LoginController/register')->name('register')->option(['real_name' => 'Đăng ký số điện thoại di động']);
    //Đổi mật khẩu số điện thoại di động
    Route::post('register/reset', 'v1.LoginController/reset')->name('registerReset')->option(['real_name' => 'Đổi mật khẩu số điện thoại di động']);
    // Ràng buộc số điện thoại di động(Ủy quyền im lặng Chưa có thông tin người dùng)
    Route::post('binding', 'v1.LoginController/binding_phone')->name('bindingPhone')->option(['real_name' => 'Liên kết số điện thoại di động']);
    // Thanh toán liên kết sao chép Alipay Không được dùng nữa
//    Route::get('ali_pay', 'v1.order.StoreOrderController/aliPay')->name('aliPay');
    //Kiểm tra bản quyền
    Route::get('copyright', 'v1.PublicController/copyright')->option(['real_name' => 'Xin bản quyền'])->option(['real_name' => 'Kiểm tra bản quyền']);
    //Giao diện tóm tắt cấu hình cơ bản của Mall
    Route::get('basic_config', 'v1.PublicController/getMallBasicConfig')->option(['real_name' => 'Giao diện tóm tắt cấu hình cơ bản của Mall']);
    //Giao diện url nhảy chương trình nhỏ
    Route::get('get_scheme_url/:id', 'v1.PublicController/getSchemeUrl')->option(['real_name' => 'Giao diện url nhảy chương trình nhỏ']);
    //Đăng ký người dùng từ xa
    Route::get('remote_register', 'v1.LoginController/remoteRegister')->option(['real_name' => 'Đăng ký người dùng từ xa']);

})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->option(['mark' => 'base', 'mark_name' => 'Giao diện cơ bản']);


//Quản lý người bán di động
Route::group(function () {
    Route::get('admin/order/statistics', 'v1.admin.StoreOrderController/statistics')->name('adminOrderStatistics')->option(['real_name' => 'Thống kê dữ liệu đơn hàng']);//Thống kê dữ liệu đơn hàng
    Route::get('admin/order/data', 'v1.admin.StoreOrderController/data')->name('adminOrderData')->option(['real_name' => 'Thống kê đặt hàng hàng tháng']);//Thống kê đặt hàng hàng tháng
    Route::get('admin/order/list', 'v1.admin.StoreOrderController/lst')->name('adminOrderList')->option(['real_name' => 'danh sách đặt hàng']);//danh sách đặt hàng
    Route::get('admin/refund_order/list', 'v1.admin.StoreOrderController/refundOrderList')->name('adminOrderRefundList')->option(['real_name' => 'Danh sách đơn hàng hoàn tiền']);//Danh sách đơn hàng hoàn tiền
    Route::get('admin/order/detail/:orderId', 'v1.admin.StoreOrderController/detail')->name('adminOrderDetail')->option(['real_name' => 'Chi tiết đơn hàng']);//Chi tiết đặt hàng
    Route::get('admin/refund_order/detail/:uni', 'v1.admin.StoreOrderController/refundOrderDetail')->name('RefundOrderDetail')->option(['real_name' => 'Chi tiết đơn hàng hoàn tiền']);//Chi tiết đơn hàng hoàn tiền
    Route::get('admin/order/delivery/gain/:orderId', 'v1.admin.StoreOrderController/delivery_gain')->name('adminOrderDeliveryGain')->option(['real_name' => 'Giao hàngNhận thông tin đơn hàng']);//Giao hàngNhận thông tin đơn hàng
    Route::post('admin/order/delivery/keep/:id', 'v1.admin.StoreOrderController/delivery_keep')->name('adminOrderDeliveryKeep')->option(['real_name' => 'Đã giao cho ĐVVC']);//Đơn hàng đã được vận chuyển
    Route::post('admin/order/price', 'v1.admin.StoreOrderController/price')->name('adminOrderPrice')->option(['real_name' => 'Thay đổi giá đặt hàng']);//Thay đổi giá đặt hàng
    Route::post('admin/order/remark', 'v1.admin.StoreOrderController/remark')->name('adminOrderRemark')->option(['real_name' => 'Ghi chú đơn hàng']);//Ghi chú đặt hàng
    Route::post('admin/order/agreeExpress', 'v1.admin.StoreOrderController/agreeExpress')->name('adminOrderAgreeExpress')->option(['real_name' => 'Đơn hàng đồng ý trả lại']);//Đơn hàng đồng ý trả lại
    Route::post('admin/refund_order/remark', 'v1.admin.StoreOrderController/refundRemark')->name('refundRemark')->option(['real_name' => 'Ghi chú đơn hàng hoàn tiền']);//Ghi chú đơn hàng hoàn tiền
    Route::get('admin/order/time', 'v1.admin.StoreOrderController/time')->name('adminOrderTime')->option(['real_name' => 'Thống kê thời gian khối lượng giao dịch đặt hàng']);//Thống kê thời gian khối lượng giao dịch đặt hàng
    Route::post('admin/order/offline', 'v1.admin.StoreOrderController/offline')->name('adminOrderOffline')->option(['real_name' => 'Thanh toán đơn hàng']);//Thanh toán đơn hàng
    Route::post('admin/order/refund', 'v1.admin.StoreOrderController/refund')->name('adminOrderRefund')->option(['real_name' => 'Hoàn tiền đơn hàng']);//Hoàn tiền đơn hàng
    Route::post('order/order_verific', 'v1.admin.StoreOrderController/order_verific')->name('order')->option(['real_name' => 'Xóa đơn hàng']);//Xóa đơn hàng
    Route::get('admin/order/delivery', 'v1.admin.StoreOrderController/getDeliveryAll')->name('getDeliveryAll')->option(['real_name' => 'Nhận người giao hàng']);//Nhận người giao hàng
    Route::get('admin/order/delivery_info', 'v1.admin.StoreOrderController/getDeliveryInfo')->name('getDeliveryInfo')->option(['real_name' => 'Lấy thông tin mặc định của biểu mẫu điện tử']);//Lấy thông tin mặc định của biểu mẫu điện tử
    Route::get('admin/order/export_temp', 'v1.admin.StoreOrderController/getExportTemp')->name('getExportTemp')->option(['real_name' => 'Lấy mẫu biểu mẫu điện tử']);//Lấy mẫu biểu mẫu điện tử
    Route::get('admin/order/export_all', 'v1.admin.StoreOrderController/getExportAll')->name('getExportAll')->option(['real_name' => 'Nhận công ty hậu cần']);//Nhận công ty hậu cần
    Route::get('admin/order/express/:uni/[:type]', 'v1.admin.StoreOrderController/express')->name('orderExpress')->option(['real_name' => 'Đặt hàng Xem hậu cần']); //Đặt hàng Xem hậu cần

    // Trang chủ quản lý người bán
    Route::get('admin/manage/statistics', 'v1.admin.StoreManageController/statistics');
    // Quản lý thương mại quản lý sản phẩm
    Route::get('admin/manage/product', 'v1.admin.StoreManageController/product');
    Route::post('admin/manage/product/set_show', 'v1.admin.StoreManageController/productShow');
    Route::get('admin/manage/product/label', 'v1.admin.StoreManageController/productLabel');
    Route::post('admin/manage/product/save_label', 'v1.admin.StoreManageController/saveProductLabel');
    Route::get('admin/manage/product/cate', 'v1.admin.StoreManageController/productCate');
    Route::post('admin/manage/product/save_cate', 'v1.admin.StoreManageController/saveProductCate');
    Route::get('admin/manage/product/attr/:id', 'v1.admin.StoreManageController/productAttr');
    Route::post('admin/manage/product/save_attr/:id', 'v1.admin.StoreManageController/saveProductAttr');
    Route::get('admin/manage/product/shipping_temp', 'v1.admin.StoreManageController/shippingTemp');
    Route::post('admin/manage/product/create', 'v1.admin.StoreManageController/createProduct');
    // Quản lý người bán Quản lý người dùng
    Route::get('admin/manage/user', 'v1.admin.StoreManageController/user');
    Route::get('admin/manage/user/group', 'v1.admin.StoreManageController/userGroup');
    Route::get('admin/manage/user/level', 'v1.admin.StoreManageController/userLevel');
    Route::get('admin/manage/user/label/[:uid]', 'v1.admin.StoreManageController/userLabel');
    Route::get('admin/manage/user/coupon', 'v1.admin.StoreManageController/userCoupon');
    Route::post('admin/manage/user/update/:uid', 'v1.admin.StoreManageController/userUpdate');
    Route::get('admin/manage/user/info/:uid', 'v1.admin.StoreManageController/userInfo');


})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->middleware(\app\api\middleware\AuthTokenMiddleware::class, true)
    ->middleware(\app\api\middleware\CustomerMiddleware::class)
    ->option(['mark' => 'admin', 'mark_name' => 'Quản lý đơn hàng di động']);;

//Giao diện phân quyền thành viên
Route::group(function () {
    Route::group(function () {
        //Nhận phương thức thanh toán
        Route::get('pay/config', 'v1.PayController/config')->name('payConfig')->option(['real_name' => 'Nhận phương thức thanh toán']);
        //Người dùng thay đổi số điện thoại di động
        Route::post('user/updatePhone', 'v1.LoginController/update_binding_phone')->name('updateBindingPhone')->option(['real_name' => 'Người dùng thay đổi số điện thoại di động']);
        //Thiết lập đăng nhậpcode
        Route::post('user/code', 'v1.user.StoreService/setLoginCode')->name('setLoginCode')->option(['real_name' => 'Thiết lập đăng nhậpcode']);
        //Kiểm tra xem mã có sẵn không
        Route::get('user/code', 'v1.LoginController/setLoginKey')->name('getLoginKey')->option(['real_name' => 'Kiểm tra xem mã có sẵn không']);
        //Người dùng liên kết số điện thoại di động
        Route::post('user/binding', 'v1.LoginController/user_binding_phone')->name('userBindingPhone')->option(['real_name' => 'Người dùng liên kết số điện thoại di động']);
        Route::get('logout', 'v1.LoginController/logout')->name('logout')->option(['real_name' => 'Đăng xuất']);// Đăng xuất
        Route::post('switch_h5', 'v1.LoginController/switch_h5')->name('switch_h5')->option(['real_name' => 'Chuyển đổi tài khoản']);// Chuyển đổi tài khoản
        //Lớp công khai
        Route::post('upload/image', 'v1.PublicController/upload_image')->name('uploadImage')->option(['real_name' => 'Tải lên hình ảnh']);//Tải lên hình ảnh
        // Giao diện chi tiết chuyển khoản WeChat của người dùng
        Route::get('transfer/info', 'v1.PublicController/getTransferInfo')->name('getTransferInfo')->option(['real_name' => 'Giao diện chi tiết chuyển khoản WeChat của người dùng']);// Giao diện chi tiết chuyển khoản WeChat của người dùng

    })->option(['mark' => 'common', 'mark_name' => 'giao diện công cộng']);

    Route::group(function () {
        //Danh mục người dùng Bản ghi trò chuyện dịch vụ khách hàng
        Route::get('user/service/list', 'v1.user.StoreService/lst')->name('userServiceList')->option(['real_name' => 'Danh sách dịch vụ khách hàng']);//Danh sách dịch vụ khách hàng
        Route::get('user/service/record', 'v1.user.StoreService/record')->name('userServiceRecord')->option(['real_name' => 'Lịch sử trò chuyện dịch vụ khách hàng']);//Lịch sử trò chuyện dịch vụ khách hàng
        Route::post('user/service/feedback', 'v1.user.StoreService/saveFeedback')->name('saveFeedback')->option(['real_name' => 'Lưu thông tin phản hồi dịch vụ khách hàng']);//Lưu thông tin phản hồi dịch vụ khách hàng
        Route::get('user/service/feedback', 'v1.user.StoreService/getFeedbackInfo')->name('getFeedbackInfo')->option(['real_name' => 'Nhận thông tin tiêu đề phản hồi dịch vụ khách hàng']);//Nhận thông tin tiêu đề phản hồi dịch vụ khách hàng
        Route::get('user/service/get_adv', 'v1.user.StoreService/getKfAdv')->name('userServiceGetKfAdv')->option(['real_name' => 'Nhận quảng cáo trang dịch vụ khách hàng']);//Nhận quảng cáo trang dịch vụ khách hàng
    })->option(['parent' => 'user', 'cate_name' => 'dịch vụ khách hàng']);

    Route::group(function () {
        //Lớp người dùng Người dùngcoupons/order
        Route::get('user', 'v1.user.UserController/user')->name('user')->option(['real_name' => 'Trung tâm cá nhân']);//Trung tâm cá nhân
        Route::post('user/spread', 'v1.user.UserController/spread')->name('userSpread')->option(['real_name' => 'Ủy quyền ràng buộc âm thầm']);//Ủy quyền ràng buộc âm thầm
        Route::post('user/edit', 'v1.user.UserController/edit')->name('userEdit')->option(['real_name' => 'Thông tin người dùng sửa đổi']);//Thông tin người dùng sửa đổi
        Route::get('user/balance', 'v1.user.UserController/balance')->name('userBalance')->option(['real_name' => 'Thống kê quỹ người dùng']);//Thống kê quỹ người dùng
        Route::get('userinfo', 'v1.user.UserController/userinfo')->name('userinfo')->option(['real_name' => 'Thông tin người dùng']);// Thông tin người dùng
    })->option(['parent' => 'user', 'cate_name' => 'Trung tâm người dùng']);

    Route::group(function () {
        //Địa chỉ lớp người dùng
        Route::get('address/detail/:id', 'v1.user.UserAddressController/address')->name('address')->option(['real_name' => 'Nhận một địa chỉ duy nhất']);//Nhận một địa chỉ duy nhất
        Route::get('address/list', 'v1.user.UserAddressController/address_list')->name('addressList')->option(['real_name' => 'danh sách địa chỉ']);//danh sách địa chỉ
        Route::post('address/default/set', 'v1.user.UserAddressController/address_default_set')->name('addressDefaultSet')->option(['real_name' => 'Đặt địa chỉ mặc định']);//Đặt địa chỉ mặc định
        Route::get('address/default', 'v1.user.UserAddressController/address_default')->name('addressDefault')->option(['real_name' => 'Nhận địa chỉ mặc định']);//Nhận địa chỉ mặc định
        Route::post('address/edit', 'v1.user.UserAddressController/address_edit')->name('addressEdit')->option(['real_name' => 'Sửa đổi/thêm địa chỉ']);//Sửa đổi thêm địa chỉ
        Route::post('address/del', 'v1.user.UserAddressController/address_del')->name('addressDel')->option(['real_name' => 'Xóa địa chỉ']);//Xóa địa chỉ
    })->option(['parent' => 'user', 'cate_name' => 'Địa chỉ người dùng']);

    Route::group(function () { //Bộ sưu tập lớp người dùng
        Route::get('collect/user', 'v1.user.UserCollectController/collect_user')->name('collectUser')->option(['real_name' => 'Danh sách sản phẩm yêu thích']);//Danh sách sản phẩm yêu thích
        Route::post('collect/add', 'v1.user.UserCollectController/collect_add')->name('collectAdd')->option(['real_name' => 'Thêm mới mục yêu thích']);//Thêm vào mục yêu thích
        Route::post('collect/del', 'v1.user.UserCollectController/collect_del')->name('collectDel')->option(['real_name' => 'Hủy yêu thích']);//Hủy yêu thích
        Route::post('collect/all', 'v1.user.UserCollectController/collect_all')->name('collectAll')->option(['real_name' => 'Thêm mục yêu thích theo đợt']);//Thêm mục yêu thích theo đợt
    })->option(['parent' => 'user', 'cate_name' => 'Người dùng yêu thích']);

    Route::group(function () {
        Route::get('rank', 'v1.user.UserController/rank')->name('rank')->option(['real_name' => 'Tài khoản chính thức đăng nhập được ủy quyền']);//Xếp hạng nhà quảng cáo
        //Chia sẻ lớp người dùng
        Route::post('user/share', 'v1.PublicController/user_share')->name('user_share')->option(['real_name' => 'Ghi lại chia sẻ của người dùng']);//Ghi lại chia sẻ của người dùng
        Route::get('user/share/words', 'v1.PublicController/copy_share_words')->name('user_share_words')->option(['real_name' => 'Chia sẻ từ khóa']);//Chia sẻ từ khóa
    })->option(['parent' => 'user', 'cate_name' => 'Chia sẻ của người dùng']);

    Route::group(function () {
        //Đăng nhập lớp người dùng
        Route::get('sign/config', 'v1.user.UserSignController/sign_config')->name('signConfig')->option(['real_name' => 'Cấu hình đăng nhập']);//Cấu hình đăng nhập
        Route::get('sign/list', 'v1.user.UserSignController/sign_list')->name('signList')->option(['real_name' => 'Danh sách đăng ký']);//Danh sách đăng ký
        Route::get('sign/month', 'v1.user.UserSignController/sign_month')->name('signIntegral')->option(['real_name' => 'Danh sách đăng nhập (năm, tháng)）']);//Danh sách đăng nhập (năm, tháng)）
        Route::get('sign/remind/:status', 'v1.user.UserSignController/sign_remind')->name('signRemind')->option(['real_name' => 'Công tắc nhắc nhở đăng nhập']);//Danh sách đăng nhập (năm, tháng)）
        Route::post('sign/user', 'v1.user.UserSignController/sign_user')->name('signUser')->option(['real_name' => 'Đăng nhập thông tin người dùng']);//Đăng nhập thông tin người dùng
        Route::post('sign/integral', 'v1.user.UserSignController/sign_integral')->name('signIntegral')->option(['real_name' => 'Tài khoản chính thức đăng nhập được ủy quyền'])->middleware(BlockerMiddleware::class);//Đăng nhập
    })->option(['mark' => 'sign', 'mark_name' => 'Đăng nhập']);

    Route::group(function () {
        //Danh mục phiếu giảm giá
        Route::post('coupon/receive', 'v1.store.StoreCouponsController/receive')->name('couponReceive')->option(['real_name' => 'Nhận phiếu giảm giá']); //Nhận phiếu giảm giá
        Route::post('coupon/receive/batch', 'v1.store.StoreCouponsController/receive_batch')->name('couponReceiveBatch')->option(['real_name' => 'Nhận phiếu giảm giá theo đợt']); //Nhận phiếu giảm giá theo đợt
        Route::get('coupons/user/:types', 'v1.store.StoreCouponsController/user')->name('couponsUser')->option(['real_name' => 'Người dùng đã nhận được phiếu giảm giá']);//Người dùng đã nhận được phiếu giảm giá
        Route::get('coupons/order/:price', 'v1.store.StoreCouponsController/order')->name('couponsOrder')->option(['real_name' => 'Danh sách đặt hàng phiếu giảm giá']);//Danh sách đặt hàng phiếu giảm giá
    })->option(['mark' => 'coupons', 'mark_name' => 'Mã giảm giá']);

    Route::group(function () {
        //Danh mục giỏ hàng
        Route::get('cart/list', 'v1.store.StoreCartController/lst')->name('cartList')->option(['real_name' => 'Danh sách giỏ hàng']); //Danh sách giỏ hàng
        Route::post('cart/add', 'v1.store.StoreCartController/add')->name('cartAdd')->option(['real_name' => 'Thêm mới giỏ hàng']); //Thêm vào giỏ hàng
        Route::post('cart/del', 'v1.store.StoreCartController/del')->name('cartDel')->option(['real_name' => 'Xóa giỏ hàng']); //Xóa giỏ hàng
        Route::post('order/cancel', 'v1.order.StoreOrderController/cancel')->name('orderCancel')->option(['real_name' => 'Hủy đơn hàng']); //Hủy đơn hàng
        Route::post('cart/num', 'v1.store.StoreCartController/num')->name('cartNum')->option(['real_name' => 'Chỉnh sửa số lượng sản phẩm trong giỏ hàng']); //Giỏ hàng Sửa đổi số lượng sản phẩm
        Route::get('cart/count', 'v1.store.StoreCartController/count')->name('cartCount')->option(['real_name' => 'Số lượng giỏ hàng']); //Giỏ hàng Nhận số lượng
    })->option(['mark' => 'cart', 'mark_name' => 'giỏ hàng']);

    Route::group(function () {
        //Loại lệnh
        Route::post('order/check_shipping', 'v1.order.StoreOrderController/checkShipping')->name('checkShipping')->option(['real_name' => 'Kiểm tra xem nhãn chuyển phát nhanh và tự nhận có hiển thị hay không']); //Kiểm tra xem nhãn chuyển phát nhanh và tự nhận có hiển thị hay không
        Route::post('order/confirm', 'v1.order.StoreOrderController/confirm')->name('orderConfirm')->option(['real_name' => 'Xác nhận đơn hàng']); //Xác nhận đơn hàng
        Route::post('order/computed/:key', 'v1.order.StoreOrderController/computedOrder')->name('computedOrder')->option(['real_name' => 'Tính số tiền đặt hàng']); //Tính số tiền đặt hàng
        Route::post('order/create/:key', 'v1.order.StoreOrderController/create')->name('orderCreate')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Tạo đơn hàng']); //Tạo đơn hàng
        Route::get('order/data', 'v1.order.StoreOrderController/data')->name('orderData')->option(['real_name' => 'Thống kê đơn hàng']); //Thống kê đơn hàng
        Route::get('order/list', 'v1.order.StoreOrderController/lst')->name('orderList')->option(['real_name' => 'danh sách đặt hàng']); //danh sách đặt hàng
        Route::get('order/detail/:uni/[:cartId]', 'v1.order.StoreOrderController/detail')->name('orderDetail')->option(['real_name' => 'Chi tiết đơn hàng']); //Chi tiết đặt hàng
        Route::get('order/refund_detail/:uni/[:cartId]', 'v1.order.StoreOrderController/refund_detail')->name('refundDetail')->option(['real_name' => 'Chi tiết đơn hàng hoàn tiền']); //Chi tiết đơn hàng hoàn tiền
        Route::get('order/refund/reason', 'v1.order.StoreOrderController/refund_reason')->name('orderRefundReason')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Lý do hoàn tiền đơn hàng']); //Lý do hoàn tiền đơn hàng
        Route::post('order/refund/verify', 'v1.order.StoreOrderController/refund_verify')->name('orderRefundVerify')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Đánh giá hoàn tiền đơn hàng']); //Đánh giá hoàn tiền đơn hàng
        Route::post('order/take', 'v1.order.StoreOrderController/take')->name('orderTake')->middleware(BlockerMiddleware::class)->option(['real_name' => 'Biên nhận đơn hàng']); //Biên nhận đơn hàng
        Route::get('order/express/:uni/[:type]', 'v1.order.StoreOrderController/express')->name('orderExpress')->option(['real_name' => 'Đặt hàng Xem hậu cần']); //Đặt hàng Xem hậu cần
        Route::post('order/del', 'v1.order.StoreOrderController/del')->name('orderDel')->option(['real_name' => 'Xóa đơn hàng']); //Xóa đơn hàng
        Route::post('order/again', 'v1.order.StoreOrderController/again')->name('orderAgain')->option(['real_name' => 'Đặt hàng lại']); //Đặt hàng Đặt hàng lại
        Route::post('order/pay', 'v1.order.StoreOrderController/pay')->name('orderPay')->option(['real_name' => 'Thanh toán đơn hàng']); //Thanh toán đơn hàng
        Route::post('order/product', 'v1.order.StoreOrderController/product')->name('orderProduct')->option(['real_name' => 'Đặt hàng thông tin sản phẩm']); //Đặt hàng thông tin sản phẩm
        Route::post('order/comment', 'v1.order.StoreOrderController/comment')->name('orderComment')->option(['real_name' => 'Đánh giá đơn hàng']); //Đánh giá đơn hàng
        Route::get('order/cashier/:orderId/[:type]', 'v1.order.StoreOrderController/cashier')->name('orderCashier')->option(['real_name' => 'Thanh toán đơn hàng']); //Thanh toán đơn hàng
        Route::get('order/friend_detail', 'v1.order.StoreOrderController/friendDetail')->name('friendDetail')->option(['real_name' => 'Chi tiết thanh toán']);//Chi tiết thanh toán
        Route::post('order/receive_gift/:oid', 'v1.order.StoreOrderController/receiveGift')->name('receiveGift')->option(['real_name' => 'nhận quà']);//nhận quà
        Route::get('order/gift_detail/:oid', 'v1.order.StoreOrderController/giftDetail')->name('giftDetail')->option(['real_name' => 'Chi tiết quà tặng']); //Chi tiết quà tặng

    })->option(['mark' => 'order', 'mark_name' => 'Đặt hàng']);

    Route::group(function () {
        //Hoạt động---Thương lượng
        Route::get('bargain/detail/:id', 'v1.activity.StoreBargainController/detail')->name('bargainDetail')->option(['real_name' => 'Chi tiết sản phẩm khuyến mại']);//Chi tiết sản phẩm khuyến mại
        Route::post('bargain/start', 'v1.activity.StoreBargainController/start')->name('bargainStart')->option(['real_name' => 'Đang đàm phán']);//Đang đàm phán
        Route::post('bargain/start/user', 'v1.activity.StoreBargainController/start_user')->name('bargainStartUser')->option(['real_name' => 'Trao đổi thông tin người dùng']);//Mặc cả Cho phép mặc cả thông tin người dùng
        Route::post('bargain/share', 'v1.activity.StoreBargainController/share')->name('bargainShare')->option(['real_name' => 'Thương lượng và chia sẻ']);//Mặc cả số lượt xem/chia sẻ/tham gia
        Route::post('bargain/help', 'v1.activity.StoreBargainController/help')->name('bargainHelp')->option(['real_name' => 'Mặc cả cho bạn bè']);//Mặc cả Giúp bạn bè mặc cả
        Route::post('bargain/help/price', 'v1.activity.StoreBargainController/help_price')->name('bargainHelpPrice')->option(['real_name' => 'Mặc cả giá, cắt giảm số lượng']);//Mặc cả giá, giảm số lượng
        Route::post('bargain/help/count', 'v1.activity.StoreBargainController/help_count')->name('bargainHelpCount')->option(['real_name' => 'Thống kê trợ giúp thương lượng']);//Mặc cả: Mặc cả tổng số người, số lượng còn lại, thanh tiến trình và mức giá đã giảm.
        Route::post('bargain/help/list', 'v1.activity.StoreBargainController/help_list')->name('bargainHelpList')->option(['real_name' => 'Thương lượng Trợ giúp thương lượng']);//Thương lượng Trợ giúp thương lượng
        Route::post('bargain/poster', 'v1.activity.StoreBargainController/poster')->name('bargainPoster')->option(['real_name' => 'áp phích mặc cả']);//áp phích mặc cả
        Route::get('bargain/user/list', 'v1.activity.StoreBargainController/user_list')->name('bargainUserList')->option(['real_name' => 'Lịch sử trả giá']);//Danh sách mặc cả(Đã tham gia)
        Route::post('bargain/user/cancel', 'v1.activity.StoreBargainController/user_cancel')->name('bargainUserCancel')->option(['real_name' => 'Giảm giá Hủy bỏ']);//Giảm giá Hủy bỏ
        Route::get('bargain/poster_info/:bargainId', 'v1.activity.StoreBargainController/posterInfo')->name('posterInfo')->option(['real_name' => 'Chi tiết áp phích giảm giá']);//Chi tiết áp phích giảm giá
    })->option(['parent' => 'activity_nologin', 'cate_name' => 'Mặc cả']);

    Route::group(function () {
        //Hoạt động---Nhóm
        Route::get('combination/pink/:id', 'v1.activity.StoreCombinationController/pink')->name('combinationPink')->option(['real_name' => 'Tham gia một nhóm để bắt đầu một nhóm']);//Tham gia một nhóm để bắt đầu một nhóm
        Route::post('combination/remove', 'v1.activity.StoreCombinationController/remove')->name('combinationRemove')->option(['real_name' => 'Tham gia nhóm Hủy nhóm']);//Tham gia nhóm Hủy nhóm
        Route::post('combination/poster', 'v1.activity.StoreCombinationController/poster')->name('combinationPoster')->option(['real_name' => 'Áp phích chia sẻ nhóm']);//Áp phích chia sẻ nhóm
        Route::get('combination/poster_info/:id', 'v1.activity.StoreCombinationController/posterInfo')->name('pinkPosterInfo')->option(['real_name' => 'Nhận thông tin chi tiết trên poster chia sẻ nhóm']);//Nhận thông tin chi tiết trên poster chia sẻ nhóm
        Route::get('combination/code/:id', 'v1.activity.StoreCombinationController/code')->name('combinationCode')->option(['real_name' => 'Áp phích sản phẩm nhóm']);//Áp phích sản phẩm nhóm
        Route::get('seckill/code/:id', 'v1.activity.StoreSeckillController/code')->name('seckillCode')->option(['real_name' => 'Áp phích sản phẩm flash sale']);//Áp phích sản phẩm flash sale
    })->option(['parent' => 'activity_nologin', 'cate_name' => 'Chia sẻ nhóm']);;

    Route::group(function () {
        //Loại hóa đơn
        Route::post('spread/people', 'v1.user.UserController/spread_people')->name('spreadPeople')->option(['real_name' => 'Người dùng được đề xuất']);//Người dùng được đề xuất
        Route::post('spread/order', 'v1.user.UserBillController/spread_order')->name('spreadOrder')->option(['real_name' => 'Đơn hàng Affiliate']);//Đơn hàng khuyến mãi
        Route::get('spread/commission/:type', 'v1.user.UserBillController/spread_commission')->name('spreadCommission')->option(['real_name' => 'Chi tiết hoa hồng khuyến mãi']);//Chi tiết hoa hồng khuyến mãi
        Route::get('spread/count/:type', 'v1.user.UserBillController/spread_count')->name('spreadCount')->option(['real_name' => 'Hoa hồng khuyến mại']);//Hoa hồng khuyến mãi 3/Rút tiền 4 Tổng cộng
        Route::get('spread/banner', 'v1.user.UserBillController/spread_banner')->name('spreadBanner')->option(['real_name' => 'Khuyến mãi và phân phối tạo áp phích mã QR']);//Khuyến mãi và phân phối tạo áp phích mã QR
        Route::get('integral/list', 'v1.user.UserBillController/integral_list')->name('integralList')->option(['real_name' => 'Kỷ lục điểm']);//Kỷ lục điểm
        Route::get('user/routine_code', 'v1.user.UserBillController/getRoutineCode')->name('getRoutineCode')->option(['real_name' => 'Mã QR chương trình nhỏ']);//Mã QR chương trình nhỏ
        Route::get('user/spread_info', 'v1.user.UserBillController/getSpreadInfo')->name('getSpreadInfo')->option(['real_name' => 'Nhận thông tin cơ bản về phân phối và các thông tin khác']);//Nhận thông tin cơ bản về phân phối và các thông tin khác
        Route::post('division/order', 'v1.user.UserBillController/divisionOrder')->name('divisionOrder')->option(['real_name' => 'Lệnh khuyến mãi của bộ phận kinh doanh']);//Lệnh khuyến mãi của bộ phận kinh doanh
    })->option(['mark' => 'division', 'mark_name' => 'hóa đơn']);

    Route::group(function () {
        //Rút tiền
        Route::get('extract/bank', 'v1.user.UserExtractController/bank')->name('extractBank')->option(['real_name' => 'Ngân hàng rút tiền']);//Ngân hàng rút tiền/số tiền rút tối thiểu
        Route::post('extract/cash', 'v1.user.UserExtractController/cash')->name('extractCash')->option(['real_name' => 'Yêu cầu rút tiền']);//Đơn xin rút tiền
    })->option(['mark' => 'extract', 'mark_name' => 'Rút tiền mặt']);

    Route::group(function () {
        //Loại nạp tiền
        Route::post('recharge/recharge', 'v1.user.UserRechargeController/recharge')->name('rechargeRecharge')->option(['real_name' => 'nạp tiền thống nhất']);//nạp tiền thống nhất
        Route::post('recharge/routine', 'v1.user.UserRechargeController/routine')->name('rechargeRoutine')->option(['real_name' => 'Nạp tiền chương trình nhỏ']);//Nạp tiền chương trình nhỏ
        Route::post('recharge/wechat', 'v1.user.UserRechargeController/wechat')->name('rechargeWechat')->option(['real_name' => 'Nạp tiền tài khoản chính thức']);//Nạp tiền tài khoản chính thức
        Route::get('recharge/index', 'v1.user.UserRechargeController/index')->name('rechargeQuota')->option(['real_name' => 'Lựa chọn số dư nạp tiền']);//Lựa chọn số dư nạp tiền
    })->option(['mark' => 'recharge', 'mark_name' => 'nạp tiền']);

    Route::group(function () {
        //Hạng mục cấp thành viên
        Route::get('user/level/detection', 'v1.user.UserLevelController/detection')->name('userLevelDetection')->option(['real_name' => 'Kiểm tra xem người dùng có thể trở thành thành viên hay không']);//Kiểm tra xem người dùng có thể trở thành thành viên hay không
        Route::get('user/level/grade', 'v1.user.UserLevelController/grade')->name('userLevelGrade')->option(['real_name' => 'Danh sách cấp thành viên']);//Danh sách cấp thành viên
        Route::get('user/level/task/:id', 'v1.user.UserLevelController/task')->name('userLevelTask')->option(['real_name' => 'Nhận nhiệm vụ cấp độ']);//Nhận nhiệm vụ cấp độ
        Route::get('user/level/info', 'v1.user.UserLevelController/userLevelInfo')->name('levelInfo')->option(['real_name' => 'Nhận nhiệm vụ cấp độ']);//Nhận nhiệm vụ cấp độ
        Route::get('user/level/expList', 'v1.user.UserLevelController/expList')->name('expList')->option(['real_name' => 'Nhận nhiệm vụ cấp độ']);//Nhận nhiệm vụ cấp độ
        Route::get('user/record', 'v1.user.StoreService/recordList')->name('recordList')->option(['real_name' => 'Lấy danh sách tin nhắn của người dùng và bộ phận chăm sóc khách hàng']);//Lấy danh sách tin nhắn của người dùng và bộ phận chăm sóc khách hàng
    })->option(['mark' => 'user_level', 'mark_name' => 'Cấp độ thành viên']);

    Route::group(function () {
        //thẻ thành viên
        Route::get('user/member/card/index', 'v1.user.MemberCardController/index')->name('userMemberCardIndex')->option(['real_name' => 'Trang chủ Quyền lợi thành viên Trang giới thiệu']);// Trang chủ Quyền lợi thành viên Trang giới thiệu
        Route::post('user/member/card/draw', 'v1.user.MemberCardController/draw_member_card')->name('userMemberCardDraw')->option(['real_name' => 'Bí mật thẻ nhận thẻ thành viên']);//Bí mật thẻ nhận thẻ thành viên
        Route::post('user/member/card/create', 'v1.order.OtherOrderController/create')->name('userMemberCardCreate')->option(['real_name' => 'Mua thẻTạo đơn hàng']);//Mua thẻTạo đơn hàng
        Route::get('user/member/coupons/list', 'v1.user.MemberCardController/memberCouponList')->name('userMemberCouponsList')->option(['real_name' => 'Danh sách phiếu giảm giá thành viên']);//Danh sách phiếu giảm giá thành viên
        Route::get('user/member/overdue/time', 'v1.user.MemberCardController/getOverdueTime')->name('userMemberOverdueTime')->option(['real_name' => 'thời gian thành viên']);//thời gian thành viên
    })->option(['parent' => 'user', 'cate_name' => 'thẻ thành viên']);

    Route::group(function () {
        //Thanh toán ngoại tuyến
        Route::post('order/offline/check/price', 'v1.order.OtherOrderController/computed_offline_pay_price')->name('orderOfflineCheckPrice')->option(['real_name' => 'Phát hiện số tiền thanh toán ngoại tuyến']); //Phát hiện số tiền thanh toán ngoại tuyến
        Route::post('order/offline/create', 'v1.order.OtherOrderController/create')->name('orderOfflineCreate')->option(['real_name' => 'Phát hiện số tiền thanh toán ngoại tuyến']); //Phát hiện số tiền thanh toán ngoại tuyến
        Route::get('order/offline/pay/type', 'v1.order.OtherOrderController/pay_type')->name('orderOfflineCreate')->option(['real_name' => 'Phương thức thanh toán ngoại tuyến']); //Phương thức thanh toán ngoại tuyến
    })->option(['mark' => 'offline', 'mark_name' => 'Thanh toán ngoại tuyến']);

    Route::group(function () {
        //Tin nhắn trong trang web
        Route::get('user/message_system/list', 'v1.user.MessageSystemController/message_list')->name('MessageSystemList')->option(['real_name' => 'Danh sách tin nhắn trang web']); //Danh sách tin nhắn trang web
        Route::get('user/message_system/detail/:id', 'v1.user.MessageSystemController/detail')->name('MessageSystemDetail')->option(['real_name' => 'Chi tiết']); //Chi tiết
        Route::get('user/message_system/edit_message', 'v1.user.MessageSystemController/edit_message')->name('EditMessage')->option(['real_name' => 'Cài đặt thông báo trang web']);//Đặt tin nhắn nội bộ là chưa đọc/xóa
    })->option(['mark' => 'message_system', 'mark_name' => 'Thông báo trang web']);

    Route::group(function () {
        //Đặt hàng tại trung tâm mua sắm Points
        Route::post('store_integral/order/confirm', 'v1.order.StoreIntegralOrderController/confirm')->name('storeIntegralOrderConfirm')->option(['real_name' => 'Xác nhận đơn hàng']); //Xác nhận đơn hàng
        Route::post('store_integral/order/create', 'v1.order.StoreIntegralOrderController/create')->name('storeIntegralOrderCreate')->option(['real_name' => 'Tạo đơn hàng']); //Tạo đơn hàng
        Route::get('store_integral/order/detail/:uni', 'v1.order.StoreIntegralOrderController/detail')->name('storeIntegralOrderDetail')->option(['real_name' => 'Chi tiết đơn hàng']); //Chi tiết đặt hàng
        Route::get('store_integral/order/list', 'v1.order.StoreIntegralOrderController/lst')->name('storeIntegralOrderList')->option(['real_name' => 'danh sách đặt hàng']); //danh sách đặt hàng
        Route::post('store_integral/order/take', 'v1.order.StoreIntegralOrderController/take')->name('storeIntegralOrderTake')->option(['real_name' => 'Biên nhận đơn hàng']); //Biên nhận đơn hàng
        Route::get('store_integral/order/express/:uni', 'v1.order.StoreIntegralOrderController/express')->name('storeIntegralOrderExpress')->option(['real_name' => 'Đặt hàng Xem hậu cần']); //Đặt hàng Xem hậu cần
        Route::post('store_integral/order/del', 'v1.order.StoreIntegralOrderController/del')->name('storeIntegralOrderDel')->option(['real_name' => 'Xóa đơn hàng']); //Xóa đơn hàng
    })->option(['mark' => 'order_integral', 'mark_name' => 'Đơn hàng điểm']);;

    Route::group(function () {
        /** Liên quan đến hoàn tiền */
        Route::get('order/refund/cart_info/:id', 'v1.order.StoreOrderController/refundCartInfo')->name('refundCartInfo')->option(['real_name' => 'Đặt hàng danh sách sản phẩm trên trang trung gian hoàn tiền']);//Đặt hàng danh sách sản phẩm trên trang trung gian hoàn tiền
        Route::post('order/refund/cart_info', 'v1.order.StoreOrderController/refundCartInfoList')->name('StoreOrderRefundCartInfoList')->option(['real_name' => 'Nhận danh sách sản phẩm được hoàn tiền']);//Nhận danh sách sản phẩm được hoàn tiền
        Route::post('order/refund/apply/:id', 'v1.order.StoreOrderController/applyRefund')->name('StoreOrderApplyRefund')->option(['real_name' => 'Yêu cầu hoàn lại tiền cho một đơn đặt hàng']);//Yêu cầu hoàn lại tiền cho một đơn đặt hàng
        Route::get('order/refund/list', 'v1.order.StoreOrderRefundController/refundList')->name('refundList')->option(['real_name' => 'Danh sách đơn hàng hoàn tiền']);//Danh sách đơn hàng hoàn tiền
        Route::get('order/refund/detail/:uni', 'v1.order.StoreOrderRefundController/refundDetail')->name('refundDetail')->option(['real_name' => 'Chi tiết đơn hàng hoàn tiền']);//Chi tiết đơn hàng hoàn tiền
        Route::post('order/refund/cancel/:uni', 'v1.order.StoreOrderRefundController/cancelApply')->name('cancelApply')->option(['real_name' => 'Người dùng hủy yêu cầu hoàn tiền']);//Người dùng hủy yêu cầu hoàn tiền
        Route::post('order/refund/express', 'v1.order.StoreOrderRefundController/applyExpress')->name('refundDetail')->option(['real_name' => 'Chi tiết đơn hàng hoàn tiền']);//Chi tiết đơn hàng hoàn tiền
        Route::get('order/refund/del/:uni', 'v1.order.StoreOrderRefundController/delRefund')->name('delRefund')->option(['real_name' => 'Người dùng hủy yêu cầu hoàn tiền']);//Người dùng hủy yêu cầu hoàn tiền
    })->option(['mark' => 'refund', 'mark_name' => 'Hậu mãi']);

    Route::group(function () {
        /** Đại lý liên quan */
        Route::get('agent/apply/info', 'v1.user.DivisionController/applyInfo')->name('Chi tiết ứng dụng')->option(['real_name' => 'Chi tiết ứng dụng']);//Chi tiết ứng dụng
        Route::post('agent/apply/:id', 'v1.user.DivisionController/applyAgent')->name('applyAgent')->option(['real_name' => 'Đăng ký làm đại lý']);//Đăng ký làm đại lý
        Route::get('agent/get_agent_agreement', 'v1.user.DivisionController/getAgentAgreement')->name('getAgentAgreement')->option(['real_name' => 'Nội quy đại lý']);//Nội quy đại lý
        Route::get('agent/get_staff_list', 'v1.user.DivisionController/getStaffList')->name('getStaffList')->option(['real_name' => 'danh sách nhân viên']);//danh sách nhân viên
        Route::post('agent/set_staff_percent', 'v1.user.DivisionController/setStaffPercent')->name('setStaffPercent')->option(['real_name' => 'Đặt tỷ lệ hoa hồng cho nhân viên']);//Đặt tỷ lệ hoa hồng cho nhân viên
        Route::get('agent/del_staff/:uid', 'v1.user.DivisionController/delStaff')->name('delStaff')->option(['real_name' => 'Xóa nhân viên']);//Xóa nhân viên
        Route::post('agent/spread', 'v1.user.DivisionController/agentSpread')->name('agentSpread')->option(['real_name' => 'Đại lý ràng buộc nhân viên']);//Đại lý ràng buộc nhân viên
    })->option(['mark' => 'agent', 'mark_name' => 'đại lý']);

    Route::group(function () {
        /** Ủy ban liên quan */
        Route::get('commission', 'v1.user.UserBrokerageController/commission')->name('commission')->option(['real_name' => 'dữ liệu khuyến mãi']);//Dữ liệu khuyến mãi Hoa hồng của ngày hôm qua Số tiền rút tích lũy Hoa hồng hiện tại
        Route::get('brokerage_rank', 'v1.user.UserBrokerageController/brokerageRank')->name('brokerageRank')->option(['real_name' => 'Xếp hạng hoa hồng']);//Xếp hạng hoa hồng
        /** Người dùng đăng xuất */
        Route::get('user_cancel', 'v1.user.UserController/SetUserCancel')->name('SetUserCancel')->option(['real_name' => 'Đăng xuất người dùng']);//Đăng xuất người dùng
        /** Lịch sử duyệt web của người dùng */
        Route::get('user/visit_list', 'v1.user.UserController/visitList')->name('visitList')->option(['real_name' => 'Danh sách duyệt sản phẩm']);//Danh sách duyệt sản phẩm
        Route::delete('user/visit', 'v1.user.UserController/visitDelete')->name('visitDelete')->option(['real_name' => 'Xóa lịch sử duyệt sản phẩm']);//Xóa lịch sử duyệt sản phẩm
    })->option(['mark' => 'user', 'mark_name' => 'người dùng']);

    Route::group(function () {
        /** Ứng dụng phân phối */
        Route::get('user/spread/apply/info', 'v1.user.SpreadApplyController/applyInfo')->name('Thông tin ứng dụng');//Thông tin ứng dụng
        Route::post('user/spread/apply/:id', 'v1.user.SpreadApplyController/applyPromoter')->name('Đăng ký làm nhà phân phối');//Đăng ký làm nhà phân phối
    })->option(['mark' => 'spread', 'mark_name' => 'Đăng ký làm Affiliate']);

})->middleware(\app\http\middleware\AllowOriginMiddleware::class)->middleware(\app\api\middleware\StationOpenMiddleware::class)->middleware(\app\api\middleware\AuthTokenMiddleware::class, true);
//Giao diện trái phép
Route::group(function () {
    Route::group(function () {
        Route::get('menu/user', 'v1.PublicController/menu_user')->name('menuUser')->option(['real_name' => 'Menu trung tâm cá nhân']);//Menu trung tâm cá nhân
        //Lớp công khai
        Route::get('index', 'v1.PublicController/index')->name('index')->option(['real_name' => 'trang đầu']);//trang đầu
        Route::get('home/banner', 'v1.PublicController/homeBanner')->name('homeBanner')->option(['real_name' => 'Banner trang chủ (Zalo Mini App)']);//Banner trang chủ - dữ liệu từ routine_home_bast_banner
        Route::get('site_config', 'v1.PublicController/getSiteConfig')->name('getSiteConfig')->option(['real_name' => 'Nhận cấu hình trang web']);//Nhận cấu hình trang web
        //Giao diện tự làm
        Route::get('diy/get_diy/[:id]', 'v1.PublicController/getDiy');
        Route::get('home/products', 'v1.PublicController/home_products_list')->name('homeProductsList')->option(['real_name' => 'Nhận hình ảnh băng chuyền và sản phẩm gợi ý các loại sản phẩm khác nhau trên trang chủ']);//Nhận hình ảnh băng chuyền và sản phẩm gợi ý các loại sản phẩm khác nhau trên trang chủ

        Route::get('theme_info/:type', 'v1.PublicController/themeInfo')->name('themeInfo')->option(['real_name' => 'Chi tiết chủ đề']);
        Route::get('theme_version', 'v1.PublicController/themeVersion')->name('themeVersion')->option(['real_name' => 'phiên bản chủ đề']);
        Route::get('theme/user', 'v1.PublicController/themeUser')->name('themeUser')->option(['real_name' => 'Người dùng thành phần tùy chỉnh']);
        Route::get('theme/article', 'v1.PublicController/themeArticle')->name('themeArticle')->option(['real_name' => 'Thành phần tùy chỉnh-Bài viết']);
        Route::get('theme/coupon', 'v1.PublicController/themeCoupon')->name('themeCoupon')->option(['real_name' => 'Mã giảm giá thành phần tùy chỉnh']);
        Route::get('theme/product', 'v1.PublicController/themeProduct')->name('themeProduct')->option(['real_name' => 'Thành phần-sản phẩm tùy chỉnh']);
        Route::get('theme/navigation', 'v1.PublicController/themeNavigation')->name('themeNavigation')->option(['real_name' => 'điều hướng']);

    })->option(['mark' => 'index', 'mark_name' => 'Giao diện trang chủ']);

    Route::group(function () {
        Route::get('search/keyword', 'v1.PublicController/search')->name('searchKeyword')->option(['real_name' => 'Có được từ khóa tìm kiếm phổ biến']);//Có được từ khóa tìm kiếm phổ biến
        //Lớp phân loại sản phẩm
        Route::get('category', 'v1.store.CategoryController/category')->name('category')->option(['real_name' => 'Danh mục sản phẩm']);
        Route::get('category_version', 'v1.store.CategoryController/getCategoryVersion')->name('getCategoryVersion')->option(['real_name' => 'Phiên bản danh mục sản phẩm']);//Phiên bản danh mục sản phẩm

        //Danh mục hàng hóa
        Route::post('image_base64', 'v1.PublicController/get_image_base64')->name('getImageBase64')->option(['real_name' => 'Nhận hình ảnhbase64']);// Nhận hình ảnhbase64
        Route::get('product/detail/:id/[:type]', 'v1.store.StoreProductController/detail')->name('detail')->option(['real_name' => 'Chi tiết sản phẩm']);//Chi tiết sản phẩm
        Route::get('groom/list/:type', 'v1.store.StoreProductController/groom_list')->name('groomList')->option(['real_name' => 'Nhận hình ảnh băng chuyền và sản phẩm gợi ý các loại sản phẩm khác nhau trên trang chủ']);//Nhận hình ảnh băng chuyền và sản phẩm gợi ý các loại sản phẩm khác nhau trên trang chủ
        Route::get('products', 'v1.store.StoreProductController/lst')->name('products')->option(['real_name' => 'Danh sách sản phẩm']);//Danh sách sản phẩm
        Route::get('product/hot', 'v1.store.StoreProductController/product_hot')->name('productHot')->option(['real_name' => 'Được đề xuất cho bạn']);//Được đề xuất cho bạn
        Route::get('reply/list/:id', 'v1.store.StoreProductController/reply_list')->name('replyList')->option(['real_name' => 'Danh sách Đánh giá sản phẩm']);//Danh sách đánh giá sản phẩm
        Route::get('reply/config/:id', 'v1.store.StoreProductController/reply_config')->name('replyConfig')->option(['real_name' => 'Số lượng Đánh giá sản phẩm và xếp hạng tích cực']);//Số lượng đánh giá sản phẩm và xếp hạng tích cực
        Route::get('advance/list', 'v1.store.StoreProductController/advanceList')->name('advanceList')->option(['real_name' => 'Danh sách sản phẩm trước khi bán']);//Danh sách sản phẩm trước khi bán
        Route::get('product/code/:id', 'v1.store.StoreProductController/code')->name('productCode')->option(['real_name' => 'Mã QR chia sẻ sản phẩm']);//Quảng cáo mã QR chia sẻ sản phẩm
        Route::get('product/real_price/:id/:unique', 'v1.store.StoreProductController/realPrice')->name('realPrice')->option(['real_name' => 'Giá sản phẩm']);//Giá sản phẩm
    })->option(['mark' => 'product', 'mark_name' => 'sản phẩm']);

    Route::group(function () {

        Route::group(function () {
            //Lớp phân loại bài viết
            Route::get('article/category/list', 'v1.publics.ArticleCategoryController/lst')->name('articleCategoryList')->option(['real_name' => 'Danh sách danh mục bài viết']);//Danh sách danh mục bài viết
            //Lớp bài viết
            Route::get('article/list/:cid', 'v1.publics.ArticleController/lst')->name('articleList')->option(['real_name' => 'Danh sách bài viết']);//Danh sách bài viết
            Route::get('article/details/:id', 'v1.publics.ArticleController/details')->name('articleDetails')->option(['real_name' => 'Chi tiết bài viết']);//Chi tiết bài viết
            Route::get('article/hot/list', 'v1.publics.ArticleController/hot')->name('articleHotList')->option(['real_name' => 'bài viết phổ biến']);//bài viết phổ biến
            Route::get('article/new/list', 'v1.publics.ArticleController/new')->name('articleNewList')->option(['real_name' => 'Bài viết mới nhất']);//Bài viết mới nhất
            Route::get('article/banner/list', 'v1.publics.ArticleController/banner')->name('articleBannerList')->option(['real_name' => 'bài báo banner']);//bài báo banner
        })->option(['parent' => 'activity_nologin', 'cate_name' => 'bài báo(trái phép)']);

        Route::group(function () {
            //Hoạt động --- Flash Sale
            Route::get('seckill/index', 'v1.activity.StoreSeckillController/index')->name('seckillIndex')->option(['real_name' => 'Khoảng thời gian của sản phẩm flash sale']);//Khoảng thời gian của sản phẩm flash sale
            Route::get('seckill/list/:time', 'v1.activity.StoreSeckillController/lst')->name('seckillList')->option(['real_name' => 'Danh sách sản phẩm Flashsale']);//Danh sách sản phẩm Flashsale
            Route::get('seckill/detail/:id', 'v1.activity.StoreSeckillController/detail')->name('seckillDetail')->option(['real_name' => 'Chi tiết sản phẩm Flashsale']);//Chi tiết sản phẩm Flashsale
        })->option(['parent' => 'activity_nologin', 'cate_name' => 'bán chớp nhoáng(trái phép)']);

        Route::group(function () {
            //Hoạt động---Thương lượng
            Route::get('bargain/config', 'v1.activity.StoreBargainController/config')->name('bargainConfig')->option(['real_name' => 'Cấu hình danh sách sản phẩm mặc cả']);//Cấu hình danh sách sản phẩm mặc cả
            Route::get('bargain/list', 'v1.activity.StoreBargainController/lst')->name('bargainList')->option(['real_name' => 'Danh sách sản phẩm mặc cả']);//Danh sách sản phẩm mặc cả
        })->option(['parent' => 'activity_nologin', 'cate_name' => 'Mặc cả(trái phép)']);

        Route::group(function () {
            //Hoạt động---Nhóm
            Route::get('combination/list', 'v1.activity.StoreCombinationController/lst')->name('combinationList')->option(['real_name' => 'Danh sách sản phẩm nhóm']);//Danh sách sản phẩm nhóm
            Route::get('combination/banner_list', 'v1.activity.StoreCombinationController/banner_list')->name('banner_list')->option(['real_name' => 'Danh sách sản phẩm nhóm']);//Danh sách sản phẩm nhóm
            Route::get('combination/detail/:id', 'v1.activity.StoreCombinationController/detail')->name('combinationDetail')->option(['real_name' => 'Chi tiết sản phẩm nhóm']);//Chi tiết sản phẩm nhóm
        })->option(['parent' => 'activity_nologin', 'cate_name' => 'Chia sẻ nhóm(trái phép)']);

        //Bán trước sự kiện
        Route::get('advance/detail/:id', 'v1.activity.StoreAdvanceController/detail')->name('advanceDetail')->option(['real_name' => 'Chi tiết sản phẩm trước khi bán']);//Chi tiết sản phẩm trước khi bán

        //Lớp người dùng
        Route::get('user/activity', 'v1.user.UserController/activity')->name('userActivity')->option(['real_name' => 'trạng thái hoạt động']);//trạng thái hoạt động

    })->option(['mark' => 'activity_nologin', 'mark_name' => 'Hoạt động']);

    Route::group(function () {
        //WeChat
        Route::get('wechat/config', 'v1.wechat.WechatController/config')->name('wechatConfig')->option(['real_name' => 'Cấu hình sdk WeChat']);//Cấu hình sdk WeChat
        Route::get('wechat/auth', 'v1.wechat.WechatController/auth')->name('wechatAuth')->option(['real_name' => 'Ủy quyền WeChat']);//Ủy quyền WeChat
        Route::post('wechat/app_auth', 'v1.wechat.WechatController/appAuth')->name('appAuth')->option(['real_name' => 'Ủy quyền ứng dụng WeChat']);//Ủy quyền ứng dụng WeChat

    })->option(['mark' => 'wechat', 'mark_name' => 'WeChat']);

    Route::group(function () {
        //Đăng nhập chương trình nhỏ
        Route::post('wechat/mp_auth', 'v1.wechat.AuthController/mp_auth')->name('mpAuth')->option(['real_name' => 'Đăng nhập chương trình nhỏ']);//Đăng nhập chương trình nhỏ
        Route::get('wechat/get_logo', 'v1.wechat.AuthController/get_logo')->name('getLogo')->option(['real_name' => 'Hiển thị ủy quyền đăng nhập chương trình nhỏlogo']);//Hiển thị ủy quyền đăng nhập chương trình nhỏlogo
        Route::get('wechat/temp_ids', 'v1.wechat.AuthController/temp_ids')->name('wechatTempIds')->option(['real_name' => 'Tin tức đăng ký chương trình nhỏ']);//Tin tức đăng ký chương trình nhỏ
        Route::get('wechat/live', 'v1.wechat.AuthController/live')->name('wechatLive')->option(['real_name' => 'Danh sách phát sóng trực tiếp chương trình mini']);//Danh sách phát sóng trực tiếp chương trình mini
        Route::get('wechat/livePlaybacks/:id', 'v1.wechat.AuthController/livePlaybacks')->name('livePlaybacks')->option(['real_name' => 'Phát lại trực tiếp chương trình nhỏ']);//Phát lại trực tiếp chương trình nhỏ
    })->option(['mark' => 'mini', 'mark_name' => 'Chương trình nhỏ']);

    Route::group(function () {
        //Công ty hậu cần
        Route::get('logistics', 'v1.PublicController/logistics')->name('logistics')->option(['real_name' => 'Danh sách công ty hậu cần']);//Danh sách công ty hậu cần

        //Chia sẻ cấu hình
        Route::get('share', 'v1.PublicController/share')->name('share')->option(['real_name' => 'Chia sẻ cấu hình']);//Chia sẻ cấu hình

        //Phiếu giảm giá
        Route::get('coupons', 'v1.store.StoreCouponsController/lst')->name('couponsList')->option(['real_name' => 'Danh sách phiếu giảm giá có sẵn']); //Danh sách phiếu giảm giá có sẵn

        // Thông báo mua hàng qua SMS không đồng bộ
        Route::post('sms/pay/notify', 'v1.PublicController/sms_pay_notify')->name('smsPayNotify')->option(['real_name' => 'Thông báo mua hàng qua SMS không đồng bộ']); //Thông báo mua hàng qua SMS không đồng bộ

        // Thu hút sự chú ý trên áp phích tài khoản công khai WeChat
        Route::get('wechat/follow', 'v1.wechat.WechatController/follow')->name('Follow')->option(['real_name' => 'Thu hút sự chú ý của poster tài khoản công khai WeChat']);
        //Người dùng có chú ý đến
        Route::get('subscribe', 'v1.user.UserController/subscribe')->name('Subscribe')->option(['real_name' => 'Người dùng có chú ý đến']);
        //Danh sách cửa hàng
        Route::get('store_list', 'v1.PublicController/store_list')->name('storeList')->option(['real_name' => 'Danh sách cửa hàng']);
        //Nhận danh sách thành phố
        Route::get('city_list', 'v1.PublicController/city_list')->name('cityList')->option(['real_name' => 'Nhận danh sách thành phố']);
        //Dữ liệu nhóm nhóm
        Route::get('pink', 'v1.PublicController/pink')->name('pinkData')->option(['real_name' => 'Dữ liệu nhóm nhóm']);
        //Nhận điều hướng phía dưới
        Route::get('navigation/[:template_name]', 'v1.PublicController/getNavigation')->name('getNavigation')->option(['real_name' => 'Nhận điều hướng phía dưới']);
        //quyền truy cập của người dùng
        Route::post('user/set_visit', 'v1.user.UserController/set_visit')->name('setVisit')->option(['real_name' => 'Thêm bản ghi truy cập của người dùng']);// Thêm bản ghi truy cập của người dùng
        // Sao chép giao diện mật khẩu
        Route::get('copy_words', 'v1.PublicController/copy_words')->name('copyWords')->option(['real_name' => 'Sao chép giao diện mật khẩu']);// Sao chép giao diện mật khẩu
        //Lấy cấu hình trang web
        Route::get('site_config', 'v1.PublicController/getSiteConfig')->name('getSiteConfig')->option(['real_name' => 'Nhận cấu hình trang web']);//Nhận cấu hình trang web
    })->option(['mark' => 'setting', 'mark_name' => 'Cấu hình trung tâm mua sắm']);

    Route::group(function () {
        //Hoạt động---Points Mall
        Route::get('store_integral/index', 'v1.activity.StoreIntegralController/index')->name('storeIntegralIndex')->option(['real_name' => 'Dữ liệu trang chủ của trung tâm mua sắm Points']);//Dữ liệu trang chủ của trung tâm mua sắm Points
        Route::get('store_integral/list', 'v1.activity.StoreIntegralController/lst')->name('storeIntegralList')->option(['real_name' => 'Danh sách sản phẩm điểm']);//Danh sách sản phẩm điểm
        Route::get('store_integral/detail/:id', 'v1.activity.StoreIntegralController/detail')->name('storeIntegralDetail')->option(['real_name' => 'Chi tiết sản phẩm điểm']);//Chi tiết sản phẩm điểm

    })->option(['mark' => 'integral_nologin', 'mark_name' => 'Trung tâm mua sắm điểm(trái phép)']);

    Route::group(function () {
        //Tải phiên bản mới nhất của ứng dụng
        Route::get('get_new_app/:platform', 'v1.PublicController/getNewAppVersion')->name('getNewAppVersion')->option(['real_name' => 'Tải phiên bản mới nhất của ứng dụng']);//Tải phiên bản mới nhất của ứng dụng
        // Lấy loại dịch vụ khách hàng
        Route::get('get_customer_type', 'v1.PublicController/getCustomerType')->name('getCustomerType')->option(['real_name' => 'Nhận loại dịch vụ khách hàng']);//Nhận loại dịch vụ khách hàng
        // Cài đặt liên kết dài
        Route::get('get_workerman_url', 'v1.PublicController/getWorkerManUrl')->name('getWorkerManUrl')->option(['real_name' => 'Cài đặt liên kết dài']);
        //Quảng cáo màn hình mở trang chủ
        Route::get('get_open_adv', 'v1.PublicController/getOpenAdv')->name('getOpenAdv')->option(['real_name' => 'Quảng cáo màn hình mở trang chủ']);
        //Nhận thỏa thuận người dùng
        Route::get('user_agreement', 'v1.PublicController/getUserAgreement')->name('getUserAgreement')->option(['real_name' => 'Nhận thỏa thuận người dùng']);
        //Nhận thỏa thuận
        Route::get('get_agreement/:type', 'v1.PublicController/getAgreement')->name('getAgreement')->option(['real_name' => 'Nhận thỏa thuận']);

    })->option(['mark' => 'other', 'mark_name' => 'Các giao diện khác']);

    Route::group(function () {
        //Nhận danh sách các loại đa ngôn ngữ
        Route::get('get_lang_type_list', 'v1.PublicController/getLangTypeList')->name('getLangTypeList')->option(['real_name' => 'Nhận danh sách các loại đa ngôn ngữ']);
        //Nhận ngôn ngữ hiện tạijson
        Route::get('get_lang_json', 'v1.PublicController/getLangJson')->name('getLangJson')->option(['real_name' => 'Nhận ngôn ngữ hiện tạijson']);
        //Nhận loại ngôn ngữ mặc định của cài đặt nền hiện tại
        Route::get('get_default_lang_type', 'v1.PublicController/getDefaultLangType')->name('getLangJson')->option(['real_name' => 'Nhận loại ngôn ngữ mặc định của cài đặt nền hiện tại']);
        //Nhận loại ngôn ngữ mặc định của cài đặt nền hiện tại
        Route::get('lang_version', 'v1.PublicController/getLangVersion')->name('getLangVersion')->option(['real_name' => 'Nhận loại ngôn ngữ mặc định của cài đặt nền hiện tại']);
    })->option(['mark' => 'lang', 'mark_name' => 'đa ngôn ngữ']);

    Route::group(function () {
        /** Giao diện nhiệm vụ theo lịch trình */
        //Giao diện gọi tác vụ theo lịch trình
        Route::get('crontab/run', 'v1.CrontabController/crontabRun')->name('crontabRun')->option(['real_name' => 'Giao diện gọi nhiệm vụ theo lịch trình']);
        //Phát hiện giao diện tác vụ theo lịch trình
        Route::get('crontab/check', 'v1.CrontabController/crontabCheck')->name('crontabCheck')->option(['real_name' => 'Phát hiện giao diện tác vụ theo lịch trình']);
        //Tự động hủy đơn hàng nếu chưa thanh toán
        Route::get('crontab/order_cancel', 'v1.CrontabController/orderUnpaidCancel')->name('orderUnpaidCancel')->option(['real_name' => 'Tự động hủy đơn hàng nếu chưa thanh toán']);
        //Xử lý đơn hàng nhóm nhóm đã hết hạn
        Route::get('crontab/pink_expiration', 'v1.CrontabController/pinkExpiration')->name('pinkExpiration')->option(['real_name' => 'Xử lý đơn hàng nhóm nhóm đã hết hạn']);
        //Tự động hủy liên kết ràng buộc cấp trên
        Route::get('crontab/agent_unbind', 'v1.CrontabController/agentUnbind')->name('agentUnbind')->option(['real_name' => 'Tự động hủy liên kết ràng buộc cấp trên']);
        //Cập nhật trạng thái sản phẩm trực tiếp
        Route::get('crontab/live_product_status', 'v1.CrontabController/syncGoodStatus')->name('syncGoodStatus')->option(['real_name' => 'Cập nhật trạng thái sản phẩm trực tiếp']);
        //Cập nhật trạng thái phòng trực tiếp
        Route::get('crontab/live_room_status', 'v1.CrontabController/syncRoomStatus')->name('syncRoomStatus')->option(['real_name' => 'Cập nhật trạng thái phòng trực tiếp']);
        //Tự động nhận
        Route::get('crontab/take_delivery', 'v1.CrontabController/autoTakeOrder')->name('autoTakeOrder')->option(['real_name' => 'Tự động nhận']);
        //Kiểm tra xem các sản phẩm đã hết hạn bán trước có tự động bị loại khỏi kệ hay không
        Route::get('crontab/advance_off', 'v1.CrontabController/downAdvance')->name('downAdvance')->option(['real_name' => 'Kiểm tra xem các sản phẩm đã hết hạn bán trước có tự động bị loại khỏi kệ hay không']);
        //Khen ngợi tự động
        Route::get('crontab/product_replay', 'v1.CrontabController/autoComment')->name('autoComment')->option(['real_name' => 'Khen ngợi tự động']);
        //Xóa áp phích ngày hôm qua
        Route::get('crontab/clear_poster', 'v1.CrontabController/emptyYesterdayAttachment')->name('emptyYesterdayAttachment')->option(['real_name' => 'Xóa áp phích ngày hôm qua']);

    })->option(['mark' => 'crontab', 'mark_name' => 'nhiệm vụ theo lịch trình']);

})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->middleware(\app\api\middleware\AuthTokenMiddleware::class, false);

// ==========================================================================
// Zalo Mini App — Giao diện công khai (không cần đăng nhập CRMEB)
// ==========================================================================
Route::group(function () {
    // Đăng nhập bằng Zalo access_token, nhận về JWT của CRMEB
    Route::post('zalo/auth', 'v1.zalo.ZaloAuthController/auth')
        ->option(['real_name' => 'Zalo Mini App - Đăng nhập']);
})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->option(['mark' => 'zalo_public', 'mark_name' => 'Zalo - Giao diện công khai']);

// ==========================================================================
// Zalo Mini App — Giao diện yêu cầu đăng nhập (cần Bearer token)
// ==========================================================================
Route::group(function () {
    // Gửi OTP để gắn số điện thoại (sau khi đã đăng nhập Zalo/CRMEB)
    Route::post('zalo/send_bind_otp', 'v1.zalo.ZaloAuthController/sendBindOtp')
        ->option(['real_name' => 'Zalo Mini App - Gửi OTP gắn số điện thoại']);
    // Gắn số điện thoại sau khi đăng nhập Zalo (OTP flow)
    Route::post('zalo/bind_phone', 'v1.zalo.ZaloAuthController/bindPhone')
        ->option(['real_name' => 'Zalo Mini App - Gắn số điện thoại']);
    // Gắn số điện thoại trực tiếp từ Zalo (không cần OTP — Zalo đã verify)
    Route::post('zalo/bind_phone_direct', 'v1.zalo.ZaloAuthController/bindPhoneDirect')
        ->option(['real_name' => 'Zalo Mini App - Gắn số điện thoại trực tiếp']);
})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->middleware(\app\api\middleware\AuthTokenMiddleware::class, true)
    ->option(['mark' => 'zalo_auth', 'mark_name' => 'Zalo - Giao diện yêu cầu đăng nhập']);

Route::miss(function () {
    if (app()->request->isOptions()) {
        $header = Config::get('cookie.header');
        unset($header['Access-Control-Allow-Credentials']);
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
