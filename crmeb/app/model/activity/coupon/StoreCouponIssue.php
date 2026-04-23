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

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Đăng phiếu giảm giáModel
 * Class StoreCouponIssue
 * @package app\model\coupon
 */
class StoreCouponIssue extends BaseModel
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
    protected $name = 'store_coupon_issue';

    /**
     * Người dùng có
     * @return \think\model\relation\HasOne
     */
    public function used()
    {
        return $this->hasMany(StoreCouponIssueUser::class, 'issue_coupon_id', 'id');
    }

    /**
     * id
     * @param Model $query
     * @param $value
     */
    public function searchIdAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('id', $value);
        else
            $query->where('id', $value);
    }

    /**
     * Trình tìm mẫu phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchCidAttr($query, $value, $data)
    {
        $query->where('cid', $value);
    }

    /**
     * Phiếu giảm giá có giới hạn không?
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsPermanentAttr($query, $value, $data)
    {
        $query->where('is_permanent', $value);
    }

    /**
     * Phiếu giảm giá có phải là phiếu giảm giá dành cho người mới không?
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsGiveSubscribeAttr($query, $value, $data)
    {
        $query->where('is_give_subscribe', $value);
    }

    /**
     * Phiếu giảm giá có đầy đủ không?
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsFullGiveAttr($query, $value, $data)
    {
        $query->where('is_full_give', $value);
    }

    /**
     * Trạng thái phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
        if ($value != '') $query->where('status', $value);
    }

    /**
     * Có nên xóa phiếu giảm giá không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * Tên phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchCouponTitleAttr($query, $value, $data)
    {
        if ($value) $query->whereLike('coupon_title', '%' . $value . '%');
    }

    /**
     * Loại phiếu giảm giá
     * @param Model $query
     * @param $value
     */
    public function searchCouponTypeAttr($query, $value)
    {
        if ($value != '') $query->where('type', $value);
    }
}
