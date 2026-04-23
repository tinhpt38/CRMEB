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

namespace app\adminapi\middleware;


use app\Request;
use app\services\system\log\SystemLogServices;
use crmeb\interfaces\MiddlewareInterface;

/**
 * Đăng nhập phần mềm trung gian
 * Class AdminLogMiddleware
 * @package app\adminapi\middleware
 */
class AdminLogMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            /** @var SystemLogServices $services */
            $services = app()->make(SystemLogServices::class);
            $services->recordAdminLog($request->adminId(), $request->adminInfo()['account'], 'system');
        } catch (\Throwable $e) {
        }
        return $next($request);
    }

}
