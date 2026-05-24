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
use app\services\system\admin\SystemRoleServices;
use crmeb\exceptions\AuthException;
use crmeb\interfaces\MiddlewareInterface;

/**
 * Xác minh quy tắc cấp phép
 * Class AdminCheckRoleMiddleware
 * @package app\http\middleware
 */class AdminCheckRoleMiddleware implements MiddlewareInterface
{
    /**
     * Xác minh quy tắc cấp phép
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \throwable
     */    public function handle(Request $request, \Closure $next)
    {
        if (!$request->adminId() || !$request->adminInfo())
            throw new AuthException('Lỗi tham số');

        if ($request->adminInfo()['level']) {
            /** @var SystemRoleServices $systemRoleService */            $systemRoleService = app()->make(SystemRoleServices::class);
            $systemRoleService->verifyAuth($request);
        }

        return $next($request);
    }
}
