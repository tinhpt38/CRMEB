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
use crmeb\services\CacheService;
use think\facade\Config;

/**
 * Rediskhóa
 * Lớp BlockerMiddleware
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/2/8
 * @package app\api\middleware
 */
class BlockerMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/11/21
     */
    public function handle(Request $request, \Closure $next)
    {
        if (Config::get('cache.default') == 'file') {
            return $next($request);
        }

        $uid = $request->uid();
        $key = md5($request->rule()->getRule() . $uid);
        if (!CacheService::setMutex($key)) {
            throw new ApiException('Yêu cầu quá thường xuyên, vui lòng thử lại sau.');
        }

        $response = $next($request);

        $this->after($response, $key);

        return $response;
    }

    /**
     * @param $response
     * @param $key
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/11/22
     */
    public function after($response, $key)
    {
        CacheService::delMutex($key);
    }
}
