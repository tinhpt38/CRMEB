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
namespace app\api\controller\v1\store;

use app\Request;
use app\services\activity\coupon\StoreCouponIssueServices;

/**
 * Danh mục phiếu giảm giá
 * Class StoreCouponsController
 * @package app\api\controller\store
 */
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
     */
    public function lst(Request $request)
    {
        $where = $request->getMore([
            ['type', 0],
            ['product_id', 0],
            ['num', 0]
        ]);
        if ($request->getFromType() == 'pc') $where['type'] = -1;
        return app('json')->success($this->services->getIssueCouponList($request->uid(), $where)['list']);
    }

    /**
     * Nhận phiếu giảm giá
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function receive(Request $request)
    {
        list($couponId) = $request->getMore([
            ['couponId', 0]
        ], true);
        if (!$couponId || !is_numeric($couponId)) return app('json')->fail('Lỗi tham số');

        /** @var StoreCouponIssueServices $couponIssueService */
        $couponIssueService = app()->make(StoreCouponIssueServices::class);
        $couponIssueService->issueUserCoupon($couponId, $request->user(), true);
        return app('json')->success('Đã nhận thành công');
    }

    /**
     * Người dùng đã nhận được phiếu giảm giá
     * @param Request $request
     * @param $types
     * @return mixed
     */
    public function user(Request $request, $types)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getUserCouponList($uid, $types));
    }

    /**
     * Mua lại đơn đặt hàng phiếu giảm giá
     * @param Request $request
     * @param StoreCouponIssueServices $service
     * @param $cartId
     * @param $new
     * @param $shippingType
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function order(Request $request, StoreCouponIssueServices $service, $cartId, $new, $shippingType)
    {
        return app('json')->success($service->beUsableCouponList((int)$request->uid(), $cartId, !!$new, (int)$shippingType));
    }
}
