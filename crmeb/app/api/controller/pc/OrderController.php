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

namespace app\api\controller\pc;


use app\Request;
use app\services\order\StoreOrderRefundServices;
use app\services\pc\OrderServices;

class OrderController
{
    protected $services;

    public function __construct(OrderServices $services)
    {
        $this->services = $services;
    }

    /**
     * Trạng thái thứ tự thăm dò ý kiến
     * @param Request $request
     * @return mixed
     */
    public function checkOrderStatus(Request $request)
    {
        list($order_id, $end_time) = $request->getMore([
            ['order_id', ''],
            ['end_time', ''],
        ], true);
        $data['status'] = $this->services->checkOrderStatus((string)$order_id);
        $time = $end_time - time();
        $data['time'] = $time > 0 ? $time : 0;
        return app('json')->success($data);
    }

    /**
     * Nhận danh sách đặt hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderList(Request $request)
    {
        $where = $request->getMore([
            ['type', '', '', 'status'],
            ['search', '', '', 'real_name'],
        ]);
        $where['uid'] = $request->uid();
        $where['is_del'] = 0;
        $where['is_system_del'] = 0;
        if (!in_array($where['status'], [-1, -2, -3])) $where['pid'] = 0;
        return app('json')->success($this->services->getOrderList($where));
    }

    /**
     * Danh sách đơn hàng hoàn tiền
     * @param Request $request
     * @param StoreOrderRefundServices $refundServices
     * @return mixed
     */
    public function getRefundOrderList(Request $request,StoreOrderRefundServices $refundServices)
    {
        $where['uid'] = $request->uid();
        $where['is_cancel'] = 0;
        $where['is_del'] = 0;
        $where['is_system_del'] = 0;
        $data = $refundServices->refundList($where);
        return app('json')->success($data);
    }
}
