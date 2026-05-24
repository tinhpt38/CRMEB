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

namespace app\services\activity\coupon;


use app\dao\activity\coupon\StoreCouponUserCouponDao;
use app\services\BaseServices;

/**
 * Nhận phiếu giảm giá mà Khách hàng có thể sử dụng dựa trên số lượng đặt hàng
 * Class StoreCouponUserCouponServices
 * @package app\services\coupon
 * @method getUidCouponList(int $uid, string $truePrice, int $productId)
 * @method getUidCouponMinList($uid, $price, $value = '', int $type = 1) Nhận phiếu giảm giá trong số tiền mua tối thiểu
 */class StoreCouponUserCouponServices extends BaseServices
{
    /**
     * StoreCouponUserCouponServices constructor.
     * @param StoreCouponUserCouponDao $dao
     */    public function __construct(StoreCouponUserCouponDao $dao)
    {
        $this->dao = $dao;
    }

}
