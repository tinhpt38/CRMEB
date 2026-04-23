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
namespace app\listener\user;


use app\services\user\UserLevelServices;
use crmeb\interfaces\ListenerInterface;

/**
 * Sự kiện nâng cấp người dùng
 * Class UserLevelListener
 * @package app\listener\user
 */
class UserLevelListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$uid] = $event;

        //Nâng cấp người dùng
        /** @var UserLevelServices $levelServices */
        $levelServices = app()->make(UserLevelServices::class);
        $levelServices->detection((int)$uid);
    }
}