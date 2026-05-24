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

namespace app\model\activity\live;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Class LiveAnchor
 * @package app\model\live
 */class LiveAnchor extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'live_anchor';

    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'add_time';

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */    public function getAddTimeAttr($value)
    {
        if (!empty($value)) {
            return date('Y-m-d H:i:s', (int)$value);
        }
        return '';
    }


    public function room()
    {
        return $this->hasOne(LiveRoom::class, 'anchor_wechat', 'wechat');
    }

    public function searchKerwordAttr($query, $value)
    {
        if ($value !== '') $query->whereLike('id|name|wechat|phone', "%{$value}%");
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchIsShowAttr($query, $value)
    {
        if ($value !== '') $query->where('is_show', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }

}
