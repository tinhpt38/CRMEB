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

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserStoreOrderDao;

/**
 *
 * Class UserStoreOrderServices
 * @package app\services\user
 */class UserStoreOrderServices extends BaseServices
{

    /**
     * UserStoreOrderServices constructor.
     * @param UserStoreOrderDao $dao
     */    public function __construct(UserStoreOrderDao $dao)
    {
        $this->dao = $dao;
    }

    public function getUserSpreadCountList($uid, $orderBy = '', $keyword = '')
    {
        if ($orderBy === '') {
            $orderBy = 'u.add_time desc';
        }
        $where = [];
        $where[] = ['u.uid', 'IN', $uid];
        if (strlen(trim($keyword))) {
            $where[] = ['u.nickname|u.phone', 'LIKE', "%$keyword%"];
        }
        [$page, $limit] = $this->getPageValue();
        $field = "u.uid,u.nickname,u.phone,u.avatar,from_unixtime(u.add_time,'%Y/%m/%d') as time,u.spread_time,u.spread_count as childCount,p.orderCount,p.numberCount";
        $list = $this->dao->getUserSpreadCountList($where, $field, $orderBy, $page, $limit);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        foreach ($list as &$item) {
            $item['childCount'] = count($userServices->getUserSpredadUids($item['uid'], 1)) ?? 0;
        }
        return $list;
    }
}
