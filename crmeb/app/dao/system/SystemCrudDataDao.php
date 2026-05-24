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

namespace app\dao\system;


use app\dao\BaseDao;
use app\model\system\SystemCrudData;

/**
 * Class SystemCrudDataDao
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/7/28
 * @package app\dao\system
 */class SystemCrudDataDao extends BaseDao
{

    /**
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/7/28
     */    protected function setModel(): string
    {
        return SystemCrudData::class;
    }
}
