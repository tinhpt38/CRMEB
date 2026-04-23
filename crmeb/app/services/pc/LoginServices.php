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

namespace app\services\pc;


use app\services\BaseServices;
use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\oauth\OAuth;

class LoginServices extends BaseServices
{
    /**
     * Quét mã QR để đăng nhập
     * @param string $key
     * @return array|int[]
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function scanLogin(string $key)
    {
        $hasKey = CacheService::has($key);
        if ($hasKey === false) {
            $status = 0;//Không cần phải làm mới mã QR
        } else {
            $keyValue = CacheService::get($key);
            if ($keyValue === 0) {
                $status = 1;//Đang quét
                /** @var UserServices $user */
                $user = app()->make(UserServices::class);
                $userInfo = $user->get(['uniqid' => $key], ['account', 'uniqid']);
                if ($userInfo) {
                    $tokenInfo = $this->authLogin($userInfo->account);
                    $tokenInfo['status'] = 3;
                    $userInfo->uniqid = '';
                    $userInfo->save();
                    CacheService::delete($key);
                    return $tokenInfo;
                }
            } else {
                $status = 2;//Không quét
            }
        }
        return ['status' => $status];
    }

    /**
     * Quét mã QR để đăng nhập
     * @param string $account
     * @param string|null $password
     * @return array
     */
    public function authLogin(string $account, string $password = null)
    {
        /** @var UserServices $user */
        $user = app()->make(UserServices::class);

        $userInfo = $user->get(['account' => $account]);
        if (!$userInfo) {
            throw new ApiException('Không có người dùng như vậy');
        }
        if ($password && !password_verify($password, $userInfo->password)) {
            throw new ApiException('Tài khoản hoặc mật khẩu không chính xác');
        }
        if (!$userInfo->status) {
            throw new ApiException('Bạn đã bị cấm đăng nhập, vui lòng liên hệ với quản trị viên');
        }
        $token = $this->createToken($userInfo->id, 'api');
        $userInfo->update_time = time();
        $userInfo->ip = request()->ip();
        $userInfo->save();
        return [
            'token' => $token['token'],
            'exp_time' => $token['params']['exp'],
            'userInfo' => $userInfo->hidden(['password', 'ip', 'update_time', 'add_time', 'status', 'mer_id', 'customer', 'notify'])->toArray()
        ];
    }


    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function wechatAuth()
    {
        /** @var OAuth $oauth */
        $oauth = app()->make(OAuth::class);
        $info = $oauth->oauth(null, ['open' => true]);

        if (!$info) {
            throw new ApiException('Ủy quyền không thành công');
        }
        $wechatInfo = $info->getOriginal();
        if (!isset($wechatInfo['unionid'])) {
            throw new ApiException('unionidkhông tồn tại');
        }
        if (!isset($wechatInfo['nickname'])) {
            $wechatInfo = $oauth->getUserInfo($wechatInfo['openid']);
            if (!isset($wechatInfo['nickname']))
                throw new ApiException('Ủy quyền không thành công');
            if (isset($wechatInfo['tagid_list']))
                $wechatInfo['tagid_list'] = implode(',', $wechatInfo['tagid_list']);
        } else {
            if (isset($wechatInfo['privilege'])) unset($wechatInfo['privilege']);
            /** @var WechatUserServices $wechatUser */
            $wechatUser = app()->make(WechatUserServices::class);
            if (!$wechatUser->getOne(['openid' => $wechatInfo['openid']])) {
                $wechatInfo['subscribe'] = 0;
            }
        }
        $wechatInfo['user_type'] = 'pc';
        $openid = $wechatInfo['openid'];
        /** @var WechatUserServices $wechatUserServices */
        $wechatUserServices = app()->make(WechatUserServices::class);
        $user = $wechatUserServices->getAuthUserInfo($openid, 'pc');
        $createData = [$openid, $wechatInfo, 0, 0, 'pc', 'pc'];
        if (!$user) {
            $user = $wechatUserServices->wechatOauthAfter($createData);
        } else {
            //Cập nhật thông tin người dùng
            $wechatUserServices->wechatUpdata([$user['uid'], $wechatInfo]);
        }
        $token = $this->createToken((int)$user->uid, 'api');
        return [
            'token' => $token['token'],
            'exp_time' => $token['params']['exp']
        ];
    }
}
