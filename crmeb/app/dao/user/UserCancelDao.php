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
namespace app\dao\user;

use app\dao\BaseDao;
use app\model\user\UserCancel;

class UserCancelDao extends BaseDao
{
    /**
     * @return string
     */
    protected function setModel(): string
    {
        return UserCancel::class;
    }

    /**
     * Nhận danh sách
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($where, $page = 0, $limit = 0)
    {
        return $this->search($where)->with(['user'])
            ->when($page && $limit, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->select()->toArray();
    }
}