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
namespace app\outapi\controller;

use app\services\order\OutStoreOrderServices;
use app\services\shipping\ExpressServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Quản lý đơn hàng
 * Class StoreOrder
 * @package app\outapi\controller
 */
class StoreOrder extends AuthController
{
    /**
     * StoreOrder constructor.
     * @param App $app
     * @param OutStoreOrderServices $service
     * @method temp
     */
    public function __construct(App $app, OutStoreOrderServices $service)
    {
        parent::__construct($app);
        $this->services = $service;
    }

    /**
     * Nhận danh sách đặt hàng
     * @return mixed
     */
    public function lst()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['real_name', ''],
            ['is_del', ''],
            ['data', '', '', 'time'],
            ['type', ''],
            ['pay_type', ''],
            ['order', ''],
            ['field_key', ''],
            ['paid', '']
        ]);
        $where['is_system_del'] = 0;
        $where['pid'] = 0;
        return app('json')->success($this->services->getOrderList($where));
    }

    /**
     * Danh sách công ty chuyển phát nhanh
     * @return mixed
     */
    public function express(ExpressServices $services)
    {
        [$status] = $this->request->getMore([
            ['status', ''],
        ], true);
        if ($status != '') $data['status'] = $status;
        $data['is_show'] = 1;
        $list = CacheService::remember('EXPRESS_LIST', function () use ($services, $data) {
            return $services->express($data);
        }, 86400);
        return app('json')->success($list);
    }

    /**
     * Đơn hàng đã được vận chuyển
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function delivery(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([
            ['delivery_name', ''],//Tên công ty chuyển phát nhanh
            ['delivery_id', ''],//Số theo dõi nhanh
            ['delivery_code', ''],//Mã công ty chuyển phát nhanh
        ]);
        $data['express_record_type'] = 1;
        $data['type'] = 1;
        return app('json')->success('Hoạt động thành công', $this->services->delivery($order_id, $data));
    }

    /**
     * Lấy danh sách các mặt hàng có thể được vận chuyển riêng cho một đơn hàng
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function splitCartInfo(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->getCartList($order_id));
    }

    /**
     * Chia đơn hàng và gửi hàng
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function splitDelivery(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([
            ['delivery_name', ''],//Tên công ty chuyển phát nhanh
            ['delivery_id', ''],//Số theo dõi nhanh
            ['delivery_code', ''],//Mã công ty chuyển phát nhanh
            ['fictitious_content', ''],//Nội dung phân phối ảo
            ['cart_ids', []]
        ]);

        if (!$data['cart_ids']) {
            return app('json')->fail('Vui lòng chọn sản phẩm cần vận chuyển');
        }
        foreach ($data['cart_ids'] as &$cart) {
            if (!isset($cart['cart_id']) || !$cart['cart_id'] || !isset($cart['cart_num']) || !$cart['cart_num']) {
                return app('json')->fail('Vui lòng chọn lại sản phẩm hoặc số lượng lô hàng');
            }
            $cart['cart_id'] = (int)$cart['cart_id'];
            $cart['cart_num'] = (int)$cart['cart_num'];
        }
        $data['express_record_type'] = 1;
        $data['type'] = 1;

        $this->services->splitDelivery($order_id, $data);
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * xác nhận đã nhận hàng
     * @param string $order_id Số đơn hàng
     * @return mixed
     * @throws \Exception
     */
    public function receive(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $this->services->receive($order_id);
        return app('json')->success('Đã nhận hàng thành công');
    }

    /**
     * Thiết lập thông tin hóa đơn
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function setInvoice(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([
            [['header_type', 'd'], 1],
            [['type', 'd'], 1],
            ['drawer_phone', ''],
            ['email', ''],
            ['name', ''],
            ['duty_number', ''],
            ['tell', ''],
            ['address', ''],
            ['bank', ''],
            ['card_number', ''],
        ]);

        if (!$data['drawer_phone']) return app('json')->fail('Vui lòng điền số điện thoại di động lập hóa đơn');
        if (!check_phone($data['drawer_phone'])) return app('json')->fail('Định dạng số điện thoại di động không chính xác');
        if (!$data['name']) return app('json')->fail('Vui lòng điền tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        if (!in_array($data['header_type'], [1, 2])) {
            $data['header_type'] = empty($data['duty_number']) ? 1 : 2;
        }
        if ($data['header_type'] == 1 && !preg_match('/^[\x80-\xff]{2,60}$/', $data['name'])) {
            return app('json')->fail('Vui lòng điền đúng tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        }
        if ($data['header_type'] == 2 && !preg_match('/^[0-9a-zA-Z&\(\)\（\）\x80-\xff]{2,150}$/', $data['name'])) {
            return app('json')->fail('Vui lòng điền đúng tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        }
        if ($data['header_type'] == 2 && !$data['duty_number']) {
            return app('json')->fail('Vui lòng điền mã số thuế trên hóa đơn');
        }
        if ($data['header_type'] == 2 && !preg_match('/^[A-Z0-9]{15}$|^[A-Z0-9]{17}$|^[A-Z0-9]{18}$|^[A-Z0-9]{20}$/', $data['duty_number'])) {
            return app('json')->fail('Vui lòng điền đúng mã số thuế trên hóa đơn');
        }
        if ($data['card_number'] && !preg_match('/^[1-9]\d{11,19}$/', $data['card_number'])) {
            return app('json')->fail('Vui lòng điền đúng số thẻ ngân hàng');
        }

        if ($this->services->setInvoice($order_id, $data)) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Thao tác không thành công');
        }
    }

    /**
     * Đặt trạng thái hóa đơn
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function setInvoiceStatus(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([
            ['is_invoice', 0],
            ['invoice_number', 0],
            ['remark', '']
        ]);

        if ($data['is_invoice'] == 1 && !$data['invoice_number']) {
            return app('json')->fail('Vui lòng điền số hóa đơn');
        }

        $this->services->setInvoice($order_id, $data);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Chi tiết đặt hàng
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function read(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->getInfo($order_id));
    }

    /**
     * Sửa đổi nhận xét
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function remark(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([['remark', '']]);
        if (!$data['remark']) return app('json')->fail('Chú thích không được để trống');

        if (!$order = $this->services->get(['order_id' => $order_id])) {
            return app('json')->fail('Đơn hàng không tồn tại');
        }
        $order->remark = $data['remark'];
        if ($order->save()) {
            return app('json')->success('Bình luận thành công');
        } else
            return app('json')->fail('Nhận xét không thành công');
    }

    /**
     * Sửa đổi thông tin vận chuyển
     * @param string $order_id Số đơn hàng
     * @return mixed
     */
    public function updateDistribution(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([['delivery_name', ''], ['delivery_code', ''], ['delivery_id', '']]);

        $this->services->updateDistribution($order_id, $data);
        return app('json')->success('Hoạt động thành công');
    }
}
