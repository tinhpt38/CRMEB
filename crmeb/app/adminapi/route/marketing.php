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
 * Phiếu giảm giá, thương lượng, mua theo nhóm, định tuyến bán hàng chớp nhoáng
 */
Route::group('marketing', function () {

    /** Phiếu giảm giá */
    Route::group(function () {
        //Danh sách phiếu giảm giá đã xuất bản
        Route::get('coupon/released', 'v1.marketing.StoreCouponIssue/index')->option(['real_name' => 'Danh sách phiếu giảm giá đã xuất bản']);
        //thêm phiếu giảm giá
        Route::post('coupon/save_coupon', 'v1.marketing.StoreCouponIssue/saveCoupon')->option(['real_name' => 'Tạo mã giảm giá']);
        //Sửa đổi trạng thái phiếu giảm giá
        Route::get('coupon/status/:id/:status', 'v1.marketing.StoreCouponIssue/status')->option(['real_name' => 'Sửa đổi trạng thái phiếu giảm giá']);
        //Sao chép phiếu giảm giá bằng một cú nhấp chuột
        Route::get('coupon/copy/:id', 'v1.marketing.StoreCouponIssue/copy')->option(['real_name' => 'Sao chép phiếu giảm giá bằng một cú nhấp chuột']);
        //Gửi danh sách phiếu giảm giá
        Route::get('coupon/grant', 'v1.marketing.StoreCouponIssue/index')->option(['real_name' => 'Gửi danh sách phiếu giảm giá']);
        //Đã xóa phiếu giảm giá đã xuất bản
        Route::delete('coupon/released/:id', 'v1.marketing.StoreCouponIssue/delete')->option(['real_name' => 'Đã xóa phiếu giảm giá đã xuất bản']);
        //Biểu mẫu trạng thái sửa đổi phiếu giảm giá đã xuất bản
        Route::get('coupon/released/:id/status', 'v1.marketing.StoreCouponIssue/edit')->option(['real_name' => 'Biểu mẫu trạng thái sửa đổi phiếu giảm giá đã xuất bản']);
        //Trạng thái sửa đổi phiếu giảm giá đã được đăng
        Route::put('coupon/released/status/:id', 'v1.marketing.StoreCouponIssue/status')->option(['real_name' => 'Trạng thái sửa đổi phiếu giảm giá đã được đăng']);
        //Kỷ lục thu thập phiếu giảm giá đã được phát hành
        Route::get('coupon/released/issue_log/:id', 'v1.marketing.StoreCouponIssue/issue_log')->option(['real_name' => 'Kỷ lục thu thập phiếu giảm giá đã được phát hành']);
        //Hồ sơ thu thập thành viên
        Route::get('coupon/user', 'v1.marketing.StoreCouponUser/index')->option(['real_name' => 'Hồ sơ thu thập thành viên']);
        //Gửi phiếu giảm giá
        Route::post('coupon/user/grant', 'v1.marketing.StoreCouponUser/grant')->option(['real_name' => 'Tặng mã giảm giá']);
    })->option(['parent' => 'marketing', 'cate_name' => 'Mã giảm giá']);

    /** Hoạt động mặc cả */
    Route::group(function () {
        //Danh sách sản phẩm mặc cả
        Route::get('bargain', 'v1.marketing.StoreBargain/index')->option(['real_name' => 'Danh sách sản phẩm mặc cả']);
        //Chi tiết mặc cả
        Route::get('bargain/:id', 'v1.marketing.StoreBargain/read')->option(['real_name' => 'Chi tiết sản phẩm khuyến mại']);
        //Lưu, thêm hoặc chỉnh sửa giá hời
        Route::post('bargain/:id', 'v1.marketing.StoreBargain/save')->option(['real_name' => 'Thêm hoặc chỉnh sửa các Sản phẩm trả giá']);
        //Xóa món hời
        Route::delete('bargain/:id', 'v1.marketing.StoreBargain/delete')->option(['real_name' => 'Xóa các Sản phẩm trả giá']);
        //Sửa đổi trạng thái thương lượng
        Route::put('bargain/set_status/:id/:status', 'v1.marketing.StoreBargain/set_status')->option(['real_name' => 'Sửa đổi trạng thái mặt hàng mặc cả']);
        //Danh sách mặc cả
        Route::get('bargain_list', 'v1.marketing.StoreBargain/bargainList')->option(['real_name' => 'Tham gia vào danh sách thương lượng']);
        //Danh sách người thương lượng
        Route::get('bargain_list_info/:id', 'v1.marketing.StoreBargain/bargainListInfo')->option(['real_name' => 'Danh sách người thương lượng']);
        //Thống kê mặc cả
        Route::get('bargain/statistics/head/:id', 'v1.marketing.StoreBargain/bargainStatistics')->option(['real_name' => 'Thống kê mặc cả']);
        //Danh sách mặc cả
        Route::get('bargain/statistics/list/:id', 'v1.marketing.StoreBargain/bargainStatisticsList')->option(['real_name' => 'Danh sách thống kê thương lượng']);
        //lệnh mặc cả
        Route::get('bargain/statistics/order/:id', 'v1.marketing.StoreBargain/bargainStatisticsOrder')->option(['real_name' => 'Thống kê thương lượng lệnh']);
    })->option(['parent' => 'marketing', 'cate_name' => 'Hoạt động mặc cả']);

    /** Hoạt động nhóm */
    Route::group(function () {
        //Danh sách sản phẩm nhóm
        Route::get('combination', 'v1.marketing.StoreCombination/index')->option(['real_name' => 'Danh sách sản phẩm nhóm']);
        //Thống kê nhóm nhóm
        Route::get('combination/statistics', 'v1.marketing.StoreCombination/statistics')->option(['real_name' => 'Thống kê sản phẩm nhóm']);
        //Chi tiết sản phẩm nhóm
        Route::get('combination/:id', 'v1.marketing.StoreCombination/read')->option(['real_name' => 'Chi tiết sản phẩm nhóm']);
        //Lưu Tân Cương hoặc chỉnh sửa
        Route::post('combination/:id', 'v1.marketing.StoreCombination/save')->option(['real_name' => 'Thêm hoặc chỉnh sửa sản phẩm nhóm']);
        //xóa bỏ
        Route::delete('combination/:id', 'v1.marketing.StoreCombination/delete')->option(['real_name' => 'Xóa sản phẩm nhóm']);
        //Sửa đổi trạng thái nhóm nhóm
        Route::put('combination/set_status/:id/:status', 'v1.marketing.StoreCombination/set_status')->option(['real_name' => 'Sửa đổi trạng thái sản phẩm của nhóm']);
        //Danh sách nhóm nhóm
        Route::get('combination/combine/list', 'v1.marketing.StoreCombination/combine_list')->option(['real_name' => 'Tham gia vào danh sách chia sẻ nhóm']);
        //Danh sách những người tham gia nhóm
        Route::get('combination/order_pink/:id', 'v1.marketing.StoreCombination/order_pink')->option(['real_name' => 'Danh sách những người tham gia nhóm']);
        //Thống kê nhóm nhóm
        Route::get('combination/statistics/head/:id', 'v1.marketing.StoreCombination/combinationStatistics')->option(['real_name' => 'Thống kê nhóm nhóm']);
        //Danh sách nhóm nhóm
        Route::get('combination/statistics/list/:id', 'v1.marketing.StoreCombination/combinationStatisticsList')->option(['real_name' => 'Danh sách thống kê nhóm nhóm']);
        //Thứ tự nhóm
        Route::get('combination/statistics/order/:id', 'v1.marketing.StoreCombination/combinationStatisticsOrder')->option(['real_name' => 'Thống kê đơn hàng nhóm']);
        //Lập nhóm ngay
        Route::get('combination/immediately/:id', 'v1.marketing.StoreCombination/immediatelyCombination')->option(['real_name' => 'Lập nhóm ngay']);

    })->option(['parent' => 'marketing', 'cate_name' => 'Hoạt động nhóm']);

    /** hoạt động flash sale */
    Route::group(function () {
        //danh sách bán chớp nhoáng
        Route::get('seckill', 'v1.marketing.StoreSeckill/index')->option(['real_name' => 'Danh sách sản phẩm Flashsale']);
        //Danh sách khoảng thời gian flash sale
        Route::get('seckill/time_list', 'v1.marketing.StoreSeckill/time_list')->option(['real_name' => 'Danh sách khoảng thời gian flash sale']);
        //Chi tiết khuyến mại chớp nhoáng
        Route::get('seckill/:id', 'v1.marketing.StoreSeckill/read')->option(['real_name' => 'Chi tiết sản phẩm Flashsale']);
        //Tắt flash, lưu, thêm hoặc chỉnh sửa
        Route::post('seckill/:id', 'v1.marketing.StoreSeckill/save')->option(['real_name' => 'Thêm hoặc chỉnh sửa sản phẩm flash sale']);
        //Xóa flash kill
        Route::delete('seckill/:id', 'v1.marketing.StoreSeckill/delete')->option(['real_name' => 'Xóa các mặt hàng flash sale']);
        //Sửa đổi trạng thái flash sale
        Route::put('seckill/set_status/:id/:status', 'v1.marketing.StoreSeckill/set_status')->option(['real_name' => 'Sửa đổi trạng thái sản phẩm Flash Sale']);
        //Thống kê tiêu diệt chớp nhoáng
        Route::get('seckill/statistics/head/:id', 'v1.marketing.StoreSeckill/seckillStatistics')->option(['real_name' => 'Thống kê tiêu diệt chớp nhoáng']);
        //Người tham gia
        Route::get('seckill/statistics/people/:id', 'v1.marketing.StoreSeckill/seckillPeople')->option(['real_name' => 'Flash kill người tham gia']);
        //Đơn hàng flash sale
        Route::get('seckill/statistics/order/:id', 'v1.marketing.StoreSeckill/seckillOrder')->option(['real_name' => 'Flash kill người tham gia']);

        Route::get('seckill_activity/list', 'v1.marketing.StoreSeckill/seckillActivityList')->option(['real_name' => 'Danh sách hoạt động flash sale']);
        Route::get('seckill_activity/info/:id', 'v1.marketing.StoreSeckill/seckillActivityInfo')->option(['real_name' => 'Chi tiết sự kiện flash sale']);
        Route::post('seckill_activity/save/:id', 'v1.marketing.StoreSeckill/seckillActivitySave')->option(['real_name' => 'Thêm hoặc sửa đổi hoạt động flash sale']);
        Route::delete('seckill_activity/del/:id', 'v1.marketing.StoreSeckill/seckillActivityDel')->option(['real_name' => 'Xóa hoạt động flash sale']);
        Route::put('seckill_activity/status/:id/:status', 'v1.marketing.StoreSeckill/seckillActivityStatus')->option(['real_name' => 'Sửa đổi trạng thái hoạt động flash sale']);



    })->option(['parent' => 'marketing', 'cate_name' => 'hoạt động flash sale']);

    /** Hoạt động điểm */
    Route::group(function () {
        //Danh sách nhật ký điểm
        Route::get('integral', 'v1.marketing.UserPoint/index')->option(['real_name' => 'Danh sách nhật ký điểm']);
        //Dữ liệu tiêu đề nhật ký điểm
        Route::get('integral/statistics', 'v1.marketing.UserPoint/integral_statistics')->option(['real_name' => 'Dữ liệu tiêu đề nhật ký điểm']);
        //Biểu mẫu chỉnh sửa cấu hình điểm
        Route::get('integral_config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => 'Biểu mẫu chỉnh sửa cấu hình điểm']);
        //Cấu hình điểm lưu dữ liệu
        Route::post('integral_config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => 'Cấu hình điểm lưu dữ liệu']);
        //Danh sách sản phẩm điểm
        Route::get('integral_product', 'v1.marketing.integral.StoreIntegral/index')->option(['real_name' => 'Danh sách sản phẩm điểm']);
        //Thêm hoặc chỉnh sửa sản phẩm điểm
        Route::post('integral/:id', 'v1.marketing.integral.StoreIntegral/save')->option(['real_name' => 'Thêm hoặc chỉnh sửa sản phẩm điểm']);
        //Chi tiết sản phẩm điểm
        Route::get('integral/:id', 'v1.marketing.integral.StoreIntegral/read')->option(['real_name' => 'Chi tiết sản phẩm điểm']);
        //Xóa sản phẩm điểm
        Route::delete('integral/:id', 'v1.marketing.integral.StoreIntegral/delete')->option(['real_name' => 'Xóa sản phẩm điểm']);
        //Sửa đổi trạng thái sản phẩm điểm
        Route::put('integral/set_show/:id/:is_show', 'v1.marketing.integral.StoreIntegral/set_show')->option(['real_name' => 'Sửa đổi trạng thái sản phẩm điểm']);
        //Danh sách đặt hàng trung tâm điểm
        Route::get('integral/order/list', 'v1.marketing.integral.StoreIntegralOrder/lst')->option(['real_name' => 'Danh sách đặt hàng trung tâm điểm']);
        //Dữ liệu đặt hàng của trung tâm điểm
        Route::get('integral/order/chart', 'v1.marketing.integral.StoreIntegralOrder/chart')->option(['real_name' => 'Dữ liệu đặt hàng của trung tâm điểm']);
        //Dữ liệu chi tiết đơn hàng của trung tâm mua sắm Points
        Route::get('integral/order/info/:id', 'v1.marketing.integral.StoreIntegralOrder/order_info')->option(['real_name' => 'Dữ liệu chi tiết đơn hàng của trung tâm mua sắm Points']);
        //Sửa đổi thông tin nhận xét đơn hàng sản phẩm điểm
        Route::put('integral/order/remark/:id', 'v1.marketing.integral.StoreIntegralOrder/remark')->option(['real_name' => 'Sửa đổi thông tin nhận xét đơn hàng sản phẩm điểm']);
        //Nhận trạng thái thứ tự điểm
        Route::get('integral/order/status/:id', 'v1.marketing.integral.StoreIntegralOrder/status')->option(['real_name' => 'Nhận trạng thái thứ tự điểm']);
        //Xóa thứ tự điểm
        Route::delete('integral/order/del/:id', 'v1.marketing.integral.StoreIntegralOrder/del')->option(['real_name' => 'Xóa thứ tự điểm']);
        //Đơn đặt hàng điểm vận chuyển
        Route::put('integral/order/delivery/:id', 'v1.marketing.integral.StoreIntegralOrder/update_delivery')->option(['real_name' => 'Đơn đặt hàng điểm vận chuyển']);
        //Nhận mẫu thông tin giao hàng đặt hàng điểm
        Route::get('integral/order/distribution/:id', 'v1.marketing.integral.StoreIntegralOrder/distribution')->option(['real_name' => 'Nhận mẫu thông tin giao hàng đặt hàng điểm']);
        //Sửa đổi thông tin giao hàng của đơn hàng điểm
        Route::put('integral/order/distribution/:id', 'v1.marketing.integral.StoreIntegralOrder/update_distribution')->option(['real_name' => 'Sửa đổi thông tin giao hàng của đơn hàng điểm']);
        //Biên nhận xác nhận đơn hàng điểm
        Route::put('integral/order/take/:id', 'v1.marketing.integral.StoreIntegralOrder/take_delivery')->option(['real_name' => 'Biên nhận xác nhận đơn hàng điểm']);
        //Công ty hậu cần mua lại đơn hàng điểm
        Route::get('integral/order/express_list', 'v1.marketing.integral.StoreIntegralOrder/express')->option(['real_name' => 'Công ty hậu cần mua lại đơn hàng điểm']);
        //Mẫu biểu mẫu điện tử của công ty chuyển phát nhanh điểm
        Route::get('integral/order/express/temp', 'v1.marketing.integral.StoreIntegralOrder/express_temp')->option(['real_name' => 'Mẫu biểu mẫu điện tử của công ty chuyển phát nhanh điểm']);
        //Nhận thông tin hậu cần cho đơn đặt hàng điểm
        Route::get('integral/order/express/:id', 'v1.marketing.integral.StoreIntegralOrder/get_express')->option(['real_name' => 'Nhận thông tin hậu cần cho đơn đặt hàng điểm']);
        //Thứ tự điểm in
        Route::get('integral/order/print/:id', 'v1.marketing.integral.StoreIntegralOrder/order_print')->option(['real_name' => 'Đơn hàng điểm in']);
        //Nhận người giao hàng từ danh sách đặt hàng điểm
        Route::get('integral/order/delivery/list', 'v1.order.DeliveryService/get_delivery_list')->option(['real_name' => 'Nhận người giao hàng từ danh sách đặt hàng điểm']);
        //Thứ tự điểm lấy thông tin cấu hình mặc định của thứ tự khuôn mặt
        Route::get('integral/order/sheet_info', 'v1.marketing.integral.StoreIntegralOrder/getDeliveryInfo')->option(['real_name' => 'Đơn hàng điểm lấy thông tin cấu hình mặc định của thứ tự khuôn mặt']);
        //Kỷ lục điểm
        Route::get('point_record', 'v1.marketing.integral.StorePointRecord/pointRecord')->option(['real_name' => 'Danh sách ghi điểm']);
        Route::post('point_record/remark/:id', 'v1.marketing.integral.StorePointRecord/pointRecordRemark')->option(['real_name' => 'Ghi chú danh sách ghi điểm']);
        Route::get('point/get_basic', 'v1.marketing.integral.StorePointRecord/getBasic')->option(['real_name' => 'Thông tin cơ bản về thống kê điểm']);
        Route::get('point/get_trend', 'v1.marketing.integral.StorePointRecord/getTrend')->option(['real_name' => 'Biểu đồ xu hướng thống kê điểm']);
        //Thống kê nguồn điểm
        Route::get('point/get_channel', 'v1.marketing.integral.StorePointRecord/getChannel')->option(['real_name' => 'Thống kê nguồn điểm']);
        //Thống kê tiêu thụ điểm
        Route::get('point/get_type', 'v1.marketing.integral.StorePointRecord/getType')->option(['real_name' => 'Thống kê tiêu thụ điểm']);
    })->option(['parent' => 'marketing', 'cate_name' => 'Hoạt động điểm']);

    /** rút thăm trúng thưởng */
    Route::group(function () {
        //Danh sách rút thăm trúng thưởng
        Route::get('lottery/list', 'v1.marketing.lottery.LuckLottery/index')->option(['real_name' => 'Danh sách rút thăm trúng thưởng']);
        //Chi tiết rút thăm may mắn
        Route::get('lottery/detail/:id', 'v1.marketing.lottery.LuckLottery/detail')->option(['real_name' => 'Chi tiết rút thăm may mắn']);
        //Thêm rút thăm trúng thưởng
        Route::post('lottery/add', 'v1.marketing.lottery.LuckLottery/add')->option(['real_name' => 'Thêm rút thăm trúng thưởng']);
        //Sửa đổi dữ liệu rút thăm trúng thưởng
        Route::put('lottery/edit/:id', 'v1.marketing.lottery.LuckLottery/edit')->option(['real_name' => 'Sửa đổi dữ liệu rút thăm trúng thưởng']);
        //Xóa rút thăm trúng thưởng
        Route::delete('lottery/del/:id', 'v1.marketing.lottery.LuckLottery/delete')->option(['real_name' => 'Xóa rút thăm trúng thưởng']);
        //Đặt xem xổ số có được hiển thị hay không
        Route::put('lottery/set_status/:id/:status', 'v1.marketing.lottery.LuckLottery/setStatus')->option(['real_name' => 'Đặt xem xổ số có được hiển thị hay không']);
        //Danh sách kỷ lục xổ số
        Route::get('lottery/record/list', 'v1.marketing.lottery.LuckLotteryRecord/index')->option(['real_name' => 'Danh sách kỷ lục xổ số']);
        //Trao giải thưởng xổ số và xử lý nhận xét
        Route::post('lottery/record/deliver', 'v1.marketing.lottery.LuckLotteryRecord/deliver')->option(['real_name' => 'Trao giải thưởng xổ số và xử lý nhận xét']);
        //Danh sách xổ số hạng mục
        Route::get('lottery/factor/list', 'v1.marketing.lottery.LuckLottery/factorList')->option(['real_name' => 'Danh sách xổ số hạng mục']);
        //Lưu cấu hình xổ số
        Route::post('lottery/factor/use', 'v1.marketing.lottery.LuckLottery/factorUse')->option(['real_name' => 'Lưu trạng thái sử dụng xổ số']);

    })->option(['parent' => 'marketing', 'cate_name' => 'rút thăm trúng thưởng']);

    /** Nhận phòng hàng ngày */
    Route::group(function () {
        //Danh sách phần thưởng đăng nhập
        Route::get('sign/rewards', 'v1.marketing.SignRewards/index')->option(['real_name' => 'Danh sách phần thưởng đăng nhập']);
        //Thêm phần thưởng đăng nhập
        Route::get('sign/add_rewards', 'v1.marketing.SignRewards/addRewards')->option(['real_name' => 'Thêm phần thưởng đăng nhập']);
        //Phần thưởng đăng nhập của biên tập viên
        Route::get('sign/edit_rewards/:id', 'v1.marketing.SignRewards/editRewards')->option(['real_name' => 'Lưu phần thưởng đăng nhập']);
        //Lưu phần thưởng đăng nhập
        Route::post('sign/save_rewards/:id', 'v1.marketing.SignRewards/saveRewards')->option(['real_name' => 'Lưu phần thưởng đăng nhập']);
        //Xóa phần thưởng đăng nhập
        Route::delete('sign/del_rewards/:id', 'v1.marketing.SignRewards/delRewards')->option(['real_name' => 'Xóa phần thưởng đăng nhập']);
    })->option(['parent' => 'marketing', 'cate_name' => 'Điểm danh nhận quà']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'marketing', 'mark_name' => 'Hoạt động tiếp thị']);
