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

namespace app\model\activity\integral;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Đặt hàngModel
 * Class StoreOrder
 * @package app\model\order
 */
class StoreIntegralOrder extends BaseModel
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
    protected $name = 'store_integral_order';

    protected $insert = ['add_time'];

    /**
     * Thời gian cập nhật
     * @var bool | string | int
     */
    protected $updateTime = false;

    /**
     * Tạo công cụ sửa đổi thời gian
     * @return int
     */
    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * Liên kết một-một của các bảng người dùng
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname', 'phone', 'spread_uid'])->bind([
            'nickname' => 'nickname',
            'phone' => 'phone',
            'spread_uid' => 'spread_uid',
        ]);
    }

    /**
     * Trình tìm kiếm ID đơn hàng
     * @param Model $query
     * @param $value
     */
    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }

    /**
     * Trình tìm trạng thái đơn hàng
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('status', $value);
        }
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     */
    public function searchProductIdAttr($query, $value)
    {
        if ($value !== '') {
            if(is_array($value)){
                $query->whereIn('product_id', $value);
            }else{
                $query->where('product_id', $value);
            }
        }
    }

    /**
     * Trình tìm kiếm mã xác minh
     * @param Model $query
     * @param $value
     */
    public function searchVerifyCodeAttr($query, $value)
    {
        $query->where('verify_code', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }

    /**
     * ID đơn hàng hoặc người tìm kiếm tên người dùng
     * @param $query
     * @param $value
     */
    public function searchOrderIdRealNameAttr($query, $value)
    {
        $query->where('order_id|real_name', $value);
    }

    /**
     * Trình tìm kiếm ID người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('uid', $value);
        else
            $query->where('uid', $value);
    }

    /**
     * Nguồn người dùng
     * @param Model $query
     * @param $value
     */
    public function searchChannelTypeAttr($query, $value)
    {
        if ($value != '') $query->where('channel_type', $value);
    }

}
