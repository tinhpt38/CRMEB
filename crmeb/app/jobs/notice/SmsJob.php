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

use app\services\message\notice\SmsService;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;


class SmsJob extends BaseJobs
{
    use QueueTrait;

    /**
     * gửi tin nhắn văn bản
     * @param $switch
     * @param $adminList
     * @param $order
     * @return bool
     */    public function doJob($phone, array $data, string $template)
    {

        try{
            /** @var SmsService $smsServices */            $smsServices = app()->make(SmsService::class);
            $smsServices->send(true, $phone, $data, $template);
            return true;
        }catch (\Throwable $e) {
            Log::error('Không gửi được SMS,Lý do thất bại:' . $e->getMessage());
        }

    }

}
