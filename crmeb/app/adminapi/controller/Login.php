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
namespace app\adminapi\controller;

use crmeb\services\CacheService;
use think\facade\App;
use crmeb\utils\Captcha;
use app\services\system\admin\SystemAdminServices;

/**
 * Đăng nhập phụ trợ
 * Class Login
 * @package app\adminapi\controller
 */
class Login extends AuthController
{

    /**
     * @var SystemAdminServices
     */
    protected $services;

    /**
     * Login constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    /**
     * Mã xác minh
     * @return $this|\think\Response
     */
    public function captcha()
    {
        return app()->make(Captcha::class)->create();
    }

    /**
     * @return mixed
     */
    public function ajcaptcha()
    {
        $captchaType = $this->request->get('captchaType');
        return app('json')->success(aj_captcha_create($captchaType));
    }

    /**
     * Một lần xác minh
     * @return mixed
     */
    public function ajcheck()
    {
        [$token, $pointJson, $captchaType] = $this->request->postMore([
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
     * Đăng nhập
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        [$account, $password, $key, $captchaVerification, $captchaType] = $this->request->postMore([
            'account',
            'pwd',
            ['key', ''],
            ['captchaVerification', ''],
            ['captchaType', '']
        ], true);

        if ($captchaVerification != '') {
            try {
                aj_captcha_check_two($captchaType, $captchaVerification);
            } catch (\Throwable $e) {
                return app('json')->fail('Lỗi mã xác minh');
            }
        }

        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return app('json')->fail('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
        }

        $this->validate(['account' => $account, 'pwd' => $password], \app\adminapi\validate\setting\SystemAdminValidata::class, 'get');
        $result = $this->services->login($account, $password, 'admin', $key);
        if (!$result) {
            $num = CacheService::get('login_captcha', 1);
            if ($num > 1) {
                return app('json')->fail('Tài khoản hoặc mật khẩu không chính xác', ['login_captcha' => 1]);
            }
            CacheService::set('login_captcha', $num + 1, 60);
            return app('json')->fail('Tài khoản hoặc mật khẩu không chính xác', ['login_captcha' => 0]);
        }
        CacheService::delete('login_captcha');
        return app('json')->success($result);
    }

    /**
     * Lấy hình ảnh băng chuyền trang đăng nhập nền vàLOGO
     * @return mixed
     */
    public function info()
    {
        return app('json')->success($this->services->getLoginInfo());
    }
}
