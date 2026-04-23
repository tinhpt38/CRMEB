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

namespace app\dao\article;


use app\dao\BaseDao;
use app\model\article\ArticleContent;

/**
 * Chi tiết bài viết
 * Class ArticleContentDao
 * @package app\dao\article
 */
class ArticleContentDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return ArticleContent::class;
    }

    /**
     * Xóa dữ liệu dựa trên id
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function del(int $id)
    {
        return $this->getModel()->where('nid',$id)->delete();
    }
}
