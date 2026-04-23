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

use app\jobs\MiniOrderJob;
use app\model\order\StoreOrder;
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\interfaces\ListenerInterface;
use crmeb\services\easywechat\orderShipping\MiniOrderService;

class OrderShippingListener implements ListenerInterface
{
    public function handle($event): void
    {
        /** @var StoreOrder $order */
        [$order_type, $order, $delivery_type, $delivery_id, $delivery_name] = $event;
        $order_shipping_open = sys_config('order_shipping_open', 0);  // Công tắc dịch vụ quản lý thông tin phân phối chương trình mini
        $secs = 0;
        if ($order && $order_shipping_open) {
            //Xác định xem đơn hàng có được chia nhỏ hay không
            $delivery_mode = 1;
            $is_all_delivered = true;
            if ($order_type == 'product') {  // Đặt hàng sản phẩm
                if ($order['is_channel'] == 1 && $order['pay_type'] == 'weixin') {
                    $out_trade_no = $order['order_id'];
                    /** @var StoreOrderCartInfoServices $orderInfoServices */
                    $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
                    $item_desc = $orderInfoServices->getCarIdByProductTitle((int)$order['id'], true);

                    if ($order['pid'] > 0) {
                        $delivery_mode = 2;
                        // Xác định xem tất cả các đơn đặt hàng đã được chuyển đi chưa
                        /** @var StoreOrderServices $orderServices */
                        $orderServices = app()->make(StoreOrderServices::class);
                        $is_all_delivered = $orderServices->checkSubOrderNotSend((int)$order['pid'], (int)$order['id']);
                        $p_order = $orderServices->get((int)$order['pid']);
                        if (!$p_order) {
                            throw new AdminException('Ngoại lệ phân chia đơn hàng');
                        }
                        $out_trade_no = $p_order['order_id'];
                    }
                    $pay_uid = $order['pay_uid'];
                    $path = 'pages/goods/order_details/index?order_id=' . $out_trade_no;
                } else {
                    return;
                }
            } else if ($order_type == 'recharge') {  // Lệnh nạp tiền
                if ($order['recharge_type'] == 'weixin') {
                    $delivery_type = 3;
                    $item_desc = 'Nạp tiền người dùng' . $order['price'];
                    $out_trade_no = $order['order_id'];
                    $pay_uid = $order['uid'];
                    $secs = 10;
                    $path = '/pages/users/user_bill/index?type=2';
                } else {
                    return;
                }
            } else if ($order_type == 'member') {  // Đơn hàng thành viên
                if ($order['pay_type'] == 'weixin') {
                    $delivery_type = 3;
                    $item_desc = 'Mua hàng của người dùng' . $order['member_type'] . 'thẻ thành viên';
                    $out_trade_no = $order['order_id'];
                    $pay_uid = $order['uid'];
                    $secs = 10;
                    $path = '/pages/annex/vip_paid/index';
                } else {
                    return;
                }
            } else if ($order_type == 'offline_scan') {  // Đơn hàng thành viên
                if ($order['pay_type'] == 'weixin') {
                    $delivery_type = 3;
                    $item_desc = 'Người dùng quét mã QR để thanh toán ngoại tuyến';
                    $out_trade_no = $order['order_id'];
                    $pay_uid = $order['uid'];
                    $secs = 10;
                    $path = '/pages/user/index';
                } else {
                    return;
                }
            } else {
                return;
            }
            // Sắp xếp thông tin sản phẩm
            $shipping_list = [
                ['item_desc' => $item_desc]
            ];
            //Xác định chế độ hậu cần đơn hàng
            if (!isset($order['shipping_type']) || $order['shipping_type'] == 1) {
                if ($delivery_type == 1) {
                    //Chỉ thực hiện công ty chuyển phát nhanh mặc định
                    $expressData = [
                        'Yunda Express' => 'YD',
                        'SF chuyển phát nhanh' => 'SF',
                        'YTO Express' => 'YTO',
                        'ZTO Express' => 'ZTO',
                        'STO Express' => 'STO',
                        'Chuyển phát nhanh tốt nhất' => 'HTKY',
                        'JD Logistics' => 'JD',
                        'Jitu Express' => 'JTSD',
                        'Gói chuyển phát nhanh bưu điện' => 'YZPY',
                        'EMS' => 'EMS',
                        'Deppon Express' => 'DBL',
                        'Hậu cần Debon' => 'DBLKY',
                        'giao hàng tận nhà' => 'ZJS',
                        'Chuyển phát nhanh xuất sắc' => 'UC',
                        'Suning Logistics' => 'SNWL',
                    ];
                    $shipping_list = [
                        [
                            'tracking_no' => $delivery_id ?? '',
                            'express_company' => $expressData[$delivery_name] ?? '',
                            'item_desc' => $item_desc,
                            'contact' => [
                                'receiver_contact' => $order['user_phone']
                            ]
                        ]
                    ];
                }
                $logistics_type = $delivery_type;
            } else {
                $logistics_type = 4;
            }
            //Tìm người trả tiềnopenid
            /** @var WechatUserServices $wechatUserService */
            $wechatUserService = app()->make(WechatUserServices::class);
            $payer_openid = $wechatUserService->uidToOpenid($pay_uid, 'routine');
            if (empty($payer_openid)) {
                throw new AdminException('Người thanh toán lệnh không bình thường');
            }
            if ($secs) {
                MiniOrderJob::dispatchSecs($secs, 'doJob', [$out_trade_no, $logistics_type, $shipping_list, $payer_openid, $path, $delivery_mode, $is_all_delivered]);
            } else {
                MiniOrderJob::dispatch('doJob', [$out_trade_no, $logistics_type, $shipping_list, $payer_openid, $path, $delivery_mode, $is_all_delivered]);
            }
        }
    }
}
