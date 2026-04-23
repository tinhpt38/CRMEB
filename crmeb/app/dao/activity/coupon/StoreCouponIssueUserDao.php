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
declare (strict_types=1);

namespace app\dao\activity\coupon;

use app\dao\BaseDao;
use app\model\activity\coupon\StoreCouponIssueUser;

/**
 *
 * Class StoreCouponIssueUserDao
 * @package app\dao\coupon
 */
class StoreCouponIssueUserDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreCouponIssueUser::class;
    }

    /**
     * Nhận danh sách phiếu giảm giá đã nhận
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->with('userInfo')->page($page, $limit)->select()->toArray();
    }

    /**
     * Xóa phiếu giảm giá mà người dùng nhận được
     * @param $where
     * @return bool
     */
    public function delIssueUserCoupon($where)
    {
        return $this->getModel()->where($where)->delete();
    }

    public function getIssueUserCount($uid, $coupon_id)
    {
        return $this->getModel()->where('uid', $uid)->where('issue_coupon_id', $coupon_id)->count();
    }
}
