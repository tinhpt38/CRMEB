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

use app\model\activity\integral\StoreIntegral;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;
use app\model\product\product\StoreProduct;

/**
 * Class StoreProductAttrValue
 * @package app\common\model\product
 */
class StoreProductAttrValue extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_product_attr_value';

    protected $insert = ['unique'];

    /**
     * sku Viết hiện trường
     * @param $value
     * @return string
     */
    public function setSukAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * UniqueViết hiện trường
     * @param $value
     * @param $data
     * @return mixed
     */
    public function setUniqueAttr($value, $data)
    {
        if (is_array($data['suk'])) {
            $data['suk'] = $this->setSukAttr($data['suk']);
        }
        return $data['unique'] ?: substr(md5($data['product_id'] . $data['suk'] . uniqid(true)), 12, 8);
    }

    /**
     * Công cụ tìm sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchProductIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('product_id', $value);
        } else {
            $query->where('product_id', $value);
        }
    }

    /**
     * Trình tìm kiếm loại sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    /**
     * Trình tìm kiếm tên thuộc tính sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchSukAttr($query, $value)
    {
        if ($value) {
            $query->where('suk', $value);
        }
    }

    /**
     * Đặc điểm kỹ thuật tìm kiếm giá trị duy nhất
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchUniqueAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('unique', $value);
        } else {
            if ($value) {
                $query->where('unique', $value);
            }
        }
    }

    /**
     * Sản phẩm liên quan
     * @return \think\model\relation\HasOne
     */
    public function product()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->field('store_name,id')->bind(['store_name']);
    }

    /**
     * Bảng trung tâm mua sắm điểm liên kết
     * @return \think\model\relation\HasOne
     */
    public function storeIntegral()
    {
        return $this->hasOne(StoreIntegral::class, 'id', 'product_id')->field('title store_name,id')->where('is_show', 1)->where('is_del', 0)->bind(['store_name']);
    }

    /**
     * người tìm kiếm số
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchBarCodeAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('bar_code', $value);
        } else {
            $query->where('bar_code', $value);
        }
    }

    public function searchBarCodeNumberAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('bar_code_number', $value);
        } else {
            $query->where('bar_code_number', $value);
        }
    }

}
