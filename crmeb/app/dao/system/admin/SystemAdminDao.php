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

namespace app\dao\system\admin;

use app\dao\BaseDao;
use app\model\system\admin\SystemAdmin;

/**
 * Class SystemAdminDao
 * @package app\dao\system\admin
 */class SystemAdminDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemAdmin::class;
    }

    /**
     * Lấy danh sách quản trị viên
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return mixed
     */    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->select()->toArray();
    }

    /**
     * Tìm thông tin quản trị viên bằng tên quản trị viên
     * @param string $account
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function accountByAdmin(string $account)
    {
        return $this->search(['account' => $account, 'is_del' => 0])->find();
    }

    /**
     * Tài khoản hiện tại có sẵn không?
     * @param string $account
     * @param int $id
     * @return int
     */    public function isAccountUsable(string $account, int $id)
    {
        return $this->search(['account' => $account, 'is_del' => 0])->where('id', '<>', $id)->count();
    }

    /**
     * lấyadminid
     * @param int $level
     * @return array
     */    public function getAdminIds(int $level)
    {
        return $this->getModel()->where('level', '>=', $level)->column('id', 'id');
    }

    /**
     * Lấy tên của quản trị viên dưới cấp độ vàid
     * @param string $field
     * @param int $level
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOrdAdmin(string $field = 'real_name,id', int $level = 0)
    {
        return $this->getModel()->where('level', '>=', $level)->field($field)->select()->toArray();
    }

    /**
     * Thu thập dữ liệu quản trị viên có điều kiện
     * @param $where
     * @return mixed
     */    public function getInfo($where)
    {
        return $this->getModel()->where($where)->find();
    }

    /**
     * Kiểm tra xem có quản trị viên nào sử dụng vai trò này không
     * @param int $id
     * @return bool
     */    public function checkRoleUse(int $id): bool
    {
        return (bool)$this->getModel()->where('level', '<>', 0)->where('is_del', 0)->whereFindInSet('roles', $id)->count();
    }
}
