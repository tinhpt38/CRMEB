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
declare (strict_types = 1);

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserUserBillDao;

/**
 *
 * Class UserUserBillServices
 * @package app\services\user
 */class UserUserBillServices extends BaseServices
{

    /**
     * UserUserBillServices constructor.
     * @param UserUserBillDao $dao
     */    public function __construct(UserUserBillDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $where
     * @param $field
     * @param $order
     * @return array
     */    public function getBrokerageList(array $where, string $field = '*', string $order = '', $is_page = true)
    {
        [$page, $limit] = $this->getPageValue($is_page);
        $list = $this->dao->getList($where, $field, $order, $page, $limit);
        $count = $this->dao->getCount($where);
        return [$count, $list];
    }
}
