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

namespace app\services\order;

use app\dao\order\StoreOrderDao;
use app\services\activity\combination\StorePinkServices;
use app\services\BaseServices;
use app\services\pay\PayServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;

/**
 * Class OutStoreOrderServices
 * @package app\services\order
 * @method getOrderIdsCount(array $ids) Lấy số lượng đơn hàng chưa bị xóa theo id đơn hàng
 * @method StoreOrderDao getUserOrderDetail(string $key, int $uid, array $with) Nhận chi tiết đơn hàng
 * @method chartTimePrice($start, $stop) Nhận số tiền thanh toán từ thời điểm hiện tại đến thời điểm quy định
 * @method chartTimeNumber($start, $stop) Lấy số lượng lệnh thanh toán từ thời điểm hiện tại đến thời điểm quy định
 * @method together(array $where, string $field, string $together = 'sum') Truy vấn tổng hợp
 * @method getBuyCount($uid, $type, $typeId) Lấy số lượng vật phẩm người dùng đã mua cho sự kiện này
 * @method getDistinctCount(array $where, $field, ?bool $search = true)
 * @method getTrendData($time, $type, $timeType, $str) Xu hướng người dùng
 * @method getRegion($time, $channelType) thống kê địa lý
 * @method getProductTrend($time, $timeType, $field, $str) Xu hướng hàng hóa
 */
class OutStoreOrderServices extends BaseServices
{

    /**
     * Loại vận chuyển
     * @var string[]
     */
    public $deliveryType = ['send' => 'giao hàng của người bán', 'express' => 'chuyển phát nhanh', 'fictitious' => 'giao hàng ảo', 'delivery_part_split' => 'Chia lô hàng từng phần', 'delivery_split' => 'Đã hoàn thành việc chia lô hàng'];

