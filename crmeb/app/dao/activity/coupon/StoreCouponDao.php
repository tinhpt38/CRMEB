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

namespace app\dao\activity\coupon;


use app\dao\BaseDao;
use app\model\activity\coupon\StoreCoupon;

/**
 * Mã giảm giá
 * Class StoreCouponDao
 * @package app\dao\coupon
 */class StoreCouponDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreCoupon::class;
    }

    /**
     * Nhận danh sách bài viết
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return mixed
     */    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Viết phiếu giảm giá
     * @param $cid
     * @param int $total_count
     * @param int $start_time
     * @param int $end_time
     * @param int $remain_count
     * @param int $status
     * @param int $is_permanent
     * @param int $full_reduction
     * @param int $is_give_subscribe
     * @param int $is_full_give
     * @return \crmeb\basic\BaseModel|\think\Model
     */    public function setIssue($cid, $total_count = 0, $start_time = 0, $end_time = 0, $remain_count = 0, $status = 0, $is_permanent = 0, $full_reduction = 0, $is_give_subscribe = 0, $is_full_give = 0)
    {
        $add_time = time();
        $data['cid'] = $cid;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['total_count'] = $total_count;
        $data['remain_count'] = $remain_count;
        $data['is_permanent'] = $is_permanent;
        $data['status'] = $status;
        $data['is_give_subscribe'] = $is_give_subscribe;
        $data['is_full_give'] = $is_full_give;
        $data['full_reduction'] = $full_reduction;
        $data['add_time'] = $add_time;
        //TODO $data Tham số không được sử dụng
        return $this->getModel()->create(compact('cid', 'start_time', 'end_time', 'total_count', 'remain_count', 'is_permanent', 'status', 'is_give_subscribe', 'is_full_give', 'full_reduction', 'add_time'));
    }
}
