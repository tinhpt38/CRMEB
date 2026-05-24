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
namespace app\model\order;

use app\model\product\product\StoreProduct;
use app\model\product\sku\StoreProductAttrValue;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * giỏ hàngModel
 * Class StoreCart
 * @package app\model\order
 */class StoreCart extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_cart';

    /**
     * Tự động thêm trường
     * @var string[]
     */    protected $insert = ['add_time'];

    /**
     * Thêm công cụ sửa đổi thời gian
     * @return int
     */    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm các sản phẩm liên kết với giỏ hàng
     * @return \think\model\relation\HasOne
     */    public function productInfo()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id');
    }

    /**
     * hiệp hội một-một
     * Thuộc tính sản phẩm sản phẩm liên quan đến giỏ hàng
     * @return \think\model\relation\HasOne
     */    public function attrInfo()
    {
        return $this->hasOne(StoreProductAttrValue::class, 'unique', 'product_attr_unique');
    }


    /**
     * Nhập trình tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value);
    }

    /**
     * Có nên trả tiền không
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsPayAttr($query, $value, $data)
    {
        $query->where('is_pay', $value);
    }

    /**
     * Có nên xóa không
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value);
    }

    /**
     * Có nên thanh toán ngay không
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsNewAttr($query, $value, $data)
    {
        $query->where('is_new', $value);
    }

    /**
     * Tìm kiếm giỏ hàng của Khách hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchProductIdAttr($query, $value, $data)
    {
        if (is_array($value)) {
            $query->whereIn('product_id', $value);
        } else {
            $query->where('product_id', $value);
        }
    }

    /**
     * Trình tìm kiếm giá trị duy nhất của đặc điểm kỹ thuật sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchProductAttrUniqueAttr($query, $value, $data)
    {
        $query->where('product_attr_unique', $value);
    }

    /**
     * Trình tìm kiếm ID nhóm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCombinationIdAttr($query, $value, $data)
    {
        $query->where('combination_id', $value);
    }

    /**
     * Công cụ tìm ID mặc cả
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchBargainIdAttr($query, $value, $data)
    {
        $query->where('bargain_id', $value);
    }

    /**
     * Trình tìm kiếm ID Flash
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchSeckillIdAttr($query, $value, $data)
    {
        $query->where('seckill_id', $value);
    }

    /**
     * liên kết một-nhiều
     * Mẫu phiếu giảm giá liên quan đến sản phẩmid
     * @return \think\model\relation\HasMany
     */    public function product()
    {
        return $this->hasMany(StoreProduct::class, 'id', 'product_id');

    }
}
