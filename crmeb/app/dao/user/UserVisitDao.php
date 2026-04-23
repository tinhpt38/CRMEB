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

namespace app\dao\user;

use app\dao\BaseDao;
use app\model\user\UserVisit;

/**
 *
 * Class UserVisitDao
 * @package app\dao\user
 */
class UserVisitDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return UserVisit::class;
    }

    /**
     * Dữ liệu xu hướng người dùng
     * @param $time
     * @param $type
     * @param $timeType
     * @param $str
     * @return mixed
     */
    public function getTrendData($time, $type, $timeType, $str)
    {
        return $this->getModel()->when($type != '', function ($query) use ($type) {
            $query->where('channel_type', $type);
        })->where(function ($query) use ($time) {
            if ($time[0] == $time[1]) {
                $query->whereDay('add_time', $time[0]);
            } else {
                $time[1] = date('Y/m/d', strtotime($time[1]) + 86400);
                $query->whereTime('add_time', 'between', $time);
            }
        })->field("FROM_UNIXTIME(add_time,'$timeType') as days,$str as num")->group('days')->select()->toArray();
    }

    /**
     * Dữ liệu địa lý của người dùng
     * @param $time
     * @param $userType
     * @return mixed
     */
    public function getRegion($time, $userType)
    {
        return $this->getModel()->when($userType != '', function ($query) use ($userType) {
            $query->where('channel_type', $userType);
        })->where(function ($query) use ($time) {
            if ($time[0] == $time[1]) {
                $query->whereDay('add_time', $time[0]);
            } else {
                $time[1] = date('Y/m/d', strtotime($time[1]) + 86400);
                $query->whereTime('add_time', 'between', $time);
            }
        })->field('COUNT(distinct(uid)) as visitNum,province')
            ->group('province')->select()->toArray();
    }

    /**
     * Lấy số lượng bản ghi theo nhóm
     * @param array $where
     * @param string $group
     * @return mixed
     */
    public function groupCount(array $where, string $group = 'uid')
    {
        return $this->search($where)->group($group)->count();
    }
}
