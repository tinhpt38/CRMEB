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
 * TODO Bộ sưu tập phiếu giảm giá của người dùng quầy lễ tânModel
 * Class StoreCouponIssueUser
 * @package app\model\coupon
 */
class StoreCouponIssueUser extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_coupon_issue_user';

    /**
     * Lấy tên và hình đại diện của người nhận
     * @return \think\model\relation\HasOne
     */
    public function userInfo()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field('uid,nickname,avatar')->bind(['nickname','avatar']);
    }

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Nhận công cụ tìm người dùng
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }

    /**
     * Nhận công cụ tìm phiếu giảm giá
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIssueCouponIdAttr($query, $value, $data)
    {
        $query->where('issue_coupon_id', $value);
    }
}
