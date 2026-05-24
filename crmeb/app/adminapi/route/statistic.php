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
 * Định tuyến liên quan đến quản lý phân phối
 */Route::group('statistic', function () {

    /** Thống kê Khách hàng */    Route::group(function () {
        //Cơ sở Khách hàng
        Route::get('user/get_basic', 'v1.statistic.UserStatistic/getBasic')->option(['real_name' => 'Thống kê cơ bản của Khách hàng']);
        //Xu hướng tăng trưởng Khách hàng
        Route::get('user/get_trend', 'v1.statistic.UserStatistic/getTrend')->option(['real_name' => 'Xu hướng tăng trưởng Khách hàng']);
        //Người dùng WeChat
        Route::get('user/get_wechat', 'v1.statistic.UserStatistic/getWechat')->option(['real_name' => 'Thống kê Khách hàng WeChat']);
        //Xu hướng tăng trưởng Khách hàng WeChat
        Route::get('user/get_wechat_trend', 'v1.statistic.UserStatistic/getWechatTrend')->option(['real_name' => 'Xu hướng tăng trưởng Khách hàng WeChat']);
        //Xếp hạng địa lý của Khách hàng
        Route::get('user/get_region', 'v1.statistic.UserStatistic/getRegion')->option(['real_name' => 'Xếp hạng địa lý của Khách hàng']);
        //Giới tính Khách hàng
        Route::get('user/get_sex', 'v1.statistic.UserStatistic/getSex')->option(['real_name' => 'Tiếp thị liên kết giới tính Khách hàng']);
        //Xuất dữ liệu sản phẩm
        Route::get('user/get_excel', 'v1.statistic.UserStatistic/getExcel')->option(['real_name' => 'Xuất dữ liệu Khách hàng']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Thống kê Khách hàng']);

    /** Thống kê sản phẩm */    Route::group(function () {
        //cơ sở sản phẩm
        Route::get('product/get_basic', 'v1.statistic.ProductStatistic/getBasic')->option(['real_name' => 'Thống kê sản phẩm cơ bản']);
        //Xu hướng sản phẩm
        Route::get('product/get_trend', 'v1.statistic.ProductStatistic/getTrend')->option(['real_name' => 'Xu hướng sản phẩm']);
        //Xếp hạng sản phẩm
        Route::get('product/get_product_ranking', 'v1.statistic.ProductStatistic/getProductRanking')->option(['real_name' => 'Xếp hạng sản phẩm']);
        //Xuất dữ liệu sản phẩm
        Route::get('product/get_excel', 'v1.statistic.ProductStatistic/getExcel')->option(['real_name' => 'Xuất dữ liệu sản phẩm']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Thống kê sản phẩm']);

    /** Thống kê giao dịch */    Route::group(function () {
        //Thống kê doanh thu ngày hôm nay
        Route::get('trade/top_trade', 'v1.statistic.TradeStatistic/topTrade')->option(['real_name' => 'Thống kê doanh thu ngày hôm nay']);
        Route::get('trade/bottom_trade', 'v1.statistic.TradeStatistic/bottomTrade')->option(['real_name' => 'Thống kê giao dịch dữ liệu đáy']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Thống kê giao dịch']);

    /** Thống kê đơn hàng */    Route::group(function () {
        //Cơ sở đặt hàng
        Route::get('order/get_basic', 'v1.statistic.OrderStatistic/getBasic')->option(['real_name' => 'Thống kê đơn hàng cơ bản']);
        //Xu hướng đặt hàng
        Route::get('order/get_trend', 'v1.statistic.OrderStatistic/getTrend')->option(['real_name' => 'Xu hướng đặt hàng']);
        //Nguồn đặt hàng
        Route::get('order/get_channel', 'v1.statistic.OrderStatistic/getChannel')->option(['real_name' => 'Nguồn đặt hàng']);
        //Loại đơn hàng
        Route::get('order/get_type', 'v1.statistic.OrderStatistic/getType')->option(['real_name' => 'Loại đơn hàng']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Thống kê đơn hàng']);

    /** Dòng tiền */    Route::group(function () {
        Route::get('flow/get_list', 'v1.statistic.FlowStatistic/getFlowList')->option(['real_name' => 'Dòng tiền']);
        Route::post('flow/set_mark/:id', 'v1.statistic.FlowStatistic/setMark')->option(['real_name' => 'Đặt ghi chú']);
        Route::get('flow/get_record', 'v1.statistic.FlowStatistic/getFlowRecord')->option(['real_name' => 'Lịch sử thanh toán']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Dòng tiền']);

    /** Thống kê số dư */    Route::group(function () {
        //Cân bằng số liệu thống kê cơ bản
        Route::get('balance/get_basic', 'v1.statistic.BalanceStatistic/getBasic')->option(['real_name' => 'Cân bằng số liệu thống kê cơ bản']);
        //Xu hướng cân bằng
        Route::get('balance/get_trend', 'v1.statistic.BalanceStatistic/getTrend')->option(['real_name' => 'Xu hướng cân bằng']);
        //Nguồn cân bằng
        Route::get('balance/get_channel', 'v1.statistic.BalanceStatistic/getChannel')->option(['real_name' => 'Nguồn cân bằng']);
        //Cân bằng tiêu dùng
        Route::get('balance/get_type', 'v1.statistic.BalanceStatistic/getType')->option(['real_name' => 'Cân bằng tiêu dùng']);
    })->option(['parent' => 'statistic', 'cate_name' => 'Thống kê số dư']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'statistic', 'mark_name' => 'thống kê chương trình']);
