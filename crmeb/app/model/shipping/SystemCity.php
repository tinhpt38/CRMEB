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


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * dữ liệu thành phố
 * Class SystemCity
 * @package app\model\shipping
 */
class SystemCity extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'system_city';

    /**
     * Nhận điều kiện truy vấn phân loại tập hợp con
     * @return \think\model\relation\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'city_id')->order('id ASC');
    }


    /**
     * cityNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchCityIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('city_id', $value);
        } else {
            $query->where('city_id', $value);
        }
    }

    /**
     * ParentIdNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchParentIdAttr($query, $value)
    {
        $query->where('parent_id', $value);
    }


}
