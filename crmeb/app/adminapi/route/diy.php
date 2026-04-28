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
 * diy Các tuyến đường liên quan
 */
Route::group('diy', function () {

    Route::get('get_list', 'v1.diy.Diy/getList')->option(['real_name' => 'DiyDanh sách mẫu']);
    Route::get('get_info/:id', 'v1.diy.Diy/getInfo')->option(['real_name' => 'DiyChi tiết dữ liệu mẫu']);
    Route::get('get_diy_info/:id', 'v1.diy.Diy/getDiyInfo')->option(['real_name' => 'DiyChi tiết dữ liệu mẫu']);
    Route::delete('del/:id', 'v1.diy.Diy/del')->option(['real_name' => 'Xóa mẫu DIY']);
    Route::put('set_status/:id', 'v1.diy.Diy/setStatus')->option(['real_name' => 'Sử dụng các mẫu DIY']);
    Route::get('create', 'v1.diy.Diy/create')->option(['real_name' => 'Thêm biểu mẫu']);
    Route::post('create', 'v1.diy.Diy/save')->option(['real_name' => 'Thêm mớiDIY']);
    Route::post('save/[:id]', 'v1.diy.Diy/saveData')->option(['real_name' => 'Thêm mẫu DIY']);
    Route::post('diy_save/[:id]', 'v1.diy.Diy/saveDiyData')->option(['real_name' => 'Thêm mẫu DIY']);
    Route::get('get_url', 'v1.diy.Diy/getUrl')->option(['real_name' => 'Lấy đường dẫn trang front-end']);
    Route::get('get_category', 'v1.diy.Diy/getCategory')->option(['real_name' => 'Nhận phân loại sản phẩm']);
    Route::get('get_product', 'v1.diy.Diy/getProduct')->option(['real_name' => 'Nhận danh sách sản phẩm']);
    Route::get('get_store_status', 'v1.diy.Diy/getStoreStatus')->option(['real_name' => 'Nhận trạng thái mở cửa hàng lấy hàng']);
    Route::get('recovery/:id', 'v1.diy.Diy/Recovery')->option(['real_name' => 'Khôi phục dữ liệu mặc định DIY']);
    Route::get('get_by_category', 'v1.diy.Diy/getByCategory')->option(['real_name' => 'Nhận Tất cả các danh mục phụ']);
    Route::get('set_recovery/:id', 'v1.diy.Diy/setRecovery')->option(['real_name' => 'Đặt dữ liệu mặc định DIY']);
    Route::get('get_product_list', 'v1.diy.Diy/getProductList')->option(['real_name' => 'Nhận danh sách sản phẩm']);
    Route::get('get_color_change/:type', 'v1.diy.Diy/getColorChange')->option(['real_name' => 'Nhận cài đặt kiểu']);
    Route::put('color_change/:status/:type', 'v1.diy.Diy/colorChange')->option(['real_name' => 'Thay đổi màu sắc và lưu phân loại']);
    Route::get('get_member', 'v1.diy.Diy/getMember')->option(['real_name' => 'Chi tiết trung tâm cá nhân']);
    Route::get('get_page_category', 'v1.diy.PageLink/getCategory')->option(['real_name' => 'Nhận danh mục liên kết trang']);
    Route::get('get_page_link/:cate_id', 'v1.diy.PageLink/getLinks')->option(['real_name' => 'Nhận liên kết trang']);
    Route::post('member_save', 'v1.diy.Diy/memberSaveData')->option(['real_name' => 'Lưu vào trung tâm cá nhân']);
    Route::get('get_routine_code/:id', 'v1.diy.Diy/getRoutineCode')->option(['real_name' => 'diyMã xem trước chương trình nhỏ']);
    Route::get('open_adv/info', 'v1.diy.Diy/getOpenAdv')->option(['real_name' => 'Nhận quảng cáo màn hình mở']);
    Route::post('open_adv/add', 'v1.diy.Diy/openAdvAdd')->option(['real_name' => 'Lưu quảng cáo màn hình mở']);
    Route::get('groom_list/:type', 'v1.diy.Diy/getGroomList')->option(['real_name' => 'Sản phẩm được đề xuất']);
    Route::get('link/category', 'v1.diy.PageLink/getLinkCategory')->option(['real_name' => 'Nhận phân loại liên kết']);
    Route::get('link/category/form/:cate_id/[:pid]', 'v1.diy.PageLink/getLinkCategoryForm')->option(['real_name' => 'Biểu mẫu danh mục liên kết']);
    Route::post('link/category/save/:cate_id', 'v1.diy.PageLink/getLinkCategorySave')->option(['real_name' => 'Lưu liên kết theo danh mục']);
    Route::delete('link/category/del/:cate_id', 'v1.diy.PageLink/getLinkCategoryDel')->option(['real_name' => 'Xóa danh mục liên kết']);
    Route::get('link/list/:cate_id', 'v1.diy.PageLink/getLinkList')->option(['real_name' => 'Danh sách liên kết']);
    Route::post('link/save/:id', 'v1.diy.PageLink/getLinkSave')->option(['real_name' => 'lưu liên kết']);
    Route::delete('link/del/:id', 'v1.diy.PageLink/getLinkDel')->option(['real_name' => 'Xóa liên kết']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'diy', 'mark_name' => 'trang trí trang']);


/**
 * diy_pro Các tuyến đường liên quan
 */
Route::group('diy_pro', function () {
    Route::get('get_list', 'v1.diy.DiyPro/getList')->option(['real_name' => 'DiyProDanh sách mẫu']);
    Route::get('get_info/:id', 'v1.diy.DiyPro/getInfo')->option(['real_name' => 'DiyProChi tiết mẫu']);
    Route::post('save/:id', 'v1.diy.DiyPro/saveInfo')->option(['real_name' => 'DiyProLưu mẫu']);
    Route::get('get_product', 'v1.diy.DiyPro/getProduct')->option(['real_name' => 'Nhận danh sách sản phẩm']);
    Route::post('update/name/:id', 'v1.diy.DiyPro/updateName')->option(['real_name' => 'Sửa đổi tên']);
    Route::get('export/data/:id', 'v1.diy.DiyPro/exportDIYData')->option(['real_name' => 'Xuất dữ liệu DIY']);
    Route::post('import/data', 'v1.diy.DiyPro/importDIYData')->option(['real_name' => 'Nhập dữ liệu DIY']);
    Route::get('text/field', 'v1.diy.DiyPro/textField')->option(['real_name' => 'trường văn bản']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'diy_pro', 'mark_name' => 'trang trí trang']);


/**
 * Định tuyến liên quan đến chủ đề
 */
Route::group('theme', function () {
    Route::get('list', 'v1.diy.Theme/getThemeList')->option(['real_name' => 'Danh sách chủ đề']);
    Route::get('info/:id/[:type]', 'v1.diy.Theme/getThemeInfo')->option(['real_name' => 'Chi tiết chủ đề']);
    Route::post('save/:id', 'v1.diy.Theme/saveTheme')->option(['real_name' => 'lưu chủ đề']);
    Route::post('save_title/:id', 'v1.diy.Theme/saveThemeTitle')->option(['real_name' => 'Lưu tên chủ đề và giới thiệu']);
    Route::post('save_image/:id', 'v1.diy.Theme/saveThemeImage')->option(['real_name' => 'Lưu hình ảnh của chủ đề']);
    Route::get('article', 'v1.diy.Theme/getThemeArticleList')->option(['real_name' => 'Thành phần tùy chỉnh-Bài viết']);
    Route::get('coupon', 'v1.diy.Theme/getThemeCouponList')->option(['real_name' => 'Mã giảm giá thành phần tùy chỉnh']);
    Route::get('product', 'v1.diy.Theme/getThemeProductList')->option(['real_name' => 'Thành phần-sản phẩm tùy chỉnh']);
    Route::delete('del/:id', 'v1.diy.Theme/deleteTheme')->option(['real_name' => 'Xóa chủ đề']);
    Route::get('export/:id', 'v1.diy.Theme/exportTheme')->option(['real_name' => 'Xuất chủ đề']);
    Route::get('export_record/:record_id', 'v1.diy.Theme/getExportRecord')->option(['real_name' => 'Bản ghi xuất chủ đề truy vấn']);
    Route::post('import', 'v1.diy.Theme/importTheme')->option(['real_name' => 'Nhập chủ đề']);
    Route::get('use/:id', 'v1.diy.Theme/useTheme')->option(['real_name' => 'Sử dụng chủ đề']);
    Route::get('use_data/:id', 'v1.diy.Theme/useThemeData')->option(['real_name' => 'Sử dụng dữ liệu chủ đề']);
    Route::get('using', 'v1.diy.Theme/getUsingTheme')->option(['real_name' => 'Chủ đề đang được sử dụng']);
    Route::get('restore/:id', 'v1.diy.Theme/restoreTheme')->option(['real_name' => 'Khôi phục chủ đề']);
    Route::get('micro_page', 'v1.diy.Theme/getMicroPageList')->option(['real_name' => 'Danh sách trang vi mô']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'theme', 'mark_name' => 'chủ đề']);

/**
 * Định tuyến liên quan đến thành phần chủ đề
 */
Route::group('theme_module', function () {
    Route::get('list', 'v1.diy.ThemeModule/index')->option(['real_name' => 'Danh sách các thành phần chủ đề']);
    Route::post('save', 'v1.diy.ThemeModule/save')->option(['real_name' => 'Thêm các thành phần chủ đề mới']);
    Route::delete('del/:id', 'v1.diy.ThemeModule/delete')->option(['real_name' => 'Xóa các thành phần chủ đề']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'theme_module', 'mark_name' => 'Thành phần chủ đề']);
