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
use think\model;

/**
 * Class UserSpread
 * @package app\model\user
 */class UserSpread extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_spread';

    /**
     * Khách hànguid
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('uid', $value);
        else
            $query->where('uid', $value);

    }

    /**
     * người quảng báuid
     * @param Model $query
     * @param $value
     */    public function searchSpreadUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('spread_uid', $value);
        else
            $query->where('spread_uid', $value);

    }
}
