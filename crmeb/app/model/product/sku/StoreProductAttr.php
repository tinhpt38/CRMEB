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
namespace app\model\product\sku;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *   Thuộc tính sản phẩmModel
 * Class StoreProductAttr
 * @package app\common\model\product
 */class StoreProductAttr extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_product_attr';

    /**
     * Bộ thu thập thông số kỹ thuật
     * @param $value
     * @return false|string[]
     */    protected function getAttrValuesAttr($value)
    {
        return explode(',', $value);
    }

    /**
     * Công cụ sửa đổi thông số kỹ thuật
     * @param $value
     * @return string
     */    protected function setAttrValuesAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Công cụ tìm sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchProductIdAttr($query, $value)
    {
        $query->where('product_id', $value);
    }

    /**
     * Trình tìm kiếm loại sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }
}
