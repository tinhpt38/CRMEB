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
use app\services\activity\combination\StorePinkServices;
use app\services\order\StoreCartServices;

/**
 * Danh mục giỏ hàng
 * Class StoreCartController
 * @package app\api\controller\store
 */
class StoreCartController
{
    protected $services;

    public function __construct(StoreCartServices $services)
    {
        $this->services = $services;
    }

    /**
     * danh sách giỏ hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lst(Request $request)
    {
        [$status] = $request->postMore([
            ['status', 1],//Trạng thái mặt hàng trong giỏ hàng
        ], true);
        return app('json')->success($this->services->getUserCartList($request->uid(), $status));
    }

    /**
     * Giỏ hàng Thêm
     * @param Request $request
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add(Request $request)
    {
        $where = $request->postMore([
            [['productId', 'd'], 0],//Số sản phẩm chung
            [['cartNum', 'd'], 1], //Số lượng giỏ hàng
            ['uniqueId', ''],//giá trị duy nhất của thuộc tính
            [['new', 'd'], 0],// 1 Thêm vào giỏ hàngMua trực tiếp 0 Thêm vào giỏ hàng
            [['is_new', 'd'], 0],// 1 Thêm vào giỏ hàngMua trực tiếp 0 Thêm vào giỏ hàng
            [['combinationId', 'd'], 0],//Số sản phẩm nhóm
            [['secKillId', 'd'], 0],//Số mặt hàng khuyến mại chớp nhoáng
            [['bargainId', 'd'], 0],//Số mặt hàng mặc cả
            [['advanceId', 'd'], 0],//Số mặt hàng bán trước
            [['pinkId', 'd'], 0],//Làm việc theo nhómID
        ]);
        if ($where['is_new'] || $where['new']) $new = true;
        else $new = false;
        /** @var StoreCartServices $cartService */
        $cartService = app()->make(StoreCartServices::class);
        if (!$where['productId'] || !is_numeric($where['productId'])) return app('json')->fail('Lỗi tham số');
        $type = 0;
        if ($where['secKillId']) {
            $type = 1;
        } elseif ($where['bargainId']) {
            $type = 2;
        } elseif ($where['combinationId']) {
            $type = 3;
            if ($where['pinkId']) {
                /** @var StorePinkServices $pinkServices */
                $pinkServices = app()->make(StorePinkServices::class);
                if ($pinkServices->isPinkStatus($where['pinkId'])) return app('json')->fail('Mua theo nhóm đã hết hạn');
            }
        } elseif ($where['advanceId']) {
            $type = 6;
        }
        if ($type == 0) $cartService->checkVipGoodsBuy($request->user(), $where['productId']);
        $res = $cartService->setCart($request->uid(), $where['productId'], $where['cartNum'], $where['uniqueId'], $type, $new, $where['combinationId'], $where['secKillId'], $where['bargainId'], $where['advanceId']);
        if (!$res) return app('json')->fail('Thêm không thành công');
        else  return app('json')->success(['cartId' => $res]);
    }

    /**
     * Giỏ hàng xóa mặt hàng
     * @param Request $request
     * @return mixed
     */
    public function del(Request $request)
    {
        $where = $request->postMore([
            ['ids', ''],//Số giỏ hàng
        ]);
        $where['ids'] = is_array($where['ids']) ? $where['ids'] : explode(',', $where['ids']);
        if (!count($where['ids']))
            return app('json')->fail('Lỗi tham số');
        if ($this->services->removeUserCart((int)$request->uid(), $where['ids']))
            return app('json')->success('Xóa thành công');
        return app('json')->fail('Xóa không thành công');
    }

    /**
     * Giỏ hàng Sửa đổi số lượng sản phẩm
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function num(Request $request)
    {
        $where = $request->postMore([
            ['id', 0],//Số giỏ hàng
            ['number', 0],//Số giỏ hàng
        ]);
        if (!$where['id'] || !is_numeric($where['id'])) return app('json')->fail('Lỗi tham số');
        if (!$where['number'] || !is_numeric($where['number'])) return app('json')->fail('Sửa đổi không thành công');
        $res = $this->services->changeUserCartNum($where['id'], $where['number'], $request->uid());
        if ($res) return app('json')->success('Sửa đổi thành công');
        else return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Giỏ hàng Thống kê Số lượng Giá
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function count(Request $request)
    {
        [$numType] = $request->postMore([
            ['numType', true],//Số giỏ hàng
        ], true);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getUserCartCount($uid, $numType));
    }

    /**
     * Lựa chọn lại giỏ hàng
     * @param Request $request
     * @return mixed
     */
    public function reChange(Request $request)
    {
        [$cart_id, $product_id, $unique] = $request->postMore([
            ['cart_id', 0],
            ['product_id', 0],
            ['unique', '']
        ], true);
        $this->services->modifyCart($cart_id, $product_id, $unique);
        return app('json')->success('Lựa chọn lại thành công');
    }
}
