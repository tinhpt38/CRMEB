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
 * Mô hình cấu hình dữ liệu kết hợp
 * Class SystemGroup
 * @package app\model\system\config
 */class SystemGroup extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_group';

    /**
     * Trình tìm kiếm tên cấu hình
     * @param Model $query
     * @param $value
     */    public function searchConfigNameAttr($query, $value)
    {
        $query->where('config_name', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchTitleAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLIke('id|name|info|config_name', "%$value%");
        }
    }

    /**
     * Phân loại truy vấn
     * @param Model $query
     * @param $value
     */    public function searchCateIdAttr($query, $value)
    {
        $query->where('cate_id', $value);
    }
}
