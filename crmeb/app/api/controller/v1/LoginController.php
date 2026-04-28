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

namespace app\api\controller\v1;

use app\Request;
use app\services\message\notice\SmsService;
use app\services\wechat\WechatServices;
use think\facade\Config;
use crmeb\services\CacheService;
use app\services\user\LoginServices;
use think\exception\ValidateException;
use app\api\validate\user\RegisterValidates;

/**
 * Lớp ủy quyền chương trình mini WeChat
 * Class AuthController
 * @package app\api\controller
 */
class LoginController
{
    protected $services;

    /**
     * LoginController constructor.
     * @param LoginServices $services
     */
    public function __construct(LoginServices $services)
    {
        $this->services = $services;
    }

    /**
     * H5Đăng nhập tài khoản
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(Request $request)
    {
        [$account, $password, $spread, $agent_id] = $request->postMore([
            'account', 'password', 'spread', ['agent_id', 0]
        ], true);
        if (!$account || !$password) {
            return app('json')->fail('Vui lòng nhập số tài khoản và mật khẩu của bạn');
        }
        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return app('json')->fail('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
        }
        return app('json')->success('Đăng nhập thành công', $this->services->login($account, $password, $spread, $agent_id));
    }

    /**
     * Đăng xuất
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $key = trim(ltrim($request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return app('json')->success('Thoát thành công');
    }

    /**
     * Nhận và gửi mã xác minhkey
     * @return mixed
     */
    public function verifyCode()
    {
        $unique = password_hash(uniqid(true), PASSWORD_BCRYPT);
        CacheService::set('sms.key.' . $unique, 0, 300);
        $time = sys_config('verify_expire_time', 1);
        return app('json')->success(['key' => $unique, 'expire_time' => $time]);
    }

    /**
     * Nhận mã xác minh hình ảnh
     * @param Request $request
     * @return \think\Response
     */
    public function captcha(Request $request)
    {
        ob_clean();
        $rep = captcha();
        $key = app('session')->get('captcha.key');
        $uni = $request->get('key');
        if ($uni) {
            CacheService::set('sms.key.cap.' . $uni, $key, 300);
        }
        return $rep;
    }

