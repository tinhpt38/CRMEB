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
namespace app\api\controller\v1\publics;

use app\services\article\ArticleCategoryServices;
use crmeb\services\CacheService;

/**
 * Lớp phân loại bài viết
 * Class ArticleCategoryController
 * @package app\api\controller\publics
 */
class ArticleCategoryController
{
    protected $services;

    public function __construct(ArticleCategoryServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách danh mục bài viết
     * @return mixed
     */
    public function lst()
    {
        $cateInfo = CacheService::remember('ARTICLE_CATEGORY', function () {
            $cateInfo = $this->services->getArticleCategory();
            array_unshift($cateInfo, ['id' => 0, 'title' => 'Phổ biến']);
            return $cateInfo;
        });
        return app('json')->success($cateInfo);
    }
}
