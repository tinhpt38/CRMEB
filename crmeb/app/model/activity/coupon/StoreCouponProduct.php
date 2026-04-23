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

/**
 * TODO Hiệp hội phiếu giảm giáModel
 * Class StoreCoupon
 * @package app\model\coupon
 */
class StoreCouponProduct extends BaseModel
{
    use ModelTrait;

    /**
     * tên bảng
     * @var string
     */
    protected $name = 'store_coupon_product';

}
