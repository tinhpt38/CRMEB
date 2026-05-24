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
namespace app\model\article;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * TODO Chi tiết bài viếtModel
 * Class ArticleContent
 * @package app\model\article
 */class ArticleContent extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'article_content';

}
