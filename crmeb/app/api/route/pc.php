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
use think\facade\Config;
use think\Response;

Route::group('pc', function () {
    //Giao diện đăng nhập
    Route::group(function () {
        Route::get('key', 'pc.LoginController/getLoginKey')->name('getLoginKey')->option(['real_name' => 'Nhận mã quét đăng nhậpkey']);//Nhận mã quét đăng nhậpkey
        Route::get('scan/:key', 'pc.LoginController/scanLogin')->name('scanLogin')->option(['real_name' => 'Phát hiện quét mã QR']);//Phát hiện quét mã QR
        Route::get('get_appid', 'pc.LoginController/getAppid')->name('getAppid')->option(['real_name' => 'Nhận một nền tảng mởappid']);//Phát hiện quét mã QR
        Route::get('wechat_auth', 'pc.LoginController/wechatAuth')->name('wechatAuth')->option(['real_name' => 'Phát hiện quét mã QR']);//Phát hiện quét mã QR
    })->middleware(\app\http\middleware\AllowOriginMiddleware::class)
        ->middleware(\app\api\middleware\StationOpenMiddleware::class)
        ->option(['parent' => 'PC', 'cate_name' => 'Đăng nhập được ủy quyền']);

    //Giao diện trái phép
    Route::group(function () {
        Route::get('get_pay_vip_code', 'pc.HomeController/getPayVipCode')->name('getPayVipCode')->option(['real_name' => 'Lấy mã QR của trang mua thành viên trả phí']);//Lấy mã QR của trang mua thành viên trả phí
        Route::get('get_product_phone_buy', 'pc.HomeController/getProductPhoneBuy')->name('getProductPhoneBuy')->option(['real_name' => 'Cấu hình url nhảy mua hàng trên thiết bị di động']);//Cấu hình url nhảy mua hàng trên thiết bị di động
        Route::get('get_banner', 'pc.HomeController/getBanner')->name('getBanner')->option(['real_name' => 'PCBăng chuyền trang chủ']);//PCBăng chuyền trang chủ
        Route::get('get_category_product', 'pc.HomeController/getCategoryProduct')->name('getCategoryProduct')->option(['real_name' => 'Home thể loại Sản phẩm thời trang']);//Home thể loại Sản phẩm thời trang
        Route::get('get_products', 'pc.ProductController/getProductList')->name('getProductList')->option(['real_name' => 'Danh sách sản phẩm']);//Danh sách sản phẩm
        Route::get('get_product_code/:product_id/[:type]', 'pc.ProductController/getProductRoutineCode')->name('getProductRoutineCode')->option(['real_name' => 'Chi tiết sản phẩm chương trình mini QR code']);//Chi tiết sản phẩm chương trình mini QR code
        Route::get('get_city/:pid', 'pc.PublicController/getCity')->name('getCity')->option(['real_name' => 'Nhận dữ liệu thành phố']);//Nhận dữ liệu thành phố
        Route::get('check_order_status/:order_id/:end_time', 'pc.OrderController/checkOrderStatus')->name('checkOrderStatus')->option(['real_name' => 'Giao diện trạng thái lệnh bỏ phiếu']);//Giao diện trạng thái lệnh bỏ phiếu
        Route::get('get_company_info', 'pc.PublicController/getCompanyInfo')->name('getCompanyInfo')->option(['real_name' => 'Nhận thông tin công ty']);//Nhận thông tin công ty
        Route::get('get_recommend/:type', 'pc.ProductController/getRecommendList')->name('getRecommendList')->option(['real_name' => 'Nhận sản phẩm được đề xuất']);//Nhận sản phẩm được đề xuất
        Route::get('get_wechat_qrcode', 'pc.PublicController/getWechatQrcode')->name('getWechatQrcode')->option(['real_name' => 'Thu hút sự chú ý bằng mã QR']);//Thu hút sự chú ý bằng mã QR
        Route::get('get_good_product', 'pc.ProductController/getGoodProduct')->name('getGoodProduct')->option(['real_name' => 'Nhận đề xuất sản phẩm']);//Nhận đề xuất sản phẩm
        Route::get('get_news_category', 'pc.PublicController/getNewsCategory')->name('getNewsCategory')->option(['real_name' => 'Nhận phân loại bài viết']);//Nhận phân loại bài viết
        Route::get('get_news_list', 'pc.PublicController/getNewsList')->name('getNewsList')->option(['real_name' => 'Nhận danh sách bài viết']);//Nhận danh sách bài viết
        Route::get('get_news_detail/:id', 'pc.PublicController/getNewsDetail')->name('getNewsDetail')->option(['real_name' => 'Nhận chi tiết bài viết']);//Nhận chi tiết bài viết
    })->middleware(\app\http\middleware\AllowOriginMiddleware::class)
        ->middleware(\app\api\middleware\StationOpenMiddleware::class)
        ->middleware(\app\api\middleware\AuthTokenMiddleware::class, false)
        ->option(['parent' => 'PC', 'cate_name' => 'Giao diện trái phép của người dùng']);

    //Giao diện phân quyền thành viên
    Route::group(function () {
        Route::get('get_cart_list', 'pc.CartController/getCartList')->name('getCartList')->option(['real_name' => 'Danh sách giỏ hàng']);//Danh sách giỏ hàng
        Route::get('get_balance_record/:type', 'pc.UserController/getBalanceRecord')->name('getBalanceRecord')->option(['real_name' => 'Hồ sơ số dư']);//Hồ sơ số dư
        Route::get('get_order_list', 'pc.OrderController/getOrderList')->name('getOrderList')->option(['real_name' => 'danh sách đặt hàng']);//danh sách đặt hàng
        Route::get('get_refund_order_list', 'pc.OrderController/getRefundOrderList')->name('getRefundOrderList')->option(['real_name' => 'Danh sách đơn hàng hoàn tiền']);//Danh sách đơn hàng hoàn tiền
        Route::get('get_collect_list', 'pc.UserController/getCollectList')->name('getCollectList')->option(['real_name' => 'danh sách yêu thích']);//danh sách yêu thích
    })->middleware(\app\http\middleware\AllowOriginMiddleware::class)
        ->middleware(\app\api\middleware\StationOpenMiddleware::class)
        ->middleware(\app\api\middleware\AuthTokenMiddleware::class, true)
        ->option(['parent' => 'PC', 'cate_name' => 'Giao diện phân quyền người dùng']);

    Route::miss(function () {
        if (app()->request->isOptions()) {
            $header = Config::get('cookie.header');
            unset($header['Access-Control-Allow-Credentials']);
            return Response::create('ok')->code(200)->header($header);
        } else
            return Response::create()->code(404);
    });
})->middleware(\app\http\middleware\AllowOriginMiddleware::class)
    ->middleware(\app\api\middleware\StationOpenMiddleware::class)
    ->option(['mark' => 'PC', 'mark_name' => 'PC']);
