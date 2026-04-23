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

use app\model\agent\AgentLevel;
use app\model\order\StoreOrder;
use app\model\system\SystemUserLevel;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Class User
 * @package app\model\user
 */
class User extends BaseModel
{
    use ModelTrait;

    /**
     * @var string
     */
    protected $pk = 'uid';

    protected $name = 'user';

    protected $insert = ['add_time', 'add_ip', 'last_time', 'last_ip'];

    protected $hidden = [
        'add_ip', 'account', 'clean_time', 'last_ip', 'pwd'
    ];

    /**
     * Tự động thay đổi loại
     * @var string[]
     */
    protected $type = [
        'birthday' => 'int'
    ];

    protected $updateTime = false;

    protected function setAddTimeAttr($value)
    {
        return time();
    }

    protected function setAddIpAttr($value)
    {
        return app('request')->ip();
    }

    protected function setLastTimeAttr($value)
    {
        return time();
    }

    protected function setLastIpAttr($value)
    {
        return app('request')->ip();
    }

//    protected function getPhoneAttr($value)
//    {
//        return $value && app('request')->hasMacro('adminInfo') && app('request')->adminInfo()['level'] != 0 ? substr_replace($value, '****', 3, 4) : $value;
//    }

    /**
     * Link form cài đặt đăng nhập thành viên
     * @return \think\model\relation\HasOne
     */
    public function systemUserLevel()
    {
        return $this->hasOne(SystemUserLevel::class, 'id', 'level');
    }

    /**
     * Nhóm người dùng được liên kết
     * @return \think\model\relation\HasOne
     */
    public function userGroup()
    {
        return $this->hasOne(UserGroup::class, 'id', 'group_id');
    }

    /**
     * Liên quan đến chính mình
     * @return \think\model\relation\HasOne
     */
    public function spreadUser()
    {
        return $this->hasOne(self::class, 'uid', 'spread_uid');
    }

    /**
     * Liên quan đến chính mình
     * @return \think\model\relation\HasOne
     */
    public function spreadCount()
    {
        return $this->hasMany(User::class, 'spread_uid', 'uid');
    }

    /**
     * Mối quan hệ thẻ người dùng được liên kết
     * @return \think\model\relation\HasMany
     */
    public function LabelRelation()
    {
        return $this->hasMany(UserLabelRelation::class, 'uid', 'uid');
    }

    /**
     * Thẻ người dùng được liên kết
     * @return \think\model\relation\HasManyThrough
     */
    public function label()
    {
        return $this->hasManyThrough(UserLabel::class, UserLabelRelation::class, 'uid', 'id', 'uid', 'label_id');
    }

    /**
     * Địa chỉ người dùng được liên kết
     * @return \think\model\relation\HasMany
     */
    public function address()
    {
        return $this->hasMany(UserAddress::class, 'uid', 'uid');
    }

    /**
     * Rút tiền liên quan
     * @return \think\model\relation\HasMany
     */
    public function extract()
    {
        return $this->hasMany(UserExtract::class, 'uid', 'uid');
    }

    /**
     * Đơn hàng liên kết
     * @return User|\think\model\relation\HasMany
     */
    public function order()
    {
        return $this->hasMany(StoreOrder::class, 'uid', 'uid');
    }

    /**
     * Các mức phân phối liên quan
     * @return \think\model\relation\HasOne
     */
    public function agentLevel()
    {
        return $this->hasOne(AgentLevel::class, 'id', 'agent_level')->where('is_del', 0)->where('status', 1);
    }

    /**
     * Dữ liệu hoa hồng liên kết
     * @return \think\model\relation\HasMany
     */
    public function bill()
    {
        return $this->hasMany(UserBill::class, 'uid', 'uid');
    }

    /**
     * người dùnguid
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('uid', $value);
        else
            $query->where('uid', $value);
    }

    /**
     * Trình tìm tài khoản
     * @param Model $query
     * @param $value
     */
    public function searchAccountAttr($query, $value)
    {
        $query->where('account', $value);
    }

    /**
     * người tìm kiếm mật khẩu
     * @param Model $query
     * @param $value
     */
    public function searchPwdAttr($query, $value)
    {
        $query->where('pwd', $value);
    }

