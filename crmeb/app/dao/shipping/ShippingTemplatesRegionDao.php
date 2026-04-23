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

/**
 * Chỉ định bưu phí
 * Class ShippingTemplatesRegionDao
 * @package app\dao\shipping
 */
class ShippingTemplatesRegionDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return ShippingTemplatesRegion::class;
    }

    /**
     * Nhận danh sách các mẫu vận chuyển hàng hóa và nhóm chúng theo các trường được chỉ định
     * @param array $where
     * @param string $group
     * @param string $field
     * @param string $key
     * @return mixed
     */
    public function getShippingGroupArray(array $where, string $group, string $field, string $key)
    {
        return $this->search($where)->group($group)->column($field, $key);
    }

    /**
     * Nhận danh sách các mẫu vận chuyển hàng hóa
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getShippingArray(array $where, string $field, string $key)
    {
        return $this->search($where)->column($field, $key);
    }

    /**
     * Nhận danh sách dữ liệu vận chuyển miễn phí dựa trên id mẫu vận chuyển hàng hóa và id thành phố
     * @param array $tempIds
     * @param array $cityId
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getTempRegionList(array $tempIds, array $cityId, string $field = '*', string $key = '*')
    {
        return $this->getModel()->whereIn('temp_id', $tempIds)->whereIn('city_id', $cityId)->order('city_id asc')->column($field, $key);
    }
}
