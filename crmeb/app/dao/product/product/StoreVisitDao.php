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
use app\model\product\product\StoreVisit;

/**
 * Class StoreVisitDao
 * @package app\dao\product\product
 */class StoreVisitDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreVisit::class;
    }

    /**
     *
     * @param int $uid
     * @return array
     */    public function getUserVisitProductId(int $uid)
    {
        return $this->getModel()->where('uid', $uid)->column('product_id');
    }

    public function getSum($where, $field)
    {
        return $this->search($where)->sum($field);
    }

    /**
     * Xu hướng sản phẩm
     * @param $time
     * @param $timeType
     * @param $str
     * @return mixed
     */    public function getProductTrend($time, $timeType, $str)
    {
        return $this->getModel()->where(function ($query) use ($time) {
            if ($time[0] == $time[1]) {
                $query->whereDay('add_time', $time[0]);
            } else {
                $time[1] = date('Y/m/d', strtotime($time[1]) + 86400);
                $query->whereTime('add_time', 'between', $time);
            }
        })->field("FROM_UNIXTIME(add_time,'$timeType') as days,$str as num")->group('days')->select()->toArray();
    }
}
