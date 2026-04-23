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

namespace app\model\product\product;

use crmeb\traits\ModelTrait;
use think\Model;

/**
 *  Hiệp hội danh mục sản phẩmModel
 * Class StoreProductCate
 * @package app\model\product\product
 */
class StoreProductCate extends Model
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
    protected $name = 'store_product_cate';

    /**
     * Liên kết một-một để có được tên danh mục
     * @return \think\model\relation\HasOne
     */
    public function cateName()
    {
        return $this->hasOne(StoreCategory::class, 'id', 'cate_id')->bind([
            'cate_name' => 'cate_name'
        ]);
    }

}