    /**
     * uidtrình tìm kiếm truy vấn phạm vi
     * @param Model $query
     * @param $value
     */
    public function searchUidsAttr($query, $value)
    {
        $query->whereIn('uid', $value);
    }

    /**
     * Trình tìm kiếm điều kiện mờ
     * @param Model $query
     * @param $value
     */
    public function searchLikeAttr($query, $value)
    {
        $query->where('account|nickname|phone|real_name|uid', 'like', '%' . $value . '%');
    }

    /**
     * Công cụ tìm số điện thoại di động
     * @param Model $query
     * @param $value
     */
    public function searchPhoneAttr($query, $value)
    {
        $query->where('phone', $value);
    }

    /**
     * Người tìm kiếm nhóm
     * @param Model $query
     * @param $value
     */
    public function searchGroupIdAttr($query, $value)
    {
        $query->where('group_id', $value);
    }

    /**
     * Có nên quảng bá công cụ tìm người hay không
     * @param Model $query
     * @param $value
     */
    public function searchIsPromoterAttr($query, $value)
    {
        $query->where('is_promoter', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * Trình tìm kiếm cấp độ thành viên
     * @param Model $query
     * @param $value
     */
    public function searchLevelAttr($query, $value)
    {
        $query->where('level', $value);
    }

    /**
     * Trình tìm kiếm uid của nhà quảng cáo
     * @param Model $query
     * @param $value
     */
    public function searchSpreadUidAttr($query, $value)
    {
        $query->where('spread_uid', $value);
    }

    /**
     * Uid của người khởi xướng không bằng người tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchNotSpreadUidAttr($query, $value)
    {
        $query->where('spread_uid', '<>', $value);
    }

    /**
     * Công cụ tìm thời gian của nhà quảng bá
     * @param Model $query
     * @param $value
     */
    public function searchSpreadTimeAttr($query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                if (count($value) == 2) $query->where('spread_time', $value[0], $value[1]);
            } else {
                $query->where('spread_time', $value);
            }
        }
    }

    /**
     * Công cụ tìm loại người dùng
     * @param Model $query
     * @param $value
     */
    public function searchUserTypeAttr($query, $value)
    {
        if ($value != '') $query->where('user_type', $value);
    }

    /**
     * Công cụ tìm số lượng mua hàng
     * @param Model $query
     * @param $value
     */
    public function searchPayCountAttr($query, $value)
    {
        $query->where('pay_count', $value);
    }

    /**
     * Trình độ khuyến mãi của người dùng
     * @param Model $query
     * @param $value
     */
    public function searchSpreadOpenAttr($query, $value)
    {
        if ($value != '') $query->where('spread_open', $value);
    }

    /**
     * nicknameNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchNicknameAttr($query, $value)
    {
        $query->where('nickname', "like", "%" . $value . "%");
    }

    /**
     * division_typeNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchDivisionTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('division_type', $value);
    }

    /**
     * division_idNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchDivisionIdAttr($query, $value)
    {
        if ((int)$value !== 0) $query->where('division_id', $value);
    }

    /**
     * agent_idNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchAgentIdAttr($query, $value)
    {
        if ($value !== '') $query->where('agent_id', $value);
    }

    /**
     * staff_idNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchStaffIdAttr($query, $value)
    {
        if ($value !== '') $query->where('staff_id', $value);
    }

    /**
     * is_divisionNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchIsDivisionAttr($query, $value)
    {
        if ($value !== '') $query->where('is_division', $value);
    }

    /**
     * is_agentNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchIsAgentAttr($query, $value)
    {
        if ($value !== '') $query->where('is_agent', $value);
    }

    /**
     * is_staffNgười tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchIsStaffAttr($query, $value)
    {
        if ($value !== '') $query->where('is_staff', $value);
    }

    /**
     * @param $query
     * @param $value
     */
    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('uid|nickname', 'like', '%' . $value . '%');
    }

    /**
     * Đăng xuất khỏi người tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }

    /**
     * Không bằng trình tìm kiếm uid
     * @param $query
     * @param $value
     */
    public function searchNotUidAttr($query, $value)
    {
        if ($value !== '') $query->where('uid', '<>', $value);
    }
}
