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

namespace app\model\order;


use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

class OtherOrder extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'other_order';

    protected $insert = ['add_time'];

    // protected $hidden = ['add_time', 'is_del', 'uid'];

    /**Loại lệnh
     * @param $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        if (is_array($value)) {
            $query->where('type', 'in', $value);
        } else {
            $query->where('type', $value);
        }

    }

    public function searchPaidAttr($query, $value)
    {
        $query->where('paid', $value);
    }

    /**Phương thức thanh toán không thuộc về
     * @param $query
     * @param $value
     */
    public function searchPayTypeNoAttr($query, $value)
    {
        $query->where('pay_type', '<>', $value);
    }

    /**
     * Nguồn người dùng
     * @param Model $query
     * @param $value
     */
    public function searchChannelTypeAttr($query, $value)
    {
        if ($value != '') $query->where('channel_type', $value);
    }

    /**tìm kiếm id đơn hàng
     * @param $query
     * @param $value
     */
    public function searchOrderIdAttr($query, $value)
    {
        if ($value != "") {
            $query->where('order_id', $value);
        }

    }

    /**
     * Liên kết một-một của các bảng người dùng
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname', 'phone', 'spread_uid', 'overdue_time']);
    }

    /**Loại thành viên
     * @param $query
     * @param $value
     */
    public function searchMemberTypeAttr($query, $value)
    {
        if ($value && $value != 'card' && $value != 'free') {
            if ($value == -1) {
                $query->where('member_type', '<>', 0);
            } else {
                $query->where('member_type', $value);
            }
        } elseif ($value == 'card') {
            $query->where('member_type', 'free')->where('code', '<>', '');
        } elseif ($value == 'free') {
            $query->where('member_type', 'free')->where('code', '');
        }

    }

    /**Phương thức thanh toán
     * @param $query
     * @param $value
     */
    public function searchPayTypeAttr($query, $value)
    {
        if ($value) {
            if ($value == "free") {
                $query->where(function ($query) {
                    $query->where('type', 'in', [0, 2])->whereOr(function ($query) {
                        $query->where(['type' => 1, 'is_free' => 1]);
                    });
                });
            } else {
                $query->where('pay_type', $value);
            }

        }
    }

    /**
     * @param $query
     * @param $value
     */
    public function searchAddTimeAttr($query, $value)
    {
        if ($value) {
            $query->whereTime('add_time', 'between', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        if ($value) {
            $query->where('uid', 'in', $value);
        }
    }


}
