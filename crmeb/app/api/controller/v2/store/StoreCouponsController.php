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
namespace app\api\controller\v2\store;

use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\product\product\StoreProductCouponServices;
use app\Request;

class StoreCouponsController
{
    protected $services;

    public function __construct(StoreCouponIssueServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách phiếu giảm giá có sẵn
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function lst(Request $request)
    {
        $where = $request->getMore([
            ['type', 0],
            ['product_id', 0]
        ]);
        return app('json')->success($this->services->getIssueCouponList($request->uid(), $where));
    }

    /**
     * Nhận vé người mới
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getNewCoupon(Request $request)
    {
        $userInfo = $request->user();
        $data = [];
        /** @var StoreCouponIssueServices $couponService */        $couponService = app()->make(StoreCouponIssueServices::class);
        $data['list'] = $couponService->getNewCoupon();
        $data['image'] = sys_config('coupon_img');
        if ($userInfo->add_time === $userInfo->last_time) {
            $data['show'] = 1;
        } else {
            $data['show'] = 0;
        }
        return app('json')->success($data);
    }

    /**
     * Mã giảm giá miễn phí liên quan đến đơn hàng sau khi đặt hàng
     * @param Request $request
     * @param $orderId
     * @return mixed
     */    public function getOrderProductCoupon(Request $request, $orderId)
    {

        $uid = (int)$request->uid() ?? 0;
        if (!$orderId) {
            return app('json')->fail('Lỗi tham số');
        }
        /** @var StoreProductCouponServices $storeProductCoupon */        $storeProductCoupon = app()->make(StoreProductCouponServices::class);
        $list = $storeProductCoupon->getOrderProductCoupon($uid, $orderId);
        return app('json')->success($list);
    }

    /**
     * Nhận phiếu giảm giá mới được thêm vào hàng ngày
     * @return mixed
     */    public function getTodayCoupon(Request $request)
    {
        $uid = $request->uid() ?? 0;
        /** @var StoreCouponIssueServices $couponService */        $couponService = app()->make(StoreCouponIssueServices::class);
        $data['list'] = $couponService->getTodayCoupon($uid);
        $data['image'] = sys_config('coupon_img');
        return app('json')->success($data);
    }
}