    /**
     * Xác minh rằng mã xác minh là chính xác
     * @param $uni
     * @param string $code
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function checkCaptcha($uni, string $code): bool
    {
        $cacheName = 'sms.key.cap.' . $uni;
        if (!CacheService::has($cacheName)) {
            return false;
        }
        $key = CacheService::get($cacheName);
        $code = mb_strtolower($code, 'UTF-8');
        $res = password_verify($code, $key);
        if ($res) {
            CacheService::delete($cacheName);
        }
        return $res;
    }

    /**
     * Mã xác minh đã được gửi
     * @param Request $request
     * @param SmsService $services
     * @return mixed
     */
    public function verify(Request $request, SmsService $services)
    {
        [$phone, $type, $key, $captchaType, $captchaVerification] = $request->postMore([
            ['phone', 0],
            ['type', ''],
            ['key', ''],
            ['captchaType', ''],
            ['captchaVerification', ''],
        ], true);

        $keyName = 'sms.key.' . $key;
        if (!CacheService::has($keyName)) return app('json')->fail('Không gửi được mã xác minh,Vui lòng làm mới trang để lấy lại');

        // Hạn chế xác thực
        //Giới hạn gửi mã xác minh mỗi phút
        $maxMinuteCountKey = 'sms.minute.' . $phone . date('YmdHi');
        $minuteCount = 0;
        if (CacheService::has($maxMinuteCountKey)) {
            $minuteCount = CacheService::get($maxMinuteCountKey) ?? 0;
            $maxMinuteCount = Config::get('sms.maxMinuteCount', 5);
            if ($minuteCount > $maxMinuteCount) return app('json')->fail('Số tin nhắn tối đa được gửi mỗi phút từ cùng một số điện thoại di động' . $maxMinuteCount . 'dải');

        }

        // Giới hạn hàng ngày để gửi mã xác minh tới một điện thoại di động
        $maxPhoneCountKey = 'sms.phone.' . $phone . '.' . date('Ymd');
        $phoneCount = 0;
        if (CacheService::has($maxPhoneCountKey)) {
            $phoneCount = CacheService::get($maxPhoneCountKey) ?? 0;
            $maxPhoneCount = Config::get('sms.maxPhoneCount', 20);
            if ($phoneCount > $maxPhoneCount) return app('json')->fail('Số lượng tin nhắn tối đa được gửi đến cùng một số điện thoại di động mỗi ngày' . $maxPhoneCount . 'dải');

        }

        // Giới hạn hàng ngày để gửi mã xác minh tới một điện thoại di động
        $maxIpCountKey = 'sms.ip.' . app()->request->ip() . '.' . date('Ymd');
        $ipCount = 0;
        if (CacheService::has($maxIpCountKey)) {
            $ipCount = CacheService::get($maxIpCountKey) ?? 0;
            $maxIpCount = Config::get('sms.maxIpCount', 50);
            if ($ipCount > $maxIpCount) return app('json')->fail('Cùng một IP có thể gửi nhiều nhất mỗi ngày' . $maxIpCount . 'dải');

        }

        //Xác minh lần thứ hai
        try {
            aj_captcha_check_two($captchaType, $captchaVerification);
        } catch (\Throwable $e) {
            return app('json')->fail($e->getMessage());
        }

        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getMessage());
        }
        $time = sys_config('verify_expire_time', 1);
        $smsCode = $this->services->verify($services, $phone, $type, $time);
        if ($smsCode) {
            CacheService::set('code_' . $phone, $smsCode, $time * 60);
            CacheService::set($maxMinuteCountKey, (int)$minuteCount + 1, 61);
            CacheService::set($maxPhoneCountKey, (int)$phoneCount + 1, 86401);
            CacheService::set($maxIpCountKey, (int)$ipCount + 1, 86401);
            return app('json')->success('Mã xác minh đã được gửi thành công');
        } else {
            return app('json')->fail('Không gửi được mã xác minh');
        }

    }

    /**
     * H5Đăng ký người dùng mới
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function register(Request $request)
    {
        [$account, $captcha, $password, $spread] = $request->postMore([['account', ''], ['captcha', ''], ['password', ''], ['spread', 0]], true);
        try {
            validate(RegisterValidates::class)->scene('register')->check(['account' => $account, 'captcha' => $captcha, 'password' => $password]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return app('json')->fail('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
        }
        $verifyCode = CacheService::get('code_' . $account);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha)
            return app('json')->fail('Lỗi mã xác minh');
        if (md5($password) == md5('123456')) return app('json')->fail('Mật khẩu quá đơn giản, vui lòng nhập mật khẩu phức tạp hơn');

        $registerStatus = $this->services->register($account, $password, $spread, 'h5');
        if ($registerStatus) {
            return app('json')->success('Đăng ký thành công');
        }
        return app('json')->fail('Đăng ký không thành công');
    }

    /**
     * Thay đổi mật khẩu
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function reset(Request $request)
    {
        [$account, $captcha, $password] = $request->postMore([['account', ''], ['captcha', ''], ['password', '']], true);
        try {
            validate(RegisterValidates::class)->scene('register')->check(['account' => $account, 'captcha' => $captcha, 'password' => $password]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return app('json')->fail('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
        }
        $verifyCode = CacheService::get('code_' . $account);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha) {
            return app('json')->fail('Lỗi mã xác minh');
        }
        if ($password == '123456') return app('json')->fail('Mật khẩu quá đơn giản, vui lòng nhập mật khẩu phức tạp hơn');
        $resetStatus = $this->services->reset($account, $password);
        if ($resetStatus) return app('json')->success('Sửa đổi thành công');
        return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Đăng nhập số điện thoại di động
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function mobile(Request $request)
    {
        [$phone, $captcha, $spread, $agent_id] = $request->postMore([['phone', ''], ['captcha', ''], ['spread', 0], ['agent_id', 0]], true);

        //Xác minh số điện thoại di động
        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }

        //Xác minh mã xác minh
        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha) {
            return app('json')->fail('Lỗi mã xác minh');
        }
        $user_type = $request->getFromType() ? $request->getFromType() : 'h5';
        $token = $this->services->mobile($phone, $spread, $user_type, $agent_id);
        if ($token) {
            CacheService::delete('code_' . $phone);
            return app('json')->success('Đăng nhập thành công', $token);
        } else {
            return app('json')->fail('Thoát thành công');
        }
    }

    /**
     * H5Chuyển đổi đăng nhập
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function switch_h5(Request $request)
    {
        $from = $request->post('from', 'wechat');
        $user = $request->user();
        $token = $this->services->switchAccount($user, $from);
        if ($token) {
            $token['userInfo'] = $user;
            return app('json')->success('Đăng nhập thành công', $token);
        } else
            return app('json')->fail('Thoát thành công');
    }

    /**
     * Ràng buộc số điện thoại di động
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function binding_phone(Request $request)
    {
        list($phone, $captcha, $key) = $request->postMore([
            ['phone', ''],
            ['captcha', ''],
            ['key', '']
        ], true);
        //Xác minh số điện thoại di động
        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if (!$key) {
            return app('json')->fail('Lỗi tham số');
        }
        if (!$phone) {
            return app('json')->fail('Vui lòng nhập số điện thoại di động');
        }
        //Xác minh mã xác minh
        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha) {
            return app('json')->fail('Lỗi mã xác minh');
        }
        $re = $this->services->bindind_phone($phone, $key);
        if ($re) {
            CacheService::delete('code_' . $phone);
            return app('json')->success('Liên kết thành công', $re);
        } else
            return app('json')->fail('Liên kết không thành công');
    }

    /**
     * Ràng buộc số điện thoại di động
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_binding_phone(Request $request)
    {
        list($phone, $captcha, $step) = $request->postMore([
            ['phone', ''],
            ['captcha', ''],
            ['step', 0]
        ], true);

        //Xác minh số điện thoại di động
        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if (!$step) {
            //Xác minh mã xác minh
            $verifyCode = CacheService::get('code_' . $phone);
            if (!$verifyCode)
                return app('json')->fail('Vui lòng lấy mã xác minh trước');
            $verifyCode = substr($verifyCode, 0, 6);
            if ($verifyCode != $captcha)
                return app('json')->fail('Lỗi mã xác minh');
        }
        $uid = (int)$request->uid();
        $re = $this->services->userBindindPhone($uid, $phone, $step);
        if ($re) {
            CacheService::delete('code_' . $phone);
            return app('json')->success($re['msg'] ?? 'Liên kết thành công', $re['data'] ?? []);
        } else
            return app('json')->fail('Liên kết không thành công');
    }

    public function update_binding_phone(Request $request)
    {
        [$phone, $captcha] = $request->postMore([
            ['phone', ''],
            ['captcha', ''],
        ], true);

        //Xác minh số điện thoại di động
        try {
            validate(RegisterValidates::class)->scene('code')->check(['phone' => $phone]);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        //Xác minh mã xác minh
        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha)
            return app('json')->fail('Lỗi mã xác minh');
        $uid = (int)$request->uid();
        $re = $this->services->updateBindindPhone($uid, $phone);
        if ($re) {
            CacheService::delete('code_' . $phone);
            return app('json')->success($re['msg'] ?? 'Sửa đổi thành công', $re['data'] ?? []);
        } else
            return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Đặt trạng thái quét mã QR
     * @param string $code
     * @return mixed
     */
    public function setLoginKey(string $code)
    {
        if (!$code) {
            return app('json')->fail('Quét không thành công, vui lòng quét lại');
        }
        $cacheCode = CacheService::get($code);
        if ($cacheCode === false || $cacheCode === null) {
            return app('json')->fail('Mã QR đã hết hạn. Vui lòng quét lại.');
        }
        CacheService::set($code, '0', 600);
        return app('json')->success();
    }

    /**
     * appleĐăng nhập nhanh
     * @param Request $request
     * @param WechatServices $services
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function appleLogin(Request $request, WechatServices $services)
    {
        [$openId, $phone, $email, $captcha] = $request->postMore([
            ['openId', ''],
            ['phone', ''],
            ['email', ''],
            ['captcha', '']
        ], true);
        if ($phone) {
            if (!$captcha) {
                return app('json')->fail('Vui lòng nhập mã xác minh');
            }
            //Xác minh mã xác minh
            $verifyCode = CacheService::get('code_' . $phone);
            if (!$verifyCode)
                return app('json')->fail('Vui lòng lấy mã xác minh trước');
            $verifyCode = substr($verifyCode, 0, 6);
            if ($verifyCode != $captcha) {
                CacheService::delete('code_' . $phone);
                return app('json')->fail('Lỗi mã xác minh');
            }
        }
        if ($email == '') $email = substr(md5($openId), 0, 12);
        $userInfo = [
            'openId' => $openId,
            'unionid' => '',
            'avatarUrl' => sys_config('h5_avatar'),
            'nickName' => $email,
        ];
        $token = $services->appAuth($userInfo, $phone, 'apple');
        if ($token) {
            return app('json')->success('Đăng nhập thành công', $token);
        } else if ($token === false) {
            return app('json')->success('Đăng nhập thành công', ['isbind' => true]);
        } else {
            return app('json')->fail('Đăng nhập không thành công');
        }

    }

    /**
     * Xác thực thanh trượt
     * @return mixed
     */
    public function ajcaptcha(Request $request)
    {
        $captchaType = $request->get('captchaType');
        return app('json')->success(aj_captcha_create($captchaType));
    }

    /**
     * Một lần xác minh
     * @return mixed
     */
    public function ajcheck(Request $request)
    {
        [$token, $pointJson, $captchaType] = $request->postMore([
            ['token', ''],
            ['pointJson', ''],
            ['captchaType', ''],
        ], true);
        try {
            aj_captcha_check_one($captchaType, $token, $pointJson);
            return app('json')->success();
        } catch (\Throwable $e) {
            return app('json')->fail('Lỗi mã xác minh');
        }
    }

    /**
     * Giao diện đăng nhập từ xa
     * @param Request $request
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/21
     */
    public function remoteRegister(Request $request)
    {
        [$remote_token] = $request->getMore([
            ['remote_token', ''],
        ], true);
        if ($remote_token == '') return app('json')->success('Đăng nhập không thành công', ['get_remote_login_url' => sys_config('get_remote_login_url')]);
        return app('json')->success('Đăng nhập thành công', $this->services->remoteRegister($remote_token));
    }
}
