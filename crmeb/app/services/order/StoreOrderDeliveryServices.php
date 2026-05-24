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

use app\jobs\MiniOrderJob;
use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\activity\integral\StoreIntegralOrderServices;
use app\services\BaseServices;
use app\dao\order\StoreOrderDao;
use app\services\message\MessageSystemServices;
use app\services\product\sku\StoreProductAttrValueServices;
use app\services\product\sku\StoreProductVirtualServices;
use app\services\serve\ServeServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder as Form;
use app\services\shipping\ExpressServices;
use think\facade\Log;

/**
 * Đơn hàng đã được vận chuyển
 * Class StoreOrderDeliveryServices
 * @package app\services\order
 */
class StoreOrderDeliveryServices extends BaseServices
{
    /**
     * Người xây dựng
     * StoreOrderDeliveryServices constructor.
     * @param StoreOrderDao $dao
     */
    public function __construct(StoreOrderDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Đơn hàng đã được vận chuyển
     * @param int $id
     * @param array $data
     * @return array
     */
    public function delivery(int $id, array $data)
    {
        $orderInfo = $this->dao->get($id, ['*'], ['pink']);
        if (!$orderInfo) {
            throw new AdminException('Không thể tìm thấy đơn đặt hàng,Không thể vận chuyển');
        }
        if ($orderInfo->is_del) {
            throw new AdminException('Đơn hàng đã bị xóa,Không thể vận chuyển');
        }
        if ($orderInfo->status) {
            throw new AdminException('Đơn đặt hàng đã được chuyển đi. Vui lòng không lặp lại thao tác.');
        }
        if ($orderInfo->shipping_type == 2) {
            throw new AdminException('Đơn đặt hàng xác nhận không thể được vận chuyển');
        }
        if (isset($orderInfo['pinkStatus']) && $orderInfo['pinkStatus'] != 2) {
            throw new AdminException('Nhóm không thể được vận chuyển cho đến khi nhóm được hoàn thành.');
        }

        if ($data['type'] == 1) {
            // Phát hiện mã công ty chuyển phát nhanh
            /** @var ExpressServices $expressServices */
            $expressServices = app()->make(ExpressServices::class);
            if (!$expressServices->be(['code' => $data['delivery_code']])) {
                throw new AdminException('Vui lòng kiểm tra mã công ty chuyển phát nhanh');
            }
        }

        /** @var StoreOrderRefundServices $storeOrderRefundServices */
        $storeOrderRefundServices = app()->make(StoreOrderRefundServices::class);
        if ($storeOrderRefundServices->count(['store_order_id' => $id, 'refund_type' => [1, 2, 4, 5], 'is_cancel' => 0, 'is_del' => 0])) {
            throw new AdminException('Nếu đơn đặt hàng của bạn có ứng dụng hậu mãi, vui lòng xử lý đơn hàng đó trước.');
        }
        return $this->doDelivery($id, $orderInfo, $data);
    }

    /**
     * Chuyển phát nhanh các đơn hàng
     * @param int $id
     * @param array $data
     */
    public function orderDeliveryGoods(int $id, array $data, $orderInfo, $storeTitle)
    {
        /** @var StoreOrderCartInfoServices $orderInfoServices */
        $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
        if (!$data['delivery_name']) {
            throw new AdminException('Hãy chọn công ty chuyển phát nhanh');
        }
        $data['delivery_type'] = 'express';
        if ($data['express_record_type'] == 2) {//Mẫu điện tử
            if (!$data['delivery_code']) {
                throw new AdminException('Mã công ty chuyển phát nhanh bị thiếu');
            }
            if (!$data['express_temp_id']) {
                throw new AdminException('Vui lòng chọn mẫu biểu mẫu điện tử');
            }
            if (!$data['to_name']) {
                throw new AdminException('Vui lòng điền tên người gửi');
            }
            if (!$data['to_tel']) {
                throw new AdminException('Vui lòng điền số điện thoại người gửi');
            }
            if (!$data['to_addr']) {
                throw new AdminException('Vui lòng điền địa chỉ người gửi');
            }
            /** @var ServeServices $expressService */
            $expressService = app()->make(ServeServices::class);
            $expData['com'] = $data['delivery_code'];
            $expData['to_name'] = $orderInfo->real_name;
            $expData['to_tel'] = $orderInfo->user_phone;
            $expData['to_addr'] = $orderInfo->user_address;
            $expData['from_name'] = $data['to_name'];
            $expData['from_tel'] = $data['to_tel'];
            $expData['from_addr'] = $data['to_addr'];
            $expData['siid'] = sys_config('config_export_siid');
            $expData['temp_id'] = $data['express_temp_id'];
            $expData['count'] = $orderInfo->total_num;
            $expData['cargo'] = $orderInfoServices->getCarIdByProductTitle((int)$orderInfo->id, true);
            $expData['order_id'] = $orderInfo->order_id;
            if (!sys_config('config_export_open', 0)) {
                throw new AdminException('Đã đóng hóa đơn điện tử, vui lòng chọn hình thức vận chuyển khác');
            }
            $dump = $expressService->express()->dump($expData);
            $orderInfo->delivery_id = $dump['kuaidinum'];
            $data['express_dump'] = json_encode([
                'com' => $expData['com'],
                'from_name' => $expData['from_name'],
                'from_tel' => $expData['from_tel'],
                'from_addr' => $expData['from_addr'],
                'temp_id' => $expData['temp_id'],
                'cargo' => $expData['cargo'],
            ]);
            $data['delivery_id'] = $dump['kuaidinum'];
        } else {
            if (!$data['delivery_id']) {
                throw new AdminException('Vui lòng nhập số chuyển phát nhanh');
            }
            $orderInfo->delivery_id = $data['delivery_id'];
        }
        $data['status'] = 1;
        $orderInfo->delivery_type = $data['delivery_type'];
        $orderInfo->delivery_name = $data['delivery_name'];
        $orderInfo->status = $data['status'];
        /** @var StoreOrderStatusServices $services */
        $services = app()->make(StoreOrderStatusServices::class);
        $this->transaction(function () use ($id, $data, $services) {
            $res = $this->dao->update($id, $data);
            $res = $res && $services->save([
                    'oid' => $id,
                    'change_time' => time(),
                    'change_type' => 'delivery_goods',
                    'change_message' => 'Công ty chuyển phát nhanh vận chuyển：' . $data['delivery_name'] . ' Số theo dõi nhanh：' . $data['delivery_id']
                ]);
            if (!$res) {
                throw new AdminException('Giao hàng không thành công');
            }
        });
        return true;
    }


    /**
     * Đặt hàng giao hàng
     * @param int $id
     * @param array $data
     */
    public function orderDelivery(int $id, array $data, $orderInfo, string $storeTitle)
    {
        $data['delivery_type'] = 'send';
        $data['delivery_name'] = $data['sh_delivery_name'];
        $data['delivery_id'] = $data['sh_delivery_id'];
        $data['delivery_uid'] = $data['sh_delivery_uid'];
        $data['shipping_type'] = 1;
        //Nhận mã xác minh
        /** @var StoreOrderCreateServices $storeOrderCreateService */
        $storeOrderCreateService = app()->make(StoreOrderCreateServices::class);
        $data['verify_code'] = $storeOrderCreateService->getStoreCode();
        unset($data['sh_delivery_name'], $data['sh_delivery_id'], $data['sh_delivery_uid']);
        if (!$data['delivery_name']) {
            throw new AdminException('Vui lòng nhập tên người giao hàng');
        }
        if (!$data['delivery_id']) {
            throw new AdminException('Vui lòng nhập số điện thoại người giao hàng');
        }
        if (!$data['delivery_uid']) {
            throw new AdminException('Vui lòng nhập thông tin người giao hàng');
        }
        if (!\crmeb\utils\PhoneValidate::isVnMobile((string)$data['delivery_id'])) {
            throw new AdminException('Vui lòng nhập đúng số điện thoại người giao hàng');
        }
        $data['status'] = 1;
        $orderInfo->delivery_type = $data['delivery_type'];
        $orderInfo->delivery_name = $data['delivery_name'];
        $orderInfo->delivery_id = $data['delivery_id'];
        $orderInfo->status = $data['status'];
        /** @var StoreOrderStatusServices $services */
        $services = app()->make(StoreOrderStatusServices::class);
        $this->transaction(function () use ($id, $data, $services) {
            $this->dao->update($id, $data);
            //Ghi lại trạng thái đơn hàng
            $services->save([
                'oid' => $id,
                'change_type' => 'delivery',
                'change_time' => time(),
                'change_message' => 'Được vận chuyển bởi người gửi hàng：' . $data['delivery_name'] . ' Số điện thoại của người gửi hàng：' . $data['delivery_id']
            ]);
        });
        return true;
    }

    /**
     * giao hàng ảo
     * @param int $id
     * @param array $data
     */
    public function orderVirtualDelivery(int $id, array $data)
    {
        $data['delivery_type'] = 'fictitious';
        $data['status'] = 1;
        unset($data['sh_delivery_name'], $data['sh_delivery_id'], $data['delivery_name'], $data['delivery_id']);
        //Lưu thông tin
        /** @var StoreOrderStatusServices $services */
        $services = app()->make(StoreOrderStatusServices::class);
        $this->transaction(function () use ($id, $data, $services) {
            $this->dao->update($id, $data);
            $services->save([
                'oid' => $id,
                'change_type' => 'delivery_fictitious',
                'change_message' => 'Hầu như đã được vận chuyển',
                'change_time' => time()
            ]);
        });
    }

    /**
     * Nhận và sửa đổi cấu trúc biểu mẫu thông tin vận chuyển
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function distributionForm(int $id)
    {
        if (!$orderInfo = $this->dao->get($id))
            throw new AdminException('Đơn hàng không tồn tại');

        $f[] = Form::input('order_id', 'Số đơn hàng', $orderInfo->getData('order_id'))->disabled(1);

        switch ($orderInfo['delivery_type']) {
            case 'send':
                $f[] = Form::input('delivery_name', 'Tên người giao hàng', $orderInfo->getData('delivery_name'))->required('Vui lòng nhập tên người giao hàng');
                $f[] = Form::input('delivery_id', 'Số điện thoại người giao hàng', $orderInfo->getData('delivery_id'))->required('Vui lòng nhập số điện thoại người giao hàng');
                break;
            case 'express':
                /** @var ExpressServices $expressServices */
                $expressServices = app()->make(ExpressServices::class);
                $f[] = Form::select('delivery_code', 'công ty chuyển phát nhanh', (string)$orderInfo->getData('delivery_code'))->setOptions($expressServices->expressSelectForm(['is_show' => 1]))->required('Hãy chọn công ty chuyển phát nhanh')->filterable(true);
                $f[] = Form::input('delivery_id', 'Số theo dõi nhanh', $orderInfo->getData('delivery_id'))->required('Vui lòng điền số chuyển phát nhanh');
                break;
        }
        return create_form('Thông tin vận chuyển', $f, $this->url('/order/distribution/' . $id), 'PUT');
    }

    /**
     * Sửa đổi thông tin vận chuyển
     * @param int $id Đặt hàngid
     * @return mixed
     */
    public function updateDistribution(int $id, array $data)
    {
        $order = $this->dao->get($id);
        if (!$order) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        switch ($order['delivery_type']) {
            case 'send':
                if (!$data['delivery_name']) {
                    throw new AdminException('Vui lòng nhập tên người giao hàng');
                }
                if (!$data['delivery_id']) {
                    throw new AdminException('Vui lòng nhập số điện thoại người giao hàng');
                }
                if (!\crmeb\utils\PhoneValidate::isVnMobile((string)$data['delivery_id'])) {
                    throw new AdminException('Vui lòng nhập đúng số điện thoại người giao hàng');
                }
                break;
            case 'express':
                if (!$data['delivery_id']) {
                    throw new AdminException('Vui lòng nhập số chuyển phát nhanh');
                }
                // Phát hiện mã công ty chuyển phát nhanh
                /** @var ExpressServices $expressServices */
                $expressServices = app()->make(ExpressServices::class);
                if ($name = $expressServices->value(['code' => $data['delivery_code']], 'name')) {
                    $data['delivery_name'] = $name;
                } else {
                    throw new AdminException('Vui lòng kiểm tra mã công ty chuyển phát nhanh');
                }
                break;
            case 'fictitious':
                throw new AdminException('Giao hàng ảo, không cần sửa đổi thông tin giao hàng');
                break;
            default:
                throw new AdminException('Chưa giao hàng, vui lòng gửi hàng trước rồi sửa đổi thông tin giao hàng.');
                break;
        }
        /** @var StoreOrderStatusServices $statusService */
        $statusService = app()->make(StoreOrderStatusServices::class);
        $statusService->save([
            'oid' => $id,
            'change_type' => 'distribution',
            'change_message' => 'Sửa thông tin vận chuyển thành' . $data['delivery_name'] . 'Con số' . $data['delivery_id'],
            'change_time' => time()
        ]);
        return $this->dao->update($id, $data);
    }

    /**In biểu mẫu điện tử sau khi đơn hàng được chuyển đi
     * @param $orderId
     * @return bool|mixed
     */
    public function orderDump($orderId, $type = 'order')
    {
        if (!$orderId) throw new AdminException('Đơn hàng không tồn tại');
//        /** @var StoreOrderServices $orderService */
//        $orderService = app()->make(StoreOrderServices::class);
//        $orderInfo = $orderService->getOne(['id' => $orderId]);
        if ($type == 'order') {
            /** @var StoreOrderServices $orderService */
            $orderService = app()->make(StoreOrderServices::class);
            $orderInfo = $orderService->getOne(['id' => $orderId]);
        } else {
            /** @var StoreIntegralOrderServices $integralOrderService */
            $integralOrderService = app()->make(StoreIntegralOrderServices::class);
            $orderInfo = $integralOrderService->getOne(['id' => $orderId]);
        }
        if (!$orderInfo) throw new AdminException('Đơn hàng không tồn tại');
        if ($orderInfo->shipping_type != 1) throw new AdminException('Đơn hàng nhận hàng không thể in được');
        if (!$orderInfo->express_dump) throw new AdminException('Vui lòng gửi hàng trước');
        if (!sys_config('config_export_open', 0)) {
            throw new AdminException('Vui lòng bật công tắc in vé trong cài đặt hệ thống trước.');
        }
        $dumpInfo = json_decode($orderInfo->express_dump, true);
        /** @var ServeServices $expressService */
        $expressService = app()->make(ServeServices::class);
        $expData['com'] = $dumpInfo['com'];
        $expData['to_name'] = $orderInfo->real_name;
        $expData['to_tel'] = $orderInfo->user_phone;
        $expData['to_addr'] = $orderInfo->user_address;
        $expData['from_name'] = $dumpInfo['from_name'];
        $expData['from_tel'] = $dumpInfo['from_tel'];
        $expData['from_addr'] = $dumpInfo['from_addr'];
        $expData['siid'] = sys_config('config_export_siid');
        $expData['temp_id'] = $dumpInfo['temp_id'];
        $expData['cargo'] = $dumpInfo['cargo'];
        $expData['count'] = $orderInfo->total_num;
        $expData['order_id'] = $orderInfo->order_id;
        $expData['weight'] = 1;

        return $expressService->express()->dump($expData);
    }

    /**
     * Chia đơn hàng và vận chuyển
     * @param int $id
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/21
     */
    public function splitDelivery(int $id, array $data, $delivery_code = true)
    {
        $orderInfo = $this->dao->get($id, ['*'], ['pink']);
        if (!$orderInfo) {
            throw new AdminException('Không thể tìm thấy đơn đặt hàng,Không thể vận chuyển');
        }
        if ($orderInfo->is_del) {
            throw new AdminException('Đơn hàng đã bị xóa,Không thể vận chuyển');
        }
        if ($orderInfo->shipping_type == 2) {
            throw new AdminException('Đơn đặt hàng xác nhận không thể được vận chuyển');
        }
        if (isset($orderInfo['pinkStatus']) && $orderInfo['pinkStatus'] != 2) {
            throw new AdminException('Nhóm không thể được vận chuyển cho đến khi nhóm được hoàn thành.');
        }
        /** @var StoreOrderRefundServices $storeOrderRefundServices */
        $storeOrderRefundServices = app()->make(StoreOrderRefundServices::class);
        if ($storeOrderRefundServices->count(['store_order_id' => $id, 'refund_type' => [1, 2, 4, 5], 'is_cancel' => 0, 'is_del' => 0])) {
            throw new AdminException('Nếu đơn đặt hàng của bạn có ứng dụng hậu mãi, vui lòng xử lý đơn hàng đó trước.');
        }

        if ($data['type'] == 1 && $delivery_code) {
            // Phát hiện mã công ty chuyển phát nhanh
            /** @var ExpressServices $expressServices */
            $expressServices = app()->make(ExpressServices::class);
            if (!$expressServices->be(['code' => $data['delivery_code']])) {
                throw new AdminException('Vui lòng kiểm tra mã công ty chuyển phát nhanh');
            }
        }

        $cart_ids = $data['cart_ids'];
        unset($data['cart_ids']);
        return $this->transaction(function () use ($id, $cart_ids, $orderInfo, $data) {
            /** @var StoreOrderSplitServices $storeOrderSplitServices */
            $storeOrderSplitServices = app()->make(StoreOrderSplitServices::class);
            //Tách lệnh
            [$splitOrderInfo, $otherOrder] = $storeOrderSplitServices->equalSplit($id, $cart_ids, $orderInfo);
            if ($splitOrderInfo) {
                $splitOrderInfo['refund_status'] = 0;
                //Chia đơn hàng để giao hàng
                $res = $this->doDelivery((int)$splitOrderInfo->id, $splitOrderInfo, $data);
                /** @var StoreOrderStatusServices $services */
                $services = app()->make(StoreOrderStatusServices::class);
                //Ghi lại trạng thái đơn hàng ban đầu
                $status_data = ['oid' => $id, 'change_time' => time()];
                $status_data['change_type'] = 'delivery_split';
                $status_data['change_message'] = 'Chia lô hàng';
                $services->save($status_data);
            } else {
                $res = $this->doDelivery($id, $orderInfo, $data);
            }
            return $res;
        });
    }

    /**
     * Thực hiện giao hàng cụ thể
     * @param int $id
     * @param $orderInfo
     * @param array $data
     * @return array
     */
    public function doDelivery(int $id, $orderInfo, array $data)
    {
        $type = (int)$data['type'];
        unset($data['type']);
        //Lấy tiêu đề sản phẩm vào giỏ hàng
        /** @var StoreOrderCartInfoServices $orderInfoServices */
        $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
        $storeName = $orderInfoServices->getCarIdByProductTitle((int)$orderInfo->id);

        if (isset($data['pickup_time']) && count($data['pickup_time']) == 2) {
            $data['pickup_start_time'] = $data['pickup_time'][0];
            $data['pickup_end_time'] = $data['pickup_time'][1];
        } else {
            $data['pickup_start_time'] = '';
            $data['pickup_end_time'] = '';
        }

        // Nhập thông tin vận chuyển
        $res = [];
        switch ($type) {
            case 1://chuyển phát nhanh
                $res = $this->orderDeliverGoods($id, $data, $orderInfo, $storeName);
                event('NoticeListener', [['orderInfo' => $orderInfo, 'storeName' => $storeName, 'data' => $data], 'order_postage_success']);

                //Tin nhắn tùy chỉnh - Chuyển phát nhanh
                $orderInfo['storeName'] = $storeName;
                $orderInfo['delivery_name'] = $data['delivery_name'];
                $orderInfo['delivery_id'] = $data['delivery_id'];
                $orderInfo['time'] = date('Y-m-d H:i:s');
                $orderInfo['phone'] = $orderInfo['user_phone'];
                event('CustomNoticeListener', [$orderInfo['uid'], $orderInfo, 'order_express_success']);
                break;
            case 2://Vận chuyển
                $this->orderDelivery($id, $data, $orderInfo, $storeName);
                event('NoticeListener', [['orderInfo' => $orderInfo, 'storeName' => $storeName, 'data' => $data], 'order_deliver_success']);

                //Tùy chỉnh tin nhắn-gửi bởi người giao hàng
                $orderInfo['storeName'] = $storeName;
                $orderInfo['delivery_name'] = $data['delivery_name'];
                $orderInfo['delivery_id'] = $data['delivery_id'];
                $orderInfo['time'] = date('Y-m-d H:i:s');
                $orderInfo['phone'] = $orderInfo['user_phone'];
                event('CustomNoticeListener', [$orderInfo['uid'], $orderInfo, 'order_send_success']);
                break;
            case 3://giao hàng ảo
                $this->orderVirtualDelivery($id, $data, $orderInfo, $storeName);
                break;
            default:
                throw new AdminException('Các loại phân phối khác hiện không được hỗ trợ');
        }
        if (!$data['delivery_id'] && !empty($res['kuaidinum'])) {
            $data['delivery_id'] = $res['kuaidinum'];
        }
        if (!$data['delivery_id']) {
            $data['delivery_id'] = uniqid();
        }
        // Quản lý đơn hàng chương trình nhỏ
        event('OrderShippingListener', ['product', $orderInfo, $type, $data['delivery_id'], $data['delivery_name']]);
        //Tự động nhận hàng khi hết hạn
        event('OrderDeliveryListener', [$orderInfo, $storeName, $data, $type]);

        //Vận chuyển đơn hàng theo sự kiện tùy chỉnh
        event('CustomEventListener', ['admin_order_express', [
            'uid' => $orderInfo['uid'],
            'real_name' => $orderInfo['real_name'],
            'user_phone' => $orderInfo['user_phone'],
            'user_address' => $orderInfo['user_address'],
            'order_id' => $orderInfo['order_id'],
            'delivery_name' => $orderInfo['delivery_name'],
            'delivery_id' => $orderInfo['delivery_id'],
            'express_time' => date('Y-m-d H:i:s'),
        ]]);

        return $res;
    }

    /**
     * Chuyển phát nhanh các đơn hàng
     * @param int $id
     * @param array $data
     */
    public function orderDeliverGoods(int $id, array $data, $orderInfo, $storeTitle)
    {
        /** @var StoreOrderCartInfoServices $orderInfoServices */
        $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
        if (!$data['delivery_name']) {
            throw new AdminException('Hãy chọn công ty chuyển phát nhanh');
        }
        $dump = [];
        $data['delivery_type'] = 'express';
        if ($data['express_record_type'] == 2) {//Mẫu điện tử
            if (!$data['delivery_code']) {
                throw new AdminException('Mã công ty chuyển phát nhanh bị thiếu');
            }
            if (!$data['express_temp_id']) {
                throw new AdminException('Vui lòng chọn mẫu biểu mẫu điện tử');
            }
            if (!$data['to_name']) {
                throw new AdminException('Vui lòng điền tên người gửi');
            }
            if (!$data['to_tel']) {
                throw new AdminException('Vui lòng điền số điện thoại người gửi');
            }
            if (!$data['to_addr']) {
                throw new AdminException('Vui lòng điền địa chỉ người gửi');
            }
            /** @var ServeServices $expressService */
            $expressService = app()->make(ServeServices::class);
            $expData['com'] = $data['delivery_code'];
            $expData['to_name'] = $orderInfo->real_name;
            $expData['to_tel'] = $orderInfo->user_phone;
            $expData['to_addr'] = $orderInfo->user_address;
            $expData['from_name'] = $data['to_name'];
            $expData['from_tel'] = $data['to_tel'];
            $expData['from_addr'] = $data['to_addr'];
            $expData['siid'] = sys_config('config_export_siid');
            $expData['temp_id'] = $data['express_temp_id'];
            $expData['count'] = $orderInfo->total_num;
            $expData['weight'] = $this->getOrderSumWeight($id);
            $expData['cargo'] = $orderInfoServices->getCarIdByProductTitle((int)$orderInfo->id, true);
            $expData['order_id'] = $orderInfo->order_id;
            if (!sys_config('config_export_open', 0)) {
                throw new AdminException('Đã đóng hóa đơn điện tử, vui lòng chọn hình thức vận chuyển khác');
            }
            $dump = $expressService->express()->dump($expData);
            $orderInfo->delivery_id = $dump['kuaidinum'];
            $data['express_dump'] = json_encode([
                'com' => $expData['com'],
                'from_name' => $expData['from_name'],
                'from_tel' => $expData['from_tel'],
                'from_addr' => $expData['from_addr'],
                'temp_id' => $expData['temp_id'],
                'cargo' => $expData['cargo'],
            ]);
            $data['delivery_id'] = $dump['kuaidinum'];
            if (!empty($dump['label'])) {
                $data['kuaidi_label'] = $dump['label'];
            }
        } else if ($data['express_record_type'] == 3) {
            //vận chuyển thương mại
            if (!$data['delivery_code']) {
                throw new AdminException('Mã công ty chuyển phát nhanh bị thiếu');
            }
            if (!$data['express_temp_id']) {
                throw new AdminException('Vui lòng chọn mẫu biểu mẫu điện tử');
            }
            if (!$data['to_name']) {
                throw new AdminException('Vui lòng điền tên người gửi');
            }
            if (!$data['to_tel']) {
                throw new AdminException('Vui lòng điền số điện thoại người gửi');
            }
            if (!$data['to_addr']) {
                throw new AdminException('Vui lòng điền địa chỉ người gửi');
            }
            /** @var ServeServices $expressService */
            $expressService = app()->make(ServeServices::class);
            $expData['kuaidicom'] = $data['delivery_code'];
            $expData['man_name'] = $orderInfo->real_name;
            $expData['phone'] = $orderInfo->user_phone;
            $expData['address'] = $orderInfo->user_address;
            $expData['send_real_name'] = $data['to_name'];
            $expData['send_phone'] = $data['to_tel'];
            $expData['send_address'] = $data['to_addr'];
            $expData['temp_id'] = $data['express_temp_id'];
            $expData['weight'] = $this->getOrderSumWeight($id);
            $expData['cargo'] = $orderInfoServices->getCarIdByProductTitle((int)$orderInfo->id, true);
            $expData['day_type'] = $data['day_type'];
            $expData['pickup_start_time'] = $data['pickup_start_time'];
            $expData['pickup_end_time'] = $data['pickup_end_time'];
//            if (!sys_config('config_shippment_open', 0)) {
//                throw new AdminException('Vận chuyển của người bán không được kích hoạt và không thể gửi được.');
//            }
            $dump = $expressService->express()->shippmentCreateOrder($expData);
            Log::error('Dữ liệu trả lại hàng khi vận chuyển của người bán：' . json_encode($dump));
            $orderInfo->delivery_id = $dump['kuaidinum'] ?? '';
            $data['express_dump'] = json_encode([
                'com' => $expData['kuaidicom'],
                'from_name' => $expData['send_real_name'],
                'from_tel' => $expData['send_phone'],
                'from_addr' => $expData['send_address'],
                'temp_id' => $expData['temp_id'],
                'cargo' => $expData['cargo'],
            ]);
            $data['delivery_id'] = $dump['kuaidinum'] ?? '';
            $data['kuaidi_label'] = $dump['label'] ?? '';
            $data['kuaidi_task_id'] = $dump['task_id'] ?? '';
            $data['kuaidi_order_id'] = $dump['order_id'] ?? '';
        } else {
            if (!$data['delivery_id']) {
                throw new AdminException('Vui lòng nhập số chuyển phát nhanh');
            }
            $orderInfo->delivery_id = $data['delivery_id'];
        }
        if (true) {
            $data['status'] = 1;
            $orderInfo->delivery_type = $data['delivery_type'];
            $orderInfo->delivery_name = $data['delivery_name'];
            $orderInfo->status = $data['status'];
            /** @var StoreOrderStatusServices $services */
            $services = app()->make(StoreOrderStatusServices::class);
            $this->transaction(function () use ($id, $data, $services) {
                $res = $this->dao->update($id, $data);
                $res = $res && $services->save([
                        'oid' => $id,
                        'change_time' => time(),
                        'change_type' => 'delivery_goods',
                        'change_message' => 'Công ty chuyển phát nhanh vận chuyển：' . $data['delivery_name'] . ' Số theo dõi nhanh：' . $data['delivery_id']
                    ]);
                if (!$res) {
                    throw new AdminException('Giao hàng không thành công');
                }
            });
        } else {

            $update = [
                'is_stock_up' => 1,
                'delivery_type' => $data['delivery_type'],
                'delivery_name' => $data['delivery_name'],
                'delivery_code' => $data['delivery_code'],
                'delivery_id' => $data['delivery_id'],
                'kuaidi_label' => $data['kuaidi_label'],
                'kuaidi_task_id' => $data['kuaidi_task_id'],
                'kuaidi_order_id' => $data['kuaidi_order_id'],
                'express_dump' => $data['express_dump']
            ];

            /** @var StoreOrderStatusServices $services */
            $services = app()->make(StoreOrderStatusServices::class);
            $this->transaction(function () use ($id, $data, $services, $update) {
                $res = $this->dao->update($id, $update);
                $res = $res && $services->save([
                        'oid' => $id,
                        'change_time' => time(),
                        'change_type' => 'stock_up_goods',
                        'change_message' => 'Công ty chuyển phát nhanh có sẵn hàng：' . $data['delivery_name'] . ' Số theo dõi nhanh：' . $data['delivery_id']
                    ]);
                if (!$res) {
                    throw new AdminException('Giao hàng không thành công');
                }
            });
        }
        return $dump;
    }

    /**
     * Trả về tổng trọng lượng của các mặt hàng trong đơn hàng
     * @param int $id
     * @return int|string
     */
    public function getOrderSumWeight(int $id, $default = false)
    {
        /** @var StoreOrderCartInfoServices $services */
        $services = app()->make(StoreOrderCartInfoServices::class);
        $orderGoodInfo = $services->getOrderCartInfo((int)$id);
        $weight = 0;
        foreach ($orderGoodInfo as $cartInfo) {
            $cart = $cartInfo['cart_info'] ?? [];
            if ($cart) {
                $weight = bcadd((string)$weight, (string)bcmul((string)$cart['cart_num'] ?? '0', (string)$cart['productInfo']['attrInfo']['weight'] ?? '0', 4), 2);
            }
        }
        return $weight ?: ($default === false ? 0 : $default);
    }

    /**
     * Tự động phân phối hàng hóa ảo
     * @param $orderInfo
     * @throws \ReflectionException
     */
    public function virtualSend($orderInfo)
    {
        /** @var StoreOrderStatusServices $statusService */
        $statusService = app()->make(StoreOrderStatusServices::class);
        /** @var StoreOrderCartInfoServices $services */
        $services = app()->make(StoreOrderCartInfoServices::class);
        $orderInfo['cart_info'] = $services->getOrderCartInfo((int)$orderInfo['id']);
        $activityStatus = $orderInfo['combination_id'] || $orderInfo['seckill_id'] || $orderInfo['bargain_id'];
        if ($orderInfo['virtual_type'] == 1) {
            /** @var StoreOrderServices $orderService */
            $orderService = app()->make(StoreOrderServices::class);
            $sku = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['attrInfo']['suk'];
            if ($activityStatus) {
                $product_id = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['product_id'];
                /** @var StoreProductAttrValueServices $attrValue */
                $attrValue = app()->make(StoreProductAttrValueServices::class);
                $disk_info = $attrValue->value(['product_id' => $product_id, 'suk' => $sku, 'type' => 0, 'is_virtual' => 1], 'disk_info');
            } else {
                $disk_info = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['attrInfo']['disk_info'];
            }
            if ($disk_info != '') {
                $orderService->update(['id' => $orderInfo['id']], ['status' => 1, 'delivery_type' => 'fictitious', 'virtual_info' => $disk_info, 'remark' => 'Phát hành chìa khóa tự động：' . $disk_info]);
                $this->SystemSend($orderInfo['uid'], [
                    'mark' => 'virtual_info',
                    'title' => 'Phát hành khóa ảo',
                    'content' => 'Sản phẩm key bạn mua đã được thanh toán thành công, số tiền thanh toán' . $orderInfo['pay_price'] . 'nhân dân tệ, số đơn hàng：' . $orderInfo['order_id'] . '，chìa khóa：' . $disk_info . '，cảm ơn bạn đã ghé thăm！'
                ]);
            } else {
                if ($activityStatus) {
                    $product_id = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['product_id'];
                    /** @var StoreProductAttrValueServices $attrValue */
                    $attrValue = app()->make(StoreProductAttrValueServices::class);
                    $unique = $attrValue->value(['product_id' => $product_id, 'suk' => $sku, 'type' => 0, 'is_virtual' => 1], 'unique');
                } else {
                    $unique = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['attrInfo']['unique'];
                }
                /** @var StoreProductVirtualServices $virtualService */
                $virtualService = app()->make(StoreProductVirtualServices::class);
                $virtual = $virtualService->get(['attr_unique' => $unique, 'uid' => 0]);
                if (!$virtual) throw new ApiException('Dữ liệu không tồn tại');
                $virtual->order_id = $orderInfo['order_id'];
                $virtual->uid = $orderInfo['uid'];
                $virtual->save();
                $orderService->update(['id' => $orderInfo['id']], ['status' => 1, 'delivery_type' => 'fictitious', 'virtual_info' => $virtual->card_unique, 'remark' => 'Mật khẩu thẻ đã được cấp tự động và số thẻ：' . $virtual->card_no . '；mật khẩu：' . $virtual->card_pwd]);
                $this->SystemSend($orderInfo['uid'], [
                    'mark' => 'virtual_info',
                    'title' => 'Cấp mật khẩu thẻ ảo',
                    'content' => 'Sản phẩm mã hóa thẻ bạn mua đã được thanh toán thành công, số tiền thanh toán' . $orderInfo['pay_price'] . 'nhân dân tệ, số đơn hàng：' . $orderInfo['order_id'] . '，số thẻ：' . $virtual->card_no . '；mật khẩu：' . $virtual->card_pwd . '，cảm ơn bạn đã ghé thăm！'
                ]);
            }
            $statusService->save([
                'oid' => $orderInfo['id'],
                'change_type' => 'delivery_fictitious',
                'change_message' => 'Giao hàng tự động bí mật thẻ',
                'change_time' => time()
            ]);
        } elseif ($orderInfo['virtual_type'] == 2) {
            if ($activityStatus) {
                $sku = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['attrInfo']['suk'];
                $product_id = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['product_id'];
                /** @var StoreProductAttrValueServices $attrValue */
                $attrValue = app()->make(StoreProductAttrValueServices::class);
                $coupon_id = $attrValue->value(['product_id' => $product_id, 'suk' => $sku, 'type' => 0, 'is_virtual' => 1], 'coupon_id');
            } else {
                $coupon_id = $orderInfo['cart_info'][$orderInfo['cart_id'][0]]['cart_info']['productInfo']['attrInfo']['coupon_id'];
            }
            /** @var StoreCouponIssueServices $issueService */
            $issueService = app()->make(StoreCouponIssueServices::class);
            $coupon = $issueService->get($coupon_id);
            if ($issueService->setCoupon($coupon, [$orderInfo['uid']])) {
                /** @var StoreOrderServices $orderService */
                $orderService = app()->make(StoreOrderServices::class);
                $orderService->update(['id' => $orderInfo['id']], ['status' => 1, 'delivery_type' => 'fictitious', 'virtual_info' => $coupon_id, 'remark' => 'Mã giảm giá đã được phát hành tự động']);
                $this->SystemSend($orderInfo['uid'], [
                    'mark' => 'virtual_info',
                    'title' => 'Mua phiếu giảm giá và phát hành chúng',
                    'content' => 'Mã giảm giá bạn mua đã được thanh toán thành công, số tiền thanh toán' . $orderInfo['pay_price'] . 'nhân dân tệ, số đơn hàng' . $orderInfo['order_id'] . 'Vui lòng kiểm tra các phiếu giảm giá trong trung tâm cá nhân,cảm ơn bạn đã ghé thăm！'
                ]);
            } else {
                throw new ApiException('Bạn đã có phiếu giảm giá này, vui lòng không mua lại');
            }
            $statusService->save([
                'oid' => $orderInfo['id'],
                'change_type' => 'delivery_fictitious',
                'change_message' => 'Mã giảm giá được tự động vận chuyển',
                'change_time' => time()
            ]);
        }
        if ($orderInfo['is_channel'] == 1 && $orderInfo['pay_type'] == 'weixin') {
            MiniOrderJob::dispatchSecs(10, 'doJob', [
                $orderInfo['order_id'],
                3,
                [['item_desc' => $orderInfo['virtual_type'] == 1 ? 'Giao hàng tự động bí mật thẻ' : 'Mã giảm giá được tự động vận chuyển']],
                app()->make(WechatUserServices::class)->uidToOpenid($orderInfo['uid'], 'routine'),
                'pages/goods/order_details/index?order_id=' . $orderInfo['order_id']
            ]);
        }
    }

    /**
     * Tin nhắn trang web hàng ảo
     * @param int $uid
     * @param array $noticeInfo
     */
    public function SystemSend(int $uid, array $noticeInfo)
    {
        /** @var MessageSystemServices $MessageSystemServices */
        $MessageSystemServices = app()->make(MessageSystemServices::class);
        $data = [];
        $data['mark'] = $noticeInfo['mark'];
        $data['uid'] = $uid;
        $data['title'] = $noticeInfo['title'];
        $data['content'] = $noticeInfo['content'];
        $data['type'] = 1;
        $data['add_time'] = time();
        $MessageSystemServices->save($data);
    }
}
