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

use app\model\order\StoreOrderCartInfo;
use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *  Đánh giá sản phẩmModel
 * Class StoreProductReply
 * @package app\model\product\product
 */
class StoreProductReply extends BaseModel
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
    protected $name = 'store_product_reply';

    /**
     * hiệp hội một-một
     * Đánh giá sản phẩm các sản phẩm liên quan
     * @return \think\model\relation\HasOne
     */
    public function productInfo()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id');
    }

    /**
     * hiệp hội một-một
     * Đơn hàng liên quan đến đánh giá sản phẩm
     * @return \think\model\relation\HasOne
     */
    public function cartInfo()
    {
        return $this->hasOne(StoreOrderCartInfo::class, 'unique', 'unique')->bind(['cart_info']);
    }

    /**
     * hiệp hội một-một
     * Đơn hàng liên quan đến đánh giá sản phẩm
     * @return \think\model\relation\HasOne
     */
    public function userInfo()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field('is_money_level,uid')->bind(['is_money_level']);
    }


    /**
     * Xem lại công cụ sửa đổi hình ảnh
     * @param $value
     * @return false|string
     */
    protected function setPicsAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Xem lại trình lấy ảnh
     * @param $value
     * @return mixed
     */
    protected function getPicsAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * Người tìm kiếm người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Công cụ tìm sản phẩm
     * @param Model $query
     * @param $value
     */
    public function searchProductIdAttr($query, $value)
    {
        $query->where('product_id', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * Có trả lời người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */
    public function searchIsReplyAttr($query, $value)
    {
        $query->where('is_reply', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchUniqueAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('unique', $value);
        } else {
            $query->where('unique', $value);
        }
    }

    /**
     * oidtìm kiếm id đơn hàng
     * @param Model $query
     * @param $value
     */
    public function searchOidAttr($query, $value)
    {
        $query->where('oid', $value);
    }

    /**
     * Công cụ tìm điểm sản phẩm
     * @param Model $query
     * @param $value
     */
    public function searchProductScoreAttr($query, $value)
    {
        $query->where('product_score', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }
}