    /**
     * StoreOrderProductServices constructor.
     * @param StoreOrderDao $dao
     */
    public function __construct(StoreOrderDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderList(array $where)
    {
        $where['order_status'] = $where['status'];
        unset($where['status']);
        if (!is_numeric($where['paid'])) {
            $where['paid'] = -1;
        }
        [$page, $limit] = $this->getPageValue();
        $field = ['id', 'pid', 'order_id', 'trade_no', 'uid', 'freight_price', 'real_name', 'user_phone', 'user_address', 'total_num',
            'total_price', 'total_postage', 'pay_price', 'coupon_price', 'deduction_price', 'paid', 'pay_time', 'pay_type', 'add_time',
            'shipping_type', 'status', 'refund_status', 'delivery_name', 'delivery_code', 'delivery_id'];
        $data = $this->dao->getOutOrderList($where, $field, $page, $limit);
        $count = $this->dao->count($where);
        $list = $this->tidyOrderList($data);
        return compact('list', 'count');
    }

    /**
     * chuyển đổi dữ liệu
     * @param array $data
     * @return array
     */
    public function tidyOrderList(array $data)
    {
        /** @var StoreOrderCartInfoServices $services */
        $services = app()->make(StoreOrderCartInfoServices::class);
        foreach ($data as &$item) {
            $list = [];
            $carts = $services->getOrderCartInfo((int)$item['id']);
            foreach ($carts as $key => $cart) {
                $list = $this->tidyCartList($cart['cart_info'], $list, $key);
            }
            $item['pay_type_name'] = PayServices::PAY_TYPE[$item['pay_type']] ?? 'những cách khác';
            $item['items'] = $list;
            unset($item['refund_status'], $item['shipping_type']);
        }
        return $data;
    }

    /**
     * Chi tiết đặt hàng
     * @param string $orderId Số đơn hàng
     * @param int $id Đặt hàngID
     * @return mixed
     */
    public function getInfo(string $orderId = '', int $id = 0)
    {
        $field = ['id', 'pid', 'order_id', 'trade_no', 'uid', 'freight_price', 'real_name', 'user_phone', 'user_address', 'total_num',
            'total_price', 'total_postage', 'pay_price', 'coupon_price', 'deduction_price', 'paid', 'pay_time', 'pay_type', 'add_time',
            'shipping_type', 'status', 'refund_status', 'delivery_name', 'delivery_code', 'delivery_id', 'refund_type', 'delivery_type', 'pink_id', 'use_integral', 'back_integral'];

        if ($id > 0) {
            $where = $id;
        } else {
            $where = ['order_id' => $orderId];
        }

        if (!$orderInfo = $this->dao->get($where, $field, ['invoice'])) {
            throw new ApiException('Đơn hàng không tồn tại');
        }

        if (!$orderInfo['invoice']) {
            $orderInfo['invoice'] = new \StdClass();
        } else {
            $orderInfo['invoice']->hidden(['uid', 'category', 'id', 'order_id', 'add_time']);
        }

        $orderInfo = $this->tidyOrder($orderInfo->toArray(), true);
        //Tính số tiền chiết khấu
        $vipTruePrice = array_column($orderInfo['items'], 'vip_sum_truePrice');
        $vipTruePrice = round(array_sum($vipTruePrice), 2);
        $orderInfo['vip_true_price'] = sprintf("%.2f", $vipTruePrice ?: '0.00');
        $orderInfo['total_price'] = bcadd($orderInfo['total_price'], $orderInfo['vip_true_price'], 2);
        return $orderInfo;
    }

    /**
     * Định dạng dữ liệu chi tiết đơn hàng
     * @param $order
     * @param bool $detail Bạn có cần đặt hàng chi tiết sản phẩm?
     * @return mixed
     */
    public function tidyOrder($order, bool $detail = false)
    {
        if ($detail == true && isset($order['id'])) {
            /** @var StoreOrderCartInfoServices $cartServices */
            $cartServices = app()->make(StoreOrderCartInfoServices::class);
            $carts = $cartServices->getOrderCartInfo((int)$order['id']);

            $list = [];
            foreach ($carts as $key => $cart) {
                $list = $this->tidyCartList($cart['cart_info'], $list, $key);
            }
            $order['items'] = $list;
        }

        $order['pay_type_name'] = PayServices::PAY_TYPE[$order['pay_type']] ?? 'những cách khác';

//        if (!$order['paid'] && $order['pay_type'] == 'offline' && !$order['status'] >= 2) {
//            $order['status_name'] = 'Thanh toán ngoại tuyến,Chưa thanh toán';
//        } else if (!$order['paid']) {
//            $order['status_name'] = 'Chưa thanh toán';
//        } else if ($order['status'] == 4) {
//            if ($order['delivery_type'] == 'send') {
//                $order['status_name'] = 'Đang chờ nhận';
//            } elseif ($order['delivery_type'] == 'express') {
//                $order['status_name'] = 'Đang chờ nhận';
//            } elseif ($order['delivery_type'] == 'split') {//Chia lô hàng
//                $order['status_name'] = 'Đang chờ nhận';
//            } else {
//                $order['status_name'] = 'Đang chờ nhận';
//            }
//        } else if ($order['refund_status'] == 1) {
//            if (in_array($order['refund_type'], [0, 1, 2])) {
//                $order['status_name'] = 'Nộp đơn xin hoàn tiền';
//            } elseif ($order['refund_type'] == 4) {
//                $order['status_name'] = 'Nộp đơn xin hoàn tiền';
//            } elseif ($order['refund_type'] == 5) {
//                $order['status_name'] = 'Nộp đơn xin hoàn tiền';
//            }
//        } else if ($order['refund_status'] == 2 || $order['refund_type'] == 6) {
//            $order['status_name'] = 'Đã hoàn tiền';
//        } else if ($order['refund_status'] == 3) {
//            $order['status_name'] = 'Hoàn tiền một phần (đơn hàng phụ）';
//        } else if ($order['refund_status'] == 4) {
//            $order['status_name'] = 'Tất cả các đơn đặt hàng phụ đã được áp dụng để hoàn lại tiền.';
//        } else if (!$order['status']) {
//            if ($order['pink_id']) {
//                /** @var StorePinkServices $pinkServices */
//                $pinkServices = app()->make(StorePinkServices::class);
//                if ($pinkServices->getCount(['id' => $order['pink_id'], 'status' => 1])) {
//                    $order['status_name'] = 'Tham gia nhóm';
//                } else {
//                    $order['status_name'] = 'Không được vận chuyển';
//                }
//            } else {
//                if ($order['shipping_type'] === 1) {
//                    $order['status_name'] = 'Không được vận chuyển';
//                } else {
//                    $order['status_name'] = 'Đang chờ xóa sổ';
//                }
//            }
//        } else if ($order['status'] == 1) {
//            if ($order['delivery_type'] == 'send') {//TODO giao hàng
//                $order['status_name'] = 'Đang chờ nhận';
//            } elseif ($order['delivery_type'] == 'express') {//TODO  vận chuyển
//                $order['status_name'] = 'Đang chờ nhận';
//            } elseif ($order['delivery_type'] == 'split') {//Chia lô hàng
//                $order['status_name'] = 'Đang chờ nhận';
//            } else {
//                $order['status_name'] = 'Đang chờ nhận';
//            }
//        } else if ($order['status'] == 2) {
//            $order['status_name'] = 'Đang chờ đánh giá';
//        } else if ($order['status'] == 3) {
//            $order['status_name'] = 'giao dịch đã hoàn tất';
//        }
        // Xử lý tình trạng chưa thanh toán
        if (!$order['paid']) {
            if ($order['pay_type'] == 'offline') {
                $order['status_name'] = 'Thanh toán ngoại tuyến,Chưa thanh toán';
            } else {
                $order['status_name'] = 'Chưa thanh toán';
            }
        } elseif ($order['status'] == 4 || $order['status'] == 1) { // Hợp nhất logic cho hàng hóa được nhận
            $order['status_name'] = 'Đang chờ nhận';
        } elseif ($order['refund_status'] == 1) {
            if (in_array($order['refund_type'], [0, 1, 2, 4, 5])) {
                $order['status_name'] = 'Nộp đơn xin hoàn tiền';
            }
        } elseif ($order['refund_status'] == 2 || $order['refund_type'] == 6) {
            $order['status_name'] = 'Đã hoàn tiền';
        } elseif ($order['refund_status'] == 3) {
            $order['status_name'] = 'Hoàn tiền một phần (đơn hàng phụ）';
        } elseif ($order['refund_status'] == 4) {
            $order['status_name'] = 'Tất cả đơn hàng phụ đang được áp dụng để hoàn lại tiền';
        } elseif (!$order['status']) {
            if ($order['pink_id']) {
                /** @var StorePinkServices $pinkServices */
                $pinkServices = app()->make(StorePinkServices::class);
                if ($pinkServices->getCount(['id' => $order['pink_id'], 'status' => 1])) {
                    $order['status_name'] = 'Tham gia nhóm';
                } else {
                    $order['status_name'] = 'Không được vận chuyển';
                }
            } else {
                if ($order['shipping_type'] === 1) {
                    $order['status_name'] = 'Không được vận chuyển';
                } else {
                    $order['status_name'] = 'Chờ xử lý';
                }
            }
        } elseif ($order['status'] == 2) {
            $order['status_name'] = 'Đang chờ đánh giá';
        } elseif ($order['status'] == 3) {
            $order['status_name'] = 'giao dịch đã hoàn tất';
        } else {
            // Xử lý trạng thái không xác định
            $order['status_name'] = 'trạng thái không xác định';
        }
        unset($order['pink_id'], $order['refund_type']);
        return $order;
    }

    /**
     * Định dạng các mục đơn hàng
     * @param array $cartInfo
     * @param array $list
     * @return array
     */
    public function tidyCartList(array $cartInfo, array $list, $cartId = 0): array
    {
        $list[] = [
            'cart_id' => $cartId,
            'store_name' => $cartInfo['productInfo']['store_name'] ?? '',
            'suk' => $cartInfo['productInfo']['attrInfo']['suk'] ?? '',
            'image' => $cartInfo['productInfo']['attrInfo']['image'] ?: $cartInfo['productInfo']['image'],
            'price' => sprintf("%.2f", $cartInfo['truePrice'] ?? '0.00'),
            'cart_num' => $cartInfo['cart_num'] ?? 0,
            'surplus_num' => $cartInfo['surplus_num'] ?? 0,
            'refund_num' => $cartInfo['refund_num'] ?? 0
        ];
        return $list;
    }

    /**
     * Nhận đơn hàng và bạn có thể chia nhỏ thông tin sản phẩm
     * @param string $orderId Số đơn hàng
     * @return array
     */
    public function getCartList(string $orderId): array
    {
        $order = $this->dao->get(['order_id' => $orderId]);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }

        $list = [];
        /** @var StoreOrderCartInfoServices $services */
        $services = app()->make(StoreOrderCartInfoServices::class);
        $carts = $services->getSplitCartList((int)$order['id']);
        foreach ($carts as $key => $cart) {
            $list = $this->tidyCartList($cart['cart_info'], $list, $key);
        }
        return $list;
    }

