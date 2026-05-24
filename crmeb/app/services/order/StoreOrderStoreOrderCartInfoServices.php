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

namespace app\services\order;


use app\dao\order\StoreOrderStoreOrderCartInfoDao;
use app\services\BaseServices;

/**
 * Class StoreOrderStoreOrderCartInfoServices
 * @package app\services\order
 * @method getUserCartProductIds(array $where) Nhận các mặt hàng được Khách hàng muaid
 */class StoreOrderStoreOrderCartInfoServices extends BaseServices
{
    /**
     * StoreOrderStoreOrderCartInfoServices constructor.
     * @param StoreOrderStoreOrderCartInfoDao $dao
     */    public function __construct(StoreOrderStoreOrderCartInfoDao $dao)
    {
        $this->dao = $dao;
    }

}
