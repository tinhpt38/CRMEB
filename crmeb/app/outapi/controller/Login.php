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
namespace app\outapi\controller;

use app\outapi\validate\LoginValidate;
use app\Request;
use think\facade\App;
use app\services\out\OutAccountServices;

/**
 * Class Login
 * @package app\out\controller
 */
class Login extends AuthController
{
    /**
     * OutAccount constructor.
     * @param App $app
     * @param OutAccountServices $services
     */
    public function __construct(App $app, OutAccountServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    /**
     * Đăng nhập dịch vụ khách hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getToken(Request $request)
    {
        [$appid, $appsecret] = $request->postMore([
            ['appid', ''],
            ['appsecret', ''],
        ], true);
        $this->validate(['appid' => $appid, 'appsecret' => $appsecret], LoginValidate::class);

        $token = $this->services->authLogin($appid, $appsecret);

        return app('json')->success('Hoạt động thành công', $token);
    }

    /**
     * làm cho khỏe lạitoken
     * @return void
     */
    public function refreshToken(Request $request)
    {
        [$token] = $request->postMore([
            ['access_token', ''],
        ], true);
        $token = $this->services->refresh($token);
        return app('json')->success('Hoạt động thành công', $token);
    }

}