    /**
     * Biên nhận đơn hàng
     * @param string $orderId Số đơn hàng
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function receive(string $orderId): bool
    {
        $order = $this->dao->get(['order_id' => $orderId]);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }

        if ($order['status'] == 2) {
            throw new ApiException('Không thể nhận hàng nhiều lần');
        }

        if (($order['paid'] == 1 && $order['status'] == 1) || $order['pay_type'] == 'offline') {
            $data['status'] = 2;
        } else {
            throw new ApiException('Vui lòng gửi hàng hoặc giao hàng trước');
        }

        if (!$this->dao->update($order['id'], $data)) {
            throw new ApiException('Biên nhận không thành công,Vui lòng thử lại sau');
        }

        /** @var StoreOrderTakeServices $takeServices */
        $takeServices = app()->make(StoreOrderTakeServices::class);
        if (!$takeServices->storeProductOrderUserTakeDelivery($order)) {
            throw new ApiException('Biên nhận không thành công,Vui lòng thử lại sau');
        }
        return true;
    }

    /**
     * vận chuyển
     * @param string $orderId Số đơn hàng
     * @param array $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delivery(string $orderId, array $data)
    {
        $orderInfo = $this->dao->get(['order_id' => $orderId]);
        if (!$orderInfo) {
            throw new ApiException('Không thể tìm thấy đơn đặt hàng,Không thể vận chuyển');
        }

        /** @var StoreOrderDeliveryServices $deliveryServices */
        $deliveryServices = app()->make(StoreOrderDeliveryServices::class);
        return $deliveryServices->delivery((int)$orderInfo['id'], $data);
    }

    /**
     * Chia đơn hàng và gửi hàng
     * @param string $orderId Số đơn hàng
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function splitDelivery(string $orderId, array $data): bool
    {
        $orderInfo = $this->dao->get(['order_id' => $orderId]);
        if (!$orderInfo) {
            throw new ApiException('Không thể tìm thấy đơn đặt hàng,Không thể vận chuyển');
        }

        /** @var StoreOrderDeliveryServices $deliveryServices */
        $deliveryServices = app()->make(StoreOrderDeliveryServices::class);
        return $deliveryServices->splitDelivery((int)$orderInfo['id'], $data);
    }

    /**
     * Thiết lập hóa đơn
     * @param string $orderId Số đơn hàng
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setInvoice(string $orderId, array $data): bool
    {
        $orderInfo = $this->dao->get(['order_id' => $orderId], ['id'], ['invoice']);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }

        if (!$orderInfo->invoice || !$invoiceId = $orderInfo->invoice->id) {
            throw new ApiException('Dữ liệu không tồn tại');
        }

        /** @var StoreOrderInvoiceServices $invoiceServices */
        $invoiceServices = app()->make(StoreOrderInvoiceServices::class);
        return $invoiceServices->setInvoice($invoiceId, $data);
    }

    /**
     * Sửa đổi thông tin vận chuyển
     * @param string $orderId Số đơn hàng
     * @param array $data
     * @return mixed
     */
    public function updateDistribution(string $orderId, array $data)
    {
        $orderInfo = $this->dao->get(['order_id' => $orderId]);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }

        /** @var StoreOrderDeliveryServices $deliveryServices */
        $deliveryServices = app()->make(StoreOrderDeliveryServices::class);
        return $deliveryServices->updateDistribution($orderInfo['id'], $data);
    }

    /**
     * Đẩy lệnh
     * @param int $id
     * @param string $pushUrl
     * @return bool
     */
    public function orderCreatePush(int $id, string $pushUrl): bool
    {
        $orderInfo = $this->getInfo('', $id);
        return out_push($pushUrl, $orderInfo, 'Đặt hàng');
    }

    /**
     * đẩy thanh toán
     * @param int $id
     * @param string $pushUrl
     * @return bool
     */
    public function paySuccessPush(int $id, string $pushUrl): bool
    {
        $orderInfo = $this->getInfo('', $id);
        return out_push($pushUrl, $orderInfo, 'Thanh toán đơn hàng');
    }
}
