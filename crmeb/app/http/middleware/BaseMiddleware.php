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

namespace app\http\middleware;


use app\Request;
use crmeb\interfaces\MiddlewareInterface;

/**
 * Class BaseMiddleware
 * @package app\api\middleware
 */class BaseMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @param bool $force
     * @return mixed
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/04/07
     */    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        if (!$request->hasMacro('uid')) {
            $request->macro('uid', function(){ return 0; });
        }
        if (!$request->hasMacro('adminId')) {
            $request->macro('adminId', function(){ return 0; });
        }
        if (!$request->hasMacro('kefuId')) {
            $request->macro('kefuId', function(){ return 0; });
        }

        return $next($request);
    }
}
