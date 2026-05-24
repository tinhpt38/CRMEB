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

namespace app\services\other;


use app\dao\other\CategoryDao;
use app\services\BaseServices;

/**
 * Class CategoryServices
 * @package app\services\other
 */class CategoryServices extends BaseServices
{

    protected $cacheName = 'crmeb_cate';

    /**
     * CategoryServices constructor.
     * @param CategoryDao $dao
     */    public function __construct(CategoryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách danh mục
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCateList(array $where = [], array $field = ['*'])
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getCateList($where, $page, $limit, $field);
        if ($where['owner_id'] == 0) {
            array_unshift($data, ['id' => 0, 'name' => 'Kỹ năng nói có hệ thống', 'sort' => 0]);
        }
        $count = $this->dao->count($where);
        return compact('data', 'count');
    }


}
