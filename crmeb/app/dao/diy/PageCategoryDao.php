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

namespace app\dao\diy;

use app\dao\BaseDao;
use app\model\diy\PageCategory;

/**
 * Class PageCategoryDao
 * @package app\dao\diy
 */class PageCategoryDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return PageCategory::class;
    }


    /**
     * Nhận danh sách
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, string $field = '*', int $page = 0, int $limit = 0)
    {
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search($where)->field($field)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page();
        })->order('sort desc,id DESC')->select()->toArray();
    }

}
