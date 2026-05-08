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
}
