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


use app\services\order\StoreOrderServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;

/**
 * Gửi SMS sau 10 phút không thanh toán
 * Class UnpaidOrderSend
 * @package crmeb\jobs
 */class UnpaidOrderSend extends BaseJobs
{
    use QueueTrait;

    public function doJob($id)
    {
        /** @var StoreOrderServices $services */        $services = app()->make(StoreOrderServices::class);
        $orderInfo = $services->get($id);
        if (!$orderInfo) {
            return true;
        }
        if ($orderInfo->paid) {
            return true;
        }
        if ($orderInfo->is_del) {
            return true;
        }
        //Gửi tin nhắn cho Khách hàng sau khi nhận hàng
        event('NoticeListener', [['order' => $orderInfo], 'order_pay_false']);
        return true;
    }

}
