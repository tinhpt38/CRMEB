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

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *
 * Class StoreOrder
 * @package app\model\order
 */class StoreOrderEconomize extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_order_economize';

    protected $insert = ['add_time'];

    /**
     * Thời gian cập nhật
     * @var bool | string | int
     */    protected $updateTime = false;


    /**
     * Liên kết một-một của các bảng Khách hàng
     * @return \think\model\relation\HasOne
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname', 'phone', 'spread_uid'])->bind([
            'nickname' => 'nickname',
            'phone' => 'phone',
        ]);
    }

    /**
     * Tìm kiếm đơn hàng
     * @param Model $query
     * @param $value
     */    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }

}
