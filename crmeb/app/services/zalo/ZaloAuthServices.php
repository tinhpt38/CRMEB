<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\services\zalo;

use app\dao\wechat\WechatUserDao;
use app\services\BaseServices;
use app\services\user\LoginServices;
use app\services\user\UserServices;
use crmeb\exceptions\ApiException;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Dịch vụ xác thực Zalo Mini App
 *
 * Luồng đăng nhập:
 *  1. Zalo Mini App gọi getAccessToken() để lấy access_token của người dùng
 *  2. Gửi access_token đó lên CRMEB: POST /api/zalo/auth
 *  3. Service này xác thực với Zalo Open API để lấy thông tin người dùng
 *  4. Tạo hoặc tìm user trong CRMEB, trả về JWT token
 *
 * Bảng sử dụng:
 *  - eb_user          : thông tin người dùng chính
 *  - eb_wechat_user   : liên kết social (user_type = 'zalo')
 *
 * Class ZaloAuthServices
 * @package app\services\zalo
 */
class ZaloAuthServices extends BaseServices
{
    /** Zalo Open API endpoint lấy thông tin user */
    const ZALO_GRAPH_API = 'https://graph.zalo.me/v2.0/me';

    /** user_type lưu trong eb_wechat_user */
    const USER_TYPE = 'zalo';

    public function __construct(WechatUserDao $dao)
    {
        $this->dao = $dao;
    }

    // -------------------------------------------------------------------------
    // Public methods
    // -------------------------------------------------------------------------

    /**
     * Đăng nhập / đăng ký qua Zalo access_token
     *
     * @param string $accessToken   access_token lấy từ Zalo Mini App SDK
     * @param int    $spread        UID người giới thiệu (tuỳ chọn)
     * @return array{token:string, expires_time:int, userInfo:array}
     * @throws ApiException
     */
    public function authLogin(string $accessToken, int $spread = 0): array
    {
        $zaloUser = $this->fetchZaloUserInfo($accessToken);
        $openid   = $zaloUser['openid'];

        // Tìm liên kết đã có trong eb_wechat_user
        $wechatUser = $this->dao->getOne([
            'openid'    => $openid,
            'user_type' => self::USER_TYPE,
        ]);

        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);

        if ($wechatUser) {
            $user = $userServices->get((int)$wechatUser['uid']);
            if (!$user) {
                throw new ApiException('Dữ liệu người dùng bị lỗi, vui lòng liên hệ hỗ trợ');
            }
            if (!$user['status']) {
                throw new ApiException('Tài khoản bị khóa, vui lòng liên hệ quản trị viên');
            }

            // Cập nhật nickname / avatar mỗi lần đăng nhập
            $userServices->update((int)$user['uid'], [
                'nickname'  => $zaloUser['nickname'],
                'avatar'    => $zaloUser['avatar'],
                'last_time' => time(),
                'last_ip'   => app('request')->ip(),
            ], 'uid');

            // Cập nhật thông tin liên kết
            $this->dao->update($wechatUser['id'], [
                'nickname'   => $zaloUser['nickname'],
                'headimgurl' => $zaloUser['avatar'],
            ]);
        } else {
            // Chưa có → tạo mới user CRMEB
            $user = $this->createZaloUser($zaloUser, $spread);

            // Lưu liên kết social vào eb_wechat_user
            $this->dao->save([
                'uid'        => $user->uid,
                'openid'     => $openid,
                'user_type'  => self::USER_TYPE,
                'nickname'   => $zaloUser['nickname'],
                'headimgurl' => $zaloUser['avatar'],
                'add_time'   => time(),
            ]);
        }

        /** @var LoginServices $loginServices */
        $loginServices = app()->make(LoginServices::class);
        $token = $loginServices->createToken((int)$user['uid'], 'api');
        if (!$token) {
            throw new ApiException('Đăng nhập không thành công, vui lòng thử lại');
        }

