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
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Thay đổi số dư Khách hàng
 */class UserMoney extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_money';

    /**
     * @param Model $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if ($value != '') $query->where('type', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchNotTypeAttr($query, $value)
    {
        if (is_array($value) && count($value)) $query->whereNotIn('type', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchPmAttr($query, $value)
    {
        if ($value !== '') $query->where('pm', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if ($value !== '') $query->where('uid', $value);
    }
}
