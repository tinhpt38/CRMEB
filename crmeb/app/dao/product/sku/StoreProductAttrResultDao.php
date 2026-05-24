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

namespace app\dao\product\sku;

use app\dao\BaseDao;
use app\model\product\sku\StoreProductAttrResult;

/**
 * Class StoreProductAttrResultDao
 * @package app\dao\product\sku
 */class StoreProductAttrResultDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreProductAttrResult::class;
    }

    /**
     * Xóa theo điều kiện quy địnhsku
     * @param int $id
     * @param int $type
     * @return bool
     * @throws \Exception
     */    public function del(int $id, int $type)
    {
        return $this->search(['product_id' => $id, 'type' => $type])->delete();
    }
}
