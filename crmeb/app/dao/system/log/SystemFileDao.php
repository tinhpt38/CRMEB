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

namespace app\dao\system\log;


use app\dao\BaseDao;
use app\model\system\log\SystemFile;

/**
 * Mô hình xác minh tập tin
 * Class SystemFileDao
 * @package app\dao\system\log
 */
class SystemFileDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return SystemFile::class;
    }

    /**
     * Nhận tất cả
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll()
    {
        return $this->getModel()->order('atime desc')->select()->toArray();
    }

}
