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

namespace app\services\wechat;


use app\dao\wechat\WechatMediaDao;
use app\services\BaseServices;

/**
 * Class WechatMediaServices
 * @package app\services\wechat
 * @method save(array $data) lưu dữ liệu
 */
class WechatMediaServices extends BaseServices
{
    /**
     * WechatMediaServices constructor.
     * @param WechatMediaDao $dao
     */
    public function __construct(WechatMediaDao $dao)
    {
        $this->dao = $dao;
    }

}
