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

use app\services\yihaotong\SmsRecordServices;
use app\services\system\attachment\SystemAttachmentServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;

/**
 * nhiệm vụ theo lịch trình
 * Class TaskJob
 * @package crmeb\jobs
 */class TaskJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Xóa áp phích ngày hôm qua
     * @return bool
     * @throws \Exception
     */    public function emptyYesterdayAttachment(): bool
    {
        /** @var SystemAttachmentServices $attach */        $attach = app()->make(SystemAttachmentServices::class);
        $attach->emptyYesterdayAttachment();
        return true;
    }
}
