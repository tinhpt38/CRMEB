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

namespace app\services\kefu;


use app\services\BaseServices;
use app\services\product\product\StoreProductCateServices;
use crmeb\exceptions\ApiException;
use app\dao\product\product\StoreProductDao;
use app\services\order\StoreOrderStoreOrderCartInfoServices;
use app\services\product\product\StoreProductVisitServices;

/**
 * Class ProductServices
 * @package app\services\kefu
 */class ProductServices extends BaseServices
{

    /**
     * ProductServices constructor.
     * @param StoreProductDao $dao
     */    public function __construct(StoreProductDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận hồ sơ mua hàng của Khách hàng
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductCartList(int $uid, string $storeName = '')
    {
        [$page, $limit] = $this->getPageValue();
        /** @var StoreOrderStoreOrderCartInfoServices $services */        $services = app()->make(StoreOrderStoreOrderCartInfoServices::class);
        $where['id'] = $services->getUserCartProductIds(['uid' => $uid]);
        $where['store_name'] = $storeName;
        return $this->dao->getProductCartList($where, $page, $limit, ['id', 'IFNULL(sales,0) + IFNULL(ficti,0) as sales', 'store_name', 'image', 'stock', 'price']);
    }

    /**
     * Nhận lịch sử duyệt web của Khách hàng
     * @param int $uid
     * @return mixed
     */    public function getVisitProductList(int $uid, string $storeName = '')
    {
        [$page, $limit] = $this->getPageValue();
        /** @var StoreProductVisitServices $service */        $service = app()->make(StoreProductVisitServices::class);
        return $service->getUserVisitProductList(['uid' => $uid, 'store_name' => $storeName], $page, $limit);
    }

    /**
     * Trước khi nhận được hàng hot20
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductHotSale(int $uid, string $storeName = '')
    {
        [$page, $limit] = $this->getPageValue();
        /** @var StoreOrderStoreOrderCartInfoServices $services */        $services = app()->make(StoreOrderStoreOrderCartInfoServices::class);
        $productIds = $services->getUserCartProductIds(['uid' => $uid]);
        /** @var StoreProductCateServices $cateService */        $cateService = app()->make(StoreProductCateServices::class);
        $where['id'] = $cateService->cateIdByProduct($cateService->productIdByCateId($productIds));
        $where['store_name'] = $storeName;
        return $this->dao->getUserProductHotSale($where, $page, $limit);
    }

    /**
     * Nhận chi tiết sản phẩm
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductInfo(int $id)
    {
        $productInfo = $this->dao->get($id, ['store_name', 'IFNULL(sales,0) + IFNULL(ficti,0) as sales', 'image',
            'slider_image', 'price', 'vip_price', 'ot_price', 'stock', 'id'], ['description']);
        if (!$productInfo) {
            throw new ApiException('Không tìm thấy sản phẩm');
        }
        return $productInfo->toArray();
    }
}
