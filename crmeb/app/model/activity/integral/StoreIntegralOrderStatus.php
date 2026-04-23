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

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Bản ghi trạng thái sửa đổi đơn hàngModel
 * Class StoreOrderStatus
 * @package app\model\order
 */
class StoreIntegralOrderStatus extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_integral_order_status';

    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'change_time';

    /**
     * Trình tìm kiếm ID đơn hàng
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchOidAttr($query, $value, $data)
    {
        $query->where('oid', $value);
    }

    /**
     * Thay đổi loại tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchChangeTypeAttr($query, $value)
    {
        $query->where('change_type', $value);
    }
}
