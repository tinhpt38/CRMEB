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
 * Class UserRecharge
 * @package app\model\user
 */class UserRecharge extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_recharge';

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * sự kết hợpuser
     * @return model\relation\HasOne
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->bind([
            'nickname' => 'nickname',
            'avatar' => 'avatar'
        ]);
    }

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
     * Số đơn hàng
     * @param Model $query
     * @param $value
     */    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }

    /**
     * Loại nạp tiền
     * @param Model $query
     * @param $value
     */    public function searchRechargeTypeAttr($query, $value)
    {
        $query->where('recharge_type', $value);
    }

    /**
     * Không bằng loại nạp tiền
     * @param Model $query
     * @param $value
     */    public function searchNoRechargeTypeAttr($query, $value)
    {
        $query->where('recharge_type', '<>', $value);
    }

    /**Số tiền hoàn lại
     * @param $query
     * @param $value
     */    public function searchRefundPriceAttr($query, $value)
    {
        $query->where('refund_price', $value);
    }

    /**
     * Có nên trả tiền không
     * @param Model $query
     * @param $value
     */    public function searchPaidAttr($query, $value)
    {
        $query->where('paid', $value);
    }

    /**
     * tìm kiếm mờ
     * @param Model $query
     * @param $value
     */    public function searchLikeAttr($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->whereLike('uid|order_id', "%" . $value . "%")->whereOr('uid', 'in', function ($query) use ($value) {
                $query->name('user')->whereLike('nickname', "%" . $value . "%")->field('uid')->select();
            });
        });
    }

    public function searchChannelTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('channel_type', $value);
    }
}
