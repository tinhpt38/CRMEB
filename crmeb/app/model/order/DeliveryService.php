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

/**người giao hàng
 * Class DeliveryService
 * @package app\model\service
 */
class DeliveryService extends BaseModel
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
    protected $name = 'delivery_service';

    /**
     * @var bool
     */
    protected $updateTime = false;


    protected function getAddTimeAttr($value)
    {
        if ($value) return date('Y-m-d H:i:s', $value);
        return $value;
    }

    /**
     * Tên người dùng liên kết một-nhiều
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname'])->bind([
            'nickname' => 'nickname'
        ]);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * statusNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * customer
     * @param Model $query
     * @param $value
     */
    public function searchCustomerAttr($query, $value)
    {
        $query->where('customer', $value);
    }

    /**
     * Người tìm kiếm biệt danh người dùng
     * @param Model $query
     * @param $value
     */
    public function searchNicknameAttr($query, $value)
    {
        $query->whereLike('nickname', '%' . $value . '%');
    }

    public function searchPhoneAttr($query, $value)
    {
        $query->where('phone', $value);
    }
}
