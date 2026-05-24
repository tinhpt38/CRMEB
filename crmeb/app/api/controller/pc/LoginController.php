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

namespace app\api\controller\pc;

use app\services\pc\LoginServices;
use crmeb\services\CacheService;

class LoginController
{
    protected $services;

    public function __construct(LoginServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nhận mã quét đăng nhậpKEY
     * @return mixed
     */    public function getLoginKey()
    {
        $key = md5(time() . uniqid());
        $time = time() + 600;
        CacheService::set($key, 1, 600);
        return app('json')->success(['key' => $key, 'time' => $time]);
    }

    /**
     * Quét mã QR để đăng nhập
     * @param string $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function scanLogin(string $key)
    {
        return app('json')->success($this->services->scanLogin($key));
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
            'appid' => sys_config('wechat_open_app_id'),
            'version' => get_crmeb_version()
        ]);
    }
}
