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
use app\model\shipping\ShippingTemplatesNoDelivery;

/**
 * Chưa giao
 * Class ShippingTemplatesNoDeliveryDao
 * @package app\dao\shipping
 */
class ShippingTemplatesNoDeliveryDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return ShippingTemplatesNoDelivery::class;
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
    public function getShippingArray(array $where)
    {
        return $this->search($where)->select()->toArray();
    }

    /**
     * Cho dù không được giao
     * @param $tempId
     * @param $cityid
     * @return int
     */
    public function isNoDelivery($tempId, $cityid)
    {
        if (is_array($tempId)) {
            return $this->getModel()->where('temp_id', 'in', $tempId)->where('city_id', $cityid)->column('temp_id');
        } else {
            return $this->getModel()->where('temp_id', $tempId)->where('city_id', $cityid)->count();
        }
    }

}
