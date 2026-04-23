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


use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class UserJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Sau khi đồng bộ dữ liệu
     * @param $openids
     * @return bool
     */
    public function doJob($openids)
    {
        if (!$openids || !is_array($openids)) {
            return true;
        }
        $noBeOpenids = [];
        try {
            /** @var WechatUserServices $wechatUser */
            $wechatUser  = app()->make(WechatUserServices::class);
            $noBeOpenids = $wechatUser->syncWechatUser($openids);
        } catch (\Throwable $e) {
            Log::error('Không thể cập nhật thông tin người dùng wechatUser,Lý do thất bại:' . $e->getMessage());
        }
        if (!$noBeOpenids) {
            return true;
        }
        try {
            /** @var UserServices $user */
            $user = app()->make(UserServices::class);
            $user->importUser($noBeOpenids);
        } catch (\Throwable $e) {
            Log::error('Không thêm được người dùng,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }
}
