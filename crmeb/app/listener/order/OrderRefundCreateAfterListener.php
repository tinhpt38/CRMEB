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
namespace app\listener\order;

use app\jobs\RefundOrderJob;
use app\services\order\OutStoreOrderRefundServices;
use crmeb\interfaces\ListenerInterface;

/**
 * Tạo đơn hàng sau bán hàng
 * Class orderRefundCreateAfter
 * @package app\listener\order
 */
class OrderRefundCreateAfterListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$order] = $event;
    }
}
