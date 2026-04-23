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

namespace app\dao\system\upgrade;

use app\dao\BaseDao;
use app\model\system\upgrade\UpgradeLog;

/**
 * Bản ghi nâng cấpdao
 * Class UpgradeLogDao
 * @package app\dao\system\upgrade
 */
class UpgradeLogDao extends BaseDao
{

    protected function setModel(): string
    {
        return UpgradeLog::class;
    }

    /**
     * danh sách
     * @param array $where
     * @param array $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $field, int $page = 0, int $limit = 0): array
    {
        return $this->search()->field($field)->page($page, $limit)->select()->toArray();
    }
}