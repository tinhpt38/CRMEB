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

namespace app\model\shipping;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 * Chưa giaoModel
 * Class ShippingTemplatesNoDelivery
 * @package app\model\shipping
 */class ShippingTemplatesNoDelivery extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'shipping_templates_no_delivery';

    /**
     * Trình tìm kiếm ID tỉnh
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchProvinceIdAttr($query, $value)
    {
        $query->where('province_id', $value);
    }

    /**
     * Công cụ tìm ID thành phố
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCityIdAttr($query, $value)
    {
        $query->where('city_id', $value);
    }

    /**
     * Tìm kiếm id mẫu
     * @param Model $query
     * @param $value
     */    public function searchTempIdAttr($query, $value)
    {
        $query->where('temp_id', $value);
    }

    /**
     * uniqid Người tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchUniqidAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('uniqid', $value);
        } else {
            $query->where('uniqid', $value);
        }
    }
}
