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
namespace app\api\controller\v1\order;

use app\Request;
use app\services\order\StoreOrderRefundServices;
use app\services\order\StoreOrderServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class StoreOrderRefundController
{
    /**
     * @var StoreOrderRefundServices
     */    protected $services;

    /**
     * StoreOrderRefundController constructor.
     * @param StoreOrderRefundServices $services
     */    public function __construct(StoreOrderRefundServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách đơn hàng hoàn tiền
     * @param Request $request
     * @return mixed
     */    public function refundList(Request $request)
    {
        $where = $request->getMore([
            ['refund_status', ''],
        ]);
        $where['uid'] = $request->uid();
        $where['is_cancel'] = 0;
        $where['is_del'] = 0;
        $data = $this->services->refundList($where);
        return app('json')->success($data);
    }

    /**
     * Chi tiết đơn hàng hoàn tiền
     * @param Request $request
     * @param $uni
     * @return mixed
     */    public function refundDetail(Request $request, $uni)
    {
        $orderData = $this->services->refundDetail($uni);
        return app('json')->success($orderData);
    }

    /**
     * Hủy đơn đăng ký
     * @param Request $request
     * @param $uni
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */    public function cancelApply(Request $request, $uni)
    {
        if (!strlen(trim($uni))) return app('json')->fail('Lỗi tham số');
        $orderRefund = $this->services->get(['order_id' => $uni, 'is_cancel' => 0]);
        if (!$orderRefund || $orderRefund['uid'] != $request->uid()) {
            return app('json')->fail('Đơn hàng không tồn tại');
        }
        if (!in_array($orderRefund['refund_type'], [1, 2, 4, 5])) {
            return app('json')->fail('Không thể hủy đơn đăng ký do Trạng thái hiện tại');
        }
        $this->services->update($orderRefund['id'], ['is_cancel' => 1]);
        $this->services->cancelOrderRefundCartInfo((int)$orderRefund['id'], (int)$orderRefund['store_order_id'], $orderRefund);

        //Sự kiện tùy chỉnh - Khách hàng hủy hoàn tiền
        event('CustomEventListener', ['order_refund_cancel', [
            'uid' => $orderRefund['uid'],
            'id' => $orderRefund['id'],
            'store_order_id' => $orderRefund['store_order_id'],
            'order_id' => $orderRefund['order_id'],
            'refund_num' => $orderRefund['refund_num'],
            'refund_price' => $orderRefund['refund_price'],
            'cancel_time' => date('Y-m-d H:i:s'),
        ]]);

        return app('json')->success('Hủy thành công');
    }

    /**
     * Người dùng trả lại hàng và gửi số theo dõi chuyển phát nhanh
     * @param Request $request
     * @return mixed
     */    public function applyExpress(Request $request)
    {
        $data = $request->postMore([
            ['id', ''],
            ['refund_express', ''],
            ['refund_phone', ''],
            ['refund_express_name', ''],
            ['refund_img', ''],
            ['refund_explain', ''],
        ]);
        if ($data['id'] == '') return app('json')->fail('Lỗi tham số');
        $res = $this->services->editRefundExpress($data);
        if ($res)
            return app('json')->success('Gửi thành công');
        else
            return app('json')->fail('Gửi không thành công');
    }

    /**
     * Xóa yêu cầu hoàn tiền
     * @param Request $request
     * @param $uni
     * @return mixed
     */    public function delRefund(Request $request, $uni)
    {
        $oid = $this->services->value(['order_id' => $uni, 'uid' => $request->uid()], 'store_order_id');
        $res = $this->services->update(['order_id' => $uni, 'uid' => $request->uid()], ['is_del' => 1]);
        /** @var StoreOrderServices $orderServices */        $orderServices = app()->make(StoreOrderServices::class);
        $orderServices->update($oid, ['is_del' => 1], 'id');
        if ($res)
            return app('json')->success('Xóa thành công');
        else
            return app('json')->fail('Xóa không thành công');
    }
}
