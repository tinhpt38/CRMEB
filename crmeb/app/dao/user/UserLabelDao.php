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
use app\model\user\UserLabel;

/**
 *
 * Class UserLabelDao
 * @package app\dao\user
 */
class UserLabelDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return UserLabel::class;
    }

    /**
     * Nhận danh sách
     * @param string $field
     * @param int $page
     * @param int $limit
     * @param array $where
     * @param array $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(int $page = 0, int $limit = 0, array $where = [], array $field = ['*']): array
    {
        return $this->search($where)->with(['cateName'])->when(isset($where['label_cate']) && $where['label_cate'], function ($query) use ($where) {
            $query->where('label_cate', $where['label_cate']);
        })->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->field($field)->select()->toArray();
    }
}
