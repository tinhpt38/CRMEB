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
use app\dao\user\UserLevelDao;
use app\services\system\SystemUserLevelServices;

/**
 * Hạng khách hàng
 * Class OutUserLevelServices
 * @package app\services\user
 */class OutUserLevelServices extends BaseServices
{

    /**
     * OutUserLevelServices constructor.
     * @param UserLevelDao $dao
     */    public function __construct(UserLevelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách thành viên
     * @param array $where
     * @return array
     */    public function levelList(array $where): array
    {
        /** @var SystemUserLevelServices $systemLevelServices */        $systemLevelServices = app()->make(SystemUserLevelServices::class);
        $field = 'id, name, grade, discount, image, icon, explain, exp_num, is_show, add_time';
        return $systemLevelServices->getLevelList($where, $field);
    }
}
