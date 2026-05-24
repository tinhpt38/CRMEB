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
use app\model\shipping\SystemCity;

/**
 * dữ liệu thành phố
 * Class SystemCityDao
 * @package app\dao\shipping
 */class SystemCityDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemCity::class;
    }

    /**
     * Lấy danh sách dữ liệu thành phố
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCityList(array $where, string $field = '*')
    {
        return $this->search($where)->field($field)->select()->toArray();
    }

    /**
     * Nhận dữ liệu thành phố và trả về dưới dạng mảng
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */    public function getCityArray(array $where, string $field, string $key)
    {
        return $this->search($where)->column($field, $key);
    }

    /**
     * Xóa thành phố mẹ và thành phố hiện tạiid
     * @param int $cityId
     * @return bool
     * @throws \Exception
     */    public function deleteCity(int $cityId)
    {
        return $this->getModel()->where('city_id', $cityId)->whereOr('parent_id', $cityId)->delete();
    }

    /**
     * Nhận giá trị tối đa của city_id
     * @return mixed
     */    public function getCityIdMax()
    {
        return $this->getModel()->max('city_id');
    }

    /**
     * Nhận lựa chọn thành phố mẫu vận chuyển sản phẩm
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getShippingCity()
    {
        return $this->getModel()->with('children')->where('parent_id', 0)->order('id asc')->select()->toArray();
    }

    /**
     * Nhận danh sách đầy đủ dữ liệu thành phố
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/04/10
     */    public function fullList($field = '*')
    {
        return $this->getModel()->order('id asc')->field($field)->select()->toArray();
    }
}
