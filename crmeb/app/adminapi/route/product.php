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

Route::group('product', function () {

    /** Phân loại sản phẩm */
    Route::group(function () {
        Route::get('category', 'v1.product.StoreCategory/index')->option(['real_name' => 'Danh sách danh mục sản phẩm']);
        //Danh sách cây sản phẩm
        Route::get('category/tree/:type', 'v1.product.StoreCategory/tree_list')->option(['real_name' => 'Danh sách cây phân loại sản phẩm']);
        //Danh sách cây phân loại sản phẩm
        Route::get('category/cascader/:type', 'v1.product.StoreCategory/cascader_list')->option(['real_name' => 'Danh sách cây phân loại sản phẩm']);
        //Danh mục sản phẩm mẫu mới
        Route::get('category/create', 'v1.product.StoreCategory/create')->option(['real_name' => 'Danh mục sản phẩm mẫu mới']);
        //Danh mục sản phẩm mới
        Route::post('category', 'v1.product.StoreCategory/save')->option(['real_name' => 'Danh mục sản phẩm mới']);
        //Form chỉnh sửa danh mục sản phẩm
        Route::get('category/:id', 'v1.product.StoreCategory/edit')->option(['real_name' => 'Form chỉnh sửa danh mục sản phẩm']);
        //Trình chỉnh sửa danh mục sản phẩm
        Route::put('category/:id', 'v1.product.StoreCategory/update')->option(['real_name' => 'Trình chỉnh sửa danh mục sản phẩm']);
        //Xóa danh mục sản phẩm
        Route::delete('category/:id', 'v1.product.StoreCategory/delete')->option(['real_name' => 'Xóa danh mục sản phẩm']);
        //Trạng thái sửa đổi danh mục sản phẩm
        Route::put('category/set_show/:id/:is_show', 'v1.product.StoreCategory/set_show')->option(['real_name' => 'Trạng thái sửa đổi danh mục sản phẩm']);
        //Chỉnh sửa nhanh các danh mục sản phẩm
        Route::put('category/set_category/:id', 'v1.product.StoreCategory/set_category')->option(['real_name' => 'Chỉnh sửa nhanh các danh mục sản phẩm']);
    })->option(['parent' => 'product', 'cate_name' => 'Danh mục sản phẩm']);

    /** hàng hóa */
    Route::group(function () {
        //Danh sách sản phẩm
        Route::get('product', 'v1.product.StoreProduct/index')->option(['real_name' => 'Danh sách sản phẩm']);
        //Thoát dữ liệu chưa được lưu
        Route::get('cache', 'v1.product.StoreProduct/getCacheData')->option(['real_name' => 'Thoát dữ liệu chưa được lưu']);
        //1Tiết kiệm dữ liệu mỗi phút
        Route::post('cache', 'v1.product.StoreProduct/saveCacheData')->option(['real_name' => 'Lưu dữ liệu chưa gửi']);
        //Nhận danh sách tất cả các sản phẩm
        Route::get('product/list', 'v1.product.StoreProduct/search_list')->option(['real_name' => 'Nhận danh sách Tất cả các sản phẩm']);
        //Nhận thông số kỹ thuật sản phẩm
        Route::get('product/attrs/:id/:type', 'v1.product.StoreProduct/get_attrs')->option(['real_name' => 'Nhận thông số kỹ thuật sản phẩm']);
        //Tiêu đề danh sách sản phẩm
        Route::get('product/type_header', 'v1.product.StoreProduct/type_header')->option(['real_name' => 'Dữ liệu tiêu đề danh sách sản phẩm']);
        //Sửa đổi trạng thái sản phẩm
        Route::put('product/set_show/:id/:is_show', 'v1.product.StoreProduct/set_show')->option(['real_name' => 'Sửa đổi trạng thái sản phẩm']);
        //Chỉnh sửa nhanh sản phẩm
//        Route::put('product/set_product/:id', 'v1.product.StoreProduct/set_product')->option(['real_name' => 'Chỉnh sửa nhanh sản phẩm']);
        //Thiết lập kệ sản phẩm hàng loạt
        Route::put('product/product_show', 'v1.product.StoreProduct/product_show')->option(['real_name' => 'Thiết lập kệ sản phẩm hàng loạt']);
        //Thiết lập loại bỏ sản phẩm hàng loạt
        Route::put('product/product_unshow', 'v1.product.StoreProduct/product_unshow')->option(['real_name' => 'Thiết lập loại bỏ sản phẩm hàng loạt']);
        //Danh sách quy tắc
        Route::get('product/rule', 'v1.product.StoreProductRule/index')->option(['real_name' => 'Danh sách quy tắc sản phẩm']);
        //Quy tắc Lưu mới hoặc chỉnh sửa
        Route::post('product/rule/:id', 'v1.product.StoreProductRule/save')->option(['real_name' => 'Tạo hoặc chỉnh sửa quy tắc sản phẩm']);
        //Chi tiết quy tắc
        Route::get('product/rule/:id', 'v1.product.StoreProductRule/read')->option(['real_name' => 'Chi tiết quy tắc sản phẩm']);
        //Xóa quy tắc thuộc tính
        Route::delete('product/rule/delete', 'v1.product.StoreProductRule/delete')->option(['real_name' => 'Xóa quy tắc sản phẩm']);
        //Nhận mẫu thuộc tính quy tắc
        Route::get('product/get_rule', 'v1.product.StoreProduct/get_rule')->option(['real_name' => 'Nhận mẫu thuộc tính quy tắc sản phẩm']);
        //Nhận mẫu vận chuyển
        Route::get('product/get_template', 'v1.product.StoreProduct/get_template')->option(['real_name' => 'Nhận mẫu vận chuyển']);
        //Giao diện key video upload
        Route::get('product/get_temp_keys', 'v1.product.StoreProduct/getTempKeys')->option(['real_name' => 'Giao diện key video upload']);
        //Kiểm tra xem một hoạt động có mở không
        Route::get('product/check_activity/:id', 'v1.product.StoreProduct/check_activity')->option(['real_name' => 'Kiểm tra xem sản phẩm có kích hoạt hoạt động hay không']);
        //Nhập mã thẻ sản phẩm ảo
        Route::get('product/import_card', 'v1.product.StoreProduct/import_card')->option(['real_name' => 'Nhập mã thẻ sản phẩm ảo']);
        //Chi tiết sản phẩm
        Route::get('product/:id', 'v1.product.StoreProduct/get_product_info')->option(['real_name' => 'Chi tiết sản phẩm']);
        //Thêm vào thùng rác
        Route::delete('product/:id', 'v1.product.StoreProduct/delete')->option(['real_name' => 'Bỏ sản phẩm vào thùng rác']);
        //Thêm vào thùng rác
        Route::post('product/batch_delete', 'v1.product.StoreProduct/batchDelete')->option(['real_name' => 'Bỏ các mục vào thùng rác theo đợt']);
        //Khôi phục hàng loạt từ Thùng rác
        Route::post('product/batch_recover', 'v1.product.StoreProduct/batchRecover')->option(['real_name' => 'Khôi phục hàng loạt từ Thùng rác']);
        //Lưu mới hoặc Lưu
        Route::post('product/:id', 'v1.product.StoreProduct/save')->option(['real_name' => 'Tạo hoặc sửa đổi sản phẩm']);
        //Tạo thuộc tính
        Route::post('generate_attr/:id/:type', 'v1.product.StoreProduct/is_format_attr')->option(['real_name' => 'Tạo danh sách thông số kỹ thuật sản phẩm']);
        //Vận hành lô sản phẩm
        Route::post('batch/setting', 'v1.product.StoreProduct/batchSetting')->option(['real_name' => 'Cài đặt lô sản phẩm']);
        //Giao diện loại sản phẩm
        Route::get('product_type_config', 'v1.product.StoreProduct/productTypeConfig')->option(['real_name' => 'Giao diện loại sản phẩm']);
        //Xuất khẩu di chuyển sản phẩm
        Route::get('product_export', 'v1.product.StoreProduct/productExport')->option(['real_name' => 'Xuất file di chuyển sản phẩm']);
        //Di chuyển và nhập khẩu sản phẩm
        Route::post('product_import', 'v1.product.StoreProduct/productImport')->option(['real_name' => 'Xuất file di chuyển sản phẩm']);
        //Xóa hoàn toàn các mục khỏi thùng rác
        Route::delete('full_del/:id', 'v1.product.StoreProduct/fullDel')->option(['real_name' => 'Xóa hoàn toàn các mục khỏi thùng rác']);

        Route::get('other_info/:id/:type', 'v1.product.StoreProduct/otherInfo')->option(['real_name' => 'Thông tin sản phẩm khác']);
        Route::post('other_save/:id/:type', 'v1.product.StoreProduct/otherSave')->option(['real_name' => 'Sửa đổi thông tin sản phẩm khác']);

    })->option(['parent' => 'product', 'cate_name' => 'sản phẩm']);

    /** đánh giá sản phẩm */
    Route::group(function () {
        //Danh sách bình luận
        Route::get('reply', 'v1.product.StoreProductReply/index')->option(['real_name' => 'Danh sách Đánh giá sản phẩm']);
        //Trả lời bình luận
        Route::put('reply/set_reply/:id', 'v1.product.StoreProductReply/set_reply')->option(['real_name' => 'Sản phẩm trả lời bình luận']);
        //Xóa bình luận
        Route::delete('reply/:id', 'v1.product.StoreProductReply/delete')->option(['real_name' => 'Xóa Đánh giá sản phẩm']);
        //Đưa ra mẫu bình luận ảo
        Route::get('reply/fictitious_reply/:product_id', 'v1.product.StoreProductReply/fictitious_reply')->option(['real_name' => 'Mẫu bình luận ảo']);
        //Lưu đánh giá ảo
        Route::post('reply/save_fictitious_reply', 'v1.product.StoreProductReply/save_fictitious_reply')->option(['real_name' => 'Lưu đánh giá ảo']);
        //Đánh giá đánh giá sản phẩm
        Route::put('reply/set_status/:id/:status', 'v1.product.StoreProductReply/set_status')->option(['real_name' => 'Đánh giá Đánh giá sản phẩm']);
        //Đánh giá sản phẩm theo đợt
        Route::post('reply/batch_set_status', 'v1.product.StoreProductReply/batch_set_status')->option(['real_name' => 'Đánh giá sản phẩm theo đợt']);
    })->option(['parent' => 'product', 'cate_name' => 'Đánh giá sản phẩm']);

    /** Bộ sưu tập sản phẩm */
    Route::group(function () {
        //Lấy dữ liệu sản phẩm
        Route::post('crawl', 'v1.product.CopyTaobao/get_request_contents')->option(['real_name' => 'Nhận dữ liệu sản phẩm được thu thập']);
        //Nhận cấu hình sản phẩm sao chép
        Route::get('copy_config', 'v1.product.CopyTaobao/getConfig')->option(['real_name' => 'Nhận cấu hình sản phẩm sao chép']);
        //Sao chép sản phẩm từ nền tảng khác
        Route::post('copy', 'v1.product.CopyTaobao/copyProduct')->option(['real_name' => 'Sao chép sản phẩm từ nền tảng khác']);
        //Lưu dữ liệu sản phẩm
        Route::post('crawl/save', 'v1.product.CopyTaobao/save_product')->option(['real_name' => 'Lưu dữ liệu sản phẩm đã thu thập']);
    })->option(['parent' => 'product', 'cate_name' => 'Sản phẩm yêu thích']);

    /** Thẻ sản phẩm */
    Route::group(function () {
        //Phân loại nhãn sản phẩm
        Route::get('label_cate/list', 'v1.product.StoreProductLabel/labelCateList')->option(['real_name' => 'Phân loại nhãn sản phẩm']);
        Route::get('label_cate/form/:id', 'v1.product.StoreProductLabel/labelCateForm')->option(['real_name' => 'Biểu mẫu thêm phân loại thẻ sản phẩm']);
        Route::post('label_cate/save/:id', 'v1.product.StoreProductLabel/labelCateSave')->option(['real_name' => 'Phân loại và lưu trữ nhãn sản phẩm']);
        Route::delete('label_cate/del/:id', 'v1.product.StoreProductLabel/labelCateDel')->option(['real_name' => 'Xóa danh mục thẻ sản phẩm']);
        Route::get('label/list', 'v1.product.StoreProductLabel/labelList')->option(['real_name' => 'Danh sách thẻ sản phẩm']);
        Route::get('label/info/:id', 'v1.product.StoreProductLabel/labelInfo')->option(['real_name' => 'Chi tiết thẻ sản phẩm']);
        Route::post('label/save', 'v1.product.StoreProductLabel/labelSave')->option(['real_name' => 'Lưu thẻ sản phẩm']);
        Route::delete('label/del/:id', 'v1.product.StoreProductLabel/labelDel')->option(['real_name' => 'Xóa thẻ sản phẩm']);
        Route::put('label/status/:id/:status', 'v1.product.StoreProductLabel/labelStatus')->option(['real_name' => 'Sửa đổi trạng thái nhãn sản phẩm']);
        Route::put('label/is_show/:id/:is_show', 'v1.product.StoreProductLabel/labelIsShow')->option(['real_name' => 'Sửa đổi hiển thị nhãn sản phẩm']);
        Route::get('label/use_list', 'v1.product.StoreProductLabel/labelUseList')->option(['real_name' => 'Sử dụng danh sách thẻ sản phẩm']);
    })->option(['parent' => 'product', 'cate_name' => 'Nhãn sản phẩm']);

    /** Thông số sản phẩm */
    Route::group(function () {
        Route::get('param/list', 'v1.product.StoreProductParam/getParamList')->option(['real_name' => 'Danh sách thông số sản phẩm']);
        Route::get('param/info/:id', 'v1.product.StoreProductParam/getParamInfo')->option(['real_name' => 'Chi tiết thông số sản phẩm']);
        Route::get('param/value/:id', 'v1.product.StoreProductParam/getParamValue')->option(['real_name' => 'Giá trị thông số sản phẩm']);
        Route::post('param/save/:id', 'v1.product.StoreProductParam/saveParamData')->option(['real_name' => 'Lưu thông số sản phẩm']);
        Route::put('param/status/:id/:status', 'v1.product.StoreProductParam/setParamStatus')->option(['real_name' => 'Sửa đổi trạng thái thông số sản phẩm']);
        Route::delete('param/del/:id', 'v1.product.StoreProductParam/delParamData')->option(['real_name' => 'Xóa thông số sản phẩm']);
    })->option(['parent' => 'product', 'cate_name' => 'Thuộc tính sản phẩm']);

    /** Bảo vệ sản phẩm */
    Route::group(function () {
        Route::get('protection/list', 'v1.product.StoreProductProtection/protectionList')->option(['real_name' => 'Danh sách bảo vệ sản phẩm']);
        Route::get('protection/info/:id', 'v1.product.StoreProductProtection/protectionInfo')->option(['real_name' => 'Chi tiết bảo vệ sản phẩm']);
        Route::get('protection/form/:id', 'v1.product.StoreProductProtection/protectionForm')->option(['real_name' => 'Mẫu bảo vệ sản phẩm']);
        Route::post('protection/save/:id', 'v1.product.StoreProductProtection/protectionSave')->option(['real_name' => 'Tiết kiệm bảo vệ sản phẩm']);
        Route::put('protection/status/:id/:status', 'v1.product.StoreProductProtection/protectionStatus')->option(['real_name' => 'Sửa đổi trạng thái bảo vệ sản phẩm']);
        Route::delete('protection/del/:id', 'v1.product.StoreProductProtection/protectionDel')->option(['real_name' => 'Loại bỏ bảo vệ sản phẩm']);
    })->option(['parent' => 'product', 'cate_name' => 'Thuộc tính sản phẩm']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'product', 'mark_name' => 'Quản lý sản phẩm']);
