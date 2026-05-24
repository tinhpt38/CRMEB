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

namespace app\dao\order;


use app\dao\BaseDao;
use app\model\order\StoreOrder;
use app\model\order\StoreOrderStatus;

/**
 * Class StoreOrderStoreOrderStatusDao
 * @package app\dao\order
 */class StoreOrderStoreOrderStatusDao extends BaseDao
{
    protected $alias = 'o';

    protected $joinAlis = 's';

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreOrder::class;
    }

    /**
     * Thiết lập mô hình danh sách liên kết
     * @return string
     */    protected function setJoinModel(): string
    {
        return StoreOrderStatus::class;
    }

    /**
     * Thiết lập mô hình
     * @return \crmeb\basic\BaseModel
     */    protected function getModel()
    {
        $name = app()->make($this->setJoinModel())->getName();
        return parent::getModel()->join($name . ' ' . $this->joinAlis, $this->joinAlis . '.oid = ' . $this->alias . '.id')->alias($this->alias);
    }

    /**
     * tìm kiếm
     * @param array $where
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     */    public function search(array $where = [], bool $search = false)
    {
        return $this->getModel()->when(isset($where['paid']), function ($query) use ($where) {
            $query->where($this->alias . '.paid', $where['paid']);
        })->when(isset($where['status']), function ($query) use ($where) {
            $query->where($this->alias . '.status', $where['status']);
        })->when(isset($where['refund_status']), function ($query) use ($where) {
            $query->where($this->alias . '.refund_status', $where['refund_status']);
        })->when(isset($where['is_del']), function ($query) use ($where) {
            $query->where($this->alias . '.is_del', $where['is_del']);
        })->when(isset($where['change_type']), function ($query) use ($where) {
            $query->whereIn($this->joinAlis . '.change_type', $where['change_type']);
        })->when(isset($where['change_time']), function ($query) use ($where) {
            $query->where($this->joinAlis . '.change_time', '<', $where['change_time']);
        });
    }

    /**
     * Nhận xác nhận đơn hàng giao hàngid
     * @param array $where
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function getTakeOrderIds(array $where, int $limit = 0)
    {
        return $this->search($where)->whereIn('refund_type', [0, 3])->where('pid', '<>', -1)->field([$this->alias . '.*'])
            ->when($limit != 0, function ($query) use ($limit) {
                $query->limit($limit);
            })->select()->toArray();
    }
}
