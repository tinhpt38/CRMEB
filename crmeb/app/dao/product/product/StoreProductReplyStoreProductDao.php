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

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProduct;
use app\model\product\product\StoreProductReply;

/**
 *
 * Class StoreProductReplyStoreProductDao
 * @package app\dao\product\product
 */class StoreProductReplyStoreProductDao extends BaseDao
{
    /**
     * bí danh bảng
     * @var string
     */    protected $alias = '';

    /**
     * Bí danh danh sách liên kết
     * @var string
     */    protected $joinAlis = '';

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreProductReply::class;
    }

    /**
     * mô hình danh sách liên kết
     * @return string
     */    public function setJoinModel(): string
    {
        return StoreProduct::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */    public function getModel(string $alias = 'r', string $join_alias = 'p', $join = 'left')
    {
        $this->alias = $alias;
        $this->joinAlis = $join_alias;
        /** @var StoreProduct $storeProduct */        $storeProduct = app()->make($this->setJoinModel());
        $table = $storeProduct->getName();
        return parent::getModel()->join($table . ' ' . $join_alias, $alias . '.product_id = ' . $join_alias . '.id', $join)->alias($alias);
    }

    /**
     * Lấy danh sách bình luận
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductReplyList(array $where, int $page, int $limit)
    {
        return $this->searchWhere($where)->page($page, $limit)->select()->toArray();
    }

    /**
     * Lấy số lượng bình luận
     * @param array $where
     * @return int
     */    public function replyCount(array $where)
    {
        return $this->searchWhere($where)->count();
    }

    /**
     * tìm kiếm
     * @param array $where
     * @return \crmeb\basic\BaseModel
     */    public function searchWhere(array $where = [])
    {
        $model = $this->getModel()->where('r.is_del', 0)->withSearch(['time'], ['time' => $where['data'], 'timeKey' => 'r.add_time'])->field('r.*,p.store_name,p.image,r.nickname as account,SUM(r.product_score+r.service_score) as score')->group('id');
        if ($where['is_reply'] != '') $model = $model->where('r.is_reply', $where['is_reply']);
        if ($where['product_id']) $model = $model->where('r.product_id', $where['product_id']);
        if ($where['store_name']) $model = $model->where('p.store_name|r.product_id', 'Like', '%' . $where['store_name'] . '%');
        if ($where['account']) $model = $model->where('r.nickname', 'LIKE', '%' . $where['account'] . '%');
        if ($where['status'] !== '') $model = $model->where('status', $where['status']);
        if ($where['key'] != '') {
            $model = $model->order($where['key'], $where['order']);
        } else {
            $model = $model->order('r.add_time desc,r.is_reply asc');
        }
        return $model;
    }

}
