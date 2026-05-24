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

/**
 *  Chi tiết sản phẩmModel
 * Class StoreDescription
 * @package app\model\product\product
 */class StoreDescription extends BaseModel
{
    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_product_description';

    use ModelTrait;

    public function getDescriptionAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param $query
     * @param $value
     */    public function searchProductIdAttr($query, $value)
    {
        if ($value) $query->where('product_id', $value);
    }

    /**
     * Nhập trình tìm kiếm
     * @param $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }
}
