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

namespace app\jobs;

use app\services\agent\AgentLevelServices;
use app\services\user\UserServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Phát hiện nâng cấp cấp nhà phân phối
 * Class OrderJob
 * @package crmeb\jobs
 */class AgentJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Thực hiện nâng cấp phát hiện
     * @param $order
     * @return bool
     */    public function doJob(int $uid)
    {
        //Phát hiện nâng cấp cấp nhà phân phối
        try {
            //Phân phối trung tâm mua sắm có được kích hoạt không?
            if (!sys_config('brokerage_func_status')) {
                return true;
            }
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $userInfo = $userServices->getUserInfo($uid);
            if (!$userInfo) {
                return true;
            }
            //Trở nên vượt trộiuid ｜｜ Bắt đầu tự mua và quay lại với chính mìnhuid
            $spread_uid = $userServices->getSpreadUid($uid, $userInfo);
            $two_spread_uid = 0;
            if ($spread_uid > 0 && $one_user_info = $userServices->getUserInfo($spread_uid)) {
                $two_spread_uid = $userServices->getSpreadUid($spread_uid, $one_user_info, false);
            }
            $uids = array_unique([$uid, $spread_uid, $two_spread_uid]);

            /** @var AgentLevelServices $agentLevelServices */            $agentLevelServices = app()->make(AgentLevelServices::class);
            //Phát hiện nâng cấp
            $agentLevelServices->checkUserLevelFinish($uid, $uids);

            return true;
        } catch (\Throwable $e) {
            Log::error('Phát hiện lỗi nâng cấp cấp độ phân phối,Lý do thất bại:' . $e->getMessage());
        }
    }
}
