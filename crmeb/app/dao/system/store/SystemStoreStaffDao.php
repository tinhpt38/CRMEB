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

namespace app\dao\system\store;


use app\dao\BaseDao;
use app\model\system\store\SystemStoreStaff;

/**
 * nhân viên cửa hàng
 * Class SystemStoreStaffDao
 * @package app\dao\system\store
 */class SystemStoreStaffDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemStoreStaff::class;
    }

    /**
     * Lấy danh sách nhân viên
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getStoreStaffList(array $where, int $page, int $limit)
    {
        return $this->search($where)->with(['store', 'user'])->page($page, $limit)->order('add_time DESC')->select()->toArray();
    }

}
