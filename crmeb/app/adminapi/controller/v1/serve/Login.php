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

namespace app\adminapi\controller\v1\serve;


use app\adminapi\controller\AuthController;
use app\adminapi\validate\serve\ServeValidata;
use app\Request;
use app\services\yihaotong\SmsAdminServices;
use crmeb\services\CacheService;
use app\services\serve\ServeServices;
use think\facade\App;

/**
 * Đăng nhập dịch vụ
 * Class Login
 * @package app\adminapi\controller\v1\serve
 */class Login extends AuthController
{

    public function __construct(App $app, ServeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Gửi mã xác minh
     * @param string $phone
     * @return mixed
     */    public function captcha(string $phone)
    {
        $this->validate(['phone' => $phone], ServeValidata::class, 'phone');
        return app('json')->success('Đã gửi thành công', $this->services->user()->code($phone));
    }

    /**
     * Xác minh mã xác minh
     * @param string $phone
     * @param $code
     * @return mixed
     */    public function checkCode()
    {
        [$phone, $verify_code] = $this->request->postMore([
            ['phone', ''],
            ['verify_code', ''],
        ], true);
        $this->validate(['phone' => $phone], ServeValidata::class, 'phone');
        return app('json')->success('success', $this->services->user()->checkCode($phone, $verify_code));
    }

    /**
     * Dịch vụ đăng ký
     * @param Request $request
     * @param SmsAdminServices $services
     * @return mixed
     */    public function register(Request $request, SmsAdminServices $services)
    {
        $data = $request->postMore([
            ['phone', ''],
            ['account', ''],
            ['password', ''],
            ['verify_code', ''],
        ]);

        $data['account'] = $data['phone'];
        $this->validate($data, ServeValidata::class);
        $data['password'] = md5($data['password']);
        $res = $this->services->user()->register($data);
        if ($res) {
            $services->updateSmsConfig($data['account'], md5($data['account'] . md5($data['password'])));
            return app('json')->success('Đăng ký thành công');
        } else {
            return app('json')->fail('Đăng ký không thành công');
        }
    }

    /**
     * Đăng nhập nền tảng
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function login(SmsAdminServices $services)
    {
        [$account, $password] = $this->request->postMore([
            ['account', ''],
            ['password', '']
        ], true);

        $this->validate(['account' => $account, 'password' => $password], ServeValidata::class, 'login');

        $password = md5($account . md5($password));

        $res = $this->services->user()->login($account, $password);
        if ($res) {
            CacheService::clear();
            CacheService::set('sms_account', $account);
            $services->updateSmsConfig($account, $password);
            return app('json')->success('Đăng nhập thành công', $res);
        } else {
            return app('json')->fail('Đăng nhập không thành công');
        }
    }
}
