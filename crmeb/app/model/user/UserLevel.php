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

use app\model\system\SystemUserLevel;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\model;

/**
 * Class UserLevel
 * @package app\model\user
 */class UserLevel extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_level';

    public function levelInfo()
    {
        return $this->hasOne(SystemUserLevel::class, 'id', 'level_id');
    }

    /**
     * Khách hànguid
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Nó có vĩnh viễn không?
     * @param Model $query
     * @param $value
     */    public function searchIsForeverAttr($query, $value)
    {
        $query->where('is_forever', $value);
    }

    /**
     * Thời gian hết hạn
     * @param Model $query
     * @param $value
     */    public function searchValidTimeAttr($query, $value)
    {
        $query->where('valid_time', '>', $value);
    }

    /**
     * Trạng thái
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * Thông báo hay không
     * @param Model $query
     * @param $value
     */    public function searchRemindAttr($query, $value)
    {
        $query->where('remind', $value);
    }

    /**
     * Có nên xóa không
     * @param Model $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value);
    }
}
