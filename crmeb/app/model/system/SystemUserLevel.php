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

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Mô hình cài đặt cấp hệ thống
 * Class SystemUserLevel
 * @package app\model\system
 */
class SystemUserLevel extends BaseModel
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
    protected $name = 'system_user_level';

    /**
     * Công cụ lấy thời gian
     * @param $value
     * @return false|string
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', (int)$value);
    }

    /**
     * Công cụ tính tỷ lệ chiết khấu
     * @param $value
     * @return int
     */
    public function getDiscountAttr($value)
    {
        return (int)$value;
    }

    /**
     * Có hiển thị hay không
     * @param Model $query
     * @param $value
     */
    public function searchIsShowAttr($query, $value)
    {
        $query->where('is_show',$value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title','LIKE', "%$value%");
    }

}
