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
use app\model\product\sku\StoreProductAttr;

/**
 * Class StoreProductAttrDao
 * @package app\dao\product\sku
 */class StoreProductAttrDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreProductAttr::class;
    }

    /**
     * Xóasku
     * @param int $id
     * @param int $type
     * @return bool
     * @throws \Exception
     */    public function del(int $id, int $type)
    {
        return $this->search(['product_id' => $id, 'type' => $type])->delete();
    }

    /**
     * Lưusku
     * @param array $data
     * @return mixed|\think\Collection
     * @throws \Exception
     */    public function saveAll(array $data)
    {
        return $this->getModel()->saveAll($data);
    }

    /**
     * Nhận sản phẩmsku
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductAttr(array $where)
    {
        return $this->search($where)->select()->toArray();
    }
}
