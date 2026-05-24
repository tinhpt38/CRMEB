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
 * Class UserAddress
 * @package app\model\user
 */class UserAddress extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_address';

    protected $insert = ['add_time'];

    protected $hidden = ['add_time', 'is_del'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * Khách hànguid
     * @param $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Có nên xóa không
     * @param Model $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value);
    }

    /**
     * Cho dù địa chỉ mặc định
     * @param Model $query
     * @param $value
     */    public function searchIsDefaultAttr($query, $value)
    {
        $query->where('is_default', $value);
    }

}
