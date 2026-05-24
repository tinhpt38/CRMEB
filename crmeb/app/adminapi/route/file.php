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
 * Định tuyến liên quan đến tệp đính kèm
 */Route::group('file', function () {
    //Danh sách đính kèm
    Route::get('file', 'v1.file.SystemAttachment/index')->option(['real_name' => 'Danh sách đính kèm hình ảnh']);
    //Xóa hình ảnh và bản ghi dữ liệu
    Route::post('file/delete', 'v1.file.SystemAttachment/delete')->option(['real_name' => 'Xóa ảnh']);
    //Hình thức chia sẻ hình ảnh di động
    Route::get('file/move', 'v1.file.SystemAttachment/move')->option(['real_name' => 'Mẫu phân loại hình ảnh di động']);
    //Phân loại hình ảnh di động
    Route::put('file/do_move', 'v1.file.SystemAttachment/moveImageCate')->option(['real_name' => 'Phân loại hình ảnh di động']);
    //Sửa tên ảnh
    Route::put('file/update/:id', 'v1.file.SystemAttachment/update')->option(['real_name' => 'Sửa tên ảnh']);
    //Tải ảnh lên
    Route::post('upload/[:upload_type]', 'v1.file.SystemAttachment/upload')->option(['real_name' => 'Tải ảnh lên']);
    //Định tuyến tài nguyên quản lý phân loại tệp đính kèm
    Route::resource('category', 'v1.file.SystemAttachmentCategory')->except(['read'])->option([
        'real_name' => [
            'index' => 'Nhận danh sách quản lý phân loại tệp đính kèm',
            'create' => 'Nhận biểu mẫu quản lý phân loại tệp đính kèm',
            'save' => 'Lưu quản lý phân loại tệp đính kèm',
            'edit' => 'Nhận biểu mẫu quản lý phân loại tệp đính kèm sửa đổi',
            'update' => 'Sửa đổi quản lý phân loại tệp đính kèm',
            'delete' => 'Xóa quản lý phân loại tệp đính kèm'
        ],

    ]);
    //Nhận loại tải lên
    Route::get('upload_type', 'v1.file.SystemAttachment/uploadType')->option(['real_name' => 'Loại tải lên']);
    //Tải video cục bộ lên theo từng phần
    Route::post('video_upload', 'v1.file.SystemAttachment/videoUpload')->option(['real_name' => 'Tải video cục bộ lên theo từng phần']);
    //Lưu trữ video lưu trữ đám mây dữ liệu
    Route::post('video_data_save', 'v1.file.SystemAttachment/videoDataSave')->option(['real_name' => 'Lưu trữ video lưu trữ đám mây dữ liệu']);
    //Lấy link trang upload code scan và thông số
    Route::get('scan_upload/qrcode', 'v1.file.SystemAttachment/scanUploadQrcode')->option(['real_name' => 'Quét mã để tải lên liên kết trang']);
    //Xóa tải lên mã quéttoken
    Route::delete('scan_upload/qrcode', 'v1.file.SystemAttachment/removeUploadQrcode')->option(['real_name' => 'Xóa liên kết trang tải lên mã quét']);
    //Lấy dữ liệu hình ảnh được tải lên bằng cách quét mã QR
    Route::get('scan_upload/image/:scan_token', 'v1.file.SystemAttachment/scanUploadImage')->option(['real_name' => 'Lấy dữ liệu hình ảnh được tải lên bằng cách quét mã QR']);
    //Tải hình ảnh lên Internet
    Route::post('online_upload', 'v1.file.SystemAttachment/onlineUpload')->option(['real_name' => 'Tải hình ảnh lên Internet']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'file', 'mark_name' => 'Quản lý vật tư']);
