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
 *  Mẫu vận chuyển hàng hóaModel
 * Class ShippingTemplates
 * @package app\model\shipping
 */
class ShippingTemplates extends BaseModel
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
    protected $name = 'shipping_templates';

    /**
     * gõ kiểu
     * @param $value
     * @return string
     */
    public function getTypeAttr($value)
    {
        $status = [1 => 'Theo số lượng mảnh', 2 => 'theo trọng lượng', 3 => 'theo khối lượng'];
        return $status[$value];
    }

    /**
     * Có bật getter miễn phí vận chuyển hay không
     * @param $value
     * @return string
     */
    public function getAppointAttr($value)
    {
        $status = [1 => 'bật lên', 0 => 'đóng cửa'];
        return $status[$value];
    }

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */
    public function getAddTimeAttr($value)
    {
        $value = date('Y-m-d H:i:s', $value);
        return $value;
    }

    /**
     * Liên kết một-nhiều khu vực vận chuyển hàng hóa
     * @return \think\model\relation\HasMany
     */
    public function region()
    {
        return $this->hasMany(ShippingTemplatesRegion::class, 'temp_id', 'id');
    }

    /**
     * Miễn phí vận chuyển khu vực liên kết một-nhiều
     * @return \think\model\relation\HasMany
     */
    public function free()
    {
        return $this->hasMany(ShippingTemplatesFree::class, 'temp_id', 'id');
    }

    /**
     * IDNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }

    /**
     * Trình tìm kiếm tên mẫu
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchNameAttr($query, $value)
    {
        if ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        }
    }

}
