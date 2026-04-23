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

namespace app\services\pc;


use app\services\BaseServices;
use app\services\shipping\SystemCityServices;

class PublicServices extends BaseServices
{
    /**
     * Nhận dữ liệu thành phố
     * @param int $pid
     * @return mixed
     */
    public function getCity(int $pid)
    {
        /** @var SystemCityServices $city */
        $city = app()->make(SystemCityServices::class);
        $list = $city->getColumn(['parent_id' => $pid, 'is_show' => 1], 'city_id,name');
        return $list;
    }
}
