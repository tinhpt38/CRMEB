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

use app\dao\order\StoreOrderRefundDao;
use app\services\BaseServices;
use app\services\pay\PayServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Đơn hàng sau bán hàng
 * Class OutStoreOrderRefundServices
 * @package app\services\order
 */
class OutStoreOrderRefundServices extends BaseServices
{
    /**
     * Đặt hàngservices
     * @var StoreOrderServices
     */
    protected $storeOrderServices;

    /**
     * Người xây dựng
     * OutStoreOrderRefundServices constructor.
     * @param StoreOrderRefundDao $dao
     */
    public function __construct(StoreOrderRefundDao $dao, OutStoreOrderServices $storeOrderServices)
    {
        $this->dao = $dao;
        $this->storeOrderServices = $storeOrderServices;
    }

    /**
     * Danh sách đơn hàng sau bán hàng
     * @param array $where
     * @return void
     */
    public function refundList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $field = 'id, store_order_id, uid, order_id, refund_type, refund_num, refund_price, refunded_price, refund_phone, refund_express, refund_express_name, 
        refund_explain, refund_img, refund_reason, refuse_reason, remark, refunded_time, cart_info, is_cancel, is_del, is_pink_cancel, add_time';
        $list = $this->dao->getList($where, $page, $limit, $field);
        $count = $this->dao->count($where);
        foreach ($list as $key => &$item) {
            $item['pay_price'] = $item['refund_price'];
            unset($item['refund_price']);
            $item['items'] = $this->tidyCartList($item['cart_info']);
            unset($list[$key]['cart_info']);
        }
        return compact('list', 'count');
    }

    /**
     * Định dạng các mục đơn hàng
     * @param array $carts
     * @return array
     */
    public function tidyCartList(array $carts): array
    {
        $list = [];
        foreach ($carts as $cart) {
            $list[] = [
                'store_name' => $cart['productInfo']['store_name'] ?? '',
                'suk' => $cart['productInfo']['attrInfo']['suk'] ?? '',
                'image' => $cart['productInfo']['attrInfo']['image'] ?: $cart['productInfo']['image'],
                'price' => sprintf("%.2f", $cart['truePrice'] ?? '0.00'),
                'cart_num' => $cart['cart_num'] ?? 0
            ];
        }
        return $list;
    }


    /**
     * Chi tiết đơn hàng hoàn tiền
     * @param string $orderId Số đơn hàng sau bán hàng
     * @param int $id Đơn hàng sau bán hàngID
     * @return mixed
     */
    public function getInfo(string $orderId = '', int $id = 0)
    {
        $field = ['id', 'store_order_id', 'order_id', 'uid', 'refund_type', 'refund_num', 'refund_price',
            'refunded_price', 'refund_phone', 'refund_express', 'refund_express_name', 'refund_explain',
            'refund_img', 'refund_reason', 'refuse_reason', 'remark', 'refunded_time', 'cart_info', 'is_cancel',
            'is_pink_cancel', 'is_del', 'add_time'];

        if ($id > 0) {
            $where = $id;
        } else {
            $where = ['order_id' => $orderId];
        }
        $refund = $this->dao->get($where, $field, ['orderData']);
        if (!$refund) throw new ApiException('Đơn hàng không tồn tại');
        $refund = $refund->toArray();

        //Tính số tiền chiết khấu
        $totalPrice = 0;
        $vipTruePrice = 0;
        foreach ($refund['cart_info'] ?? [] as $key => &$cart) {
            $cart['sum_true_price'] = sprintf("%.2f", $cart['sum_true_price'] ?? bcmul((string)$cart['truePrice'], (string)$cart['cart_num'], 2));
            $cart['vip_sum_truePrice'] = bcmul($cart['vip_truePrice'], $cart['cart_num'] ?: 1, 2);
            $vipTruePrice = bcadd((string)$vipTruePrice, $cart['vip_sum_truePrice'], 2);
            $totalPrice = bcadd($totalPrice, $cart['sum_true_price'], 2);
        }
        $refund['vip_true_price'] = $vipTruePrice;

        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);
        $refund['use_integral'] = $refundServices->getOrderSumPrice($refund['cart_info'], 'use_integral', false);
        $refund['coupon_price'] = $refundServices->getOrderSumPrice($refund['cart_info'], 'coupon_price', false);
        $refund['deduction_price'] = $refundServices->getOrderSumPrice($refund['cart_info'], 'integral_price', false);
        $refund['pay_postage'] = $refundServices->getOrderSumPrice($refund['cart_info'], 'postage_price', false);
        $refund['total_price'] = bcadd((string)$totalPrice, bcadd((string)$refund['deduction_price'], (string)$refund['coupon_price'], 2), 2);
        $refund['items'] = $this->tidyCartList($refund['cart_info']);
        if (in_array($refund['refund_type'], [1, 2, 4, 5])) {
            $title = 'Nộp đơn xin hoàn tiền';
        } elseif ($refund['refund_type'] == 3) {
            $title = 'Từ chối hoàn tiền';
        } else {
            $title = 'Đã hoàn tiền';
        }

        $refund['refund_type_name'] = $title;
        $refund['pay_type_name'] = PayServices::PAY_TYPE[$refund['pay_type']] ?? 'những cách khác';
        unset($refund['cart_info']);
        return $refund;
    }

    /**
     * Sửa đổi nhận xét đơn hàng sau bán hàng
     * @param string $orderId Số đơn hàng sau bán hàng
     * @param string $remark Nhận xét
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function remark(string $orderId, string $remark): bool
    {
        $order = $this->dao->get(['order_id' => $orderId]);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);
        return $refundServices->updateRemark((int)$order['id'], $remark);
    }

    /**
     * Hoàn tiền đơn hàng
     * @param string $orderId Số đơn hàng sau bán hàng
     * @param string $refundPrice Số tiền hoàn lại
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refundPrice(string $orderId, string $refundPrice): bool
    {
        $orderRefund = $this->dao->get(['order_id' => $orderId]);
        if (!$orderRefund) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        if ($orderRefund['is_cancel'] == 1) {
            throw new ApiException('Đơn hàng không tồn tại');
        }

        $order = $this->storeOrderServices->get((int)$orderRefund['store_order_id']);
        if (!$order) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        if (!in_array($orderRefund['refund_type'], [1, 5])) {
            throw new ApiException('Trạng thái đơn hàng sau bán hàng không hỗ trợ thao tác này');
        }

        $data['refund_type'] = 6;
        $data['refunded_time'] = time();

        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);

        //0hoàn lại tiền nhân dân tệ
        if ($orderRefund['refund_price'] == 0 && in_array($orderRefund['refund_type'], [1, 5])) {
            $refundPrice = 0;
        } else {
            if (!$refundPrice) {
                throw new ApiException('Vui lòng nhập số tiền hoàn lại');
            }
            if ($orderRefund['refund_price'] == $orderRefund['refunded_price']) {
                throw new ApiException('Số tiền thanh toán đã được hoàn lại và không thể hoàn lại được nữa.');
            }

            $data['refunded_price'] = bcadd($refundPrice, $orderRefund['refunded_price'], 2);
            $bj = bccomp((string)$orderRefund['refund_price'], $data['refunded_price'], 2);
            if ($bj < 0) {
                throw new ApiException('Số tiền hoàn lại lớn hơn số tiền thanh toán, vui lòng sửa đổi số tiền hoàn trả');
            }
        }

        $refundData['pay_price'] = $order['pay_price'];
        $refundData['refund_price'] = $refundPrice;
        if ($order['refund_price'] > 0) {
            mt_srand();
            $refundData['refund_id'] = $order['order_id'] . rand(100, 999);
        }
        $refundData['order_id'] = $orderId;
        //Sửa đổi trạng thái hoàn tiền đơn hàng
        if ($refundServices->agreeRefund((int)$orderRefund['id'], $refundData)) {
            $refundServices->update((int)$orderRefund['id'], $data);
            return true;
        } else {
            $refundServices->storeProductOrderRefundYFasle((int)$orderRefund['id'], $refundPrice);
            throw new ApiException('Hoàn tiền không thành công');
        }
    }

    /**
     * Đồng ý hoàn tiền
     * @param string $orderId Số đơn hàng sau bán hàng
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function agree(string $orderId): bool
    {
        $orderRefund = $this->dao->get(['order_id' => $orderId]);
        if (!$orderRefund) {
            throw new ApiException('Dữ liệu không tồn tại');
        }

        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);
        return $refundServices->agreeExpress((int)$orderRefund['id']);
    }

    /**
     * Từ chối hoàn tiền
     * @param string $orderId Số đơn hàng sau bán hàng
     * @param string $refundReason Lý do không hoàn tiền
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refuse(string $orderId, string $refundReason): bool
    {
        $orderRefund = $this->dao->get(['order_id' => $orderId]);
        if (!$orderRefund) {
            throw new ApiException('Dữ liệu không tồn tại');
        }

        /** @var StoreOrderRefundServices $refundServices */
        $refundServices = app()->make(StoreOrderRefundServices::class);
        $refundServices->refuse((int)$orderRefund['id'], $refundReason);
        return true;
    }

    /**
     * Tạo đơn hàng sau bán hàng
     * @param int $id
     * @param string $pushUrl
     * @return bool
     */
    public function refundCreatePush(int $id, string $pushUrl): bool
    {
        $refundInfo = $this->getInfo('', $id);
        /** @var OutStoreOrderServices $orderServices */
        $orderServices = app()->make(OutStoreOrderServices::class);
        $orderInfo = $orderServices->get($refundInfo['store_order_id'], ['id', 'order_id']);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        $refundInfo['order'] = $orderInfo->toArray();
        return out_push($pushUrl, $refundInfo, 'Yêu cầu trả hàng / hoàn tiền');
    }

    /**
     * Hủy đơn hàng sau bán hàng
     * @param int $id
     * @param string $pushUrl
     * @return bool
     */
    public function cancelApplyPush(int $id, string $pushUrl): bool
    {
        $refundInfo = $this->getInfo('', $id);
        /** @var OutStoreOrderServices $orderServices */
        $orderServices = app()->make(OutStoreOrderServices::class);
        $orderInfo = $orderServices->get($refundInfo['store_order_id'], ['id', 'order_id']);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        $refundInfo['order'] = $orderInfo->toArray();
        return out_push($pushUrl, $refundInfo, 'Hủy đơn hàng sau bán hàng');
    }
}