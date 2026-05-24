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

namespace app\model\order;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Lịch sử đơn hàngModel
 * Class StoreOrderCartInfo
 * @package app\model\order
 */class StoreOrderCartInfo extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_order_cart_info';

    /**
     * Công cụ lấy thông tin giỏ hàng
     * @param $value
     * @return array|mixed
     */    public function getCartInfoAttr($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Trình tìm kiếm ID đơn hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchOidAttr($query, $value, $data)
    {
        if ($value !== '') $query->where('oid', $value);
    }

    /**
     * Trình tìm ID giỏ hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCartIdAttr($query, $value, $data)
    {
        if (is_array($value)) {
            $query->whereIn('cart_id', $value);
        } else {
            $query->where('cart_id', $value);
        }
    }

    /**
     * Trình tìm kiếm ID giỏ hàng ban đầu
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchOldCartIdAttr($query, $value, $data)
    {
        if (is_array($value)) {
            $query->whereIn('old_cart_id', $value);
        } else {
            $query->where('old_cart_id', $value);
        }
    }

    /**
     *  Chia tách trạng thái tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchSplitStatusAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('split_status', $value);
        } else {
            if (in_array($value, [0, 1, 2])) {
                $query->where('split_status', $value);
            }
        }
    }
}
