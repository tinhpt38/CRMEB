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
use app\model\system\SystemCrud;

/**
 * Class SystemCrudDao
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\dao\system
 */class SystemCrudDao extends BaseDao
{

    /**
     * Lấy mô hình hiện tại
     * @return string
     */    protected function setModel(): string
    {
        return SystemCrud::class;
    }
}
