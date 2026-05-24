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

namespace app\model\service;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Lịch sử trò chuyện CSKH
 * Class StoreServiceLog
 * @package app\model\service
 */class StoreServiceLog extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_service_log';

    public function getAddTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * hiệp hội một-một
     * @return mixed
     */    public function service()
    {
        return $this->hasOne(StoreService::class, 'uid', 'uid')->field(['uid', 'nickname', 'avatar'])->bind([
            'nickname' => 'nickname',
            'avatar' => 'avatar'
        ]);
    }

    /**
     * hiệp hội một-một
     * @return mixed
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname', 'avatar'])->bind([
            'nickname' => 'nickname',
            'avatar' => 'avatar'
        ]);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        $query->where('uid|to_uid', $value);
    }

    /**
     * Trình tìm kiếm lịch sử trò chuyện
     * @param Model $query
     * @param $value
     */    public function searchChatAttr($query, $value)
    {
        $query->whereIn('uid', $value)->whereIn('to_uid', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchIsTouristAttr($query, $value)
    {
        $query->where('is_tourist', $value);
    }
}