        return [
            'token'        => $token['token'],
            'expires_time' => $token['params']['exp'],
            'userInfo'     => [
                'uid'       => $user['uid'],
                'nickname'  => $zaloUser['nickname'],
                'avatar'    => $zaloUser['avatar'],
                'phone'     => $user['phone'] ?? '',
                'user_type' => self::USER_TYPE,
            ],
        ];
    }

    /**
     * Gắn số điện thoại vào tài khoản Zalo đang đăng nhập
     *
     * @param int    $uid   UID người dùng hiện tại
     * @param string $phone Số điện thoại cần gắn
     * @return bool
     * @throws ApiException
     */
    public function bindPhone(int $uid, string $phone): bool
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);

        $user = $userServices->get($uid);
        if (!$user) {
            throw new ApiException('Người dùng không tồn tại');
        }
        if (!empty($user['phone'])) {
            throw new ApiException('Tài khoản đã được gắn số điện thoại');
        }

        // Kiểm tra số điện thoại đã được dùng bởi tài khoản khác chưa
        $exist = $userServices->getOne([['phone', '=', $phone], ['is_del', '=', 0]]);
        if ($exist && (int)$exist['uid'] !== $uid) {
            throw new ApiException('Số điện thoại này đã được đăng ký bởi tài khoản khác');
        }

        return (bool)$userServices->update($uid, [
            'phone'   => $phone,
            'account' => $phone,
        ], 'uid');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Gọi Zalo Graph API để lấy thông tin người dùng
     *
     * @param string $accessToken
     * @return array{openid:string, nickname:string, avatar:string}
     * @throws ApiException
     */
    private function fetchZaloUserInfo(string $accessToken): array
    {
        $appSecret      = (string)sys_config('zalo_app_secret', '');
        $appsecretProof = hash_hmac('sha256', $accessToken, $appSecret);

        // access_token + appsecret_proof bắt buộc trong header từ 01/01/2024
        try {
            $response = HttpService::getRequest(
                self::ZALO_GRAPH_API,
                ['fields' => 'id,name,picture'],
                [
                    'access_token: '    . $accessToken,
                    'appsecret_proof: ' . $appsecretProof,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('[ZaloAuth] Lỗi kết nối Zalo API: ' . $e->getMessage());
            throw new ApiException('Không thể kết nối Zalo API, vui lòng thử lại');
        }

        if (!$response) {
            throw new ApiException('Không nhận được phản hồi từ Zalo API');
        }

        $data = json_decode($response, true);

        if (!isset($data['id']) || isset($data['error'])) {
            $errMsg = $data['message']
                ?? ($data['error']['message'] ?? 'Access token Zalo không hợp lệ hoặc đã hết hạn');
            Log::error('[ZaloAuth] Lỗi từ Zalo API: ' . $errMsg . ' | Token: ' . substr($accessToken, 0, 10) . '...');
            throw new ApiException($errMsg);
        }

        return [
            'openid'   => (string)$data['id'],
            'nickname' => $data['name'] ?? ('Zalo_' . substr((string)$data['id'], -6)),
            'avatar'   => $data['picture']['data']['url'] ?? sys_config('h5_avatar'),
        ];
    }

    /**
     * Tạo người dùng CRMEB mới từ thông tin Zalo
     *
     * @param array{openid:string, nickname:string, avatar:string} $zaloUser
     * @param int $spread UID người giới thiệu
     * @return object  Bản ghi eb_user vừa tạo
     * @throws ApiException
     */
    private function createZaloUser(array $zaloUser, int $spread): object
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);

        $data = [
            'account'   => 'zalo_' . $zaloUser['openid'],
            'pwd'       => md5(uniqid('zalo_', true)),
            'nickname'  => $zaloUser['nickname'],
            'avatar'    => $zaloUser['avatar'],
            'phone'     => '',
            'user_type' => self::USER_TYPE,
            'add_time'  => time(),
            'add_ip'    => app('request')->ip(),
            'last_time' => time(),
            'last_ip'   => app('request')->ip(),
            'status'    => 1,
        ];

        if ($spread > 0) {
            $spreadInfo = $userServices->get($spread);
            if ($spreadInfo) {
                $data['spread_uid']  = $spread;
                $data['spread_time'] = time();
                $data['agent_id']    = $spreadInfo['agent_id']    ?? 0;
                $data['division_id'] = $spreadInfo['division_id'] ?? 0;
                $data['staff_id']    = $spreadInfo['staff_id']    ?? 0;
            }
        }

        $user = $userServices->save($data);
        if (!$user) {
            throw new ApiException('Không thể tạo tài khoản, vui lòng thử lại');
        }

        // Tặng thưởng người dùng mới (nếu có cấu hình)
        $userServices->rewardNewUser((int)$user->uid);

        event('UserRegisterListener', [$spread, self::USER_TYPE, $data['nickname'], $user->uid, 1]);
        event('CustomEventListener', ['user_register', [
            'uid'       => $user->uid,
            'nickname'  => $data['nickname'],
            'phone'     => '',
            'add_time'  => date('Y-m-d H:i:s'),
            'user_type' => self::USER_TYPE,
        ]]);

        if ($spread > 0) {
            event('NoticeListener', [
                ['spreadUid' => $spread, 'user_type' => self::USER_TYPE, 'nickname' => $data['nickname']],
                'bind_spread_uid',
            ]);
            event('CustomEventListener', ['user_spread', [
                'uid'         => $user->uid,
                'nickname'    => $data['nickname'],
                'spread_uid'  => $spread,
                'spread_time' => date('Y-m-d H:i:s'),
                'user_type'   => self::USER_TYPE,
            ]]);
        }

        return $user;
    }
}
