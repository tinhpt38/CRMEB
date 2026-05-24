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

namespace app\services\activity\integral;

use app\dao\activity\integral\StoreIntegralOrderDao;
use app\services\BaseServices;
use app\services\product\sku\StoreProductAttrValueServices;
use app\services\serve\ServeServices;
use app\services\shipping\ExpressServices;
use app\services\user\UserServices;
use app\services\user\UserAddressServices;
use app\services\user\UserBillServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder as Form;
use crmeb\services\printer\Printer;

/**
 * Class StoreIntegralOrderServices
 * @package app\services\order
 * @method getOrderIdsCount(array $ids) Lấy số lượng đơn hàng chưa bị xóa theo id đơn hàng
 * @method getUserOrderDetail(string $key, int $uid) Nhận chi tiết đơn hàng
 * @method getBuyCount($uid, $type) Lấy số lượng vật phẩm Khách hàng đã mua cho sự kiện này
 */class StoreIntegralOrderServices extends BaseServices
{

    /**
     * Loại vận chuyển
     * @var string[]
     */    public $deliveryType = ['send' => 'giao hàng của người bán', 'express' => 'chuyển phát nhanh', 'fictitious' => 'giao hàng ảo'];

    /**
     * StoreIntegralOrderServices constructor.
     * @param StoreIntegralOrderDao $dao
     */    public function __construct(StoreIntegralOrderDao $dao)
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
     */    public function getOrderList(array $where, array $field = ['*'], array $with = [])
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getOrderList($where, $field, $page, $limit, $with);
        $count = $this->dao->count($where);
        $data = $this->tidyOrderList($data);
        $batch_url = "file/upload/1";
        return compact('data', 'count', 'batch_url');
    }

    /**
     * Nhận dữ liệu xuất
     * @param array $where
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExportList(array $where, int $limit = 0)
    {
        if ($limit) {
            [$page] = $this->getPageValue();
        } else {
            [$page, $limit] = $this->getPageValue();
        }
        $data = $this->dao->getOrderList($where, ['*'], $page, $limit);
        $data = $this->tidyOrderList($data);
        return $data;
    }

    /**
     * Danh sách đơn hàng phía trước
     * @param array $where
     * @param array|string[] $field
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOrderApiList(array $where, array $field = ['*'], array $with = [])
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getOrderList($where, $field, $page, $limit, $with);
        return $this->tidyOrderList($data);
    }

    /**
     * Định dạng dữ liệu chi tiết đơn hàng
     * @param $order
     * @return mixed
     */    public function tidyOrder($order)
    {
        $order['add_time'] = date('Y-m-d H:i:s', $order['add_time']);
        if ($order['status'] == 1) {
            $order['status_name'] = 'Không được vận chuyển';
        } else if ($order['status'] == 2) {
            $order['status_name'] = 'Đang chờ nhận';
        } else if ($order['status'] == 3) {
            $order['status_name'] = 'Hoàn thành';
        }
        $order['price'] = (int)$order['price'];
        $order['total_price'] = (int)$order['total_price'];
        return $order;
    }

    /**
     * chuyển đổi dữ liệu
     * @param array $data
     * @return array
     */    public function tidyOrderList(array $data)
    {
        foreach ($data as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            if ($item['status'] == 1) {
                $item['status_name'] = 'Không được vận chuyển';
            } else if ($item['status'] == 2) {
                $item['status_name'] = 'Đang chờ nhận';
            } else if ($item['status'] == 3) {
                $item['status_name'] = 'Hoàn thành';
            }
            $item['price'] = (int)$item['price'];
            $item['total_price'] = (int)$item['total_price'];
        }
        return $data;
    }

    /**
     * Tạo đơn hàng
     * @param $uid
     * @param $addressId
     * @param string $mark
     * @param $user
     * @param $num
     * @param $productInfo
     * @throws \Exception
     */    public function createOrder($uid, $addressId, $mark = '', $userInfo, $num, $productInfo)
    {
        /** @var UserAddressServices $addressServices */        $addressServices = app()->make(UserAddressServices::class);
        if (!$addressId) {
            throw new ApiException('Vui lòng chọn địa chỉ giao hàng');
        }
        if (!$addressInfo = $addressServices->getOne(['uid' => $uid, 'id' => $addressId, 'is_del' => 0])) throw new ApiException('Chọn sai địa chỉ');
        $addressInfo = $addressInfo->toArray();
        $total_price = bcmul($productInfo['price'], $num, 2);
        /** @var UserBillServices $userBillServices */        $userBillServices = app()->make(UserBillServices::class);
        $usable_integral = bcsub((string)$userInfo['integral'], (string)$userBillServices->getBillSum(['uid' => $userInfo['uid'], 'is_frozen' => 1]), 0);
        if ($total_price > $usable_integral) throw new ApiException('Không đủ điểm');
        $orderInfo = [
            'uid' => $uid,
            'order_id' => $this->getNewOrderId(),
            'real_name' => $addressInfo['real_name'],
            'user_phone' => $addressInfo['phone'],
            'user_address' => $addressInfo['province'] . ' ' . $addressInfo['city'] . ' ' . $addressInfo['district'] . ' ' . $addressInfo['detail'],
            'product_id' => $productInfo['product_id'],
            'image' => $productInfo['image'],
            'store_name' => $productInfo['store_name'],
            'suk' => $productInfo['suk'],
            'total_num' => $num,
            'price' => $productInfo['price'],
            'total_price' => $total_price,
            'add_time' => time(),
            'status' => 1,
            'mark' => $mark,
            'channel_type' => $userInfo['user_type']
        ];
        $order = $this->transaction(function () use ($orderInfo, $userInfo, $productInfo, $uid, $num, $total_price) {
            //Tạo đơn hàng
            $order = $this->dao->save($orderInfo);
            if (!$order) {
                throw new ApiException('Tạo đơn hàng không thành công');
            }
            //Khấu trừ hàng tồn kho
            $this->decGoodsStock($productInfo, $num);
            //điểm trừ
            $this->deductIntegral($userInfo, $total_price, (int)$userInfo['uid'], $order->id);
            return $order;
        });
        /** @var StoreIntegralOrderStatusServices $statusService */        $statusService = app()->make(StoreIntegralOrderStatusServices::class);
        $statusService->save([
            'oid' => $order['id'],
            'change_type' => 'cache_key_create_order',
            'change_message' => 'Tạo đơn hàng',
            'change_time' => time()
        ]);
        return $order;
    }

    /**
     * Điểm trừ
     * @param array $userInfo
     * @param bool $useIntegral
     * @param array $priceData
     * @param int $uid
     * @param string $key
     */    public function deductIntegral(array $userInfo, $priceIntegral, int $uid, string $orderId)
    {
        $res2 = true;
        if ($userInfo['integral'] > 0) {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $res2 = false !== $userServices->bcDec($userInfo['uid'], 'integral', $priceIntegral, 'uid');
            /** @var UserBillServices $userBillServices */            $userBillServices = app()->make(UserBillServices::class);
            $res3 = $userBillServices->income('storeIntegral_use_integral', $uid, $priceIntegral, $userInfo['integral'] - $priceIntegral, $orderId);
            $res2 = $res2 && false != $res3;
        }
        if (!$res2) {
            throw new ApiException('Không thể sử dụng điểm để khấu trừ');
        }
    }

    /**
     * Khấu trừ hàng tồn kho
     * @param array $cartInfo
     * @param int $combinationId
     * @param int $seckillId
     * @param int $bargainId
     */    public function decGoodsStock(array $productInfo, int $num)
    {
        $res5 = true;
        /** @var StoreIntegralServices $StoreIntegralServices */        $StoreIntegralServices = app()->make(StoreIntegralServices::class);
        try {
            $res5 = $res5 && $StoreIntegralServices->decIntegralStock((int)$num, $productInfo['product_id'], $productInfo['unique']);
            if (!$res5) {
                throw new ApiException('Sản phẩm này đã hết hàng');
            }
        } catch (\Throwable $e) {
            throw new ApiException('Sản phẩm này đã hết hàng');
        }
    }

    /**
     * Tạo đơn hàng bằng thuật toán bông tuyếtID
     * @return string
     * @throws \Exception
     */    public function getNewOrderId(string $prefix = 'wx')
    {
        $snowflake = new \Godruoyi\Snowflake\Snowflake();
        //32Chút
        if (PHP_INT_SIZE == 4) {
            $id = abs($snowflake->id());
        } else {
            $id = $snowflake->setStartTimeStamp(strtotime('2020-06-05') * 1000)->id();
        }
        return $prefix . $id;
    }

    /**
     *Nhận số lượng đặt hàng
     * @param array $where
     * @return mixed
     */    public function orderCount(array $where)
    {
        //Tất cả đơn hàng
        $data['statusAll'] = (string)$this->dao->count($where + ['is_system_del' => 0]);
        //Không được vận chuyển
        $data['unshipped'] = (string)$this->dao->count($where + ['status' => 1, 'is_system_del' => 0]);
        //Đang chờ nhận
        $data['untake'] = (string)$this->dao->count($where + ['status' => 2, 'is_system_del' => 0]);
        //Đang chờ đánh giá
//        $data['unevaluate'] = (string)$this->dao->count(['status' => 3, 'time' => $where['time'], 'is_system_del' => 0]);
        //giao dịch đã hoàn tất
        $data['complete'] = (string)$this->dao->count($where + ['status' => 3, 'is_system_del' => 0]);
        return $data;
    }


    /**
     * Lệnh in
     * @param $order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function orderPrint($order)
    {
        $data = [
            'clientId' => sys_config('printing_client_id', ''),
            'apiKey' => sys_config('printing_api_key', ''),
            'partner' => sys_config('develop_id', ''),
            'terminal' => sys_config('terminal_number', '')
        ];
        if (!$data['clientId'] || !$data['apiKey'] || !$data['partner'] || !$data['terminal']) {
            throw new AdminException('Trước tiên hãy định cấu hình nhà phát triển in biên lai');
        }
        $printer = new Printer('yi_lian_yun', $data);
        $res = $printer->setIntegralPrinterContent([
            'name' => sys_config('site_name'),
            'orderInfo' => is_object($order) ? $order->toArray() : $order,
        ])->startPrinter();
        if (!$res) {
            throw new AdminException($printer->getError());
        }
        return $res;
    }

    /**
     * Nhận dữ liệu xác nhận đơn hàng
     * @param array $user
     * @param $cartId
     * @return mixed
     */    public function getOrderConfirmData(array $user, $unique, $num)
    {
        /** @var StoreProductAttrValueServices $StoreProductAttrValueServices */        $StoreProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
        $attrValue = $StoreProductAttrValueServices->uniqueByField($unique, 'product_id,suk,price,image,unique');
        if (!$attrValue || !isset($attrValue['storeIntegral']) || !$attrValue['storeIntegral']) {
            throw new ApiException('Sản phẩm này đã bị gỡ bỏ khỏi kệ hoặc bị xóa');
        }
        $data = [];
        $attrValue = is_object($attrValue) ? $attrValue->toArray() : $attrValue;
        $attrValue['price'] = (int)$attrValue['price'];
        /** @var UserBillServices $userBillServices */        $userBillServices = app()->make(UserBillServices::class);
        $data['integral'] = bcsub((string)$user['integral'], (string)$userBillServices->getBillSum(['uid' => $user['uid'], 'is_frozen' => 1]), 0);
        $data['num'] = $num;
        $data['total_price'] = bcmul($num, $attrValue['price']);
        $data['productInfo'] = $attrValue;
        return $data;
    }

    /**
     * Xóa đơn hàng
     * @param $uni
     * @param $uid
     * @return bool
     */    public function removeOrder(string $order_id, int $uid)
    {
        $order = $this->getUserOrderDetail($order_id, $uid);
        if ($order['status'] != 3)
            throw new ApiException('Xóa không thành công');

        $order->is_del = 1;
        /** @var StoreIntegralOrderStatusServices $statusService */        $statusService = app()->make(StoreIntegralOrderStatusServices::class);
        $res = $statusService->save([
            'oid' => $order['id'],
            'change_type' => 'remove_order',
            'change_message' => 'Xóa đơn hàng',
            'change_time' => time()
        ]);
        if ($order->save() && $res) {
            return true;
        } else
            throw new ApiException('Xóa không thành công');
    }

    /**
     * Đã giao cho ĐVVC
     * @param int $id
     * @param array $data
     * @return bool
     */    public function delivery(int $id, array $data)
    {
        $orderInfo = $this->dao->get($id);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        if ($orderInfo->is_del) {
            throw new AdminException('Đơn hàng đã bị xóa');
        }
        if ($orderInfo->status != 1) {
            throw new AdminException('Đơn hàng đã được chuyển đi');
        }
        $type = (int)$data['type'];
        unset($data['type']);
        if ($type == 1) {
            // Phát hiện mã công ty chuyển phát nhanh
            /** @var ExpressServices $expressServices */            $expressServices = app()->make(ExpressServices::class);
            if (!$expressServices->be(['code' => $data['delivery_code']])) {
                throw new AdminException('Vui lòng kiểm tra mã công ty chuyển phát nhanh');
            }
        }
        switch ($type) {
            case 1:
                //vận chuyển
                $this->orderDeliverGoods($id, $data, $orderInfo);
                break;
            case 2:
                $this->orderDelivery($id, $data, $orderInfo);
                break;
            case 3:
                $this->orderVirtualDelivery($id, $data, $orderInfo);
                break;
            default:
                throw new AdminException('Các loại phân phối khác hiện không được hỗ trợ');
        }
        return true;
    }

    /**
     * giao hàng ảo
     * @param int $id
     * @param array $data
     */    public function orderVirtualDelivery(int $id, array $data)
    {
        $data['delivery_type'] = 'fictitious';
        $data['status'] = 2;
        unset($data['sh_delivery_name'], $data['sh_delivery_id'], $data['delivery_name'], $data['delivery_id']);
        //Lưu thông tin
        /** @var StoreIntegralOrderStatusServices $services */        $services = app()->make(StoreIntegralOrderStatusServices::class);
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
     * Đơn hàng giao hàng
     * @param int $id
     * @param array $data
     */    public function orderDelivery(int $id, array $data, $orderInfo)
    {
        $data['delivery_type'] = 'send';
        $data['delivery_name'] = $data['sh_delivery_name'];
        $data['delivery_id'] = $data['sh_delivery_id'];
        $data['delivery_uid'] = $data['sh_delivery_uid'];
//        Nhận mã xác minh
        $data['verify_code'] = $this->getStoreCode();
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
        if (!check_phone($data['delivery_id'])) {
            throw new AdminException('Vui lòng nhập đúng số điện thoại người giao hàng');
        }
        $data['status'] = 2;
        $orderInfo->delivery_type = $data['delivery_type'];
        $orderInfo->delivery_name = $data['delivery_name'];
        $orderInfo->delivery_id = $data['delivery_id'];
        $orderInfo->status = $data['status'];
        /** @var StoreIntegralOrderStatusServices $services */        $services = app()->make(StoreIntegralOrderStatusServices::class);
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
     * Chuyển phát nhanh các đơn hàng
     * @param int $id
     * @param array $data
     */    public function orderDeliverGoods(int $id, array $data, $orderInfo)
    {
        if (!$data['delivery_name']) {
            throw new AdminException('Hãy chọn công ty chuyển phát nhanh');
        }
        $data['delivery_type'] = 'express';
        if ($data['express_record_type'] == 2) {//Mẫu điện tử
            if (!$data['delivery_code']) {
                throw new AdminException('Thiếu số công ty chuyển phát nhanh');
            }
            if (!$data['express_temp_id']) {
                throw new AdminException('Vui lòng chọn mẫu biểu mẫu điện tử');
            }
            if (!$data['to_name']) {
                throw new AdminException('Vui lòng điền tên người gửi');
            }
            if (!$data['to_tel']) {
                throw new AdminException('Vui lòng nhập số điện thoại di động của người gửi');
            }
            if (!$data['to_addr']) {
                throw new AdminException('Vui lòng điền địa chỉ chi tiết của người gửi');
            }
            /** @var ServeServices $ServeServices */            $ServeServices = app()->make(ServeServices::class);
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
            $expData['weight'] = 1;
            $expData['cargo'] = $orderInfo->store_name . '(' . $orderInfo->suk . ')*' . $orderInfo->total_num;
            $expData['order_id'] = $orderInfo->order_id;
            if (!sys_config('config_export_open', 0)) {
                throw new AdminException('Đã đóng hóa đơn điện tử, vui lòng chọn hình thức vận chuyển khác');
            }
            $dump = $ServeServices->express()->dump($expData);
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
                throw new AdminException('Số theo dõi chuyển phát nhanh không tồn tại');
            }
            $orderInfo->delivery_id = $data['delivery_id'];
        }
        $data['status'] = 2;
        $orderInfo->delivery_type = $data['delivery_type'];
        $orderInfo->delivery_name = $data['delivery_name'];
        $orderInfo->status = $data['status'];
        /** @var StoreIntegralOrderStatusServices $services */        $services = app()->make(StoreIntegralOrderStatusServices::class);
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
     * Lệnh xác nhận tạo ra mã xác nhận
     * @return false|string
     */    public function getStoreCode()
    {
        mt_srand();
        list($msec, $sec) = explode(' ', microtime());
        $num = time() + mt_rand(10, 999999) . '' . substr($msec, 2, 3);//Tạo số ngẫu nhiên
        if (strlen($num) < 12)
            $num = str_pad((string)$num, 12, 0, STR_PAD_RIGHT);
        else
            $num = substr($num, 0, 12);
        if ($this->dao->count(['verify_code' => $num])) {
            return $this->getStoreCode();
        }
        return $num;
    }

    /**
     * Nhận và sửa đổi cấu trúc biểu mẫu thông tin vận chuyển
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function distributionForm(int $id)
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
                /** @var ExpressServices $expressServices */                $expressServices = app()->make(ExpressServices::class);
                $f[] = Form::select('delivery_code', 'công ty chuyển phát nhanh', (string)$orderInfo->getData('delivery_code'))->setOptions($expressServices->expressSelectForm(['is_show' => 1]))->required('Hãy chọn công ty chuyển phát nhanh');
                $f[] = Form::input('delivery_id', 'Số theo dõi nhanh', $orderInfo->getData('delivery_id'))->required('Vui lòng điền số chuyển phát nhanh');
                break;
        }
        return create_form('Thông tin vận chuyển', $f, $this->url('/marketing/integral/order/distribution/' . $id), 'PUT');
    }

    /**
     * Biên nhận đặt hàng của Khách hàng
     * @param $uni
     * @param $uid
     * @return bool
     */    public function takeOrder(string $order_id, int $uid)
    {
        $order = $this->dao->getUserOrderDetail($order_id, $uid);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        if ($order['status'] != 2) {
            throw new ApiException('Lỗi trạng thái đơn hàng');
        }
        $order->status = 3;
        /** @var StoreIntegralOrderStatusServices $statusService */        $statusService = app()->make(StoreIntegralOrderStatusServices::class);
        $res = $order->save() && $statusService->save([
                'oid' => $order['id'],
                'change_type' => 'user_take_delivery',
                'change_message' => 'Người dùng đã nhận được hàng',
                'change_time' => time()
            ]);
        if (!$res) {
            throw new ApiException('Biên nhận không thành công,Vui lòng thử lại sau');
        }
        return $order;
    }

    /**
     * Sửa đổi thông tin vận chuyển
     * @param int $id Đơn hàngid
     * @return mixed
     */    public function updateDistribution(int $id, array $data)
    {
        $order = $this->dao->get($id);
        if (!$order) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        switch ($order['delivery_type']) {
            case 'send':
                if (!$data['delivery_name']) {
                    throw new AdminException('Vui lòng nhập tên người giao hàng');
                }
                if (!$data['delivery_id']) {
                    throw new AdminException('Vui lòng nhập số điện thoại người giao hàng');
                }
                if (!check_phone($data['delivery_id'])) {
                    throw new AdminException('Vui lòng nhập đúng số điện thoại người giao hàng');
                }
                break;
            case 'express':
                // Phát hiện mã công ty chuyển phát nhanh
                /** @var ExpressServices $expressServices */                $expressServices = app()->make(ExpressServices::class);
                if ($name = $expressServices->value(['code' => $data['delivery_code']], 'name')) {
                    $data['delivery_name'] = $name;
                } else {
                    throw new AdminException('Vui lòng kiểm tra mã công ty chuyển phát nhanh');
                }
                break;
            default:
                throw new AdminException('Vui lòng gửi hàng trước rồi sửa đổi thông tin giao hàng.');
        }
        /** @var StoreIntegralOrderStatusServices $statusService */        $statusService = app()->make(StoreIntegralOrderStatusServices::class);
        $statusService->save([
            'oid' => $id,
            'change_type' => 'distribution',
            'change_message' => 'Sửa thông tin vận chuyển thành' . $data['delivery_name'] . 'Con số' . $data['delivery_id'],
            'change_time' => time()
        ]);
        return $this->dao->update($id, $data);
    }

    /**
     * Xóa các đơn hàng đã bị Khách hàng xóa theo đợt
     * @return string|null
     */    public function delOrders(array $ids)
    {
        if (!count($ids)) throw new AdminException('Lỗi tham số');
        if ($this->getOrderIdsCount($ids)) throw new AdminException('Đơn hàng không tồn tại');
        return $this->batchUpdate($ids, ['is_system_del' => 1]);
    }

    /**
     * Xóa đơn hàng
     * @param $id
     * @return bool
     */    public function delOrder(int $id)
    {
        if (!$id || !($orderInfo = $this->get($id))) throw new AdminException('Đơn hàng không tồn tại');
        if (!$orderInfo->is_del) throw new AdminException('Đơn hàng bạn chọn đã tồn tại và chưa bị Khách hàng xóa.');
        $orderInfo->is_system_del = 1;
        return $orderInfo->save();
    }

    /**
     * Sửa đổi nhận xét
     * @param int $id
     * @param string $remark
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function remark(int $id, string $remark)
    {
        if (!$remark) throw new AdminException('Chú thích không được để trống');
        if (!$id) throw new AdminException('Lỗi tham số');
        if (!$order = $this->dao->get($id)) {
            throw new AdminException('Nhận xét không thành công');
        }

        $order->remark = $remark;
        return $order->save();
    }
}
