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

namespace app\dao\activity\integral;

use app\dao\BaseDao;
use app\model\activity\integral\StoreIntegral;

/**
 *
 * Class StoreIntegralDao
 * @package app\dao\activity
 */class StoreIntegralDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreIntegral::class;
    }

    /**
     * Lấy số lượng vật phẩm theo điều kiện quy định
     * @param array $where
     * @param bool $search
     * @return int
     * @throws \ReflectionException
     */    public function count(array $where = [], bool $search = true)
    {
        return $this->search($where, $search)->count();
    }

    /**
     * Danh sách sản phẩm điểm
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, int $page = 0, int $limit = 0, string $field = '*')
    {
        return $this->search($where, false)->where('is_del', 0)
            ->when(isset($where['integral_time']) && $where['integral_time'] !== '', function ($query) use ($where) {
                list($startTime, $endTime) = explode('-', $where['integral_time']);
                $query->where('add_time', '>', strtotime($startTime))
                    ->where('add_time', '<', strtotime($endTime) + 24 * 3600);
            })->when(isset($where['priceOrder']) && $where['priceOrder'] != '', function ($query) use ($where) {
                if ($where['priceOrder'] === 'desc') {
                    $query->order("price desc");
                } else {
                    $query->order("price asc");
                }
            })->when(isset($where['salesOrder']) && $where['salesOrder'] != '', function ($query) use ($where) {
                if ($where['salesOrder'] === 'desc') {
                    $query->order("sales desc");
                } else {
                    $query->order("sales asc");
                }
            })->when($page != 0 && $limit != 0, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->field($field)->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Nhận dữ liệu sản phẩm điểm
     * @param int $id
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function validProduct(int $id, string $field)
    {
        $where = ['is_show' => 1, 'is_del' => 0];
        return $this->search($where)->where('id', $id)->field($field)->order('add_time desc')->find();
    }
}
