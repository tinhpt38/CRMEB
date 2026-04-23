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

class UserFriends extends BaseModel
{

    use ModelTrait;

    /**
     * trình diễn
     * @var string
     */
    protected $name = 'user_friends';

    /**
     * khóa chính
     * @var string
     */
    protected $pk = 'id';

    /**
     *
     * @return \think\model\relation\HasOne
     */
    public function level()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'level'])->bind([
            'level' => 'level'
        ]);
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function nickname()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname'])->bind([
            'nickname' => 'nickname'
        ]);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Sửa đổi và thêm thời gian
     * @param $value
     * @return false|string
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
}
