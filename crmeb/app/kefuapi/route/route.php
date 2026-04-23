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
use app\kefuapi\middleware\KefuAuthTokenMiddleware;
use think\facade\Config;
use think\facade\Route;
use think\Response;

Route::group(function () {

    Route::group(function () {
        Route::post('login', 'Login/login')->name('kefuLogin')->option(['real_name' => 'Đăng nhập tài khoản']);//Đăng nhập tài khoản
        Route::get('key', 'Login/getLoginKey')->name('getLoginKey')->option(['real_name' => 'Nhận mã quét đăng nhậpkey']);//Nhận mã quét đăng nhậpkey
        Route::get('scan/:key', 'Login/scanLogin')->name('scanLogin')->option(['real_name' => 'Phát hiện quét mã QR']);//Phát hiện quét mã QR
        Route::get('config', 'Login/getAppid')->name('getAppid')->option(['real_name' => 'Nhận cấu hình']);//Nhận cấu hình
        Route::get('wechat', 'Login/wechatAuth')->name('wechatAuth')->option(['real_name' => 'WeChat quét mã để đăng nhập']);//WeChat quét mã để đăng nhập
    })->option(['mark' => 'login', 'mark_name' => 'Đăng nhập']);


    Route::group(function () {

        Route::post('upload', 'User/upload')->name('upload')->option(['real_name' => 'Tải ảnh lên', 'mark' => 'common', 'mark_name' => 'giao diện công cộng']);//Tải ảnh lên

    })->middleware(KefuAuthTokenMiddleware::class);

    Route::group('user', function () {

        Route::get('record', 'User/recordList')->name('recordList')->option(['real_name' => 'Người dùng đã trò chuyện với dịch vụ khách hàng']);//Người dùng đã trò chuyện với dịch vụ khách hàng
        Route::get('info/:uid', 'User/userInfo')->name('getUserInfo')->option(['real_name' => 'Chi tiết người dùng']);//Chi tiết người dùng
        Route::get('label/:uid', 'User/getUserLabel')->name('getUserLabel')->option(['real_name' => 'Thẻ người dùng']);//Thẻ người dùng
        Route::put('label/:uid', 'User/setUserLabel')->name('setUserLabel')->option(['real_name' => 'Đặt nhãn người dùng']);//Đặt nhãn người dùng
        Route::get('group', 'User/getUserGroup')->name('getUserGroup')->option(['real_name' => 'Nhận nhóm người dùng']);//Đăng xuất
        Route::put('group/:uid/:id', 'User/setUserGroup')->name('setUserGroup')->option(['real_name' => 'Thiết lập nhóm người dùng']);//Đăng xuất
        Route::post('logout', 'User/logout')->name('logout')->option(['real_name' => 'Đăng xuất']);//Đăng xuất

    })->middleware(KefuAuthTokenMiddleware::class)
        ->option(['mark' => 'user', 'mark_name' => 'người dùng']);

    Route::group('order', function () {

        Route::get('list/:uid', 'Order/getUserOrderList')->name('getUserOrderList')->option(['real_name' => 'danh sách đặt hàng']);//danh sách đặt hàng
        Route::post('delivery/:id', 'Order/delivery_keep')->name('orderDeliveryKeep')->option(['real_name' => 'Đơn hàng đã được vận chuyển']);//Đơn hàng đã được vận chuyển
        Route::put('update/:id', 'Order/update')->name('orderUpdate')->option(['real_name' => 'Sửa đổi đơn hàng']);//Sửa đổi đơn hàng
        Route::post('refund', 'Order/refund')->name('orderRefund')->option(['real_name' => 'Hoàn tiền đơn hàng']);//Hoàn tiền đơn hàng
        Route::get('refund_form/:id', 'Order/refundForm')->name('orderRefund')->option(['real_name' => 'Hoàn tiền đơn hàng']);//Hoàn tiền đơn hàng
        Route::get('edit/:id', 'Order/edit')->name('orderEdit')->option(['real_name' => 'Hoàn tiền đơn hàng']);//Hoàn tiền đơn hàng
        Route::post('remark', 'Order/remark')->name('remark')->option(['real_name' => 'Ghi chú đặt hàng']);//Ghi chú đặt hàng
        Route::get('info/:id', 'Order/orderInfo')->name('orderInfo')->option(['real_name' => 'Nhận chi tiết đơn hàng']);//Nhận chi tiết đơn hàng
        Route::get('export', 'Order/export')->name('export')->option(['real_name' => 'Nhận chi tiết đơn hàng']);//Nhận chi tiết đơn hàng
        Route::get('temp', 'Order/getExportTemp')->name('getExportTemp')->option(['real_name' => 'Nhận mẫu công ty hậu cần']);//Nhận mẫu công ty hậu cần
        Route::get('delivery_all', 'Order/getDeliveryAll')->name('getDeliveryAll')->option(['real_name' => 'Nhận danh sách đầy đủ người giao hàng']);//Nhận danh sách đầy đủ người giao hàng
        Route::get('delivery_info', 'Order/getDeliveryInfo')->name('getDeliveryInfo')->option(['real_name' => 'Nhận danh sách đầy đủ người giao hàng']);//Nhận danh sách đầy đủ người giao hàng
        Route::get('verific/:id', 'Order/order_verific')->name('orderVerific')->option(['real_name' => 'Viết ra một số đơn hàng']);//Viết ra một số đơn hàng

    })->middleware(KefuAuthTokenMiddleware::class)
        ->option(['mark' => 'order', 'mark_name' => 'Đặt hàng']);

    Route::group('product', function () {

        Route::get('hot/:uid', 'Product/getProductHotSale')->name('getProductHotSale')->option(['real_name' => 'Đồ nóng']);//Đồ nóng
        Route::get('visit/:uid', 'Product/getVisitProductList')->name('getVisitProductList')->option(['real_name' => 'Chi tiết sản phẩm']);//Chi tiết sản phẩm
        Route::get('cart/:uid', 'Product/getCartProductList')->name('getCartProductList')->option(['real_name' => 'Lịch sử mua hàng']);//Lịch sử mua hàng
        Route::get('info/:id', 'Product/getProductInfo')->name('getProductInfo')->option(['real_name' => 'Chi tiết sản phẩm']);//Chi tiết sản phẩm

    })->middleware(KefuAuthTokenMiddleware::class)
        ->option(['mark' => 'service', 'mark_name' => 'hàng hóa']);

    Route::group('service', function () {

        Route::get('list', 'Service/getChatList')->name('getChatList')->option(['real_name' => 'Lịch sử trò chuyện']);//Lịch sử trò chuyện
        Route::get('info', 'Service/getServiceInfo')->name('getServiceInfo')->option(['real_name' => 'Chi tiết dịch vụ khách hàng']);//Chi tiết dịch vụ khách hàng
        Route::get('speechcraft', 'Service/getSpeechcraftList')->name('getSpeechcraftList')->option(['real_name' => 'Kỹ năng phục vụ khách hàng']);//Kỹ năng phục vụ khách hàng
        Route::post('transfer', 'Service/transfer')->name('transfer')->option(['real_name' => 'Chuyển dịch vụ khách hàng']);//Chuyển dịch vụ khách hàng
        Route::get('transfer_list', 'Service/getServiceList')->name('getServiceList')->option(['real_name' => 'Chuyển dịch vụ khách hàng']);//Chuyển dịch vụ khách hàng
        Route::get('cate', 'Service/getCateList')->name('getCateList')->option(['real_name' => 'Danh sách danh mục']);//Danh sách danh mục
        Route::post('cate', 'Service/saveCate')->name('saveCate')->option(['real_name' => 'Lưu danh mục']);//Lưu danh mục
        Route::put('cate/:id', 'Service/editCate')->name('editCate')->option(['real_name' => 'Chỉnh sửa danh mục']);//Chỉnh sửa danh mục
        Route::delete('cate/:id', 'Service/deleteCate')->name('deleteCate')->option(['real_name' => 'Xóa danh mục']);//Xóa danh mục
        Route::post('speechcraft', 'Service/saveSpeechcraft')->name('saveSpeechcraft')->option(['real_name' => 'Thêm từ']);//Thêm từ
        Route::put('speechcraft/:id', 'Service/editSpeechcraft')->name('editSpeechcraft')->option(['real_name' => 'Sửa đổi lời nói của bạn']);//Sửa đổi lời nói của bạn
        Route::delete('speechcraft/:id', 'Service/deleteSpeechcraft')->name('deleteSpeechcraft')->option(['real_name' => 'Xóa từ']);//Xóa từ

    })->middleware(KefuAuthTokenMiddleware::class)
        ->option(['mark' => 'service', 'mark_name' => 'dịch vụ khách hàng']);

    Route::group('tourist', function () {
        Route::get('user', 'Common/getServiceUser')->name('getServiceUser')->option(['real_name' => 'Thông tin dịch vụ khách hàng ngẫu nhiên']);//Thông tin dịch vụ khách hàng ngẫu nhiên
        Route::get('adv', 'Common/getKfAdv')->name('getKfAdv')->option(['real_name' => 'Nhận quảng cáo dịch vụ khách hàng']);//Nhận quảng cáo dịch vụ khách hàng
        Route::post('feedback', 'Common/saveFeedback')->name('saveFeedback')->option(['real_name' => 'Lưu nội dung phản hồi dịch vụ khách hàng']);//Lưu nội dung phản hồi dịch vụ khách hàng
        Route::get('feedback', 'Common/getFeedbackInfo')->name('getFeedbackInfo')->option(['real_name' => 'Nhận nội dung không gian quảng cáo trên trang phản hồi']);//Nhận nội dung không gian quảng cáo trên trang phản hồi
        Route::get('order/:order_id', 'Common/getOrderInfo')->name('getOrderInfo')->option(['real_name' => 'Nhận thông tin đặt hàng']);//Nhận thông tin đặt hàng
        Route::get('product/:id', 'Common/getProductInfo')->name('getProductInfo')->option(['real_name' => 'Nhận thông tin sản phẩm']);//Nhận thông tin sản phẩm
        Route::get('chat', 'Common/getChatList')->name('getChatList')->option(['real_name' => 'Nhận lịch sử trò chuyện']);//Nhận lịch sử trò chuyện
        Route::post('upload', 'Common/upload')->name('upload')->option(['real_name' => 'Tải lên hình ảnh']);//Tải lên hình ảnh
    })->option(['mark' => 'tourist', 'mark_name' => 'Dịch vụ khách hàng du lịch']);

})->middleware(AllowOriginMiddleware::class);

Route::miss(function () {
    if (app()->request->isOptions()) {
        $header = Config::get('cookie.header');
        $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
