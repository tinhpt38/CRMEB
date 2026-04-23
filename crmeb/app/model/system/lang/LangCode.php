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
namespace app\model\system\lang;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class LangCode extends BaseModel
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
    protected $name = 'lang_code';

    /**
     * type_idNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchTypeIdAttr($query, $value)
    {
        if ($value !== '' && $value !== 0) $query->where('type_id', $value);
    }

    /**
     * codeNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchCodeAttr($query, $value)
    {
        if ($value !== '') $query->where('code', 'like', '%' . $value . '%');
    }

    /**
     * remarksNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchRemarksAttr($query, $value)
    {
        if ($value !== '') $query->where('remarks|code|lang_explain', 'like', '%' . $value . '%');
    }

    /**
     * is_adminNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchIsAdminAttr($query, $value)
    {
        if ($value !== '') $query->where('is_admin', $value);
    }
}