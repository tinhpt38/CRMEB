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
use app\model\order\StoreOrderCartInfo;

/**
 *
 * Class StoreOrderStoreOrderCartInfoDao
 * @package app\dao\order
 */class StoreOrderStoreOrderCartInfoDao extends BaseDao
{

    protected $alias = 'a';

    protected $joinAlis = 'c';

    /**
     * Đặt mô hình bảng chính
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
        return StoreOrderCartInfo::class;
    }

    /**
     * Thiết lập mô hình
     * @return \crmeb\basic\BaseModel
     */    public function getModel()
    {
        $name = app()->make($this->setJoinModel())->getName();
        return parent::getModel()->alias($this->alias)->join($name . ' ' . $this->joinAlis, $this->alias . '.id =' . $this->joinAlis . '.oid');
    }

    /**
     * Nhận các mặt hàng được Khách hàng muaid
     * @param array $where
     * @return array
     */    public function getUserCartProductIds(array $where)
    {
        return $this->getModel()->when(isset($where['uid']), function ($query) use ($where) {
            $query->where($this->alias . '.uid', $where['uid']);
        })->column($this->joinAlis . '.product_id');
    }
}
