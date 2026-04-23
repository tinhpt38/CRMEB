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

namespace app\dao\system\config;

use app\dao\BaseDao;
use app\model\system\config\SystemGroup;

/**
 * Class SystemGroupDao
 * @package app\dao\system\config
 */
class SystemGroupDao extends BaseDao
{
    /**
     * @return string
     */
    protected function setModel(): string
    {
        return SystemGroup::class;
    }

    /**
     * Nhận danh sách phân trang của dữ liệu kết hợp
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupList(array $where, array $field = ['*'], int $page = 0, int $limit = 0)
    {
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->select()->toArray();
    }

    /**
     * Nhận cấu hình dựa trên tên cấu hìnhid
     * @param string $configName
     * @return mixed
     */
    public function getConfigNameId(string $configName)
    {
        return $this->value(['config_name' => $configName]);
    }
}
