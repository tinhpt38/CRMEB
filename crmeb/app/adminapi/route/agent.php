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
 */
Route::group('agent', function () {

    /** Quản lý nhà phân phối */
    Route::group(function () {
        //danh sách nhân viên bán hàng
        Route::get('index', 'v1.agent.AgentManage/index')->option(['real_name' => 'Danh sách nhà phân phối']);
        //Sửa đổi trình quảng bá ưu việt
        Route::put('spread', 'v1.agent.AgentManage/editSpread')->option(['real_name' => 'Đổi người giới thiệu']);
        //Thống kê đầu
        Route::get('statistics', 'v1.agent.AgentManage/get_badge')->option(['real_name' => 'Thống kê đầu danh sách nhà phân phối']);
        //Danh sách nhà quảng bá
        Route::get('stair', 'v1.agent.AgentManage/get_stair_list')->option(['real_name' => 'Danh sách người giới thiệu']);
        //Thống kê danh sách đơn hàng khuyến mãi
        Route::get('stair/order', 'v1.agent.AgentManage/get_stair_order_list')->option(['real_name' => 'Danh sách đơn hàng khuyến mãi']);
        //Rõ ràng các nhà quảng bá vượt trội
        Route::put('stair/delete_spread/:uid', 'v1.agent.AgentManage/delete_spread')->option(['real_name' => 'Rõ ràng các nhà quảng bá vượt trội']);
        //Bị loại khỏi chương trình khuyến mãi
        Route::put('stair/delete_system_spread/:uid', 'v1.agent.AgentManage/delete_system_spread')->option(['real_name' => 'Hủy tư cách Affiliate mãi']);
        //Xem mã QR để khuyến mãi tài khoản công cộng
        Route::get('look_code', 'v1.agent.AgentManage/look_code')->option(['real_name' => 'Xem mã QR để khuyến mãi tài khoản công cộng']);
        //Xem mã QR khuyến mãi chương trình mini
        Route::get('look_xcx_code', 'v1.agent.AgentManage/look_xcx_code')->option(['real_name' => 'Xem mã QR khuyến mãi chương trình mini']);
        //Xem mã QR khuyến mãi H5
        Route::get('look_h5_code', 'v1.agent.AgentManage/look_h5_code')->option(['real_name' => 'Xem mã QR khuyến mãi H5']);
    })->option(['parent' => 'agent', 'cate_name' => 'Quản lý Affiliate']);

    /** Cài đặt phân phối */
    Route::group(function () {
        //Biểu mẫu chỉnh sửa cấu hình phân phối
        Route::get('config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => 'Biểu mẫu chỉnh sửa cấu hình điểm']);
        //Cấu hình phân phối lưu dữ liệu
        Route::post('config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => 'Cấu hình điểm lưu dữ liệu']);
    })->option(['parent' => 'agent', 'cate_name' => 'Cài đặt Affiliate']);

    /** Cấp độ phân phối */
    Route::group(function () {
        //Định tuyến tài nguyên cấp nhà phân phối
        Route::resource('level', 'v1.agent.AgentLevel')->except(['read'])->name('AgentLevel')->option([
            'real_name' => [
                'index' => 'Lấy danh sách cấp độ nhà phân phối',
                'create' => 'Nhận biểu mẫu cấp độ nhà phân phối',
                'save' => 'Lưu cấp độ nhà phân phối',
                'edit' => 'Nhận biểu mẫu để sửa đổi cấp độ nhà phân phối',
                'update' => 'Sửa đổi cấp độ nhà phân phối',
                'delete' => 'Xóa cấp độ nhà phân phối'
            ]
        ]);
        //Sửa đổi trạng thái cấp độ phân phối
        Route::put('level/set_status/:id/:status', 'v1.agent.AgentLevel/set_status')->name('levelSetStatus')->option(['real_name' => 'Sửa đổi trạng thái cấp độ phân phối']);
        //Định tuyến tài nguyên nhiệm vụ cấp nhà phân phối
        Route::resource('level_task', 'v1.agent.AgentLevelTask')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách nhiệm vụ cấp nhà phân phối',
                'create' => 'Nhận biểu mẫu nhiệm vụ cấp nhà phân phối',
                'save' => 'Lưu nhiệm vụ cấp nhà phân phối',
                'edit' => 'Lấy biểu mẫu nhiệm vụ để sửa đổi cấp độ nhà phân phối',
                'update' => 'Sửa đổi nhiệm vụ cấp nhà phân phối',
                'delete' => 'Xóa nhiệm vụ cấp nhà phân phối'
            ]
        ]);
        //Sửa đổi trạng thái nhiệm vụ phân phối
        Route::put('level_task/set_status/:id/:status', 'v1.agent.AgentLevelTask/set_status')->name('levelTaskSetStatus')->option(['real_name' => 'Sửa đổi trạng thái nhiệm vụ cấp phân phối']);
        //Nhận biểu mẫu cấp độ phân phối miễn phí
        Route::get('get_level_form', 'v1.agent.AgentManage/getLevelForm')->name('getLevelForm')->option(['real_name' => 'Nhận biểu mẫu cấp độ phân phối miễn phí']);
        //Mức độ phân phối miễn phí
        Route::post('give_level', 'v1.agent.AgentManage/giveAgentLevel')->name('giveAgentLevel')->option(['real_name' => 'Mức độ phân phối miễn phí']);
        //Đặt biểu mẫu số lượng hoàn thành nhiệm vụ
        Route::get('get_task_num_form/:id', 'v1.agent.AgentLevel/getTaskNumForm')->name('getTaskNumForm')->option(['real_name' => 'Nhận biểu mẫu số lượng hoàn thành nhiệm vụ']);
        //Đặt số lượng nhiệm vụ đã hoàn thành
        Route::post('set_task_num/:id', 'v1.agent.AgentLevel/setTaskNum')->name('setTaskNum')->option(['real_name' => 'Đặt số lượng nhiệm vụ đã hoàn thành']);
    })->option(['parent' => 'agent', 'cate_name' => 'Cấp bậc Affiliate']);

    /** Đơn vị kinh doanh */
    Route::group(function () {
        Route::get('division/list', 'v1.agent.Division/divisionList')->name('divisionList')->option(['real_name' => 'Danh sách Đơn vị kinh doanh']);//Danh sách Phòng/Đại lý/Nhân viên
        Route::get('division/down_list', 'v1.agent.Division/divisionDownList')->name('divisionDownList')->option(['real_name' => 'Danh sách cấp dưới']);//Danh sách cấp dưới
        Route::get('division/create/:uid', 'v1.agent.Division/divisionCreate')->name('divisionCreate')->option(['real_name' => 'Thêm Đơn vị kinh doanh']);//Thêm đơn vị kinh doanh
        Route::post('division/save', 'v1.agent.Division/divisionSave')->name('divisionSave')->option(['real_name' => 'Lưu theo phòng kinh doanh']);//Lưu theo phòng kinh doanh
        Route::get('division/agent/create/:uid', 'v1.agent.Division/divisionAgentCreate')->name('divisionAgentCreate')->option(['real_name' => 'Thêm Đơn vị kinh doanh']);//Thêm đại lý
        Route::post('division/agent/save', 'v1.agent.Division/divisionAgentSave')->name('divisionAgentSave')->option(['real_name' => 'Lưu theo phòng kinh doanh']);//Lưu đại lý
        Route::put('division/set_status/:status/:uid', 'v1.agent.Division/setDivisionStatus')->name('setDivisionStatus')->option(['real_name' => 'Chuyển đổi trạng thái']);//Chuyển đổi trạng thái
        Route::delete('division/del/:type/:uid', 'v1.agent.Division/delDivision')->name('delDivision')->option(['real_name' => 'Xóa đại lý']);//Chuyển đổi trạng thái
        Route::get('division/staff/create/:uid', 'v1.agent.Division/divisionStaffCreate')->name('divisionStaffCreate')->option(['real_name' => 'Thêm Đơn vị kinh doanh']);//Thêm đại lý
        Route::post('division/staff/save', 'v1.agent.Division/divisionStaffSave')->name('divisionStaffSave')->option(['real_name' => 'Lưu theo phòng kinh doanh']);//Lưu đại lý
        Route::get('division/agent_apply/list', 'v1.agent.Division/AdminApplyList')->name('AdminApplyList')->option(['real_name' => 'Danh sách ứng dụng đại lý']);//Danh sách ứng dụng đại lý
        Route::get('division/examine_apply/:id/:type', 'v1.agent.Division/examineApply')->name('examineApply')->option(['real_name' => 'Biểu mẫu đánh giá']);//Biểu mẫu đánh giá
        Route::post('division/apply_agent/save', 'v1.agent.Division/applyAgentSave')->name('applyAgentSave')->option(['real_name' => 'Gửi để xem xét']);//Gửi để xem xét
        Route::delete('division/del_apply/:id', 'v1.agent.Division/delApply')->name('delApply')->option(['real_name' => 'Xóa đánh giá']);//Xóa đánh giá
        Route::get('division/statistics', 'v1.agent.Division/divisionStatistics')->name('divisionStatistics')->option(['real_name' => 'Thống kê Đơn vị kinh doanh']);//Thống kê đơn vị kinh doanh
    })->option(['parent' => 'agent', 'cate_name' => 'Đơn vị kinh doanh']);

    /** Ứng dụng phân phối */
    Route::group(function () {
        Route::get('spread/apply/list', 'v1.agent.SpreadApply/applyList')->name('applyList')->option(['real_name' => 'Danh sách ứng dụng nhà phân phối']);
        Route::post('spread/apply/examine/:id/:uid/:status', 'v1.agent.SpreadApply/applyExamine')->name('applyExamine')->option(['real_name' => 'Đánh giá nhà phân phối']);
        Route::delete('spread/apply/del/:id', 'v1.agent.SpreadApply/applyDelete')->name('applyDelete')->option(['real_name' => 'Xóa ứng dụng nhà phân phối']);
    })->option(['parent' => 'agent', 'cate_name' => 'Đăng ký làm Affiliate']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'agent', 'mark_name' => 'Mô-đun phân phối']);
