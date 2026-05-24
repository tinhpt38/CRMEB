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
 * Bảo trì Cài đặt hệ thống, quản lý quyền hệ thống, quản lý menu hệ thống, cấu hình hệ thống, định tuyến liên quan
 */Route::group('setting', function () {

    /** quản trị viên */    Route::group(function () {
        //Định tuyến tài nguyên quản trị viên
        Route::resource('admin', 'v1.setting.SystemAdmin')->except(['read'])->option([
            'real_name' => [
                'index' => 'Lấy danh sách quản trị viên',
                'create' => 'Nhận biểu mẫu quản trị viên',
                'save' => 'lưu quản trị viên',
                'edit' => 'Nhận biểu mẫu chỉnh sửa quản trị viên',
                'update' => 'Sửa đổi quản trị viên',
                'delete' => 'Xóa quản trị viên'
            ]
        ]);
        //Đăng xuất
        Route::get('admin/logout', 'v1.setting.SystemAdmin/logout')->name('SystemAdminLogout')->option(['real_name' => 'Đăng xuất']);
        //Sửa đổi trạng thái
        Route::put('set_status/:id/:status', 'v1.setting.SystemAdmin/set_status')->name('SystemAdminSetStatus')->option(['real_name' => 'Sửa đổi trạng thái quản trị viên']);
        //Nhận thông tin quản trị viên hiện tại
        Route::get('info', 'v1.setting.SystemAdmin/info')->name('SystemAdminInfo')->option(['real_name' => 'Nhận thông tin quản trị viên hiện tại']);
        //Sửa đổi thông tin quản trị viên hiện tại
        Route::put('update_admin', 'v1.setting.SystemAdmin/update_admin')->name('SystemAdminUpdateAdmin')->option(['real_name' => 'Sửa đổi thông tin quản trị viên hiện tại']);
        //Đặt mật khẩu quản lý tập tin
        Route::put('set_file_password', 'v1.setting.SystemAdmin/set_file_password')->name('SystemAdminSetFilePassword')->option(['real_name' => 'Đặt mật khẩu quản lý tập tin hiện tại']);
    })->option(['parent' => 'setting', 'cate_name' => 'quản trị viên']);

    /** Trình đơn quyền */    Route::group(function () {
        //Nhận quyền của menu và số nhận dạng quyền
        Route::get('menus/unique', 'v1.setting.SystemMenus/unique')->name('SystemMenusUnique')->option(['real_name' => 'Nhận quyền của menu và số nhận dạng quyền']);
        //Quyền lưu hàng loạt
        Route::post('menus/batch', 'v1.setting.SystemMenus/batchSave')->name('SystemMenusBatchSave')->option(['real_name' => 'Quyền lưu hàng loạt']);
        //Định tuyến tài nguyên menu quyền
        Route::resource('menus', 'v1.setting.SystemMenus')->option([
            'real_name' => [
                'index' => 'Nhận danh sách menu quyền',
                'create' => 'Nhận mẫu menu quyền',
                'save' => 'Lưu menu quyền',
                'edit' => 'Nhận mẫu menu cho phép sửa đổi',
                'read' => 'Xem thông tin menu quyền',
                'update' => 'Sửa đổi menu quyền',
                'delete' => 'Xóa menu quyền'
            ],
        ]);
        //Danh sách các quy tắc cấp phép chưa được thêm
        Route::get('ruleList', 'v1.setting.SystemMenus/ruleList')->option(['real_name' => 'Danh sách các quy tắc cấp phép']);
        //Phân loại quy tắc cấp phép
        Route::get('rule_cate', 'v1.setting.SystemMenus/ruleCate')->option(['real_name' => 'Phân loại quy tắc cấp phép']);
        //Sửa đổi hiển thị
        Route::put('menus/show/:id', 'v1.setting.SystemMenus/show')->name('SystemMenusShow')->option(['real_name' => 'Sửa đổi trạng thái hiển thị thông số quyền']);
    })->option(['parent' => 'setting', 'cate_name' => 'Trình đơn quyền']);

    /** Trạng thái quản trị viên */    Route::group(function () {
        //danh sách nhận dạng
        Route::get('role', 'v1.setting.SystemRole/index')->option(['real_name' => 'Danh sách nhận dạng quản trị viên']);
        //Danh sách quyền nhận dạng
        Route::get('role/create', 'v1.setting.SystemRole/create')->option(['real_name' => 'Danh sách quyền nhận dạng quản trị viên']);
        //Chỉnh sửa chi tiết
        Route::get('role/:id/edit', 'v1.setting.SystemRole/edit')->option(['real_name' => 'Chỉnh sửa chi tiết quản trị viên']);
        //Lưu mới hoặc chỉnh sửa
        Route::post('role/:id', 'v1.setting.SystemRole/save')->option(['real_name' => 'Tạo hoặc chỉnh sửa quản trị viên']);
        //Sửa đổi trạng thái nhận dạng
        Route::put('role/set_status/:id/:status', 'v1.setting.SystemRole/set_status')->option(['real_name' => 'Sửa đổi trạng thái quản trị viên']);
        //Xóa danh tính
        Route::delete('role/:id', 'v1.setting.SystemRole/delete')->option(['real_name' => 'Xóa vai trò quản trị viên']);
    })->option(['parent' => 'setting', 'cate_name' => 'Trạng thái quản trị viên']);

    /** Cấu hình hệ thống */    Route::group(function () {
        //Định cấu hình định tuyến tài nguyên được phân loại
        Route::resource('config_class', 'v1.setting.SystemConfigTab')->except(['read'])->option([
            'real_name' => [
                'index' => 'Lấy danh sách phân loại cấu hình hệ thống',
                'create' => 'Nhận biểu mẫu phân loại cấu hình hệ thống',
                'save' => 'Lưu danh mục cấu hình hệ thống',
                'edit' => 'Nhận mẫu phân loại cấu hình hệ thống sửa đổi',
                'update' => 'Sửa danh mục cấu hình hệ thống',
                'delete' => 'Xóa danh mục cấu hình hệ thống'
            ],
        ]);
        //Sửa đổi trạng thái phân loại cấu hình
        Route::put('config_class/set_status/:id/:status', 'v1.setting.SystemConfigTab/set_status')->option(['real_name' => 'Sửa đổi trạng thái phân loại cấu hình']);
        //Định cấu hình định tuyến tài nguyên
        Route::resource('config', 'v1.setting.SystemConfig')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách cấu hình hệ thống',
                'create' => 'Nhận mẫu cấu hình hệ thống',
                'save' => 'Lưu cấu hình hệ thống',
                'edit' => 'Nhận mẫu cấu hình hệ thống sửa đổi',
                'update' => 'Sửa đổi cấu hình hệ thống',
                'delete' => 'Xóa cấu hình hệ thống'
            ]
        ]);
        //Sửa đổi trạng thái cấu hình
        Route::put('config/set_status/:id/:status', 'v1.setting.SystemConfig/set_status')->option(['real_name' => 'Sửa đổi trạng thái cấu hình']);
        //Mẫu chỉnh sửa cấu hình cơ bản
        Route::get('config/header_basics', 'v1.setting.SystemConfig/header_basics')->option(['real_name' => 'Dữ liệu tiêu đề chỉnh sửa cấu hình cơ bản']);
        //Mẫu chỉnh sửa cấu hình cơ bản
        Route::get('config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => 'Mẫu chỉnh sửa cấu hình cơ bản']);
        //Cấu hình cơ bản lưu dữ liệu
        Route::post('config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => 'Cấu hình cơ bản lưu dữ liệu']);
        //Tệp tải lên cấu hình cơ bản
        Route::post('config/upload', 'v1.setting.SystemConfig/file_upload')->option(['real_name' => 'Tệp tải lên cấu hình cơ bản']);
        //Nhận một giá trị cấu hình duy nhất
        Route::get('config/get_system/:name', 'v1.setting.SystemConfig/get_system')->option(['real_name' => 'Mẫu chỉnh sửa cấu hình cơ bản']);
        //Nhận Tất cả thông tin cấu hình theo một danh mục nhất định
        Route::get('config_list/:tabId', 'v1.setting.SystemConfig/get_config_list')->option(['real_name' => 'Nhận Tất cả thông tin cấu hình theo một danh mục nhất định']);
    })->option(['parent' => 'setting', 'cate_name' => 'Cấu hình hệ thống']);

    /** Dữ liệu kết hợp */    Route::group(function () {
        //Định tuyến tài nguyên dữ liệu kết hợp
        Route::resource('group', 'v1.setting.SystemGroup')->option([
            'real_name' => [
                'index' => 'Nhận danh sách dữ liệu kết hợp',
                'create' => 'Nhận mẫu dữ liệu kết hợp',
                'save' => 'Lưu dữ liệu kết hợp',
                'edit' => 'Nhận mẫu dữ liệu kết hợp được sửa đổi',
                'update' => 'Sửa đổi dữ liệu kết hợp',
                'delete' => 'Xóa dữ liệu kết hợp'
            ]
        ]);
        //Tất cả dữ liệu tổng hợp
        Route::get('group_all', 'v1.setting.SystemGroup/getGroup')->option(['real_name' => 'Tất cả dữ liệu tổng hợp']);
        //Định tuyến tài nguyên dữ liệu con dữ liệu kết hợp
        Route::resource('group_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Lấy danh sách dữ liệu con dữ liệu kết hợp',
                'create' => 'Nhận biểu mẫu dữ liệu con kết hợp',
                'save' => 'Lưu dữ liệu con dữ liệu kết hợp',
                'edit' => 'Nhận biểu mẫu dữ liệu con dữ liệu kết hợp đã sửa đổi',
                'update' => 'Sửa đổi dữ liệu con dữ liệu kết hợp',
                'delete' => 'Xóa dữ liệu con dữ liệu kết hợp'
            ]
        ]);
        //Sửa đổi trạng thái dữ liệu
        Route::get('group_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Tiêu đề dữ liệu kết hợp']);
        //Sửa đổi trạng thái dữ liệu
        Route::put('group_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Sửa đổi trạng thái dữ liệu kết hợp']);
        //Lưu cấu hình dữ liệu
        Route::post('group_data/save_all', 'v1.setting.SystemGroupData/saveAll')->option(['real_name' => 'Gửi cấu hình dữ liệu']);
        //Nhận quảng cáo CSKH
        Route::get('get_kf_adv', 'v1.setting.SystemGroupData/getKfAdv')->option(['real_name' => 'Nhận quảng cáo CSKH']);
        //Thiết lập quảng cáo CSKH
        Route::post('set_kf_adv', 'v1.setting.SystemGroupData/setKfAdv')->option(['real_name' => 'Thiết lập quảng cáo CSKH']);
        //Tài nguyên cấu hình ngày nhận phòng
        Route::resource('sign_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Lấy danh sách cấu hình ngày nhận phòng',
                'create' => 'Nhận biểu mẫu cấu hình ngày nhận phòng',
                'save' => 'Lưu cấu hình ngày nhận phòng',
                'edit' => 'Nhận biểu mẫu cấu hình để sửa đổi ngày nhận phòng',
                'update' => 'Sửa đổi cấu hình ngày nhận phòng',
                'delete' => 'Xóa cấu hình ngày nhận phòng'
            ]
        ]);
        //Trường dữ liệu đăng nhập
        Route::get('sign_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Tiêu đề dữ liệu đăng nhập']);
        //Sửa đổi trạng thái dữ liệu đăng ký
        Route::put('sign_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Sửa đổi trạng thái dữ liệu đăng ký']);
        //Chi tiết đơn hàng tài nguyên cấu hình biểu đồ động
        Route::resource('order_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận chi tiết đơn hàng danh sách biểu đồ động',
                'create' => 'Nhận chi tiết đơn hàng biểu đồ động',
                'save' => 'Lưu chi tiết đơn hàng hình ảnh động',
                'edit' => 'Nhận biểu mẫu động để sửa đổi chi tiết đơn hàng',
                'update' => 'Sửa đổi biểu đồ động chi tiết đơn hàng',
                'delete' => 'Xóa chi tiết đơn hàng biểu đồ động'
            ]
        ]);
        //Trường dữ liệu đặt hàng
        Route::get('order_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Trường dữ liệu đặt hàng']);
        //Trạng thái dữ liệu đơn hàng
        Route::put('order_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Trạng thái dữ liệu đơn hàng']);
        //Tài nguyên cấu hình menu trung tâm cá nhân
        Route::resource('usermenu_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách menu trung tâm cá nhân',
                'create' => 'Nhận mẫu menu trung tâm cá nhân',
                'save' => 'Lưu menu trung tâm cá nhân',
                'edit' => 'Nhận mẫu menu trung tâm cá nhân sửa đổi',
                'update' => 'Sửa đổi menu trung tâm cá nhân',
                'delete' => 'Xóa menu trung tâm cá nhân'
            ]
        ]);
        //Trường dữ liệu menu trung tâm cá nhân
        Route::get('usermenu_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Trường dữ liệu menu trung tâm cá nhân']);
        //Trạng thái dữ liệu menu trung tâm cá nhân
        Route::put('usermenu_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Trạng thái dữ liệu menu trung tâm cá nhân']);
        //Chia sẻ tài nguyên cấu hình áp phích
        Route::resource('poster_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách các áp phích được chia sẻ',
                'create' => 'Nhận mẫu chia sẻ áp phích',
                'save' => 'Lưu Chia sẻ Áp phích',
                'edit' => 'Nhận mẫu áp phích chia sẻ đã sửa đổi',
                'update' => 'Sửa đổi áp phích được chia sẻ',
                'delete' => 'Xóa áp phích được chia sẻ'
            ]
        ]);
        //Chia sẻ trường dữ liệu áp phích
        Route::get('poster_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Chia sẻ trường dữ liệu áp phích']);
        //Chia sẻ trạng thái dữ liệu áp phích
        Route::put('poster_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Chia sẻ trạng thái dữ liệu áp phích']);
        //Tài nguyên cấu hình flash sale
        Route::resource('seckill_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
            'real_name' => [
                'index' => 'Lấy danh sách cấu hình tiêu diệt ngay lập tức',
                'create' => 'Nhận mẫu cấu hình flash sale',
                'save' => 'Lưu cấu hình flash sale',
                'edit' => 'Nhận mẫu cấu hình flash sale sửa đổi',
                'update' => 'Sửa đổi cấu hình flash sale',
                'delete' => 'Xóa cấu hình flash sale'
            ]
        ]);
        //Trường dữ liệu flash sale
        Route::get('seckill_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => 'Trường dữ liệu flash sale']);
        //Trạng thái dữ liệu flash sale
        Route::put('seckill_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => 'Trạng thái dữ liệu flash sale']);
        //Nhận thỏa thuận về quyền riêng tư
        Route::get('get_user_agreement', 'v1.setting.SystemGroupData/getUserAgreement')->option(['real_name' => 'Nhận thỏa thuận về quyền riêng tư']);
        //Đặt thỏa thuận quyền riêng tư
        Route::post('set_user_agreement', 'v1.setting.SystemGroupData/setUserAgreement')->option(['real_name' => 'Đặt thỏa thuận quyền riêng tư']);
    })->option(['parent' => 'setting', 'cate_name' => 'Dữ liệu kết hợp']);

    /** dữ liệu thành phố */    Route::group(function () {
        //Nhận danh sách đầy đủ dữ liệu thành phố
        Route::get('city/full_list', 'v1.setting.SystemCity/fullList')->option(['real_name' => 'Nhận danh sách đầy đủ dữ liệu thành phố']);
        //Lấy danh sách dữ liệu thành phố
        Route::get('city/list/:parent_id', 'v1.setting.SystemCity/index')->option(['real_name' => 'Lấy danh sách dữ liệu thành phố']);
        //Thêm mẫu dữ liệu thành phố
        Route::get('city/add/:parent_id', 'v1.setting.SystemCity/add')->option(['real_name' => 'Thêm mẫu dữ liệu thành phố']);
        //Sửa đổi biểu mẫu dữ liệu thành phố
        Route::get('city/:id/edit', 'v1.setting.SystemCity/edit')->option(['real_name' => 'Sửa đổi biểu mẫu dữ liệu thành phố']);
        //Thêm/sửa đổi dữ liệu thành phố
        Route::post('city/save', 'v1.setting.SystemCity/save')->option(['real_name' => 'Thêm/sửa đổi dữ liệu thành phố']);
        //Sửa đổi biểu mẫu dữ liệu thành phố
        Route::delete('city/del/:city_id', 'v1.setting.SystemCity/delete')->option(['real_name' => 'Xóa dữ liệu thành phố']);
        //Xóa bộ nhớ đệm dữ liệu thành phố
        Route::get('city/clean_cache', 'v1.setting.SystemCity/clean_cache')->option(['real_name' => 'Xóa bộ nhớ đệm dữ liệu thành phố']);
    })->option(['parent' => 'setting', 'cate_name' => 'dữ liệu thành phố']);

    /** Mẫu vận chuyển sản phẩm */    Route::group(function () {
        //Danh sách mẫu vận chuyển sản phẩm
        Route::get('shipping_templates/list', 'v1.setting.ShippingTemplates/temp_list')->option(['real_name' => 'Danh sách mẫu vận chuyển sản phẩm']);
        //Sửa đổi dữ liệu mẫu vận chuyển sản phẩm
        Route::get('shipping_templates/:id/edit', 'v1.setting.ShippingTemplates/edit')->option(['real_name' => 'Sửa đổi dữ liệu mẫu vận chuyển sản phẩm']);
        //Lưu những thay đổi mới
        Route::post('shipping_templates/save/:id', 'v1.setting.ShippingTemplates/save')->option(['real_name' => 'Thêm hoặc sửa đổi mẫu vận chuyển sản phẩm']);
        //Xóa mẫu vận chuyển
        Route::delete('shipping_templates/del/:id', 'v1.setting.ShippingTemplates/delete')->option(['real_name' => 'Xóa mẫu vận chuyển']);
        //Giao diện dữ liệu thành phố
        Route::get('shipping_templates/city_list', 'v1.setting.ShippingTemplates/city_list')->option(['real_name' => 'Giao diện dữ liệu thành phố']);
    })->option(['parent' => 'setting', 'cate_name' => 'Mẫu vận chuyển sản phẩm']);


    /** Thông báo hệ thống */    Route::group(function () {
        //Danh sách thông báo hệ thống
        Route::get('notification/index', 'v1.setting.SystemNotification/index')->option(['real_name' => 'Danh sách thông báo hệ thống']);
        //Thêm thông báo tùy chỉnh để sửa đổi biểu mẫu
        Route::get('notification/not_form/:id', 'v1.setting.SystemNotification/notForm')->option(['real_name' => 'Thêm thông báo tùy chỉnh để sửa đổi biểu mẫu']);
        //Xóa tin nhắn tùy chỉnh
        Route::delete('notification/del_not/:id', 'v1.setting.SystemNotification/delNot')->option(['real_name' => 'Xóa tin nhắn tùy chỉnh']);
        //Lưu tin nhắn tùy chỉnh
        Route::post('notification/not_form_save/:id', 'v1.setting.SystemNotification/notFormSave')->option(['real_name' => 'Lưu tin nhắn tùy chỉnh']);
        //Nhận một phần dữ liệu
        Route::get('notification/info', 'v1.setting.SystemNotification/info')->option(['real_name' => 'Nhận dữ liệu thông báo duy nhất']);
        //Lưu Cài đặt thông báo
        Route::post('notification/save', 'v1.setting.SystemNotification/save')->option(['real_name' => 'Lưu Cài đặt thông báo']);
        //Gửi thử thông báo Telegram
        Route::post('notification/test_telegram', 'v1.setting.SystemNotification/testTelegram')->option(['real_name' => 'Gửi thử thông báo Telegram']);
        //Sửa đổi trạng thái tin nhắn
        Route::put('notification/set_status/:type/:status/:id', 'v1.setting.SystemNotification/set_status')->option(['real_name' => 'Sửa đổi trạng thái tin nhắn']);

        //Danh sách kênh Telegram để chọn trong từng thông báo
        Route::get('notification/telegram_channels', 'v1.setting.NoticeChannel/telegramOptions')->option(['real_name' => 'Danh sách kênh Telegram']);
    })->option(['parent' => 'setting', 'cate_name' => 'Thông báo hệ thống']);

    /** Kênh thông báo tập trung */    Route::group(function () {
        //Danh sách kênh
        Route::get('notice_channel/index', 'v1.setting.NoticeChannel/index')->option(['real_name' => 'Danh sách kênh thông báo']);
        //Thêm kênh
        Route::post('notice_channel/save', 'v1.setting.NoticeChannel/save')->option(['real_name' => 'Thêm kênh thông báo']);
        //Sửa kênh
        Route::post('notice_channel/update/:id', 'v1.setting.NoticeChannel/update')->option(['real_name' => 'Sửa kênh thông báo']);
        //Chuyển trạng thái
        Route::put('notice_channel/set_status/:id/:status', 'v1.setting.NoticeChannel/setStatus')->option(['real_name' => 'Sửa trạng thái kênh']);
        //Xóa kênh
        Route::delete('notice_channel/delete/:id', 'v1.setting.NoticeChannel/delete')->option(['real_name' => 'Xóa kênh thông báo']);
        //Gửi thử Telegram
        Route::post('notice_channel/test_telegram', 'v1.setting.NoticeChannel/testTelegram')->option(['real_name' => 'Gửi thử Telegram kênh']);
    })->option(['parent' => 'setting', 'cate_name' => 'Thông báo hệ thống']);

    /** Thỏa thuận bản quyền */    Route::group(function () {
        //Điều khoản & chính sách
        Route::get('get_agreement/:type', 'v1.setting.SystemAgreement/getAgreement')->option(['real_name' => 'Nhận Nội dung thỏa thuận']);
        Route::post('save_agreement', 'v1.setting.SystemAgreement/saveAgreement')->option(['real_name' => 'Đặt Nội dung giao thức']);
        //Nhận thông tin bản quyền
        Route::get('get_version', 'v1.setting.SystemConfig/getVersion')->option(['real_name' => 'Nhận thông tin bản quyền']);
    })->option(['parent' => 'setting', 'cate_name' => 'Thỏa thuận bản quyền']);


    /** Kết nối API ngoài */    Route::group(function () {
        //Thông tin tài khoản giao diện bên ngoài
        Route::get('system_out_account/index', 'v1.setting.SystemOutAccount/index')->option(['real_name' => 'Thông tin tài khoản giao diện bên ngoài']);
        //Thêm tài khoản giao diện bên ngoài
        Route::post('system_out_account/save', 'v1.setting.SystemOutAccount/save')->option(['real_name' => 'Thêm tài khoản giao diện bên ngoài']);
        //Sửa đổi tài khoản giao diện bên ngoài
        Route::post('system_out_account/update/:id', 'v1.setting.SystemOutAccount/update')->option(['real_name' => 'Sửa đổi tài khoản giao diện bên ngoài']);
        //Đặt xem tài khoản có bị vô hiệu hóa hay không
        Route::put('system_out_account/set_status/:id/:status', 'v1.setting.SystemOutAccount/set_status')->option(['real_name' => 'Đặt xem tài khoản có bị vô hiệu hóa hay không']);
        //Thiết lập giao diện đẩy tài khoản
        Route::put('system_out_account/set_up/:id', 'v1.setting.SystemOutAccount/outSetUpSave')->option(['real_name' => 'Thiết lập giao diện đẩy tài khoản']);
        //Xóa tài khoản
        Route::delete('system_out_account/:id', 'v1.setting.SystemOutAccount/delete')->option(['real_name' => 'Xóa tài khoản']);
        //Kiểm tra giao diện mã thông báo
        Route::post('system_out_account/text_out_url', 'v1.setting.SystemOutAccount/textOutUrl')->option(['real_name' => 'Kiểm tra giao diện mã thông báo']);

        //Danh sách giao diện bên ngoài
        Route::get('system_out_interface/list', 'v1.setting.SystemOutAccount/outInterfaceList')->option(['real_name' => 'Danh sách giao diện bên ngoài']);
        //Thêm và sửa đổi giao diện bên ngoài
        Route::post('system_out_interface/save/:id', 'v1.setting.SystemOutAccount/saveInterface')->option(['real_name' => 'Thêm và sửa đổi giao diện bên ngoài']);
        //Thông tin giao diện bên ngoài
        Route::get('system_out_interface/info/:id', 'v1.setting.SystemOutAccount/interfaceInfo')->option(['real_name' => 'Thông tin giao diện bên ngoài']);
        //Sửa đổi tên giao diện
        Route::put('system_out_interface/edit_name', 'v1.setting.SystemOutAccount/editInterfaceName')->option(['real_name' => 'Sửa đổi tên giao diện']);
        //Xóa giao diện
        Route::delete('system_out_interface/del/:id', 'v1.setting.SystemOutAccount/delInterface')->option(['real_name' => 'Xóa giao diện']);
    })->option(['parent' => 'setting', 'cate_name' => 'Kết nối API ngoài']);


    /** đa ngôn ngữ */    Route::group(function () {
        //Danh sách quốc gia ngôn ngữ
        Route::get('lang_country/list', 'v1.setting.LangCountry/langCountryList')->option(['real_name' => 'Danh sách quốc gia ngôn ngữ']);
        //Thêm biểu mẫu ngôn ngữ
        Route::get('lang_country/form/:id', 'v1.setting.LangCountry/langCountryForm')->option(['real_name' => 'Thêm biểu mẫu ngôn ngữ']);
        //Lưu ngôn ngữ
        Route::post('lang_country/save/:id', 'v1.setting.LangCountry/langCountrySave')->option(['real_name' => 'Lưu ngôn ngữ']);
        //Xóa ngôn ngữ
        Route::delete('lang_country/del/:id', 'v1.setting.LangCountry/langCountryDel')->option(['real_name' => 'Xóa ngôn ngữ']);
        //Danh sách các loại ngôn ngữ
        Route::get('lang_type/list', 'v1.setting.LangType/langTypeList')->option(['real_name' => 'Danh sách các loại ngôn ngữ']);
        //Đã thêm biểu mẫu để sửa đổi loại ngôn ngữ
        Route::get('lang_type/form/:id', 'v1.setting.LangType/langTypeForm')->option(['real_name' => 'Đã thêm biểu mẫu để sửa đổi loại ngôn ngữ']);
        //Lưu ngôn ngữ sửa đổi mới
        Route::post('lang_type/save/:id', 'v1.setting.LangType/langTypeSave')->option(['real_name' => 'Lưu ngôn ngữ sửa đổi mới']);
        //Xóa ngôn ngữ
        Route::delete('lang_type/del/:id', 'v1.setting.LangType/langTypeDel')->option(['real_name' => 'Xóa ngôn ngữ']);
        //Sửa đổi trạng thái loại ngôn ngữ
        Route::put('lang_type/status/:id/:status', 'v1.setting.LangType/langTypeStatus')->option(['real_name' => 'Sửa đổi trạng thái loại ngôn ngữ']);
        //Nhận danh sách ngôn ngữ
        Route::get('lang_code/list', 'v1.setting.LangCode/langCodeList')->option(['real_name' => 'Danh sách ngôn ngữ']);
        //Nhận thông tin ngôn ngữ
        Route::get('lang_code/info', 'v1.setting.LangCode/langCodeInfo')->option(['real_name' => 'Chi tiết ngôn ngữ']);
        //Lưu ngôn ngữ đã sửa đổi
        Route::post('lang_code/save', 'v1.setting.LangCode/langCodeSave')->option(['real_name' => 'Lưu ngôn ngữ đã sửa đổi']);
        //Xóa ngôn ngữ
        Route::delete('lang_code/del/:id', 'v1.setting.LangCode/langCodeDel')->option(['real_name' => 'Xóa ngôn ngữ']);
        //dịch máy
        Route::post('lang_code/translate', 'v1.setting.LangCode/langCodeTranslate')->option(['real_name' => 'dịch máy']);
    })->option(['parent' => 'setting', 'cate_name' => 'đa ngôn ngữ']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'setting', 'mark_name' => 'Cài đặt hệ thống']);
