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
use app\model\system\SystemCrudList;

/**
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2024/5/20
 */class SystemCrudListDao extends BaseDao
{
    /**
     * @return string
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/20
     */    protected function setModel(): string
    {
        return SystemCrudList::class;
    }
}