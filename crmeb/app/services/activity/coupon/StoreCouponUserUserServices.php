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
use app\dao\activity\coupon\StoreCouponUserUserDao;

/**
 *
 * Class StoreCouponUserUserServices
 * @package app\services\coupon
 */class StoreCouponUserUserServices extends BaseServices
{

    /**
     * StoreCouponUserUserServices constructor.
     * @param StoreCouponUserUserDao $dao
     */    public function __construct(StoreCouponUserUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->sysPage($where, $page, $limit);
        foreach ($list as $k => &$v) {
            $v['start_time'] = $v['start_time'] > 0 ? $v['start_time'] : $v['add_time'];
            if ($v['end_time'] < time() || $v['use_time'] != 0) $v['is_fail'] = 1;
        }
        $count = $this->dao->sysCount($where);
        return compact('list', 'count');
    }
}
