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

namespace app\services\product\product;


use app\dao\product\product\StoreProductCateDao;
use app\services\BaseServices;

/**
 * Class StoreProductCateService
 * @package app\services\product\product
 * @method productIdByCateId(array $productId) Nhận phân loại dựa trên id sản phẩmid
 * @method cateIdByProduct(array $cate_id) Nhận sản phẩm theo danh mụcid
 */class StoreProductCateServices extends BaseServices
{
    public function __construct(StoreProductCateDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Thêm sản phẩm, sửa đổi liên kết danh mục sản phẩm
     * @param $id
     * @param $cateData
     */    public function change($id, $cateData)
    {
        $this->dao->delete(['product_id' => $id]);
        $this->dao->saveAll($cateData);
    }


}
