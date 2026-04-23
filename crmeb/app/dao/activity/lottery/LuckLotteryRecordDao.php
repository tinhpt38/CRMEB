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

namespace app\dao\activity\lottery;

use app\dao\BaseDao;
use app\model\activity\lottery\LuckLotteryRecord;

/**
 *
 * Class LuckLotteryRecordDao
 * @package app\dao\activity\lottery
 */
class LuckLotteryRecordDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return LuckLotteryRecord::class;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param string $field
     * @param array $with
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, $field = '*', array $with = [], int $page = 0, int $limit = 10)
    {
        return $this->search($where)->when($with, function ($query) use ($with) {
            $query->with($with);
        })->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('add_time desc')->select()->toArray();
    }

    /**
     * @param array $where
     * @param string $group
     * @return int
     */
    public function getCount(array $where, string $group = '')
    {
        return $this->getModel()->where($where)->when($group, function ($query) use ($group) {
            $query->group($group);
        })->count();
    }

    public function getRecordByOrderId($uid, $order_id)
    {
        $info = $this->getModel()->where('uid', $uid)->where('wechat_order_id', $order_id)->find();
        return $info ? $info->toArray() : [];
    }
}
