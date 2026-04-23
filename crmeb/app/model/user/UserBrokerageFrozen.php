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

namespace app\model\user;


use crmeb\basic\BaseModel;
use think\Model;

/**
 * Hoa hồng đóng băng
 * Class UserBrokerageFrozen
 * @package app\model\user
 */
class UserBrokerageFrozen extends BaseModel
{

    /**
     * Đặt khóa chính
     * @var string
     */
    protected $pk = 'id';

    /**
     * Đặt tên bảng
     * @var string
     */
    protected $name = 'user_brokerage_frozen';


    /**
     * người tìm kiếm id người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }
}
