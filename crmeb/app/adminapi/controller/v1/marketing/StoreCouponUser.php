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
namespace app\adminapi\controller\v1\marketing;

use app\adminapi\controller\AuthController;
use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\activity\coupon\StoreCouponUserServices;
use think\facade\App;

/**
 * Người kiểm soát hồ sơ phát hành phiếu giảm giá
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class StoreCouponUser extends AuthController
{
    public function __construct(App $app, StoreCouponUserServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Bản ghi bộ sưu tập của người dùng
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['coupon_title', ''],
            ['nickname', ''],
        ]);
        $list = $this->services->systemPage($where);
        return app('json')->success($list);
    }

    /**
     * Phát hành phiếu giảm giá cho các cá nhân được chỉ định
     * @return mixed
     */
    public function grant()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['uid', '']
        ]);
        if (!$data['id']) return app('json')->fail('Lỗi tham số');
        /** @var StoreCouponIssueServices $issueService */
        $issueService = app()->make(StoreCouponIssueServices::class);
        $coupon = $issueService->get($data['id']);
        if (!$coupon) {
            return app('json')->fail('Dữ liệu không tồn tại');
        } else {
            $coupon = $coupon->toArray();
        }
        $user = explode(',', $data['uid']);
        if (!$issueService->setCoupon($coupon, $user))
            return app('json')->fail('Gửi không thành công');
        else
            return app('json')->success('Đã gửi thành công');

    }
}
