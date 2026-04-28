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
 * Định tuyến liên quan đến mô-đun ứng dụng
 */
Route::group('app', function () {

    /** Tài khoản chính thức */
    Route::group(function () {
        //giá trị thực đơn
        Route::get('wechat/menu', 'v1.application.wechat.menus/index')->option(['real_name' => 'Danh sách menu tài khoản công khai WeChat']);
        //lưu thực đơn
        Route::post('wechat/menu', 'v1.application.wechat.menus/save')->option(['real_name' => 'Lưu menu tài khoản chính thức của WeChat']);
        //Danh sách hình ảnh và văn bản
        Route::get('wechat/news', 'v1.application.wechat.WechatNewsCategory/index')->option(['real_name' => 'Danh sách hình ảnh và văn bản']);
        //Chi tiết
        Route::get('wechat/news/:id', 'v1.application.wechat.WechatNewsCategory/read')->option(['real_name' => 'Chi tiết hình ảnh và văn bản']);
        //Lưu hình ảnh và văn bản
        Route::post('wechat/news', 'v1.application.wechat.WechatNewsCategory/save')->option(['real_name' => 'Lưu hình ảnh và văn bản']);
        //Xóa hình ảnh và văn bản
        Route::delete('wechat/news/:id', 'v1.application.wechat.WechatNewsCategory/delete')->option(['real_name' => 'Xóa hình ảnh và văn bản']);
        //Gửi tin nhắn đồ họa
        Route::post('wechat/push', 'v1.application.wechat.WechatNewsCategory/push')->option(['real_name' => 'Gửi tin nhắn đồ họa']);
        //Theo dõi câu trả lời
        Route::get('wechat/reply', 'v1.application.wechat.Reply/reply')->option(['real_name' => 'Theo dõi câu trả lời']);
        //Nhận được sự chú ý trả lời mã QR
        Route::get('wechat/code_reply/:id', 'v1.application.wechat.Reply/code_reply')->option(['real_name' => 'Nhận được sự chú ý trả lời mã QR']);
        //Danh sách trả lời từ khóa
        Route::get('wechat/keyword', 'v1.application.wechat.Reply/index')->option(['real_name' => 'Danh sách trả lời từ khóa']);
        //Chi tiết từ khóa
        Route::get('wechat/keyword/:id', 'v1.application.wechat.Reply/read')->option(['real_name' => 'Chi tiết trả lời từ khóa']);
        //Lưu thay đổi từ khóa
        Route::post('wechat/keyword/:id', 'v1.application.wechat.Reply/save')->option(['real_name' => 'Lưu câu trả lời từ khóa']);
        //Xóa từ khóa
        Route::delete('wechat/keyword/:id', 'v1.application.wechat.Reply/delete')->option(['real_name' => 'Xóa câu trả lời từ khóa']);
        //Sửa đổi trạng thái từ khóa
        Route::put('wechat/keyword/set_status/:id/:status', 'v1.application.wechat.Reply/set_status')->option(['real_name' => 'Sửa đổi trạng thái trả lời từ khóa']);
        //Đồng bộ hóa tin nhắn mẫu WeChat bằng một cú nhấp chuột
        Route::get('wechat/syncSubscribe', 'v1.application.wechat.WechatTemplate/syncSubscribe')->name('syncSubscribe')->option(['real_name' => 'Đồng bộ hóa tin nhắn mẫu bằng một cú nhấp chuột']);
    })->option(['parent' => 'app', 'cate_name' => 'Tài khoản chính thức']);

    /** Chương trình nhỏ */
    Route::group(function () {
        //Đồng bộ hóa tin nhắn đăng ký bằng một cú nhấp chuột
        Route::get('routine/syncSubscribe', 'v1.application.routine.RoutineTemplate/syncSubscribe')->name('syncSubscribe')->option(['real_name' => 'Đồng bộ hóa tin nhắn đăng ký bằng một cú nhấp chuột']);
        //Tải xuống dữ liệu trang mẫu chương trình nhỏ
        Route::get('routine/info', 'v1.application.routine.RoutineTemplate/getDownloadInfo')->option(['real_name' => 'Tải xuống dữ liệu trang chương trình nhỏ']);
        //Tải xuống mẫu chương trình nhỏ
        Route::post('routine/download', 'v1.application.routine.RoutineTemplate/downloadTemp')->option(['real_name' => 'Tải xuống mẫu chương trình nhỏ']);

        // ==================== Tải lên tự động CI chương trình nhỏ ====================
        //Nhận trạng thái môi trường đang chạy
        Route::get('routine/ci/environment', 'v1.application.routine.RoutineCI/environment')->option(['real_name' => 'Có được môi trường chạy CI chương trình nhỏ']);
        //Nhận hướng dẫn cài đặt
        Route::get('routine/ci/guide', 'v1.application.routine.RoutineCI/installGuide')->option(['real_name' => 'Nhận hướng dẫn cài đặt môi trường']);
        //Nhận cấu hình tải lên
        Route::get('routine/ci/config', 'v1.application.routine.RoutineCI/uploadConfig')->option(['real_name' => 'Nhận cấu hình tải lên chương trình nhỏ']);
        //Lưu khóa tải lên
        Route::post('routine/ci/private_key', 'v1.application.routine.RoutineCI/savePrivateKey')->option(['real_name' => 'Lưu khóa tải lên chương trình mini']);
        //Tải lên mã chương trình nhỏ
        Route::post('routine/ci/upload', 'v1.application.routine.RoutineCI/upload')->option(['real_name' => 'Tải lên mã chương trình nhỏ']);
        //Nhận mã QR xem trước
        Route::post('routine/ci/preview', 'v1.application.routine.RoutineCI/preview')->option(['real_name' => 'Nhận mã QR xem trước chương trình mini']);

        Route::get('routine/scheme_list', 'v1.application.routine.RoutineScheme/schemeList')->name('schemeList')->option(['real_name' => 'Danh sách liên kết bên ngoài chương trình nhỏ']);
        Route::get('routine/scheme_form/:id', 'v1.application.routine.RoutineScheme/schemeForm')->name('schemeForm')->option(['real_name' => 'Mẫu bổ sung và sửa đổi liên kết bên ngoài chương trình nhỏ']);
        Route::post('routine/scheme_save/:id', 'v1.application.routine.RoutineScheme/schemeSave')->name('schemeSave')->option(['real_name' => 'Thêm, sửa đổi và lưu các liên kết bên ngoài vào chương trình mini']);
        Route::delete('routine/scheme_del/:id', 'v1.application.routine.RoutineScheme/schemeDel')->name('schemeDel')->option(['real_name' => 'Chương trình nhỏ xóa liên kết bên ngoài']);


    })->option(['parent' => 'app', 'cate_name' => 'Chương trình nhỏ']);

    /** Mã kênh tài khoản chính thức */
    Route::group(function () {
        Route::get('wechat_qrcode/cate/list', 'v1.application.wechat.WechatQrcode/getCateList')->option(['real_name' => 'Danh sách phân loại mã kênh']);
        Route::get('wechat_qrcode/cate/create/:id', 'v1.application.wechat.WechatQrcode/createForm')->option(['real_name' => 'Thêm biểu mẫu chỉnh sửa cho danh mục mã kênh']);
        Route::post('wechat_qrcode/cate/save', 'v1.application.wechat.WechatQrcode/saveCate')->option(['real_name' => 'Phân loại và lưu trữ mã kênh']);
        Route::delete('wechat_qrcode/cate/del/:id', 'v1.application.wechat.WechatQrcode/delCate')->option(['real_name' => 'Xóa danh mục mã kênh']);
        Route::post('wechat_qrcode/save/:id', 'v1.application.wechat.WechatQrcode/saveQrcode')->option(['real_name' => 'Lưu mã kênh']);
        Route::get('wechat_qrcode/info/:id', 'v1.application.wechat.WechatQrcode/qrcodeInfo')->option(['real_name' => 'Chi tiết mã kênh']);
        Route::get('wechat_qrcode/list', 'v1.application.wechat.WechatQrcode/qrcodeList')->option(['real_name' => 'Danh sách mã kênh']);
        Route::delete('wechat_qrcode/del/:id', 'v1.application.wechat.WechatQrcode/delQrcode')->option(['real_name' => 'Xóa mã kênh']);
        Route::put('wechat_qrcode/set_status/:id/:status', 'v1.application.wechat.WechatQrcode/setStatus')->option(['real_name' => 'Chuyển trạng thái mã kênh']);
        Route::get('wechat_qrcode/user_list/:qid', 'v1.application.wechat.WechatQrcode/userList')->option(['real_name' => 'Danh sách người dùng mã kênh']);
        Route::get('wechat_qrcode/statistic/:qid', 'v1.application.wechat.WechatQrcode/qrcodeStatistic')->option(['real_name' => 'Thống kê mã kênh']);
    })->option(['parent' => 'app', 'cate_name' => 'Mã kênh tài khoản chính thức']);

    /** Dịch vụ khách hàng liên quan */
    Route::group(function () {
        //Giao diện phản hồi dịch vụ khách hàng
        Route::resource('feedback', 'v1.kefu.StoreServiceFeedback')->only(['index', 'delete', 'update', 'edit'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách phản hồi của người dùng',
                'edit' => 'Nhận mẫu phản hồi của người dùng đã sửa đổi',
                'update' => 'Sửa đổi phản hồi của người dùng',
                'delete' => 'Xóa phản hồi của người dùng'
            ]
        ]);
        //giao diện lời nói
        Route::resource('wechat/speechcraft', 'v1.kefu.StoreServiceSpeechcraft')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách các cụm từ dịch vụ khách hàng',
                'create' => 'Nhận mẫu kỹ năng dịch vụ khách hàng',
                'save' => 'Lưu kỹ năng phục vụ khách hàng',
                'edit' => 'Lấy mẫu để sửa đổi kỹ năng dịch vụ khách hàng',
                'update' => 'Sửa đổi từ vựng dịch vụ khách hàng',
                'delete' => 'Xóa từ dịch vụ khách hàng'
            ]
        ]);
        //Giao diện phân loại giọng nói
        Route::resource('wechat/speechcraftcate', 'v1.kefu.StoreServiceSpeechcraftCate')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách các hạng mục kỹ năng dịch vụ khách hàng',
                'create' => 'Nhận mẫu phân loại kỹ năng dịch vụ khách hàng',
                'save' => 'Lưu danh mục kỹ năng dịch vụ khách hàng',
                'edit' => 'Lấy biểu mẫu sửa đổi phân loại từ vựng dịch vụ khách hàng',
                'update' => 'Sửa danh mục kỹ năng nói dịch vụ khách hàng',
                'delete' => 'Xóa danh mục kỹ năng dịch vụ khách hàng'
            ]
        ]);
        //Danh sách dịch vụ khách hàng
        Route::get('wechat/kefu', 'v1.kefu.StoreService/index')->option(['real_name' => 'Danh sách dịch vụ khách hàng']);
        //Đăng nhập dịch vụ khách hàng
        Route::get('wechat/kefu/login/:id', 'v1.kefu.StoreService/keufLogin')->option(['real_name' => 'Đăng nhập dịch vụ khách hàng']);
        //Đã thêm danh sách người dùng lựa chọn dịch vụ khách hàng
        Route::get('wechat/kefu/create', 'v1.kefu.StoreService/create')->option(['real_name' => 'Đã thêm danh sách người dùng lựa chọn dịch vụ khách hàng']);
        //Thêm biểu mẫu dịch vụ khách hàng
        Route::get('wechat/kefu/add', 'v1.kefu.StoreService/add')->option(['real_name' => 'Thêm biểu mẫu dịch vụ khách hàng']);
        //Lưu dữ liệu mới tạo
        Route::post('wechat/kefu', 'v1.kefu.StoreService/save')->option(['real_name' => 'Thêm dịch vụ khách hàng']);
        //Chỉnh sửa biểu mẫu dịch vụ khách hàng
        Route::get('wechat/kefu/:id/edit', 'v1.kefu.StoreService/edit')->option(['real_name' => 'Sửa đổi mẫu dịch vụ khách hàng']);
        //Lưu dữ liệu đã chỉnh sửa
        Route::put('wechat/kefu/:id', 'v1.kefu.StoreService/update')->option(['real_name' => 'Sửa đổi dịch vụ khách hàng']);
        //xóa bỏ
        Route::delete('wechat/kefu/:id', 'v1.kefu.StoreService/delete')->option(['real_name' => 'Xóa dịch vụ khách hàng']);
        //Sửa đổi trạng thái
        Route::put('wechat/kefu/set_status/:id/:status', 'v1.kefu.StoreService/set_status')->option(['real_name' => 'Sửa đổi trạng thái dịch vụ khách hàng']);
        //Lịch sử trò chuyện
        Route::get('wechat/kefu/record/:id', 'v1.kefu.StoreService/chat_user')->option(['real_name' => 'Lịch sử trò chuyện']);
        //Xem cuộc trò chuyện
        Route::get('wechat/kefu/chat_list', 'v1.kefu.StoreService/chat_list')->option(['real_name' => 'Xem cuộc trò chuyện']);

        //Danh sách trả lời tự động của dịch vụ khách hàng
        Route::get('kefu/auto_reply/list', 'v1.kefu.StoreServiceAutoReply/autoReplyList')->option(['real_name' => 'Danh sách trả lời tự động của dịch vụ khách hàng']);
        //Dịch vụ khách hàng tự động trả lời để thêm và sửa đổi biểu mẫu
        Route::get('kefu/auto_reply/form/:id', 'v1.kefu.StoreServiceAutoReply/autoReplyForm')->option(['real_name' => 'Dịch vụ khách hàng tự động trả lời để thêm và sửa đổi biểu mẫu']);
        //Dịch vụ khách hàng tự động trả lời, thêm, thay đổi và lưu
        Route::post('kefu/auto_reply/save/:id', 'v1.kefu.StoreServiceAutoReply/autoReplySave')->option(['real_name' => 'Dịch vụ khách hàng tự động trả lời, thêm, thay đổi và lưu']);
        //Dịch vụ khách hàng tự động trả lời để sửa đổi trạng thái
        Route::put('kefu/auto_reply/status/:id/:status', 'v1.kefu.StoreServiceAutoReply/autoReplyStatus')->option(['real_name' => 'Dịch vụ khách hàng tự động trả lời để sửa đổi trạng thái']);
        //Dịch vụ khách hàng tự động xóa trả lời
        Route::delete('kefu/auto_reply/del/:id', 'v1.kefu.StoreServiceAutoReply/autoReplyDel')->option(['real_name' => 'Dịch vụ khách hàng tự động xóa trả lời']);

    })->option(['parent' => 'app', 'cate_name' => 'Dịch vụ khách hàng liên quan']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'app', 'mark_name' => 'mô-đun ứng dụng']);
