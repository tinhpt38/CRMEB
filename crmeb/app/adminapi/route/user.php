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
 * Định tuyến liên quan đến mô-đun người dùng
 */
Route::group('user', function () {

    /** người dùng */
    Route::group(function () {
        //Định tuyến tài nguyên quản lý người dùng
        Route::resource('user', 'v1.user.User')->option([
            'real_name' => [
                'index' => 'Lấy danh sách người dùng',
                'create' => 'Nhận biểu mẫu người dùng',
                'save' => 'lưu người dùng',
                'read' => 'Nhận thông tin chi tiết người dùng',
                'edit' => 'Nhận biểu mẫu người dùng đã sửa đổi',
                'update' => 'Sửa đổi người dùng',
                'delete' => 'Xóa người dùng'
            ]
        ]);
        //Thêm người dùng lưu
        Route::post('user/save', 'v1.user.User/save_info')->option(['real_name' => 'Thêm người dùng']);
        //Đồng bộ hóa người dùng WeChat
        Route::get('user/syncUsers', 'v1.user.User/syncWechatUsers')->option(['real_name' => 'Đồng bộ hóa người dùng WeChat']);
        //Thông tin người dùng
        Route::get('user/user_save_info/:uid', 'v1.user.User/userSaveInfo')->option(['real_name' => 'Thêm thông tin khi chỉnh sửa thông tin người dùng']);
        //Cấp độ thành viên miễn phí
        Route::get('give_level/:id', 'v1.user.User/give_level')->option(['real_name' => 'Cấp độ người dùng miễn phí']);
        //Triển khai cấp độ thành viên miễn phí
        Route::put('save_give_level/:id', 'v1.user.User/save_give_level')->option(['real_name' => 'Triển khai cấp độ người dùng miễn phí']);
        //Thời gian thành viên trả phí miễn phí
        Route::get('give_level_time/:id', 'v1.user.User/give_level_time')->option(['real_name' => 'Thời gian thành viên trả phí miễn phí']);
        //Thực hiện thời hạn thành viên trả phí miễn phí
        Route::put('save_give_level_time/:id', 'v1.user.User/save_give_level_time')->option(['real_name' => 'Thực hiện thời hạn thành viên trả phí miễn phí']);
        //Xóa cấp độ thành viên
        Route::delete('del_level/:id', 'v1.user.User/del_level')->option(['real_name' => 'Xóa cấp độ người dùng']);
        //Chỉnh sửa khác
        Route::get('edit_other/:id/:type', 'v1.user.User/edit_other')->option(['real_name' => 'Sửa đổi biểu mẫu cân bằng điểm']);
        //Chỉnh sửa khác
        Route::put('update_other/:id', 'v1.user.User/update_other')->option(['real_name' => 'Sửa đổi số dư điểm']);
        //Sửa đổi trạng thái người dùng
        Route::put('set_status/:status/:id', 'v1.user.User/set_status')->option(['real_name' => 'Sửa đổi trạng thái người dùng']);
        //Nhận thông tin về một người dùng được chỉ định
        Route::get('one_info/:id', 'v1.user.User/oneUserInfo')->option(['real_name' => 'Nhận thông tin về một người dùng được chỉ định']);
        //Thiết lập các nhóm thành viên
        Route::post('set_group', 'v1.user.User/set_group')->option(['real_name' => 'Biểu mẫu nhóm người dùng']);
        //Thực hiện cài đặt nhóm thành viên
        Route::put('save_set_group', 'v1.user.User/save_set_group')->option(['real_name' => 'Thiết lập nhóm người dùng']);
        //Đặt nhãn thành viên
        Route::post('set_label', 'v1.user.User/set_label')->option(['real_name' => 'Đặt nhãn người dùng']);
    })->option(['parent' => 'user', 'cate_name' => 'người dùng']);

    /** Cấp độ người dùng */
    Route::group(function () {
        //Nhận biểu mẫu thêm cấp độ thành viên
        Route::get('user_level/create', 'v1.user.UserLevel/create')->option(['real_name' => 'Thêm biểu mẫu cấp độ người dùng']);
        //Thêm hoặc sửa đổi cấp độ thành viên
        Route::post('user_level', 'v1.user.UserLevel/save')->option(['real_name' => 'Thêm hoặc sửa đổi cấp độ người dùng']);
        //Chi tiết cấp độ
        Route::get('user_level/read/:id', 'v1.user.UserLevel/read')->option(['real_name' => 'Chi tiết cấp độ người dùng']);
        //Lấy danh sách VIP do hệ thống thiết lập
        Route::get('user_level/vip_list', 'v1.user.UserLevel/get_system_vip_list')->option(['real_name' => 'Lấy danh sách cấp độ người dùng do hệ thống thiết lập']);
        //Xóa cấp độ thành viên
        Route::put('user_level/delete/:id', 'v1.user.UserLevel/delete')->option(['real_name' => 'Xóa cấp độ người dùng']);
        //Thiết lập một sản phẩm duy nhất để đặt trên kệ|Đã xóa khỏi kệ
        Route::put('user_level/set_show/:id/:is_show', 'v1.user.UserLevel/set_show')->option(['real_name' => 'Đặt cấp độ người dùng để tải lên và xóa']);
        //Chỉnh sửa nhanh danh sách cấp độ
        Route::put('user_level/set_value/:id', 'v1.user.UserLevel/set_value')->option(['real_name' => 'Chỉnh sửa nhanh danh sách cấp độ người dùng']);
        //Danh sách nhiệm vụ cấp độ
        Route::get('user_level/task/:level_id', 'v1.user.UserLevel/get_task_list')->option(['real_name' => 'Danh sách nhiệm vụ cấp người dùng']);
        //Chỉnh sửa nhanh các nhiệm vụ cấp độ
        Route::put('user_level/set_task/:id', 'v1.user.UserLevel/set_task_value')->option(['real_name' => 'Chỉnh sửa nhanh các tác vụ cấp người dùng']);
        //Đặt hiển thị nhiệm vụ cấp độ|trốn
        Route::put('user_level/set_task_show/:id/:is_show', 'v1.user.UserLevel/set_task_show')->option(['real_name' => 'Đặt hiển thị tác vụ ở cấp độ người dùng|trốn']);
        //Có phải đạt được cài đặt hay không
        Route::put('user_level/set_task_must/:id/:is_must', 'v1.user.UserLevel/set_task_must')->option(['real_name' => 'Có phải hoàn thành nhiệm vụ thiết lập cấp độ người dùng hay không']);
        //Thêm biểu mẫu nhiệm vụ cấp độ
        Route::get('user_level/create_task', 'v1.user.UserLevel/create_task')->option(['real_name' => 'Thêm biểu mẫu nhiệm vụ cấp người dùng']);
        //Lưu hoặc sửa đổi nhiệm vụ
        Route::post('user_level/save_task', 'v1.user.UserLevel/save_task')->option(['real_name' => 'Lưu hoặc sửa đổi nhiệm vụ cấp người dùng']);
        //Xóa tác vụ
        Route::delete('user_level/delete_task/:id', 'v1.user.UserLevel/delete_task')->option(['real_name' => 'Xóa nhiệm vụ cấp người dùng']);
    })->option(['parent' => 'user', 'cate_name' => 'Cấp độ người dùng']);

    /** Nhóm người dùng */
    Route::group(function () {
        //Lấy danh sách nhóm người dùng
        Route::get('user_group/list', 'v1.user.UserGroup/index')->option(['real_name' => 'Lấy danh sách nhóm người dùng']);
        //Thêm và sửa đổi biểu mẫu nhóm
        Route::get('user_group/add/:id', 'v1.user.UserGroup/add')->option(['real_name' => 'Thêm và sửa đổi biểu mẫu nhóm']);
        //Lưu dữ liệu biểu mẫu được nhóm
        Route::post('user_group/save', 'v1.user.UserGroup/save')->option(['real_name' => 'Lưu dữ liệu biểu mẫu được nhóm']);
        //Xóa dữ liệu được nhóm
        Route::delete('user_group/del/:id', 'v1.user.UserGroup/delete')->option(['real_name' => 'Xóa dữ liệu nhóm người dùng']);
    })->option(['parent' => 'user', 'cate_name' => 'Nhóm người dùng']);

    /** Thẻ người dùng */
    Route::group(function () {
        //Danh sách thẻ thành viên
        Route::get('user_label', 'v1.user.UserLabel/index')->option(['real_name' => 'Danh sách thẻ người dùng']);
        //Mẫu bổ sung và sửa đổi thẻ thành viên
        Route::get('user_label/add/:id', 'v1.user.UserLabel/add')->option(['real_name' => 'Thêm hoặc sửa đổi biểu mẫu nhãn người dùng']);
        //Lưu dữ liệu biểu mẫu nhãn
        Route::post('user_label/save', 'v1.user.UserLabel/save')->option(['real_name' => 'Thêm hoặc sửa đổi thẻ người dùng']);
        //Xóa thẻ thành viên
        Route::delete('user_label/del/:id', 'v1.user.UserLabel/delete')->option(['real_name' => 'Xóa nhãn người dùng']);
        //Nhận thẻ người dùng
        Route::get('label/:uid', 'v1.user.UserLabel/getUserLabel')->option(['real_name' => 'Nhận thẻ người dùng']);
        //Đặt và hủy thẻ người dùng
        Route::post('label/:uid', 'v1.user.UserLabel/setUserLabel')->option(['real_name' => 'Đặt và hủy thẻ người dùng']);
        //Thiết lập các nhóm thành viên
        Route::put('save_set_label', 'v1.user.user/save_set_label')->option(['real_name' => 'Lưu nhãn người dùng']);
        //Phân loại thẻ
        Route::resource('user_label_cate', 'v1.user.UserLabelCate')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận phân loại thẻ',
                'create' => 'Nhận mẫu phân loại thẻ',
                'save' => 'Lưu danh mục thẻ',
                'edit' => 'Nhận mẫu phân loại nhãn sửa đổi',
                'update' => 'Sửa đổi phân loại nhãn',
                'delete' => 'Xóa danh mục thẻ'
            ]
        ]);
        Route::get('user_label_cate/all', 'v1.user.UserLabelCate/getAll')->option(['real_name' => 'Nhận tất cả các danh mục thẻ người dùng']);
        //Danh sách cây thẻ người dùng (danh mục)
        Route::get('user_tree_label', 'v1.user.UserLabel/tree_list')->option(['real_name' => 'Danh sách cây thẻ người dùng (danh mục)']);
    })->option(['parent' => 'user', 'cate_name' => 'Thẻ người dùng']);

    /** Thành viên trả phí */
    Route::group(function () {
        //Tài nguyên danh sách lô thẻ thành viên
        Route::get('member_batch/index', 'v1.user.member.MemberCardBatch/index')->option(['real_name' => 'Danh sách lô thẻ thành viên']);
        //Thêm đợt thẻ thành viên
        Route::post('member_batch/save/:id', 'v1.user.member.MemberCardBatch/save')->option(['real_name' => 'Thêm đợt thẻ thành viên']);
        //Danh sách thẻ thành viên
        Route::get('member_card/index/:card_batch_id', 'v1.user.member.MemberCard/index')->option(['real_name' => 'Danh sách thẻ thành viên']);
        //Tình trạng sửa đổi thẻ thành viên
        Route::get('member_card/set_status', 'v1.user.member.MemberCard/set_status')->option(['real_name' => 'Tình trạng sửa đổi thẻ thành viên']);
        //Danh sách thao tác sửa đổi trường biểu mẫu
        Route::get('member_batch/set_value/:id', 'v1.user.member.MemberCardBatch/set_value')->option(['real_name' => 'Sửa đổi nhanh chóng các lô thẻ thành viên']);
        //Loại thành viên
        Route::get('member/ship', 'v1.user.member.MemberCard/member_ship')->option(['real_name' => 'Danh sách các loại thành viên']);
        //Loại thành viên xóa
        Route::delete('member_ship/delete/:id', 'v1.user.member.MemberCard/delete')->option(['real_name' => 'Loại thành viên xóa']);
        //Trạng thái sửa đổi loại thành viên
        Route::get('member_ship/set_ship_status', 'v1.user.member.MemberCard/set_ship_status')->option(['real_name' => 'Trạng thái sửa đổi loại thành viên']);
        //Chỉnh sửa loại thẻ thành viên
        Route::post('member_ship/save/:id', 'v1.user.member.MemberCard/ship_save')->option(['real_name' => 'Chỉnh sửa loại thẻ thành viên']);
        //Đổi mã QR thẻ thành viên
        Route::get('member_scan', 'v1.user.member.MemberCardBatch/member_scan')->option(['real_name' => 'Đổi mã QR thẻ thành viên']);
        //Hồ sơ thành viên
        Route::get('member/record', 'v1.user.member.MemberCard/member_record')->option(['real_name' => 'Hồ sơ thành viên']);
        //Quyền thành viên
        Route::get('member/right', 'v1.user.member.MemberCard/member_right')->option(['real_name' => 'Danh sách lợi ích thành viên']);
        //Sửa đổi quyền thành viên
        Route::post('member_right/save/:id', 'v1.user.member.MemberCard/right_save')->option(['real_name' => 'Sửa đổi quyền thành viên']);
        //Thỏa thuận thành viên
        Route::post('member_agreement/save/:id', 'v1.user.member.MemberCardBatch/save_member_agreement')->option(['real_name' => 'Thỏa thuận thành viên']);
        //Nhận thỏa thuận thành viên
        Route::get('member/agreement', 'v1.user.member.MemberCardBatch/getAgreement')->option(['real_name' => 'Nhận thỏa thuận thành viên']);
    })->option(['parent' => 'user', 'cate_name' => 'Thành viên trả phí']);


    /** Đăng xuất người dùng */
    Route::group(function () {
        Route::get('cancel_list', 'v1.user.UserCancel/getCancelList')->option(['real_name' => 'Danh sách đăng xuất của người dùng']);
        Route::post('cancel/set_mark', 'v1.user.UserCancel/setMark')->option(['real_name' => 'Ghi chú danh sách đăng xuất']);
        Route::get('cancel/agree/:id', 'v1.user.UserCancel/agreeCancel')->option(['real_name' => 'Đồng ý đăng xuất']);
        Route::get('cancel/refuse/:id', 'v1.user.UserCancel/refuseCancel')->option(['real_name' => 'Từ chối đăng xuất']);
    })->option(['parent' => 'user', 'cate_name' => 'Đăng xuất người dùng']);

    /** lễ cưới */
    Route::group(function () {
        Route::get('new_gift', 'v1.user.User/getNewGift')->option(['real_name' => 'Nhận quà tân hôn']);
        Route::post('new_gift/save', 'v1.user.User/saveNewGift')->option(['real_name' => 'Tiết kiệm quà cưới']);
    })->option(['parent' => 'user', 'cate_name' => 'lễ cưới']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'user', 'mark_name' => 'Quản lý người dùng']);
