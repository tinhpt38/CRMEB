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
 * Mô hình phân loại quản lý tệp đính kèm
 * Class SystemAttachmentCategory
 * @package app\model\system\attachment
 */
class SystemAttachmentCategory extends BaseModel
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
    protected $name = 'system_attachment_category';


    /**
     * Trình tìm kiếm biệt hiệu phân loại tệp đính kèm
     * @param Model $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {
        if ($value != '') $query->where('name', 'like', '%' . $value . '%');
    }

    /**
     * pidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchPidAttr($query, $value)
    {
        if ($value !== '') $query->where('pid', $value);
    }

    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('type', $value);
    }
}
