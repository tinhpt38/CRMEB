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
declare (strict_types=1);

namespace app\services\product\product;

use app\services\BaseServices;
use app\dao\product\product\StoreProductReplyStoreProductDao;

/**
 *
 * Class StoreProductReplyStoreProductServices
 * @package app\services\product\product
 */
class StoreProductReplyStoreProductServices extends BaseServices
{

    /**
     * StoreProductReplyStoreProductServices constructor.
     * @param StoreProductReplyStoreProductDao $dao
     */
    public function __construct(StoreProductReplyStoreProductDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy danh sách bình luận
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getProductReplyList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getProductReplyList($where, $page, $limit);
        $count = $this->dao->replyCount($where + ['is_del' => 0]);
        return compact('list', 'count');
    }
}
