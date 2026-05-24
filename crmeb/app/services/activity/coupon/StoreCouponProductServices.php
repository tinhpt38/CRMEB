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

namespace app\services\activity\coupon;

use app\services\BaseServices;
use app\dao\activity\coupon\StoreCouponProductDao;

/**
 *
 * Class StoreCouponProductServices
 * @package app\services\coupon
 * @method saveAll(array $data) Lưu theo đợt
 */class StoreCouponProductServices extends BaseServices
{

    /**
     * StoreCouponProductServices constructor.
     * @param StoreCouponProductDao $dao
     */    public function __construct(StoreCouponProductDao $dao)
    {
        $this->dao = $dao;
    }

}
