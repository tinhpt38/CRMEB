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
use think\facade\Config;
use think\Response;

/**
 * Phần mềm trung gian tên miền chéo
 * Class AllowOriginMiddleware
 * @package app\http\middleware
 */
class AllowOriginMiddleware implements MiddlewareInterface
{

    /**
     * Cho phép tên miền nhiều miền
     * @var string
     */
    protected $cookieDomain;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header = Config::get('cookie.header');
        $origin = $request->header('origin');

        if ($origin && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain)))
            $header['Access-Control-Allow-Origin'] = $origin;
        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
        } else {
            $response = $next($request)->header($header);
        }
//        $request->filter(['strip_tags', 'addslashes', 'trim']);
        return $response;
    }
}
