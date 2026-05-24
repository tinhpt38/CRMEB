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

namespace app\services\kefu;


use crmeb\exceptions\AuthException;
use crmeb\services\oauth\OAuth;
use crmeb\utils\JwtAuth;
use app\services\BaseServices;
use crmeb\services\CacheService;
use app\dao\service\StoreServiceDao;
use app\services\wechat\WechatUserServices;

/**
 * Đăng nhập CSKH
 * Class LoginServices
 * @package app\services\kefu
 * @method get($id, ?array $field = [], ?array $with = []) Lấy một phần dữ liệu
 */class LoginServices extends BaseServices
{
    /**
     * LoginServices constructor.
     * @param StoreServiceDao $dao
     */    public function __construct(StoreServiceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Đăng nhập mật khẩu tài khoản CSKH
     * @param string $account
     * @param string $password
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function authLogin(string $account, string $password = null)
    {
        $kefuInfo = $this->dao->get(['account' => $account]);
        if (!$kefuInfo) {
            throw new AuthException('Không có Khách hàng như vậy');
        }
        if ($password && !password_verify($password, $kefuInfo->password)) {
            throw new AuthException('Tài khoản hoặc mật khẩu không chính xác');
        }
        if (!$kefuInfo->status) {
            throw new AuthException('Bạn đã bị cấm đăng nhập, vui lòng liên hệ với quản trị viên');
        }
        $token = $this->createToken($kefuInfo->id, 'kefu');
        $kefuInfo->update_time = time();
        $kefuInfo->ip = request()->ip();
        $kefuInfo->online = 1;
        $kefuInfo->save();
        return [
            'token' => $token['token'],
            'exp_time' => $token['params']['exp'],
            'kefuInfo' => $kefuInfo->hidden(['password', 'ip', 'update_time', 'add_time', 'status', 'mer_id', 'customer', 'notify'])->toArray()
        ];
    }

    /**
     * phân tích cú pháptoken
     * @param string $token
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function parseToken(string $token)
    {
        $noCli = !request()->isCli();
        //Kiểm tra xem mã thông báo đã hết hạn chưa
        $md5Token = md5($token);
        if (!$token || !CacheService::has($md5Token) || !(CacheService::get($md5Token, '', NULL, 'kefu'))) {
            throw new AuthException('Vui lòng đăng nhập', [], 402);
        }
        if ($token === 'undefined') {
            throw new AuthException('Vui lòng đăng nhập', [], 402);
        }

        /** @var JwtAuth $jwtAuth */        $jwtAuth = app()->make(JwtAuth::class);
        //Thiết lập phân tích cú pháptoken
        [$id, $type] = $jwtAuth->parseToken($token);

        //xác minhtoken
        try {
            $jwtAuth->verifyToken();
        } catch (\Throwable $e) {
            $noCli && CacheService::delete($md5Token);
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 402);
        }

        //Nhận thông tin quản trị viên
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo || !$adminInfo->id) {
            $noCli && CacheService::delete($md5Token);
            throw new AuthException('Trạng thái đăng nhập sai,Vui lòng đăng nhập lại', [], 402);
        }

        $adminInfo->type = $type;
        return $adminInfo->hidden(['password', 'ip', 'status']);
    }

    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function wechatAuth()
    {
        /** @var OAuth $oauth */        $oauth = app()->make(OAuth::class);
        $original = $oauth->oauth(null, ['open' => true]);
        if (!isset($original['unionid'])) {
            throw new AuthException('unionidkhông tồn tại');
        }
        /** @var WechatUserServices $userService */        $userService = app()->make(WechatUserServices::class);
        $uid = $userService->value(['unionid' => $original['unionid']], 'uid');
        if (!$uid) {
            throw new AuthException('Không lấy được UID Khách hàng');
        }
        $kefuInfo = $this->dao->get(['uid' => $uid]);
        if (!$kefuInfo) {
            throw new AuthException('Dịch vụ khách hàng không tồn tại');
        }
        if (!$kefuInfo->status) {
            throw new AuthException('Bạn đã bị cấm đăng nhập, vui lòng liên hệ với quản trị viên');
        }
        $token = $this->createToken($kefuInfo->id, 'kefu');
        $kefuInfo->update_time = time();
        $kefuInfo->ip = request()->ip();
        $kefuInfo->save();
        return [
            'token' => $token['token'],
            'exp_time' => $token['params']['exp'],
            'kefuInfo' => $kefuInfo->hidden(['password', 'ip', 'update_time', 'add_time', 'status', 'mer_id', 'customer', 'notify'])->toArray()
        ];
    }

    /**
     * Kiểm tra xem có ai quét và đăng nhập không
     * @param string $key
     * @return array|int[]
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function scanLogin(string $key)
    {
        $hasKey = CacheService::has($key);
        if ($hasKey === false) {
            $status = 0;//Không cần phải làm mới mã QR
        } else {
            $keyValue = CacheService::get($key);
            if ($keyValue === '0') {
                $status = 1;//Đang quét
                $kefuInfo = $this->dao->get(['uniqid' => $key], ['account', 'uniqid']);
                if ($kefuInfo) {
                    $tokenInfo = $this->authLogin($kefuInfo->account);
                    $tokenInfo['status'] = 3;
                    $kefuInfo->uniqid = '';
                    $kefuInfo->save();
                    CacheService::delete($key);
                    return $tokenInfo;
                }
            } else {
                $status = 2;//Không quét
            }
        }
        return ['status' => $status];
    }
}
