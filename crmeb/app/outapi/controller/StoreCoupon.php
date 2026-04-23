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

use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\product\product\StoreProductCouponServices;
use think\facade\App;

/**
 * Bộ điều khiển phiếu giảm giá
 * Class StoreCoupon
 * @package app\outapi\controller
 */
class StoreCoupon extends AuthController
{
    /**
     * StoreCoupon constructor.
     * @param App $app
     * @param StoreCouponIssueServices $service
     * @method temp
     */
    public function __construct(App $app, StoreCouponIssueServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách phiếu giảm giá
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lst()
    {
        $where = $this->request->getMore([
            ['status', 1],
            ['coupon_title', ''],
            ['receive_type', ''],
            ['type', ''],
        ]);
        $list = $this->services->getCouponList($where);
        return app('json')->success($list);
    }

    /**
     * Thêm phiếu giảm giá
     * @return void
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['coupon_title', ''],
            ['coupon_price', 0.00],
            ['use_min_price', 0.00],
            ['coupon_time', 0],
            ['start_use_time', 0],
            ['end_use_time', 0],
            ['start_time', 0],
            ['end_time', 0],
            ['receive_type', 0],
            ['total_count', 0],
            ['status', 0],
        ]);
        $data['type'] = 0;
        $data['product_id'] = '';
        $data['category_id'] = 0;

        $data['is_permanent'] = 0;
        if ((int)$data['total_count'] == 0) {
            $data['is_permanent'] = 1;
        }
        $id = $this->services->saveCoupon($data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Sửa đổi trạng thái phiếu giảm giá
     * @param $id
     * @param $status
     * @return mixed
     */
    public function status($id, $status)
    {
        if ($id < 1 || !in_array((int)$status, [0, 1])) {
            return app('json')->fail('Lỗi tham số');
        }
        $this->services->update($id, ['status' => $status]);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * xóa bỏ
     * @param string $id
     * @return mixed
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');

        $this->services->update($id, ['is_del' => 1]);
        /** @var StoreProductCouponServices $storeProductService */
        $storeProductService = app()->make(StoreProductCouponServices::class);
        //Xóa phiếu giảm giá này được liên kết với sản phẩm
        $storeProductService->delete(['issue_coupon_id' => $id]);
        return app('json')->success('Xóa thành công');
    }
}