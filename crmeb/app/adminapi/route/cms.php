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
 * Các tuyến đường liên quan đến quản lý bài viết
 */
Route::group('cms', function () {

    /** bài báo */
    Route::group(function () {
        //Định tuyến tài nguyên bài viết
        Route::resource('cms', 'v1.cms.Article')->option([
            'real_name' => [
                'index' => 'Nhận danh sách bài viết',
                'create' => 'Nhận mẫu bài viết',
                'read' => 'Nhận chi tiết bài viết',
                'save' => 'Lưu bài viết',
                'edit' => 'Nhận mẫu bài viết chỉnh sửa',
                'update' => 'Sửa đổi bài viết',
                'delete' => 'Xóa bài viết'
            ]
        ]);
        //Sản phẩm liên quan
        Route::put('cms/relation/:id', 'v1.cms.Article/relation')->name('Relation')->option(['real_name' => 'Sản phẩm liên quan đến bài viết']);
        //Tách rời
        Route::put('cms/unrelation/:id', 'v1.cms.Article/unrelation')->name('UnRelation')->option(['real_name' => 'Hủy bài viết liên quan đến sản phẩm']);
    })->option(['parent' => 'cms', 'cate_name' => 'Quản lý bài viết']);

    /** Phân loại bài viết */
    Route::group(function () {
        //Định tuyến tài nguyên phân loại bài viết
        Route::resource('category', 'v1.cms.ArticleCategory')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách các danh mục bài viết',
                'create' => 'Nhận mẫu phân loại bài viết',
                'save' => 'Lưu danh mục bài viết',
                'edit' => 'Nhận mẫu phân loại bài viết sửa đổi',
                'update' => 'Sửa đổi danh mục bài viết',
                'delete' => 'Xóa danh mục bài viết'
            ]
        ]);
        //Sửa đổi trạng thái
        Route::put('category/set_status/:id/:status', 'v1.cms.ArticleCategory/set_status')->name('CategoryStatus')->option(['real_name' => 'Sửa đổi trạng thái phân loại bài viết']);
        //Danh sách danh mục
        Route::get('category_list', 'v1.cms.ArticleCategory/categoryList')->name('categoryList')->option(['real_name' => 'Danh sách danh mục']);
        //Danh sách cây danh mục
        Route::get('category_tree_list', 'v1.cms.ArticleCategory/getTreeList')->name('getTreeList')->option(['real_name' => 'Danh sách cây danh mục']);
    })->option(['parent' => 'cms', 'cate_name' => 'Phân loại bài viết']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'cms', 'mark_name' => 'Mô-đun bài viết']);
