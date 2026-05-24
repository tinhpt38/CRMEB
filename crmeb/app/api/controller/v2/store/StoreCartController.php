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


use app\services\order\StoreCartServices;
use app\Request;

class StoreCartController
{
    protected $services;

    public function __construct(StoreCartServices $services)
    {
        $this->services = $services;
    }

    /**
     * Lựa chọn lại giỏ hàng
     * @param Request $request
     * @return mixed
     */    public function resetCart(Request $request)
    {
        list($id, $unique, $num, $product_id) = $request->postMore([
            ['id', 0],
            ['unique', ''],
            ['num', 1],
            ['product_id', 0]
        ], true);
        $this->services->resetCart($id, $request->uid(), $product_id, $unique, $num);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Nhận giỏ hàng của Khách hàng
     * @param Request $request
     * @return mixed
     */    public function getCartList(Request $request)
    {
        $uid = (int)$request->uid();
        $data = $this->services->getCartList(['uid' => $uid, 'is_del' => 0, 'is_new' => 0, 'is_pay' => 0, 'combination_id' => 0, 'seckill_id' => 0, 'bargain_id' => 0], 0, 0, ['productInfo', 'attrInfo']);
        [$data, $valid, $invalid] = $this->services->handleCartList($uid, $data);
        return app('json')->success($data);
    }

    /**
     * Trang chủThêm mới giỏ hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function setCartNum(Request $request)
    {
        list($product_id, $num, $unique, $type) = $request->postMore([
            ['product_id', 0],
            ['num', 1],
            ['unique', ''],
            ['type', -1]
        ], true);
        /** @var StoreCartServices $cartService */        $cartService = app()->make(StoreCartServices::class);
        if (!$product_id || !is_numeric($product_id)) return app('json')->fail('Lỗi tham số');
        $res = $cartService->setCartNum($request->uid(), $product_id, $num, $unique, $type);
        if ($res) return app('json')->success('Đã thêm vào giỏ hàng thành công!');
        return app('json')->fail('Thêm mới giỏ hàng không thành công!');
    }
}
