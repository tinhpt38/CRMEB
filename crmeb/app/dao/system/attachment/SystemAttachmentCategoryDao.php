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

namespace app\dao\system\attachment;

use app\dao\BaseDao;
use app\model\system\attachment\SystemAttachmentCategory;

/**
 *
 * Class SystemAttachmentCategoryDao
 * @package app\dao\attachment
 */
class SystemAttachmentCategoryDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return SystemAttachmentCategory::class;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where)
    {
        return $this->search($where)->select()->toArray();
    }

    /**
     * Nhận số lượng
     * @param array $where
     * @return int
     */
    public function getCount(array $where)
    {
        return $this->search($where)->count();
    }

    /**
     * Tìm kiếm danh mục đính kèmsearch
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     * @throws \ReflectionException
     */
    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)->when(isset($where['id']), function ($query) use ($where) {
            $query->whereIn('id', $where['id']);
        });
    }
}
