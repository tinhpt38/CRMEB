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

namespace app\dao\agent;

use app\dao\BaseDao;
use app\model\agent\AgentLevelTask;

/**
 * Class AgentLevelTaskDao
 * @package app\dao\agent
 */class AgentLevelTaskDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return AgentLevelTask::class;
    }

    /**
     * Nhận nhiệm vụ cấp độ
     * @param array $where
     * @param string $field
     * @param array $with
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTaskList(array $where, string $field = '*', array $with = [], int $page = 0, int $limit = 0)
    {
        return $this->search($where)->when($with, function ($query) use ($with) {
            $query->with($with);
        })->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Nhận Tất cả các nhiệm vụ cùng loại
     * @param int $type
     * @param int $grade
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTypTaskList(int $type)
    {
        return $this->getModel()->with(['level' => function ($query) {
            $query->field('id,grade')->where('status', 1)->where('is_del', 0)->bind(['grade']);
        }])->where('type', $type)->where('is_del', 0)->where('status', 1)->order('number desc')->select()->toArray();
    }

}
