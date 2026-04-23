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
namespace app\adminapi\controller\v1\marketing\integral;

use app\adminapi\controller\AuthController;
use app\services\serve\ServeServices;
use app\services\activity\integral\{
    StoreIntegralOrderServices,
    StoreIntegralOrderStatusServices
};
use app\services\order\StoreOrderDeliveryServices;
use app\services\shipping\ExpressServices;
use app\services\user\UserServices;
use think\facade\App;

/**
 * Quản lý đơn hàng
 * Class StoreOrder
 * @package app\controller\admin\v1\order
 */
class StoreIntegralOrder extends AuthController
{
    /**
     * StoreIntegralOrder constructor.
     * @param App $app
     * @param StoreIntegralOrderServices $service
     * @method temp
     */
    public function __construct(App $app, StoreIntegralOrderServices $service)
    {
        parent::__construct($app);
        $this->services = $service;
    }

    /**
     * Nhận số lượng loại đơn đặt hàng
     * @return mixed
     */
    public function chart()
    {
        $where = $this->request->getMore([
            ['data', '', '', 'time'],
            ['product_id', '']
        ]);
        $data = $this->services->orderCount($where);
        return app('json')->success($data);
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
            ['data', '', '', 'time'],
            ['order', ''],
            ['field_key', ''],
            ['product_id', '']
        ]);
        $where['is_system_del'] = 0;
        return app('json')->success($this->services->getOrderList($where, ['*']));
    }

    /**
     * Nhận công ty chuyển phát nhanh
     * @return mixed
     */
    public function express(ExpressServices $services)
    {
        [$status] = $this->request->getMore([
            ['status', ''],
        ], true);
        if ($status != '') $data['status'] = $status;
        $data['is_show'] = 1;
        return app('json')->success($services->express($data));
    }

    /**
     * Xóa các đơn hàng đã bị người dùng xóa theo đợt
     * @return mixed
     */
    public function del_orders()
    {
        [$ids, $all, $where] = $this->request->postMore([
            ['ids', []],
            ['where', []],
        ], true);
        if ($this->services->delOrders($ids)) {
            return app('json')->success('Xóa thành công');
        } else {
            return app('json')->fail('Xóa không thành công');
        }
    }

    /**
     * Xóa đơn hàng
     * @param $id
     * @return mixed
     */
    public function del($id)
    {
        if ($this->services->delOrder($id)) {
            return app('json')->success('Xóa thành công');
        } else {
            return app('json')->fail('Xóa không thành công');
        }
    }

    /**
     * Đơn hàng đã được vận chuyển
     * @param $id
     * @return mixed
     */
    public function update_delivery($id)
    {
        $data = $this->request->postMore([
            ['type', 1],
            ['delivery_name', ''],//Tên công ty chuyển phát nhanh
            ['delivery_id', ''],//Số theo dõi nhanh
            ['delivery_code', ''],//Mã công ty chuyển phát nhanh

            ['express_record_type', 2],//Loại hồ sơ vận chuyển
            ['express_temp_id', ""],//Mẫu biểu mẫu điện tử
            ['to_name', ''],//Tên người gửi
            ['to_tel', ''],//Số điện thoại của người gửi
            ['to_addr', ''],//Địa chỉ người gửi

            ['sh_delivery_name', ''],//Tên người giao hàng
            ['sh_delivery_id', ''],//Số điện thoại người giao hàng
            ['sh_delivery_uid', ''],//người giao hàngID

            ['fictitious_content', '']//Nội dung phân phối ảo
        ]);
        $this->services->delivery((int)$id, $data);
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * xác nhận đã nhận hàng
     * @param $id
     * @return mixed
     */
    public function take_delivery($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $order = $this->services->get($id);
        if (!$order)
            return app('json')->fail('Dữ liệu không tồn tại');
        if ($order['status'] == 3)
            return app('json')->fail('Không thể nhận hàng nhiều lần');
        if ($order['status'] == 2)
            $data['status'] = 3;
        else
            return app('json')->fail('Vui lòng gửi hàng hoặc giao hàng trước');

        if (!$this->services->update($id, $data)) {
            return app('json')->fail('Biên nhận không thành công,Vui lòng thử lại sau');
        } else {
            //Thêm trạng thái đơn hàng giao hàng
            /** @var StoreIntegralOrderStatusServices $statusService */
            $statusService = app()->make(StoreIntegralOrderStatusServices::class);
            $statusService->save([
                'oid' => $order['id'],
                'change_type' => 'take_delivery',
                'change_message' => 'Hàng đã nhận',
                'change_time' => time()
            ]);
            return app('json')->success('Đã nhận hàng thành công');
        }
    }

    /**
     * Chi tiết đặt hàng
     * @param $id Đặt hàngid
     * @return mixed
     */
    public function order_info($id)
    {
        if (!$id || !($orderInfo = $this->services->get($id))) {
            return app('json')->fail('Đơn hàng không tồn tại');
        }
        /** @var UserServices $services */
        $services = app()->make(UserServices::class);
        $userInfo = $services->get($orderInfo['uid']);
        if (!$userInfo) return app('json')->fail('Thông tin người dùng không tồn tại');
        $userInfo = $userInfo->hidden(['pwd', 'add_ip', 'last_ip', 'login_type']);
        $orderInfo = $this->services->tidyOrder($orderInfo->toArray());
        $userInfo = $userInfo->toArray();
        return app('json')->success(compact('orderInfo', 'userInfo'));
    }

    /**
     * Truy vấn thông tin hậu cần
     * @param $id Đặt hàngid
     * @return mixed
     */
    public function get_express($id, ExpressServices $services)
    {
        if (!$id || !($orderInfo = $this->services->get($id)))
            return app('json')->fail('Đơn hàng không tồn tại');
        if ($orderInfo['delivery_type'] != 'express' || !$orderInfo['delivery_id'])
            return app('json')->fail('Số theo dõi chuyển phát nhanh không tồn tại');

        $cacheName = 'integral' . $orderInfo['order_id'] . $orderInfo['delivery_id'];

        $data['delivery_name'] = $orderInfo['delivery_name'];
        $data['delivery_id'] = $orderInfo['delivery_id'];
        $data['result'] = $services->query($cacheName, $orderInfo['delivery_id'], $orderInfo['delivery_code'] ?? null, $orderInfo['user_phone']);
        return app('json')->success($data);
    }

    /**
     * Nhận và sửa đổi cấu trúc biểu mẫu thông tin vận chuyển
     * @param $id Đặt hàngid
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function distribution($id)
    {
        if (!$id) {
            return app('json')->fail('Đơn hàng không tồn tại');
        }
        return app('json')->success($this->services->distributionForm((int)$id));
    }

    /**
     * Sửa đổi thông tin vận chuyển
     * @param $id  Đặt hàngid
     * @return mixed
     */
    public function update_distribution($id)
    {
        $data = $this->request->postMore([['delivery_name', ''], ['delivery_code', ''], ['delivery_id', '']]);
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->updateDistribution($id, $data);
        return app('json')->success('Sửa đổi thành công');
    }


    /**
     * Sửa đổi nhận xét
     * @param $id
     * @return mixed
     */
    public function remark($id)
    {
        $data = $this->request->postMore([['remark', '']]);
        if ($this->services->remark($id, $data['remark'])) {
            return app('json')->success('Bình luận thành công');
        } else {
            return app('json')->fail('Nhận xét không thành công');
        }
    }

    /**
     * Nhận danh sách trạng thái đơn hàng và phân trang
     * @param $id
     * @return mixed
     */
    public function status(StoreIntegralOrderStatusServices $services, $id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($services->getStatusList(['oid' => $id])['list']);
    }

    /**
     * In máy in đám mây Yilian
     * @param $id
     * @return mixed
     */
    public function order_print($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $order = $this->services->get($id);
        if (!$order) {
            return app('json')->fail('Đơn hàng không tồn tại');
        }
        $res = $this->services->orderPrint($order);
        if ($res) {
            return app('json')->success('In thành công');
        } else {
            return app('json')->fail('In không thành công');
        }
    }

    /**
     * Mẫu biểu mẫu điện tử
     * @param $com
     * @return mixed
     */
    public function expr_temp(ServeServices $services, $com)
    {
        if (!$com) {
            return app('json')->fail('Thiếu số công ty chuyển phát nhanh');
        }
        $list = $services->express()->temp($com);
        return app('json')->success($list);
    }

    /**
     * Nhận mẫu
     */
    public function express_temp(ServeServices $services)
    {
        $data = $this->request->getMore([['com', '']]);
        $tpd = $services->express()->temp($data['com']);
        return app('json')->success($tpd['data']);
    }

    /**
     * In biểu mẫu điện tử sau khi đơn hàng được chuyển đi
     * @param $order_id
     * @param StoreOrderDeliveryServices $storeOrderDeliveryServices
     * @return mixed
     */
    public function order_dump($order_id, StoreOrderDeliveryServices $storeOrderDeliveryServices)
    {
        return app('json')->success($storeOrderDeliveryServices->orderDump($order_id, 'integral_order'));

    }

    /**
     * Nhận thông tin cấu hình
     * @return mixed
     */
    public function getDeliveryInfo()
    {
        return app('json')->success([
            'express_temp_id' => sys_config('config_export_temp_id'),
            'id' => sys_config('config_export_id'),
            'to_name' => sys_config('config_export_to_name'),
            'to_tel' => sys_config('config_export_to_tel'),
            'to_add' => sys_config('config_export_to_address'),
            'export_open' => (bool)((int)sys_config('config_export_open'))
        ]);
    }

}
