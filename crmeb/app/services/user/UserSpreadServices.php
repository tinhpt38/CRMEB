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
use app\dao\user\UserSpreadDao;

/**
 * Class UserSpreadServices
 * @package app\services\user
 */
class UserSpreadServices extends BaseServices
{

    /**
     * UserSpreadServices constructor.
     * @param UserSpreadDao $dao
     */
    public function __construct(UserSpreadDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Ghi lại các mối quan hệ quảng cáo
     * @param int $uid
     * @param int $spread_uid
     * @return false|mixed
     */
    public function setSpread(int $uid, int $spread_uid, int $spread_time = 0)
    {
        if (!$uid || !$spread_uid) return false;
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);

        if (!$userServices->getUserInfo($uid, 'uid')) {
            return false;
        }
        if (!$userServices->getUserInfo($spread_uid, 'uid')) {
            return false;
        }
        if ($this->dao->save(['uid' => $uid, 'spread_uid' => $spread_uid, 'spread_time' => $spread_time ?: time()])) {
            $userServices->incField($spread_uid, 'spread_count', 1);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Truy vấn người dùng quảng cáouids
     * @param int $uid
     * @param int $type 1:Cấp 2: Cấp 2 0: Tất cả
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpreadUids(int $uid, int $type = 0, array $where = [])
    {
        if (!$uid) return [];
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        if (!$userServices->getUserInfo($uid, 'uid')) {
            return [];
        }
        if ($where && isset($where['time'])) {
            $where['timeKey'] = 'spread_time';
        }
        $where['spread_uid'] = $uid;
        $spread_one = $this->dao->getSpreadUids($where);
        if ($type == 1) {
            return $spread_one;
        }
        $where['spread_uid'] = $spread_one;
        $spread_two = $this->dao->getSpreadUids($where);
        if ($type == 2) {
            return $spread_two;
        }
        return array_unique(array_merge($spread_one, $spread_two));
    }
}
