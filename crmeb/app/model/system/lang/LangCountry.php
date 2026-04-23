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

class LangCountry extends BaseModel
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
    protected $name = 'lang_country';

    /**
     * type_idNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchTypeIdAttr($query, $value)
    {
        if ($value !== '') $query->where('type_id', $value);
    }

    /**
     * code/nameNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('name|code', 'like', '%' . $value . '%');
    }
}