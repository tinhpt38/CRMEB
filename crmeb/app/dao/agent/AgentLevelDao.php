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
declare (strict_types = 1);

namespace app\dao\agent;

use app\dao\BaseDao;
use app\model\agent\AgentLevel;

/**
 * Class AgentLevelDao
 * @package app\dao\agent
 */
class AgentLevelDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return AgentLevel::class;
    }

    /**
     * Nhận tất cả các cấp độ nhà phân phối
     * @param array $where
     * @param string $field
     * @param array $with
     * @param int $page
     * @param int $limit
     * @param int $grade
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where = [], string $field = '*', array $with = [], int $page = 0, int $limit = 0, $grade = 0)
    {
        return $this->search($where, false)->when($grade, function ($query) use ($grade) {
            $query->where('grade', '>=', $grade);
        })->field($field)->when($with, function ($query) use ($with) {
            $query->with($with);
        })->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('grade asc,id desc')->select()->toArray();
    }
}
