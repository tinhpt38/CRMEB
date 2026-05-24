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

namespace app\dao\user;

use app\dao\BaseDao;
use app\model\user\UserSpread;

/**
 * Class UserSpreadDao
 * @package app\dao\user
 */class UserSpreadDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return UserSpread::class;
    }

    /**
     * Nhận danh sách khuyến mãi
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, string $field = '*', int $page = 0, $limit = 0)
    {
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('spread_time desc,d desc')->select()->toArray();
    }

    /**
     * Nhận khuyến mãiuids
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSpreadUids(array $where)
    {
        return $this->search($where)->order('spread_time desc,id desc')->column('uid');
    }
}
