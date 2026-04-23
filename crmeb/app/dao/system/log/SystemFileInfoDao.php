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
use app\model\system\log\SystemFileInfo;

/**
 * @author thủy triều
 * @email 442384644@qq.com
 * @date 2023/04/07
 */
class SystemFileInfoDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return SystemFileInfo::class;
    }
}