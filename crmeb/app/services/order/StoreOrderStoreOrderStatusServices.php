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


use app\dao\order\StoreOrderStoreOrderStatusDao;
use app\services\BaseServices;

/**
 * Class StoreOrderStoreOrderStatusServices
 * @package app\services\order
 * @method getTakeOrderIds(array $where, ?int $limit = 0)
 */
class StoreOrderStoreOrderStatusServices extends BaseServices
{

    /**
     * StoreOrderStoreOrderStatusServices constructor.
     * @param StoreOrderStoreOrderStatusDao $dao
     */
    public function __construct(StoreOrderStoreOrderStatusDao $dao)
    {
        $this->dao = $dao;
    }
}
