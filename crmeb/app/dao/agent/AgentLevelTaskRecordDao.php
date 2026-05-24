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
use app\model\agent\AgentLevelTaskRecord;

/**
 * Class AgentLevelTaskRecordDao
 * @package app\dao\agent
 */class AgentLevelTaskRecordDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return AgentLevelTaskRecord::class;
    }

    /**
     * Nhận Tất cả các cấp độ nhà phân phối
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where = [], string $field = '*')
    {
        return $this->search($where + ['is_del' => 0, 'status' => 1])->field($field)->order('sort desc,id desc')->select()->toArray();
    }
}
