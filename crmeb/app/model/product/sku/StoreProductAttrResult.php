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
 * Class StoreProductAttrResult
 * @package app\common\model\product
 */class StoreProductAttrResult extends BaseModel
{

    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_product_attr_result';

    protected $insert = ['change_time'];

    /**
     * Tự động tăng thời gian thay đổi
     * @param $value
     * @return int
     */    protected static function setChangeTimeAttr($value)
    {
        return time();
    }

    /**
     * JSON hóa dữ liệu
     * @param $value
     * @return false|string
     */    protected static function setResultAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
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
