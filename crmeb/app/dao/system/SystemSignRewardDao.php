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
use app\model\system\SystemSignReward;

/**
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/7/28
 */class SystemSignRewardDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/28
     */    protected function setModel(): string
    {
        return SystemSignReward::class;
    }
}