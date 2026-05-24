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
use app\dao\user\UserVisitDao;
use think\facade\Log;

/**
 *
 * Class UserVisitServices
 * @package app\services\user
 * @method count(array $where)
 * @method getDistinctCount(array $where, $field, ?bool $search = true)
 * @method sum(array $where, string $field)
 * @method getTrendData($time, $type, $timeType, $str)
 * @method getRegion($time, $channelType)
 * @method int groupCount(array $where, string $group = 'uid') Lấy số lượng bản ghi theo nhóm
 */class UserVisitServices extends BaseServices
{

    /**
     * UserVisitServices constructor.
     * @param UserVisitDao $dao
     */    public function __construct(UserVisitDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Ghi lại hồ sơ truy cập sau khi đăng nhập
     * @param array|object $user
     * @return mixed
     */    public function loginSaveVisit($user)
    {
        try {
            $data = [
                'url' => '/pages/index/index',
                'uid' => $user['uid'] ?? 0,
                'ip' => request()->ip(),
                'add_time' => time(),
                'province' => $user['province'] ?? '',
                'channel_type' => $user['user_type'] ?? 'h5'
            ];
            if (!$data['uid']) {
                return false;
            }
            return $this->dao->save($data);
        } catch (\Throwable $e) {
            Log::error('Lịch sử đăng nhập nhập truy cập lỗi nhật ký, lý do lỗi：' . $e->getMessage());
        }
    }

}
