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

namespace app\model\wechat;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Class WechatUser
 * @package app\model\wechat
 */
class WechatUser extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'uid';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'wechat_user';

    protected $insert = ['add_time'];

    public static function setAddTimeAttr()
    {
        return time();
    }

    protected function getAddTimeAttr($value)
    {
        if ($value) return date('Y-m-d H:i', (int)$value);
        return $value;
    }

    /**
     * sự kết hợpuser
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid');
    }

    /**
     * Ràng buộc tài khoản chính thức
     * @param Model $query
     * @param $value
     */
    public function searchUnionidAttr($query, $value)
    {
        return $query->where('unionid', $value);
    }

    /**
     * Tài khoản công khai duy nhấtid
     * @param Model $query
     * @param $value
     */
    public function searchOpenidAttr($query, $value)
    {
        return $query->where('openid', $value);
    }

    /**
     * Nhóm
     * @param Model $query
     * @param $value
     */
    public function searchGroupIdAttr($query, $value)
    {
        return $query->where('group_id', $value);
    }

    /**
     * giới tính
     * @param Model $query
     * @param $value
     */
    public function searchSexAttr($query, $value)
    {
        return $query->where('sex', $value);
    }

    /**
     * Bạn có chú ý không?
     * @param Model $query
     * @param $value
     */
    public function searchSubscribeAttr($query, $value)
    {
        return $query->where('subscribe', $value);
    }

    /**
     * Loại người dùng
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        return $query->where('user_type', $value);
    }

    /**
     * Loại người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUserTypeAttr($query, $value)
    {
        return $query->where('user_type', $value);
    }

    /**
     * is_del Người tìm kiếm
     * @param $query
     * @param $value
     * @return void
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/03
     */
    public function searchIsDelAttr($query, $value)
    {
        if($value !== '') return $query->where('is_del', $value);
    }

}
