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

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProductCate;

/**
 * Class StoreProductCateDao
 * @package app\dao\product\product
 */
class StoreProductCateDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreProductCate::class;
    }

    /**
     * lưu dữ liệu
     * @param array $data
     * @return mixed|void
     */
    public function saveAll(array $data)
    {
        $this->getModel()->insertAll($data);
    }

    /**
     * Nhận phân loại dựa trên id sản phẩmid
     * @param array $productId
     * @return array
     */
    public function productIdByCateId(array $productId)
    {
        return $this->getModel()->whereIn('product_id', $productId)->column('cate_id');
    }

    /**
     * Nhận sản phẩm theo danh mụcid
     * @param array $cate_id
     * @return array
     */
    public function cateIdByProduct(array $cate_id)
    {
        return $this->getModel()->whereIn('cate_id', $cate_id)->column('product_id');
    }
}
