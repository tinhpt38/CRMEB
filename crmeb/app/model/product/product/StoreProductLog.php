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


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class StoreProductLog extends BaseModel
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
    protected $name = 'store_product_log';

    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'add_time';

    /**
     * Thêm công cụ sửa đổi thời gian
     * @return int
     */
    public function setAddTimeAttr()
    {
        return time();
    }

    /**
     * hiệp hội một-một
     * Tên sản phẩm gắn liền với hồ sơ sản phẩm
     * @return \think\model\relation\HasOne
     */
    public function storeName()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->bind([
            'store_name',
            'image',
            'product_price'=>'price',
            'stock',
            'is_show'
        ]);
    }

    /**
     * Trình tìm kiếm loại bản ghi
     * @param $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value != '') $query->where('type', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param $query
     * @param $value
     */
    public function searchProductIdAttr($query, $value)
    {
        if ($value != '') $query->where('product_id', $value);
    }
    /**
     * Trình tìm kiếm ID người dùng
     * @param $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        if ($value != '') $query->where('uid', $value);
    }
}
