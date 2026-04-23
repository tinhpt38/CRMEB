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

namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\SystemUserLevel;

/**
 *
 * Class SystemUserLevelDao
 * @package app\dao\system
 */
class SystemUserLevelDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return SystemUserLevel::class;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, string $field = '*', int $page = 0, $limit = 0)
    {
        return $this->getModel()->where($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('grade asc')->select()->toArray();
    }

    /**
     * Lấy tổng số điều kiện phức tạp
     * @param array $where
     * @return int
     */
    public function getCount(array $where)
    {
        return $this->getModel()->where($where)->count();
    }

    /**
     * Nhận cấp độ người dùng trước đó
     * @param $grade
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPreLevel($grade, string $field = '*')
    {
        return $this->getModel()->where('grade', '<', $grade)->where('is_del', 0)->field($field)->order('grade desc')->find();
    }

    /**
     * Nhận cấp độ người dùng tiếp theo
     * @param $grade
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNextLevel($grade, string $field = '*')
    {
        return $this->getModel()->where('grade', '>', $grade)->where('is_del', 0)->field($field)->order('grade asc')->find();
    }
}
