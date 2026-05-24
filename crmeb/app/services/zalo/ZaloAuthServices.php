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
use app\services\user\UserLabelCateServices;
use app\services\user\UserLabelRelationServices;
use app\services\user\UserLabelServices;
use app\services\user\UserServices;
use crmeb\exceptions\ApiException;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Dịch vụ xác thực Zalo Mini App
 *
 * Luồng đăng nhập:
 *  1. Zalo Mini App gọi getAccessToken() để lấy access_token của Khách hàng
 *  2. Gửi access_token đó lên CRMEB: POST /api/zalo/auth
 *  3. Service này xác thực với Zalo Open API để lấy thông tin Khách hàng
 *  4. Tạo hoặc tìm user trong CRMEB, trả về JWT token
 *
 * Bảng sử dụng:
 *  - eb_user          : thông tin Khách hàng chính
 *  - eb_wechat_user   : liên kết social (user_type = 'zalo')
 *
 * Class ZaloAuthServices
 * @package app\services\zalo
 */class ZaloAuthServices extends BaseServices
{
    /** Zalo Open API endpoint lấy thông tin user */    const ZALO_GRAPH_API = 'https://graph.zalo.me/v2.0/me';

    /** user_type lưu trong eb_wechat_user */    const USER_TYPE = 'zalo';
    const DEFAULT_SOURCE = 'fchan';
    const SOURCE_LABEL_CATE_NAME = 'Nguồn đăng nhập miniapp';

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
     * @param string $source        Nguồn đăng nhập (ví dụ: fchan)
     * @param string $phone         Số điện thoại đã xác thực từ luồng tích hợp
     * @return array{token:string, expires_time:int, userInfo:array}
     * @throws ApiException
     */    public function authLogin(string $accessToken, int $spread = 0, string $source = self::DEFAULT_SOURCE, string $phone = ''): array
    {
        $zaloUser = $this->fetchZaloUserInfo($accessToken);
        $openid = $zaloUser['openid'];
        $source = $this->normalizeSource($source);
        $phone = $this->normalizePhone($phone);

        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $user = $this->resolveUserByPhoneOrMapping($phone, $openid);

        if ($user) {
            if (!(int)$user['status']) {
                throw new ApiException('Tài khoản bị khóa, vui lòng liên hệ quản trị viên');
            }
            $this->limitedUpdateExistingUser((int)$user['uid'], $zaloUser);
            if ($phone !== '' && empty($user['phone'])) {
                $userServices->update((int)$user['uid'], ['phone' => $phone], 'uid');
            }
        } else {
            $user = $this->createZaloUser($zaloUser, $spread, $phone);
        }
        $this->syncZaloMapping((int)$user['uid'], $zaloUser);
        try {
            $this->attachSourceTag((int)$user['uid'], $source);
        } catch (\Throwable $e) {
            // Tag nguồn không quan trọng với flow login — ghi log rồi bỏ qua.
            Log::warning('[ZaloAuth] attachSourceTag failed: ' . $e->getMessage());
        }
        $user = $userServices->get((int)$user['uid']);

        /** @var LoginServices $loginServices */        $loginServices = app()->make(LoginServices::class);
        $token = $loginServices->createToken((int)$user['uid'], 'api');
        if (!$token) {
            throw new ApiException('Đăng nhập không thành công, vui lòng thử lại');
        }

        return [
            'token'        => $token['token'],
            'expires_time' => $token['params']['exp'],
            'source'       => $source,
            'userInfo'     => [
                'uid'       => $user['uid'],
                'nickname'  => $user['nickname'] ?? $zaloUser['nickname'],
                'avatar'    => $user['avatar'] ?? $zaloUser['avatar'],
                'phone'     => $user['phone'] ?? '',
                'user_type' => self::USER_TYPE,
            ],
        ];
    }

    /**
     * Lấy số điện thoại từ phone_token (Zalo getPhoneNumber flow)
     *
     * Endpoint: GET https://graph.zalo.me/v2.0/me?fields=number&code={phone_token}
     * Headers (từ 01/01/2024 Zalo yêu cầu appsecret_proof thay vì secret_key raw):
     *   access_token:    {user_access_token}
     *   appsecret_proof: HMAC-SHA256(access_token, app_secret)
     * Response: {"data":{"number":"849..."},"error":0,"message":"Success"}
     *
     * @param string $accessToken  access_token từ Zalo Mini App SDK
     * @param string $phoneToken   token từ getPhoneNumber()
     * @return string  Số điện thoại đã chuẩn hoá (vd: "0901234567")
     * @throws ApiException
     */    public function fetchPhoneFromToken(string $accessToken, string $phoneToken): string
    {
        $secretKey = (string)sys_config('zalo_app_secret', '');
        if ($secretKey === '') {
            throw new ApiException('Chưa cấu hình Zalo App Secret. Vui lòng vào Admin → Cài đặt → Zalo và điền zalo_app_secret.');
        }

        // Zalo Graph API yêu cầu appsecret_proof (HMAC-SHA256) từ 01/01/2024,
        // không còn chấp nhận secret_key dạng raw — nhất quán với fetchZaloUserInfo.
        $appsecretProof = hash_hmac('sha256', $accessToken, $secretKey);

        try {
            $response = HttpService::getRequest(
                self::ZALO_GRAPH_API,
                ['fields' => 'number', 'code' => $phoneToken],
                [
                    'access_token: '    . $accessToken,
                    'appsecret_proof: ' . $appsecretProof,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('[ZaloAuth] fetchPhoneFromToken – lỗi kết nối: ' . $e->getMessage());
            throw new ApiException('Không thể kết nối Zalo API để lấy số điện thoại');
        }

        if (!$response) {
            throw new ApiException('Zalo API không trả về dữ liệu số điện thoại');
        }

        $data = json_decode($response, true);
        Log::info('[ZaloAuth] fetchPhoneFromToken response: ' . json_encode($data));

        $errorCode = (int)($data['error'] ?? -1);
        $phoneNumber = (string)($data['data']['number'] ?? '');
        if ($errorCode !== 0) {
            $errMsg = (string)($data['message'] ?? 'Không lấy được số điện thoại từ Zalo');
            Log::error('[ZaloAuth] fetchPhoneFromToken error: ' . $errMsg . ' | Raw: ' . json_encode($data));
            throw new ApiException($errMsg);
        }
        if ($phoneNumber === '') {
            Log::error('[ZaloAuth] fetchPhoneFromToken missing number | Raw: ' . json_encode($data));
            throw new ApiException(
                'Zalo chưa trả số điện thoại. Vui lòng cấp quyền số điện thoại (scope.userPhonenumber) trên Mini App và thử lại.'
            );
        }

        // Zalo trả về dạng "849xxxxxxxx" (quốc tế) → chuẩn hoá về "09xxxxxxxx"
        $raw = preg_replace('/\D+/', '', $phoneNumber);
        if (str_starts_with($raw, '84') && strlen($raw) >= 10) {
            $raw = '0' . substr($raw, 2);
        }
        return $raw;
    }

    /**
     * Lấy tọa độ từ location token (Zalo getLocation flow)
     *
     * Endpoint: GET https://graph.zalo.me/v2.0/me?fields=location&code={location_token}
     *
     * @param string $accessToken   access_token từ Zalo Mini App SDK
     * @param string $locationToken  token từ getLocation()
     * @return array{lat: float, lng: float}
     * @throws ApiException
     */    public function fetchLocationFromToken(string $accessToken, string $locationToken): array
    {
        $secretKey = (string)sys_config('zalo_app_secret', '');
        if ($secretKey === '') {
            throw new ApiException('Chưa cấu hình Zalo App Secret. Vui lòng vào Admin → Cài đặt → Zalo và điền zalo_app_secret.');
        }

        $appsecretProof = hash_hmac('sha256', $accessToken, $secretKey);

        try {
            $response = HttpService::getRequest(
                self::ZALO_GRAPH_API,
                ['fields' => 'location', 'code' => $locationToken],
                [
                    'access_token: '    . $accessToken,
                    'appsecret_proof: ' . $appsecretProof,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('[ZaloAuth] fetchLocationFromToken – lỗi kết nối: ' . $e->getMessage());
            throw new ApiException('Không thể kết nối Zalo API để lấy vị trí');
        }

        if (!$response) {
            throw new ApiException('Zalo API không trả về dữ liệu vị trí');
        }

        $data = json_decode($response, true);
        Log::info('[ZaloAuth] fetchLocationFromToken response: ' . json_encode($data));

        $errorCode = (int)($data['error'] ?? -1);
        if ($errorCode !== 0) {
            $errMsg = (string)($data['message'] ?? 'Không lấy được vị trí từ Zalo');
            Log::error('[ZaloAuth] fetchLocationFromToken error: ' . $errMsg . ' | Raw: ' . json_encode($data));
            throw new ApiException($errMsg);
        }

        $payload = $data['data'] ?? [];
        if (isset($payload['location']) && is_array($payload['location'])) {
            $payload = $payload['location'];
        }

        $lat = (float)($payload['latitude'] ?? $payload['lat'] ?? 0);
        $lng = (float)($payload['longitude'] ?? $payload['lng'] ?? 0);
        if ($lat === 0.0 && $lng === 0.0) {
            throw new ApiException('Zalo chưa trả tọa độ vị trí. Vui lòng cấp quyền vị trí và thử lại.');
        }

        return ['lat' => $lat, 'lng' => $lng];
    }

    /**
     * Gắn số điện thoại vào tài khoản Zalo đang đăng nhập
     *
     * @param int    $uid   UID Khách hàng hiện tại
     * @param string $phone Số điện thoại cần gắn
     * @return bool
     * @throws ApiException
     */    public function bindPhone(int $uid, string $phone): bool
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);

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
     * Gọi Zalo Graph API để lấy thông tin Khách hàng
     *
     * @param string $accessToken
     * @return array{openid:string, nickname:string, avatar:string}
     * @throws ApiException
     */    private function fetchZaloUserInfo(string $accessToken): array
    {
        $appSecret = (string)sys_config('zalo_app_secret', '');

        // Zalo Graph API v2.0: access_token và appsecret_proof truyền qua header;
        // fields truyền qua query param.
        // appsecret_proof chỉ thêm khi zalo_app_secret đã được cấu hình trong admin.
        $headers = ['access_token: ' . $accessToken];
        if ($appSecret !== '') {
            $headers[] = 'appsecret_proof: ' . hash_hmac('sha256', $accessToken, $appSecret);
        }

        try {
            $response = HttpService::getRequest(
                self::ZALO_GRAPH_API,
                ['fields' => 'id,name,picture'],
                $headers
            );
        } catch (\Throwable $e) {
            Log::error('[ZaloAuth] Lỗi kết nối Zalo API: ' . $e->getMessage());
            throw new ApiException('Không thể kết nối Zalo API, vui lòng thử lại');
        }

        if (!$response) {
            throw new ApiException('Không nhận được phản hồi từ Zalo API');
        }

        $data = json_decode($response, true);
        Log::info('[ZaloAuth] Zalo Graph API response: ' . json_encode($data));

        $hasId = isset($data['id']) && (string)$data['id'] !== '';
        $errorCode = $data['error'] ?? null;
        $hasBizError = $errorCode !== null && (int)$errorCode !== 0;

        if (!$hasId || $hasBizError) {
            $errMsg = is_array($data['error'])
                ? ($data['error']['message'] ?? 'Access token Zalo không hợp lệ hoặc đã hết hạn')
                : ($data['message'] ?? 'Access token Zalo không hợp lệ hoặc đã hết hạn');
            Log::error('[ZaloAuth] Lỗi từ Zalo API: ' . $errMsg . ' | Raw: ' . json_encode($data) . ' | Token: ' . substr($accessToken, 0, 10) . '...');
            throw new ApiException($errMsg);
        }

        return [
            'openid'   => (string)$data['id'],
            'nickname' => $data['name'] ?? ('Zalo_' . substr((string)$data['id'], -6)),
            'avatar'   => $data['picture']['data']['url'] ?? sys_config('h5_avatar'),
        ];
    }

    /**
     * Tạo Khách hàng CRMEB mới từ thông tin Zalo
     *
     * @param array{openid:string, nickname:string, avatar:string} $zaloUser
     * @param int $spread UID người giới thiệu
     * @return object  Bản ghi eb_user vừa tạo
     * @throws ApiException
     */    private function createZaloUser(array $zaloUser, int $spread, string $phone = ''): object
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);

        $data = [
            'account'   => $phone ?: ('zalo_' . $zaloUser['openid']),
            'pwd'       => md5(uniqid('zalo_', true)),
            'nickname'  => $zaloUser['nickname'],
            'avatar'    => $zaloUser['avatar'],
            'phone'     => $phone,
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

        // Tặng thưởng Khách hàng mới (nếu có cấu hình)
        $userServices->rewardNewUser((int)$user->uid);

        event('UserRegisterListener', [$spread, self::USER_TYPE, $data['nickname'], $user->uid, 1]);
        event('CustomEventListener', ['user_register', [
            'uid'       => $user->uid,
            'nickname'  => $data['nickname'],
            'phone'     => $data['phone'] ?: '',
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

    /**
     * Ưu tiên tra theo phone trong eb_user, sau đó fallback mapping social.
     * @param string $phone
     * @param string $openid
     * @return array|\think\Model|null
     */    private function resolveUserByPhoneOrMapping(string $phone, string $openid)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        if ($phone !== '') {
            $user = $userServices->getOne(['phone' => $phone, 'is_del' => 0]);
            if ($user) return $user;
        }

        $wechatUser = $this->dao->getOne([
            'openid' => $openid,
            'user_type' => self::USER_TYPE,
        ]);
        if ($wechatUser) {
            return $userServices->getOne(['uid' => (int)$wechatUser['uid'], 'is_del' => 0]);
        }
        return null;
    }

    /**
     * Cập nhật giới hạn cho user trùng số điện thoại.
     */    /**
     * Nickname fallback do Graph /me không có name (chưa xin scope.userInfo) — cho phép ghi đè khi đã có tên thật.
     */    private function isZaloPlaceholderNickname(string $nickname): bool
    {
        $nickname = trim($nickname);

        return $nickname !== '' && (bool)preg_match('/^Zalo_\d{4,}$/', $nickname);
    }

    /**
     * Avatar mặc định CRMEB — nên cập nhật khi Zalo đã trả ảnh thật.
     */    private function isLikelyDefaultAvatar(string $avatar): bool
    {
        $avatar = trim($avatar);

        return $avatar === '' || strpos($avatar, 'default_avatar') !== false;
    }

    private function limitedUpdateExistingUser(int $uid, array $zaloUser): void
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $currentUser = $userServices->get($uid);
        if (!$currentUser) {
            throw new ApiException('Người dùng không tồn tại');
        }

        $updateData = [
            'last_time' => time(),
            'last_ip' => app('request')->ip(),
        ];
        $currentNick = (string)($currentUser['nickname'] ?? '');
        $zaloNick = (string)($zaloUser['nickname'] ?? '');
        $nickEmpty = $currentNick === '';
        $nickPlaceholder = $this->isZaloPlaceholderNickname($currentNick);
        $zaloNickUsable = $zaloNick !== '' && !$this->isZaloPlaceholderNickname($zaloNick);
        if ($zaloNickUsable && ($nickEmpty || $nickPlaceholder)) {
            $updateData['nickname'] = $zaloNick;
        }

        $currentAvatar = (string)($currentUser['avatar'] ?? '');
        $zaloAvatar = (string)($zaloUser['avatar'] ?? '');
        if ($zaloAvatar !== '' && ($this->isLikelyDefaultAvatar($currentAvatar) || $currentAvatar === '')) {
            $updateData['avatar'] = $zaloAvatar;
        } elseif (empty($currentUser['avatar']) && $zaloAvatar !== '') {
            $updateData['avatar'] = $zaloAvatar;
        }
        $userServices->update($uid, $updateData, 'uid');
    }

    /**
     * Đồng bộ mapping zalo trong eb_wechat_user theo uid hiện tại.
     */    private function syncZaloMapping(int $uid, array $zaloUser): void
    {
        $wechatUser = $this->dao->getOne([
            'openid' => $zaloUser['openid'],
            'user_type' => self::USER_TYPE,
        ]);
        $syncData = [
            'uid' => $uid,
            'nickname' => $zaloUser['nickname'],
            'headimgurl' => $zaloUser['avatar'],
        ];

        if ($wechatUser) {
            $this->dao->update((int)$wechatUser['id'], $syncData);
        } else {
            $this->dao->save($syncData + [
                'openid' => $zaloUser['openid'],
                'user_type' => self::USER_TYPE,
                'add_time' => time(),
            ]);
        }
    }

    /**
     * Gắn tag nguồn đăng nhập cho user.
     */    private function attachSourceTag(int $uid, string $source): void
    {
        /** @var UserLabelServices $labelServices */        $labelServices = app()->make(UserLabelServices::class);
        /** @var UserLabelRelationServices $relationServices */        $relationServices = app()->make(UserLabelRelationServices::class);

        $labelCateId = $this->getOrCreateSourceLabelCateId();
        $labelName = 'source:' . $source;
        $labelId = (int)$labelServices->value(['label_name' => $labelName, 'label_cate' => $labelCateId], 'id');
        if (!$labelId) {
            $labelServices->save(0, [
                'label_name' => $labelName,
                'label_cate' => $labelCateId,
            ]);
            $labelId = (int)$labelServices->value(['label_name' => $labelName, 'label_cate' => $labelCateId], 'id');
        }
        if (!$labelId) return;

        $currentLabelIds = array_map('intval', $relationServices->getUserLabels($uid));
        if (in_array($labelId, $currentLabelIds, true)) {
            return;
        }
        $relationServices->setUserLabel([$uid], [$labelId], 1);
    }

    /**
     * Lấy hoặc tạo category chứa source label.
     */    private function getOrCreateSourceLabelCateId(): int
    {
        /** @var UserLabelCateServices $cateServices */        $cateServices = app()->make(UserLabelCateServices::class);
        $cateId = (int)$cateServices->value(['type' => 0, 'name' => self::SOURCE_LABEL_CATE_NAME], 'id');
        if ($cateId) return $cateId;

        $cateServices->save([
            'type' => 0,
            'name' => self::SOURCE_LABEL_CATE_NAME,
            'sort' => 0,
        ]);
        return (int)$cateServices->value(['type' => 0, 'name' => self::SOURCE_LABEL_CATE_NAME], 'id');
    }

    /**
     * Chuẩn hóa số điện thoại đầu vào.
     */    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        if ($phone === '') return '';
        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) return '';
        return strlen($digits) >= 8 ? $digits : '';
    }

    /**
     * Chuẩn hóa source để lưu tag.
     */    private function normalizeSource(string $source): string
    {
        $source = strtolower(trim($source));
        $source = preg_replace('/[^a-z0-9_\-]/', '', $source);
        return $source ?: self::DEFAULT_SOURCE;
    }
}
