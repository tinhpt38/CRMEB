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
use app\services\user\UserAuthServices;
use crmeb\exceptions\AuthException;
use crmeb\interfaces\MiddlewareInterface;

/**
 * Class AuthTokenMiddleware
 * @package app\api\middleware
 */class AuthTokenMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @param bool $force
     * @return int|mixed|\think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/04/07
     */    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        $authInfo = null;
        $token = trim(ltrim($request->header('Authori-zation'), 'Bearer'));
        if (!$token) $token = trim(ltrim($request->header('Authorization'), 'Bearer'));//Đối với phiên bản chính thức, hãy xóa dòng này. Một số máy chủ không thể nhận được mã thông báo. Điều chỉnh nó thành Authori-zation
        try {
            /** @var UserAuthServices $service */            $service = app()->make(UserAuthServices::class);
            $authInfo = $service->parseToken($token);
        } catch (AuthException $e) {
            if ($force)
                return app('json')->make($e->getCode(), $e->getMessage());
        }

        if (!is_null($authInfo)) {
            $request->macro('user', function (string $key = null) use (&$authInfo) {
                if ($key) {
                    return $authInfo['user'][$key] ?? '';
                }
                return $authInfo['user'];
            });
            $request->macro('tokenData', function () use (&$authInfo) {
                return $authInfo['tokenData'];
            });
        }
        $request->macro('isLogin', function () use (&$authInfo) {
            return !is_null($authInfo);
        });
        $request->macro('uid', function () use (&$authInfo) {
            return is_null($authInfo) ? 0 : (int)$authInfo['user']->uid;
        });

        return $next($request);
    }
}
