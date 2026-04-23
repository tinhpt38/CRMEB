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
use app\model\system\log\SystemFileMd5;

class SystemFileMd5Dao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemFileMd5::class;
    }

    public function getList()
    {
        return $this->getModel()->select()->toArray();
    }
}
