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
declare (strict_types=1);

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProductCoupon;

/**
 *
 * Class StoreProductCouponDao
 * @package app\dao\coupon
 */class StoreProductCouponDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreProductCoupon::class;
    }

    /**
     * Nhận phiếu giảm giá liên quan đến sản phẩm
     * @param array $product_ids
     * @param string $field
     * @return int|void
     */    public function getProductCoupon(array $product_ids, string $field = '*')
    {
        return $this->search(['product_id' => $product_ids])->field($field)->select()->toArray();
    }

}
