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
namespace app\listener\user;


use app\services\product\product\StoreVisitServices;
use crmeb\interfaces\ListenerInterface;

/**
 * ghi quyền truy cập của Khách hàng
 * Class UserVisitListener
 * @package app\listener\user
 */class UserVisitListener implements ListenerInterface
{
    public function handle($event): void
    {
        [$uid, $product_id, $product_type, $cate, $type] = $event;

        //Viết bản ghi truy cập của Khách hàng
        /** @var StoreVisitServices $storeVisit */        $storeVisit = app()->make(StoreVisitServices::class);
        $storeVisit->setView($uid, $product_id, $product_type, $cate, $type);
    }
}