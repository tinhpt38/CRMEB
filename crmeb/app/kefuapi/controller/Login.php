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

namespace app\kefuapi\controller;


use app\Request;
use crmeb\basic\BaseController;
use crmeb\services\CacheService;
use app\services\kefu\LoginServices;
use app\kefuapi\validate\LoginValidate;
use think\facade\App;

/**
 * Class Login
 * @package app\kefu\controller
 */class Login extends BaseController
{
    /**
     * Login constructor.
     * @param LoginServices $services
     */    public function __construct(App $app, LoginServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    /**
     * Đăng nhập CSKH
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function login(Request $request)
    {
        [$account, $password] = $request->postMore([
            ['account', ''],
            ['password', ''],
        ], true);
        validate(LoginValidate::class)->check(['account' => $account, 'password' => $password]);
        $token = $this->services->authLogin($account, $password);

        return app('json')->success('Đăng nhập thành công', $token);
    }

    /**
     * Mở mã quét nền tảng để đăng nhập
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function wechatAuth()
    {
        return app('json')->success($this->services->wechatAuth());
    }

    /**
     * Nhận nền tảng công cộngid
     * @return mixed
     */    public function getAppid()
    {
        return app('json')->success([
            'appid' => sys_config('wechat_open_app_id', 'wxc736972a4ca1e2a1'),
            'version' => get_crmeb_version(),
            'site_name' => sys_config('site_name'),
            'copyright' => sys_config('nncnL_crmeb_copyright', ''),
            'copyrightImg' => sys_config('nncnL_crmeb_copyright_image', ''),
        ]);
    }

    /**
     * Chỉ nhận được thông tin đăng nhậpcode
     * @return mixed
     */    public function getLoginKey()
    {
        $key = md5(time() . uniqid());
        $time = time() + 600;
        CacheService::set($key, 1, 600);
        return app('json')->success(['key' => $key, 'time' => $time]);
    }

    /**
     * Xác minh đăng nhập
     * @param string $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function scanLogin(string $key)
    {
        return app('json')->success($this->services->scanLogin($key));
    }
}
