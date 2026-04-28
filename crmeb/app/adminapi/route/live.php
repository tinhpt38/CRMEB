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
 * Định tuyến liên quan đến phát sóng trực tiếp
 */
Route::group('live', function () {

    /** Neo */
    Route::group(function () {
        //Danh sách neo
        Route::get('anchor/list', 'v1.marketing.live.LiveAnchor/list')->option(['real_name' => 'Danh sách neo']);
        //Thêm và sửa đổi biểu mẫu máy chủ
        Route::get('anchor/add/:id', 'v1.marketing.live.LiveAnchor/add')->option(['real_name' => 'Thêm và sửa đổi biểu mẫu máy chủ']);
        //Lưu dữ liệu neo
        Route::post('anchor/save', 'v1.marketing.live.LiveAnchor/save')->option(['real_name' => 'Lưu dữ liệu neo']);
        //Xóa mỏ neo
        Route::delete('anchor/del/:id', 'v1.marketing.live.LiveAnchor/delete')->option(['real_name' => 'Xóa mỏ neo']);
        //Đặt xem có hiển thị hay không
        Route::get('anchor/set_show/:id/:is_show', 'v1.marketing.live.LiveAnchor/setShow')->option(['real_name' => 'Đặt xem neo có được hiển thị hay không']);
    })->option(['parent' => 'live', 'cate_name' => 'Neo']);

    /** Sản phẩm sống */
    Route::group(function () {
        //Danh sách sản phẩm trực tiếp
        Route::get('goods/list', 'v1.marketing.live.LiveGoods/list')->option(['real_name' => 'Danh sách sản phẩm trực tiếp']);
        //Tạo sản phẩm phát sóng trực tiếp
        Route::post('goods/create', 'v1.marketing.live.LiveGoods/create')->option(['real_name' => 'Tạo sản phẩm phát sóng trực tiếp']);
        //Thêm và sửa đổi sản phẩm
        Route::post('goods/add', 'v1.marketing.live.LiveGoods/add')->option(['real_name' => 'Thêm và sửa đổi các sản phẩm phát sóng trực tiếp']);
        //Chi tiết sản phẩm
        Route::get('goods/detail/:id', 'v1.marketing.live.LiveGoods/detail')->option(['real_name' => 'Chi tiết sản phẩm trực tiếp']);
        //Đánh giá sản phẩm
        Route::get('goods/audit/:id', 'v1.marketing.live.LiveGoods/audit')->option(['real_name' => 'Đánh giá sản phẩm trực tiếp']);
        //Đánh giá rút sản phẩm
        Route::get('goods/resestAudit/:id', 'v1.marketing.live.LiveGoods/resetAudit')->option(['real_name' => 'Đánh giá rút sản phẩm trực tiếp']);
        //Xóa sản phẩm
        Route::delete('goods/del/:id', 'v1.marketing.live.LiveGoods/delete')->option(['real_name' => 'Xóa sản phẩm trực tiếp']);
        //Đặt xem có hiển thị hay không
        Route::get('goods/set_show/:id/:is_show', 'v1.marketing.live.liveGoods/setShow')->option(['real_name' => 'Đặt xem các sản phẩm phát sóng trực tiếp có được hiển thị hay không']);
        //Đồng bộ hóa trạng thái sản phẩm trực tiếp
        Route::get('goods/syncGoods', 'v1.marketing.live.liveGoods/syncGoods')->option(['real_name' => 'Đồng bộ hóa trạng thái sản phẩm trực tiếp']);
    })->option(['parent' => 'live', 'cate_name' => 'Sản phẩm sống']);

    /** phòng neo */
    Route::group(function () {
        //Danh sách phòng phát sóng trực tiếp
        Route::get('room/list', 'v1.marketing.live.LiveRoom/list')->option(['real_name' => 'Danh sách phòng phát sóng trực tiếp']);
        //Thêm phòng phát sóng trực tiếp
        Route::post('room/add', 'v1.marketing.live.LiveRoom/add')->option(['real_name' => 'Thêm phòng phát sóng trực tiếp']);
        //Chi tiết phòng phát sóng trực tiếp
        Route::get('room/detail/:id', 'v1.marketing.live.LiveRoom/detail')->option(['real_name' => 'Chi tiết phòng phát sóng trực tiếp']);
        //Thêm sản phẩm vào phòng phát sóng trực tiếp
        Route::post('room/add_goods', 'v1.marketing.live.LiveRoom/addGoods')->option(['real_name' => 'Thêm sản phẩm vào phòng phát sóng trực tiếp']);
        //Xóa chương trình phát sóng trực tiếp
        Route::delete('room/del/:id', 'v1.marketing.live.LiveRoom/delete')->option(['real_name' => 'Xóa phòng trực tiếp']);
        //Đặt xem có hiển thị hay không
        Route::get('room/set_show/:id/:is_show', 'v1.marketing.live.LiveRoom/setShow')->option(['real_name' => 'Đặt xem phòng phát sóng trực tiếp có được hiển thị hay không']);
        //Đồng bộ trạng thái phòng live
        Route::get('room/syncRoom', 'v1.marketing.live.LiveRoom/syncRoom')->option(['real_name' => 'Đồng bộ trạng thái phòng live']);
    })->option(['parent' => 'live', 'cate_name' => 'Phòng phát sóng trực tiếp']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'live', 'mark_name' => 'Livestream']);
