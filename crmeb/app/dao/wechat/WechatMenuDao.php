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

namespace app\dao\wechat;

use app\dao\BaseDao;
use app\model\other\Cache;

/**
 * Class WechatMenuDao
 * @package app\dao\wechat
 */
class WechatMenuDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    public function setModel(): string
    {
        return Cache::class;
    }
}
