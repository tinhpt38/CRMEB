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

namespace app\model\activity\coupon;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO mẫu phiếu giảm giáModel
 * Class StoreCoupon
 * @package app\model\coupon
 */
class StoreCoupon extends BaseModel
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
    protected $name = 'store_coupon';

    /**
     * Loại phiếu giảm giá
     * @var string[]
     */
    protected $couponType = [0 => 'Phiếu giảm giá phổ quát', 1 => 'Phiếu giảm giá danh mục', 2 => 'phiếu giảm giá hàng hóa'];

    /**
     * liên kết một-nhiều
     * @return \think\model\relation\HasMany
     */
    public function productId()
    {
        return $this->hasMany(StoreCouponProduct::class, 'coupon_id', 'id');
    }

    /**
     * Trình nhận loại phiếu giảm giá
     * @param $value
     * @return string
     */
    public function getTypeAttr($value)
    {
        return $this->couponType[$value];
    }

    /**
     * Trình tìm tiêu đề mẫu phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchTitleAttr($query, $value, $data)
    {
        if ($value) $query->where('title', 'like', '%' . $value . '%');
    }

    /**
     * Trình tìm trạng thái mẫu phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
        if ($value != '') $query->where('status', $value);
    }

    /**
     * Công cụ tìm chi tiêu tối thiểu
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchUseMinPriceAttr($query, $value, $data)
    {
        $query->where('use_min_price', $value);
    }

    /**
     * Công cụ tìm giá trị phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchCouponPriceAttr($query, $value, $data)
    {
        $query->where('coupon_price', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * Công cụ tìm loại phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value ?? 0);
    }

    /**
     * Trình tìm kiếm ID danh mục
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchCategoryIdAttr($query, $value, $data)
    {
        $query->where('category_id', $value);
    }
}
