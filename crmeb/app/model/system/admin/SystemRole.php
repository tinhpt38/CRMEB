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

namespace app\model\system\admin;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Quy tắc cấp phép quản trị viên
 * Class SystemRole
 * @package app\model\system\admin
 */
class SystemRole extends BaseModel
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
    protected $name = 'system_role';

    /**
     * Công cụ sửa đổi quy tắc
     * @param Model $value
     * @return string
     */
    public static function setRulesAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Trình tìm trạng thái đặc tả quyền
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
     * Trình tìm cấp đặc quyền
     * @param Model $query
     * @param $value
     */
    public function searchLevelAttr($query, $value)
    {
        $query->where('level', $value);
    }

    /**
     * idNgười tìm kiếm
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
     * Tìm kiếm quản lý danh tính
     * @param Model $query
     * @param $value
     */
    public function searchRoleNameAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('role_name', '%' . $value . '%');
        }
    }
}
