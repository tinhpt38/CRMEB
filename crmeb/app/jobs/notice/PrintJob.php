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

use app\services\order\StoreOrderServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * In biên lai
 * Class PrintJob
 * @package app\jobs\notice
 */
class PrintJob extends BaseJobs
{
    use QueueTrait;

    /**
     * In biên lai
     * @param $id
     * @return bool|void
     */
    public function doJob($id, $print_type)
    {
        try {
            /** @var StoreOrderServices $orderServices */
            $orderServices = app()->make(StoreOrderServices::class);
            $orderServices->orderPrintTicket((int)$id, $print_type);
            return true;
        } catch (\Throwable $e) {
            Log::error('Không in được biên lai,Lý do thất bại:' . $e->getMessage());
        }
    }
}
