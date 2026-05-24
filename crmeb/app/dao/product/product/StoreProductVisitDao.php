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
use app\model\product\product\StoreProduct;
use app\model\product\product\StoreVisit;

/**
 * Class StoreProductVisitDao
 * @package app\dao\product\product
 */class StoreProductVisitDao extends BaseDao
{

    /**
     * Bí danh bảng chính
     * @var string
     */    protected $alias = 'a';

    /**
     * Lên lịch bí danh
     * @var string
     */    protected $joinAlis = 'c';

    /**
     * Cài đặt mô hình bảng chính
     * @return string
     */    protected function setModel(): string
    {
        return StoreProduct::class;
    }

    /**
     * Cài đặt mô hình bảng được kết nối
     * @return string
     */    protected function setJoinModel(): string
    {
        return StoreVisit::class;
    }

    /**
     * Thiết lập mô hình
     * @return \crmeb\basic\BaseModel
     */    protected function getModel()
    {
        $name = app()->make($this->setJoinModel())->getName();
        return parent::getModel()->alias($this->alias)->join($name . ' ' . $this->joinAlis, $this->alias . '.id = ' . $this->joinAlis . '.product_id');
    }

    /**
     * Lịch sử duyệt sản phẩm của Khách hàng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserVisitProductList(array $where, int $page, int $limit)
    {
        return $this->getModel()->when(isset($where['uid']), function ($query) use ($where) {
            $query->where($this->joinAlis . '.uid', $where['uid']);
        })->when($page, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->when(isset($where['store_name']), function ($query) use ($where) {
            $query->whereLike($this->alias . '.store_name', '%' . $where['store_name'] . '%');
        })->where(['is_del' => 0, 'is_show' => 1])->field([
            $this->alias . '.store_name',
            $this->alias . '.image',
            $this->alias . '.price',
            'IFNULL(' . $this->alias . '.sales,0) + IFNULL(' . $this->alias . '.ficti,0) as sales',
            $this->alias . '.stock',
            $this->alias . '.id'
        ])->order($this->alias . '.sort DESC,' . $this->alias . '.id DESC')->select()->toArray();
    }
}
