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
namespace app\services\product\sku;


use app\dao\product\sku\StoreProductVirtualDao;
use app\services\BaseServices;

class StoreProductVirtualServices extends BaseServices
{
    public function __construct(StoreProductVirtualDao $dao)
    {
        $this->dao = $dao;
    }

    public function getArr($unique, $product_id)
    {
        $res = $this->dao->getColumn(['attr_unique' => $unique, 'product_id' => $product_id], 'card_no,card_pwd');
        $data = [];
        foreach ($res as $item) {
            $data[] = ['key' => $item['card_no'], 'value' => $item['card_pwd']];
        }
        return $data;
    }
}
