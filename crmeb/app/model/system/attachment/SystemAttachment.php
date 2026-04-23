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

namespace app\model\system\attachment;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Mô hình quản lý tệp đính kèm
 * Class SystemAttachment
 * @package app\model\system\attachment
 */
class SystemAttachment extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'att_id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'system_attachment';

    /**
     * Công cụ tìm loại hình ảnh
     * @param Model $query
     * @param $value
     */
    public function searchModuleTypeAttr($query, $value)
    {
        $query->where('module_type', $value ?: 1);
    }

    /**
     * pidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchPidAttr($query, $value)
    {
        if ($value) $query->where('pid', $value);
    }

    /**
     * nametìm kiếm mờ
     * @param Model $query
     * @param $value
     */
    public function searchLikeNameAttr($query, $value)
    {
        if ($value) $query->where('name', 'LIKE', "$value%");
    }

    /**
     * real_nametìm kiếm mờ
     * @param Model $query
     * @param $value
     */
    public function searchRealNameAttr($query, $value)
    {
        if ($value != '') $query->where('real_name', 'LIKE', "%$value%");
    }

    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('type', $value);
    }
}
