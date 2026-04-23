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

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 *  Thích và sưu tầmmodel
 * Class StoreProductRelation
 * @package app\model\product\product
 */
class StoreProductRelation extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_product_relation';

    /**
     * Sản phẩm liên quan
     */
    public function product()
    {
        return $this->hasOne(StoreProduct::class,'id','product_id');
    }
    /**
     * Người tìm kiếm người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Công cụ tìm sản phẩm
     * @param Model $query
     * @param $value
     */
    public function searchProductIdAttr($query, $value)
    {
        $query->where('product_id', $value);
    }

    /**
     * Nhập trình tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    /**
     * Trình tìm kiếm loại sản phẩm
     * @param Model $query
     * @param $value
     */
    public function searchCategoryAttr($query, $value)
    {
        $query->where('category', $value);
    }
}
