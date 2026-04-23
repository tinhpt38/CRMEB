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
 * Hồ sơ người dùng trò chuyện dịch vụ khách hàng
 * Class StoreServiceRecord
 * @package app\model\service
 */
class StoreServiceRecord extends BaseModel
{
    use ModelTrait;

    protected $name = 'store_service_record';

    protected $pk = 'id';

    /**
     * Thời gian cập nhật
     * @var bool | string | int
     */
    protected $updateTime = false;

    /**
     * Hiệp hội người dùng
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'to_uid')->field(['nickname', 'uid', 'avatar'])->bind([
            'wx_nickname' => 'nickname',
            'wx_avatar' => 'avatar',
        ]);
    }

    /**
     * Người sử dụng dịch vụ khách hàng
     * @return \think\model\relation\HasOne
     */
    public function service()
    {
        return $this->hasOne(StoreService::class, 'uid', 'to_uid')->field(['nickname', 'uid', 'avatar'])->bind([
            'kefu_nickname' => 'nickname',
            'kefu_avatar' => 'avatar',
        ]);
    }

    /**
     * người tìm kiếm id người gửi
     * @param Model $query
     * @param $value
     */
    public function searchUserIdAttr($query, $value)
    {
        $query->where('user_id', $value);
    }

    /**
     * Người giao hàng tìm kiếm uid
     * @param Model $query
     * @param $value
     */
    public function searchToUidAttr($query, $value)
    {
        $query->where('to_uid', $value);
    }

    /**
     * Người tìm kiếm biệt danh người dùng
     * @param Model $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        if ($value) {
            $query->whereIn('to_uid', function ($query) use ($value) {
                $query->name('user')->whereLike('nickname|uid', '%' . $value . '%')->field('uid');
            });
        }
    }

    /**
     * Dù là khách du lịch
     * @param Model $query
     * @param $value
     */
    public function searchIsTouristAttr($query, $value)
    {
        $query->where('is_tourist', $value);
    }
}
