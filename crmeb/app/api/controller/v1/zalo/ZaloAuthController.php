<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\api\controller\v1\zalo;

use app\Request;
use app\services\zalo\ZaloAuthServices;
use app\services\message\notice\SmsService;
use app\services\user\LoginServices;
use crmeb\services\CacheService;
use app\api\validate\user\RegisterValidates;
use think\exception\ValidateException;
use think\facade\Config;

/**
 * Xác thực người dùng từ Zalo Mini App
 *
 * Endpoints:
 *  POST /api/zalo/auth           - Đăng nhập bằng Zalo access_token (không cần token CRMEB)
 *  POST /api/zalo/bind_phone     - Gắn số điện thoại (yêu cầu đăng nhập CRMEB trước)
 *
 * Class ZaloAuthController
 * @package app\api\controller\v1\zalo
 */
class ZaloAuthController
{
    protected ZaloAuthServices $services;

    public function __construct(ZaloAuthServices $services)
    {
        $this->services = $services;
    }

    /**
     * Đăng nhập Zalo Mini App
     *
     * Request (POST /api/zalo/auth):
     *   access_token  string  required  - Token lấy từ Zalo Mini App SDK (za.getAccessToken)
     *   spread        int     optional  - UID người giới thiệu
     *   source        string  optional  - Nguồn đăng nhập (ví dụ: fchan)
     *   phone         string  optional  - Số điện thoại đã xác thực từ client/luồng tích hợp
     *
     * Response 200:
     *   token         string  - JWT dùng cho các request sau
     *   expires_time  int     - Unix timestamp hết hạn token
     *   userInfo      object  - Thông tin cơ bản người dùng {uid, nickname, avatar, phone}
     *
     * @param Request $request
     * @return mixed
     */
    public function auth(Request $request)
    {
        [$accessToken, $spread, $source, $phone] = $request->postMore([
            ['access_token', ''],
            ['spread', 0],
            ['source', 'fchan'],
            ['phone', ''],
        ], true);

        if (empty($accessToken)) {
            return app('json')->fail('Thiếu access_token từ Zalo');
        }

        $result = $this->services->authLogin(
            trim($accessToken),
            (int)$spread,
            trim((string)$source),
            trim((string)$phone)
        );
        return app('json')->success('Đăng nhập thành công', $result);
    }

