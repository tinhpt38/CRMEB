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
namespace app\dao\agent;


use app\dao\BaseDao;
use app\model\agent\DivisionAgentApply;

class DivisionAgentApplyDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return DivisionAgentApply::class;
    }

    /**
     * Đăng ký danh sách đại lý
     * @param array $where
     * @param string $field
     * @param array $with
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where = [], int $page = 0, int $limit = 0, string $field = '*', array $with = [])
    {
        return $this->search($where)->field($field)->when($with, function ($query) use ($with) {
            $query->with($with);
        })->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('id desc')->select()->toArray();
    }
}