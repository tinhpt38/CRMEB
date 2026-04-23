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

namespace app\services\article;

use app\dao\article\ArticleContentDao;
use app\services\BaseServices;

/**
 * Class ArticleContentServices
 * @package app\services\article
 * @method save(array $data)cứu
 * @method update($id, array $data, ?string $key = null)
 */
class ArticleContentServices extends BaseServices
{
    /**
     * ArticleContentServices constructor.
     * @param ArticleContentDao $dao
     */
    public function __construct(ArticleContentDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * xóa bỏ
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        return $this->dao->del($id);
    }
}
