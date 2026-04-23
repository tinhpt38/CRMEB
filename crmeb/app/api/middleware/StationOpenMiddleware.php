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

namespace app\api\middleware;


use app\Request;
use crmeb\exceptions\ApiException;
use crmeb\interfaces\MiddlewareInterface;

/**
 * Class StationOpenMiddleware
 * @package app\api\middleware
 */
class StationOpenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        if (!sys_config('station_open', 1)) {
            throw new ApiException('Trang web đang được nâng cấp, vui lòng truy cập sau.', [], 402);
        }
        return $next($request);
    }
}
