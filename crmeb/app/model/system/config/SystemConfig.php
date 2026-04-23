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
 * mô hình cấu hình hệ thống
 * Class SystemConfig
 * @package app\model\system\config
 */
class SystemConfig extends BaseModel
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
    protected $name = 'system_config';

    /**
     * Trình tìm kiếm tên menu
     * @param Model $query
     * @param $value
     */
    public function searchMenuNameAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('menu_name', $value);
        } else {
            $query->where('menu_name', $value);
        }
    }

    /**
     * tab id tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchTabIdAttr($query, $value)
    {
        if ($value != 0) {
            $query->where('config_tab_id', $value);
        }
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value ?: 1);
    }

    /**
     * valueNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchValueAttr($query, $value)
    {
        $query->where('value', $value);
    }

    /**
     * infoNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchConfigNameAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('info|menu_name', 'like', "%$value%");
        }
    }
}
