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

namespace app\model\activity\coupon;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Phát hành phiếu giảm giáModel
 * Class StoreCouponUser
 * @package app\model\coupon
 */class StoreCouponUser extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_coupon_user';

    /**
     * Nhận loại
     * @var string[]
     */    protected $gainType = ['send' => 'Phân phối phụ trợ', 'get' => 'Thu thập thủ công'];

    /**
     * gõ kiểu
     * @param $value
     * @return string
     */    public function getTypeAttr($value)
    {
        return $this->gainType[$value];
    }

    /**
     * trạng thái sử dụng
     * @var string[]
     */    protected $statusType = [0 => 'Không được sử dụng', 1 => 'Đã sử dụng', 2 => 'Hết hạn'];

    /**
     * nhận trạng thái
     * @param $value
     * @return string
     */    public function getStatusAttr($value)
    {
        return $this->statusType[$value];
    }

    /**
     * @return \think\model\relation\HasOne
     */    public function issue()
    {
        return $this->hasOne(StoreCouponIssue::class, 'id', 'cid')->field(['id', 'end_use_time', 'start_use_time', 'type', 'coupon_time', 'product_id', 'category_id', 'receive_type'])->bind([
            'applicable_type' => 'type',
            'coupon_time' => 'coupon_time',
            'product_id',
            'category_id',
            'receive_type',
            'start_use_time',
            'end_use_time'
        ]);
    }

    /**
     * Lấy tên và hình đại diện của người nhận
     * @return \think\model\relation\HasOne
     */    public function userInfo()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field('uid,nickname,avatar')->bind(['nickname', 'avatar']);
    }

    /**
     * Trình tìm ID phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCidAttr($query, $value, $data)
    {
        if (is_array($value)) {
            $query->where('cid', 'IN', $value);
        } else {
            $query->where('cid', $value);
        }
    }

    /**
     * Trình tìm kiếm ID Khách hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm tên phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCouponTitleAttr($query, $value, $data)
    {
        $query->where('coupon_title', 'like', '%' . $value . '%');
    }

    /**
     * Nhận công cụ tìm kiếm phương pháp
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value);
    }


    /**
     * Cho dù nó không hợp lệ
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsFailAttr($query, $value, $data)
    {
        $query->where('is_fail', $value);
    }

    /**
     * Công cụ tìm kiếm có còn hạn sử dụng không?
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTimeAttr($query, $value, $data)
    {
        $query->whereTime('add_time', '>=', $value)->whereTime('end_time', '<=', $value);
    }
}
