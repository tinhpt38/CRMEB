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
use app\services\system\admin\AdminAuthServices;
use app\services\system\log\SystemFileServices;
use crmeb\exceptions\AuthException;
use crmeb\interfaces\MiddlewareInterface;
use crmeb\services\CacheService;
use think\facade\Config;

/**
 * Phần mềm trung gian xác minh đăng nhập phụ trợ
 * Class AdminAuthTokenMiddleware
 * @package app\adminapi\middleware
 */class AdminEditorTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $token = CacheService::get(trim($request->get('fileToken')));

        /** @var SystemFileServices $service */        $service = app()->make(SystemFileServices::class);
        $service->parseToken($token);

        return $next($request);
    }
}
