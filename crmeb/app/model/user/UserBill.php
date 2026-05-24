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

use app\model\order\StoreOrder;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\model;

/**
 * Class UserBill
 * @package app\model\user
 */class UserBill extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_bill';

    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'add_time';

    /**
     * Thêm công cụ sửa đổi thời gian
     * @return int
     */    public function setAddTimeAttr()
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
            if (is_string($value)) {
                return $value;
            } elseif (is_int($value)) {
                return date('Y-m-d H:i:s', (int)$value);
            }
        }
        return '';
    }

    /**
     * Bảng thứ tự liên kết
     * @return UserBill|model\relation\HasOne
     */    public function order()
    {
        return $this->hasOne(StoreOrder::class, 'id', 'link_id')->field(['id', 'total_num'])->bind(['total_num']);
    }

    /**
     * Người dùng được liên kết
     * @return model\relation\HasOne
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid');
    }

    /**
     * Khách hànguid
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if ($value !== '') {
            if (is_array($value))
                $query->whereIn('uid', $value);
            else
                $query->where('uid', $value);
        }
    }

    /**
     * sự kết hợpid
     * @param Model $query
     * @param $value
     */    public function searchLinkIdAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('link_id', $value);
        else
            $query->where('link_id', $value);
    }

    /**
     * chi tiêu|lấy
     * @param Model $query
     * @param $value
     */    public function searchPmAttr($query, $value)
    {
        if ($value !== '') $query->where('pm', $value);
    }

    /**
     * kiểu now_money:Số dư integral:điểm thưởng exp:kinh nghiệm
     * @param Model $query
     * @param $value
     */    public function searchCategoryAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('category', $value);
        else
            $query->where('category', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchNotCategoryAttr($query, $value)
    {
        if (is_array($value))
            $query->whereNotIn('category', $value);
        else
            $query->where('category', '<>', $value);
    }

    /**
     * kiểu
     * @param Model $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('type', $value);
        else
            $query->where('type', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchNotTypeAttr($query, $value)
    {
        if (is_array($value))
            $query->whereNotIn('type', $value);
        else
            $query->where('type', '<>', $value);
    }

    /**
     * Trạng thái 0: Đã xác nhận 1: Hợp lệ -1: Không hợp lệ
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * Có nhận hàng hay không 0: Chưa nhận 1: Đã nhận
     * @param Model $query
     * @param $value
     */    public function searchTakeAttr($query, $value)
    {
        $query->where('take', $value);
    }

    /**
     *
     * @param $query
     * @param $value
     */    public function searchIntegralTypeAttr($query, $value)
    {
        if ($value == 'get') {
            $query->where('type', '<>', 'pay_product_integral_back');
        }
    }

    /**
     * tìm kiếm mờ
     * @param Model $query
     * @param $value
     */    public function searchLikeAttr($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('uid|title', 'like', "%$value%")->whereOr('uid', 'in', function ($query) use ($value) {
                $query->name('user')->whereLike('uid|account|nickname|phone', '%' . $value . '%')->field('uid')->select();
            });
        });
    }

    /**
     * thời gian
     * @param Model $query
     * @param $value
     */    public function searchAddTimeAttr($query, $value)
    {
        if (is_string($value)) $query->whereTime($query, $value);
        if (is_array($value) && count($value) == 2) $query->whereTime('add_time', 'between', $value);
    }

    /**
     * @param $query
     * @param $value
     */    public function searchTradingTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('type', $value);
    }

    /**
     * @param $query
     * @param $value
     */    public function searchIsFrozenAttr($query, $value)
    {
        if ($value) $query->where('frozen_time', '>', time());
    }

}
