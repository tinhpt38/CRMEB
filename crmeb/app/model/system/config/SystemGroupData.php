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

namespace app\model\system\config;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Mô hình danh sách dữ liệu dữ liệu kết hợp
 * Class SystemGroupData
 * @package app\model\system\config
 */class SystemGroupData extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_group_data';

    /**
     * công cụ tìm trạng thái
     * @param $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value != '') {
            $query->where('status', $value);
        }
    }

    /**
     * GidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchGidAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('gid', $value);
        } else {
            $query->where('gid', $value);
        }
    }
}