    /**
     * Gắn số điện thoại cho tài khoản Zalo
     *
     * Request (POST /api/zalo/bind_phone):
     *   phone    string  required  - Số điện thoại
     *   captcha  string  required  - Mã OTP đã gửi
     *
     * Header yêu cầu: Authorization: Bearer {token}
     *
     * @param Request $request
     * @return mixed
     */
    public function bindPhone(Request $request)
    {
        [$phone, $captcha] = $request->postMore([
            ['phone', ''],
            ['captcha', ''],
        ], true);

        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }

        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode) {
            return app('json')->fail('Mã xác minh không tồn tại, vui lòng gửi lại');
        }
        if (substr((string)$verifyCode, 0, 6) !== $captcha) {
            return app('json')->fail('Mã xác minh không đúng');
        }

        $uid = (int)$request->uid();
        $this->services->bindPhone($uid, $phone);
        CacheService::delete('code_' . $phone);

        return app('json')->success('Gắn số điện thoại thành công');
    }

    /**
     * Gắn số điện thoại trực tiếp từ Zalo (không cần SMS OTP)
     *
     * Luồng:
     *  1. Mini App gọi getPhoneNumber() → nhận phone_token
     *  2. Mini App gọi getAccessToken()  → nhận access_token
     *  3. POST /api/zalo/bind_phone_direct {access_token, phone_token}
     *  4. Backend decode phone_token qua Zalo Graph API → lấy số thực → gắn vào tài khoản
     *
     * Zalo đã xác thực số điện thoại → không cần thêm SMS OTP phía server.
     *
     * Request (POST /api/zalo/bind_phone_direct):
     *   access_token string required  - từ getAccessToken()
     *   phone_token  string required  - từ getPhoneNumber()
     *
     * Header: Authorization: Bearer {crmeb_token}
     *
     * @param Request $request
     * @return mixed
     */
    public function bindPhoneDirect(Request $request)
    {
        [$accessToken, $phoneToken] = $request->postMore([
            ['access_token', ''],
            ['phone_token', ''],
        ], true);

        if (empty($accessToken) || empty($phoneToken)) {
            return app('json')->fail('Thiếu access_token hoặc phone_token');
        }

        try {
            $phone = $this->services->fetchPhoneFromToken(trim($accessToken), trim($phoneToken));
        } catch (\crmeb\exceptions\ApiException $e) {
            return app('json')->fail($e->getMessage());
        } catch (\Throwable $e) {
            return app('json')->fail('Không lấy được số điện thoại từ Zalo');
        }

        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }

        $uid = (int)$request->uid();
        $this->services->bindPhone($uid, $phone);

        return app('json')->success('Gắn số điện thoại thành công', ['phone' => $phone]);
    }

    /**
     * Gửi OTP để gắn số điện thoại sau đăng nhập Zalo
     *
     * Khác với /register/verify:
     * - Không yêu cầu key/captcha slider phía web.
     * - Chỉ dùng cho user đã có token CRMEB (đã đăng nhập).
     *
     * Request (POST /api/zalo/send_bind_otp):
     *   phone string required
     *
     * @param Request $request
     * @param SmsService $smsService
     * @param LoginServices $loginServices
     * @return mixed
     */
    public function sendBindOtp(Request $request, SmsService $smsService, LoginServices $loginServices)
    {
        [$phone] = $request->postMore([
            ['phone', ''],
        ], true);

        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }

        // Giới hạn gửi giống luồng register/verify để tránh spam.
        $maxMinuteCountKey = 'sms.minute.' . $phone . date('YmdHi');
        $minuteCount = (int)(CacheService::get($maxMinuteCountKey) ?? 0);
        $maxMinuteCount = (int)Config::get('sms.maxMinuteCount', 5);
        if ($minuteCount > $maxMinuteCount) {
            return app('json')->fail('Số tin nhắn tối đa được gửi mỗi phút từ cùng một số điện thoại di động' . $maxMinuteCount . 'dải');
        }

        $maxPhoneCountKey = 'sms.phone.' . $phone . '.' . date('Ymd');
        $phoneCount = (int)(CacheService::get($maxPhoneCountKey) ?? 0);
        $maxPhoneCount = (int)Config::get('sms.maxPhoneCount', 20);
        if ($phoneCount > $maxPhoneCount) {
            return app('json')->fail('Số lượng tin nhắn tối đa được gửi đến cùng một số điện thoại di động mỗi ngày' . $maxPhoneCount . 'dải');
        }

        $maxIpCountKey = 'sms.ip.' . app()->request->ip() . '.' . date('Ymd');
        $ipCount = (int)(CacheService::get($maxIpCountKey) ?? 0);
        $maxIpCount = (int)Config::get('sms.maxIpCount', 50);
        if ($ipCount > $maxIpCount) {
            return app('json')->fail('Cùng một IP có thể gửi nhiều nhất mỗi ngày' . $maxIpCount . 'dải');
        }

        $time = (int)sys_config('verify_expire_time', 1);
        $smsCode = $loginServices->verify($smsService, $phone, '', $time);
        if (!$smsCode) {
            return app('json')->fail('Không gửi được mã xác minh');
        }

        CacheService::set('code_' . $phone, $smsCode, $time * 60);
        CacheService::set($maxMinuteCountKey, $minuteCount + 1, 61);
        CacheService::set($maxPhoneCountKey, $phoneCount + 1, 86401);
        CacheService::set($maxIpCountKey, $ipCount + 1, 86401);

        return app('json')->success('Mã xác minh đã được gửi thành công');
    }
}
