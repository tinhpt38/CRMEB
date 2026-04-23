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

namespace app\jobs\notice;

use app\services\message\notice\EnterpriseWechatService;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class EnterpriseWechatJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Gửi tin nhắn đến nhóm WeChat doanh nghiệp
     * @param $data
     * @return bool
     */
    public function doJob($data): bool
    {
        try {
            /** @var EnterpriseWechatService $enterpriseWechatService */
            $enterpriseWechatService = app()->make(EnterpriseWechatService::class);
            $enterpriseWechatService->weComSend($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Không gửi được tin nhắn nhóm doanh nghiệp,Lý do thất bại:' . $e->getMessage());
        }
    }
}
