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


use app\jobs\notice\PrintJob;
use app\jobs\OrderCreateAfterJob;
use app\jobs\OrderJob;
use app\jobs\ProductLogJob;
use app\jobs\UnpaidOrderCancelJob;
use app\jobs\UnpaidOrderSend;
use app\services\order\StoreOrderCreateServices;
use app\services\order\StoreOrderStatusServices;
use crmeb\interfaces\ListenerInterface;
use crmeb\services\CacheService;
use crmeb\services\SystemConfigService;
use crmeb\utils\Arr;

/**
 * Tạo đơn hàng sau sự kiện
 * Class OrderCreateAfterListener
 * @package app\listener\order
 */class OrderCreateAfterListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$order, $group, $uid, $key, $combinationId, $seckillId, $bargainId] = $event;

        //Sau khi dữ liệu đơn hàng được tạo, hãy tính số lượng thực tế của sản phẩm, tính hoa hồng, tính chiết khấu, đặt địa chỉ mặc định và dọn sạch giỏ hàng.
        /** @var StoreOrderCreateServices $orderCreate */        $orderCreate = app()->make(StoreOrderCreateServices::class);
        $orderCreate->orderCreateAfter($order, $group, $combinationId || $seckillId || $bargainId);

        //Xóa bộ nhớ đệm đơn hàng
        CacheService::delete('user_order_' . $uid . $key);

        //Viết bảng ghi đơn hàng
        /** @var StoreOrderStatusServices $statusService */        $statusService = app()->make(StoreOrderStatusServices::class);
        $statusService->save([
            'oid' => $order['id'],
            'change_type' => 'cache_key_create_order',
            'change_message' => 'Tạo đơn hàng',
            'change_time' => time()
        ]);

        //Đơn hàng tự động bị hủy
        $this->pushJob($order['id'], $combinationId, $seckillId, $bargainId);

        //Tính số lượng thực tế của đơn hàng
        //OrderCreateAfterJob::dispatch([$order, $group, $combinationId || $seckillId || $bargainId]);

        //Lịch sử đơn hàng
        ProductLogJob::dispatch(['order', ['uid' => $uid, 'order_id' => $order['id']]]);

        //In phiếu giao hàng
        PrintJob::dispatch([$order['id'], 2]);
    }

    /**
     * Đơn hàng sẽ tự động bị hủy và thêm vào hàng đợi tin nhắn bị trì hoãn
     * @param int $orderId
     * @param int $combinationId
     * @param int $seckillId
     * @param int $bargainId
     * @return mixed
     */    public function pushJob(int $orderId, int $combinationId, int $seckillId, int $bargainId)
    {
        //Hệ thống Cài đặt trước khoảng thời gian hủy đơn hàng
        $keyValue = ['order_cancel_time', 'order_activity_time', 'order_bargain_time', 'order_seckill_time', 'order_pink_time'];
        //Nhận cấu hình
        $systemValue = SystemConfigService::more($keyValue);
        //Định dạng dữ liệu
        $systemValue = Arr::setValeTime($keyValue, is_array($systemValue) ? $systemValue : []);
        if ($combinationId) {
            $secs = $systemValue['order_pink_time'] ?: $systemValue['order_activity_time'];
        } elseif ($seckillId) {
            $secs = $systemValue['order_seckill_time'] ?: $systemValue['order_activity_time'];
        } elseif ($bargainId) {
            $secs = $systemValue['order_bargain_time'] ?: $systemValue['order_activity_time'];
        } else {
            $secs = $systemValue['order_cancel_time'];
        }
        //Gửi SMS sau 10 phút không thanh toán
        UnpaidOrderSend::dispatchSecs(600, [$orderId]);
        //Đơn hàng chưa thanh toán bị hủy dựa trên sự kiện Cài đặt hệ thống
        UnpaidOrderCancelJob::dispatchSecs((int)($secs * 3600), [$orderId]);
    }
}
