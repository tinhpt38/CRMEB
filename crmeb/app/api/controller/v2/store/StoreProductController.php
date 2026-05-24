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

use app\Request;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrServices;

class StoreProductController
{
    protected $services;

    public function __construct(StoreProductServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nhận thuộc tính sản phẩm
     * @param Request $request
     * @return mixed
     */    public function getProductAttr(Request $request)
    {
        list($id, $type) = $request->getMore([
            ['id', 0],
            ['type', 0]
        ], true);
        if (!$id) return app('json')->fail('Lỗi tham số');
        /** @var StoreProductAttrServices $storeProductAttrServices */        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        list($data['productAttr'], $data['productValue']) = $storeProductAttrServices->getProductAttrDetail($id, $request->uid(), $type);
        $storeInfo = $this->services->getOne(['id' => $id]);
        $data['storeInfo'] = $storeInfo ? $storeInfo->toArray() : [];
        return app('json')->success($data);
    }
}
