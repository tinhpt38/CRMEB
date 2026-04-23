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
namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProductProtection;

class StoreProductProtectionDao extends BaseDao
{
    protected function setModel(): string
    {
        return StoreProductProtection::class;
    }

    public function conditionSearch($where)
    {
        return $this->getModel()
            ->when(isset($where['title']) && $where['title'] !== '', function ($query) use ($where) {
                $query->whereLike('title', '%' . $where['title'] . '%');
            })->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where) {
                $query->where('is_del', $where['is_del']);
            })->when(isset($where['status']) && $where['status'] !== '', function ($query) use ($where) {
                $query->where('status', $where['status']);
            });
    }

    public function protectionList(array $where = [], string $field = '*', int $page = 0, int $limit = 0)
    {
        return $this->conditionSearch($where)->field($field)
            ->when($page && $limit, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->order('sort DESC')->select()->toArray();
    }

    public function protectionCount(array $where = [])
    {
        return $this->conditionSearch($where)->count();
    }

    public function protectionListByIds($ids)
    {
        return $this->getModel()->whereIn('id', $ids)->select()->toArray();
    }
}
