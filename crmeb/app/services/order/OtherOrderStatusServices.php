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


use app\dao\order\OtherOrderStatusDao;
use app\services\BaseServices;

/**
 * Class OtherOrderStatusServices
 * @package app\services\order
 */class OtherOrderStatusServices extends BaseServices
{

    /**
     * OtherOrderStatusServices constructor.
     * @param OtherOrderStatusDao $dao
     */    public function __construct(OtherOrderStatusDao $dao)
    {
        $this->dao = $dao;
    }
}
