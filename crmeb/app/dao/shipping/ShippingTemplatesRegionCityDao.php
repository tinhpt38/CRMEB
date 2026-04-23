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

namespace app\dao\shipping;


use app\dao\BaseDao;
use app\model\shipping\ShippingTemplatesRegion;
use app\model\shipping\SystemCity;

/**
 *
 * Class ShippingTemplatesRegionCityDao
 * @package app\dao\shipping
 */
class ShippingTemplatesRegionCityDao extends BaseDao
{

    /**
     * Bí danh bảng hiện tại
     * @var string
     */
    protected $alias = 'a';

    /**
     * Đặt bí danh bảng tham gia
     * @var string
     */
    protected $joinAlis = 'c';

    /**
     * Đặt mô hình hiện tại
     * @return string
     */
    protected function setModel(): string
    {
        return ShippingTemplatesRegion::class;
    }

    /**
     * Thiết lập mô hình bảng tham gia
     * @return string
     */
    protected function setJoinModel(): string
    {
        return SystemCity::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */
    protected function getModel(string $key = 'province_id', string $join = 'LEFT')
    {
        /** @var SystemCity $city */
        $city = app()->make($this->setJoinModel());
        $name = $city->getName();
        return parent::getModel()->join($name . ' ' . $this->joinAlis, $this->alias . '.' . $key . ' = ' . $this->joinAlis . '.city_id', $join)->alias($this->alias);
    }

    /**
     * Nhận danh sách vận chuyển miễn phí theo các điều kiện được chỉ định
     * @param array $where
     * @return mixed
     */
    public function getUniqidList(array $where, bool $group = true)
    {
        return $this->getModel($group ? 'province_id' : 'city_id')->when(isset($where['uniqid']), function ($query) use ($where) {
            $query->where($this->alias . '.uniqid', $where['uniqid']);
        })->when(isset($where['province_id']), function ($query) use ($where) {
            $query->where($this->alias . '.province_id', $where['province_id']);
        })->when($group, function ($query) {
            $query->group($this->alias . '.province_id');
        })->field($this->alias . '.province_id,' . $this->joinAlis . '.name,' . $this->alias . '.city_id')->select()->toArray();
    }
}
