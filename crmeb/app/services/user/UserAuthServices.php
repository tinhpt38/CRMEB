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

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserAuthDao;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;
use crmeb\utils\JwtAuth;

/**
 *
 * Class UserAuthServices
 * @package app\services\user
 */class UserAuthServices extends BaseServices
{

    /**
     * UserAuthServices constructor.
     * @param UserAuthDao $dao
     */    public function __construct(UserAuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận thông tin ủy quyền
     * @param $token
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function parseToken($token): array
    {
        $md5Token = is_null($token) ? '' : md5($token);

        if ($token === 'undefined') {
            throw new AuthException('Vui lòng đăng nhập', [], 401);
        }
        if (!$token || !$tokenData = CacheService::get($md5Token))
            throw new AuthException('Vui lòng đăng nhập', [], 401);

        if (!is_array($tokenData) || empty($tokenData) || !isset($tokenData['uid'])) {
            throw new AuthException('Vui lòng đăng nhập', [], 401);
        }

        /** @var JwtAuth $jwtAuth */        $jwtAuth = app()->make(JwtAuth::class);
        //Thiết lập phân tích cú pháptoken
        [$id, $type] = $jwtAuth->parseToken($token);


        try {
            $jwtAuth->verifyToken();
        } catch (\Throwable $e) {
            if (!request()->isCli()) CacheService::delete($md5Token);
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 401);
        }

        $user = $this->dao->get(['uid' => $id, 'is_del' => 0, 'status' => 1]);

        if (!$user || $user->uid != $tokenData['uid']) {
            if (!request()->isCli()) CacheService::delete($md5Token);
            throw new AuthException('Trạng thái đăng nhập sai,Vui lòng đăng nhập lại', [], 401);
        }

        $this->dao->update(['uid' => $id], ['last_time' => time()]);

        $tokenData['type'] = $type;
        return compact('user', 'tokenData');
    }

}
