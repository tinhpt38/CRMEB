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

namespace app\services\activity\live;


use app\dao\activity\live\LiveRoomGoodsDao;
use app\services\BaseServices;

/**
 * Class LiveRoomGoodsServices
 * @package app\services\activity\live
 */
class LiveRoomGoodsServices extends BaseServices
{
    public function __construct(LiveRoomGoodsDao $dao)
    {
        $this->dao = $dao;
    }
}
