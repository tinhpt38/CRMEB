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
namespace app\model\product\sku;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class StoreProductVirtual extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_product_virtual';

    /**
     * Trình tìm kiếm số thẻ
     * @param $query
     * @param $value
     */
    public function searchCardNoAttr($query, $value)
    {
        $query->where('card_no', $value);
    }

    /**
     * Công cụ tìm kiếm thẻ
     * @param $query
     * @param $value
     */
    public function searchCardPwdAttr($query, $value)
    {
        $query->where('card_pwd', $value);
    }

    /**
     * Công cụ tìm sản phẩm
     * @param $query
     * @param $value
     */
    public function searchProductIdAttr($query, $value)
    {
        $query->where('product_id', $value);
    }

    /**
     * Người tìm kiếm người dùng
     * @param $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm đơn hàng
     * @param $query
     * @param $value
     */
    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }
}
