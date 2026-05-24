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

namespace app\kefuapi\controller;


use think\facade\App;
use app\services\kefu\ProductServices;

/**
 * Class Product
 * @package app\kefuapi\controller
 */class Product extends AuthController
{
    /**
     * Product constructor.
     * @param App $app
     * @param ProductServices $services
     */    public function __construct(App $app, ProductServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận hồ sơ mua hàng của Khách hàng
     * @param $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCartProductList($uid, string $store_name = '')
    {
        return app('json')->success(get_thumb_water($this->services->getProductCartList((int)$uid, $store_name)));
    }

    /**
     * Lịch sử duyệt web của Khách hàng
     * @param $uid
     * @param string $store_name
     * @return mixed
     */    public function getVisitProductList($uid, string $store_name = '')
    {
        return app('json')->success(get_thumb_water($this->services->getVisitProductList((int)$uid, $store_name)));
    }

    /**
     * Nhận các sản phẩm bán chạy nhất được Khách hàng mua
     * @param $uid
     * @param string $store_name
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductHotSale($uid, string $store_name = '')
    {
        return app('json')->success(get_thumb_water($this->services->getProductHotSale((int)$uid, $store_name)));
    }

    /**
     * Chi tiết sản phẩm
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductInfo($id)
    {
        return app('json')->success(get_thumb_water($this->services->getProductInfo((int)$id), 'big', ['image', 'slider_image']));
    }
}
