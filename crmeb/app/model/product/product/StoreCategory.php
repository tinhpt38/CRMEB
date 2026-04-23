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

namespace app\model\product\product;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Phân loại sản phẩmModel
 * Class StoreCategory
 * @package app\model\product\product
 */
class StoreCategory extends BaseModel
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
    protected $name = 'store_category';

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */
    protected function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Nhận điều kiện truy vấn phân loại tập hợp con
     * @return \think\model\relation\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id')->where('is_show', 1)->order('sort DESC,id DESC');
    }

    /**
     * Liệu danh mục có hiển thị công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsShowAttr($query, $value, $data)
    {
        if ($value !== '') $query->where('is_show', $value);
    }

    /**
     * Liệu danh mục có hiển thị công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchPidAttr($query, $value, $data)
    {
        if ($value !== '') $query->where('pid', $value);
    }

    /**
     * Liệu danh mục có hiển thị công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchCateNameAttr($query, $value, $data)
    {
        if ($value !== '') $query->where('cate_name', 'like', '%' . $value . '%');
    }

    /**
     * Liệu danh mục có hiển thị công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIdAttr($query, $value, $data)
    {
        if ($value) $query->whereIn('id', is_array($value) ? $value : (string)$value);
    }
}
