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

use app\Request;
use app\services\order\OutStoreOrderRefundServices;
use think\facade\App;

/**
 * Bộ điều khiển đơn hậu mãi
 * Class RefundOrder
 * @package app\outapi\controller
 */
class RefundOrder extends AuthController
{
    /**
     * RefundOrder constructor.
     * @param App $app
     * @param OutStoreOrderRefundServices $service
     * @method temp
     */
    public function __construct(App $app, OutStoreOrderRefundServices $service)
    {
        parent::__construct($app);
        $this->services = $service;
    }

    /**
     * Nhận danh sách đơn hàng sau bán hàng
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lst()
    {
        $where = $this->request->getMore([
            ['order_id', ''],
            ['time', ''],
            ['refund_type', 0]
        ]);
        $where['is_cancel'] = 0;

        return app('json')->success($this->services->refundList($where));
    }

    /**
     * Sửa đổi nhận xét
     * @param string $order_id Số đơn hàng sau bán hàng
     * @return mixed
     */
    public function remark(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        [$remark] = $this->request->postMore([['remark', '']], true);

        $this->services->remark($order_id, $remark);
        return app('json')->success('Bình luận thành công');
    }

    /**
     * Đồng ý hoàn tiền
     * @param string $order_id Số đơn hàng sau bán hàng
     * @return mixed
     */
    public function agree(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
       $this->services->agree($order_id);
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Đơn đặt hàng không được hoàn lại
     * @param string $order_id Số đơn hàng sau bán hàng
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refuse(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        [$refund_reason] = $this->request->postMore([['refund_reason', '']], true);

        $this->services->refuse($order_id, $refund_reason);
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Chi tiết đặt hàng
     * @param string $order_id Số đơn hàng sau bán hàng
     * @return mixed
     */
    public function read(string $order_id)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        $data = $this->services->getInfo($order_id);
        return app('json')->success($data);
    }

    /**
     * Hoàn tiền đơn hàng
     * @param string $order_id Số đơn hàng sau bán hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refundPrice(string $order_id, Request $request)
    {
        if (!$order_id) return app('json')->fail('Lỗi tham số');
        [$refund_price] = $request->postMore([['refund_price', '']], true);
        $this->services->refundPrice($order_id, $refund_price);
        return app('json')->success('Hoàn tiền thành công');
    }

}
