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

namespace app\dao\activity\coupon;

use app\dao\BaseDao;
use app\model\activity\coupon\StoreCouponProduct;

/**
 *
 * Class StoreCouponProductDao
 * @package app\dao\coupon
 */
class StoreCouponProductDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreCouponProduct::class;
    }

}
