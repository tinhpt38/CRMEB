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

namespace app\api\controller\v1\wechat;


use app\Request;
use app\services\wechat\WechatServices as WechatAuthServices;
use crmeb\services\CacheService;

/**
 * Tài khoản công khai WeChat
 * Class WechatController
 * @package app\api\controller\wechat
 */class WechatController
{
    protected $services = NUll;

    /**
     * WechatController constructor.
     * @param WechatAuthServices $services
     */    public function __construct(WechatAuthServices $services)
    {
        $this->services = $services;
    }

    /**
     * Dịch vụ tài khoản công cộng WeChat
     * @return \think\Response
     */    public function serve()
    {
        return $this->services->serve();
    }

    /**
     * Dịch vụ tài khoản công cộng chương trình mini WeChat
     * @return \think\Response
     */    public function miniServe()
    {
        return $this->services->miniServe();
    }

    /**
     * Trả tiền gọi lại không đồng bộ
     */    public function notify()
    {
        return $this->services->notify();
    }

    public function v3notify()
    {
        return $this->services->v3notify();
    }

    /**
     * Lấy thông tin cấu hình quyền tài khoản công cộng
     * @param Request $request
     * @return mixed
     */    public function config(Request $request)
    {
        return app('json')->success($this->services->config($request->get('url')));
    }

    /**
     * Appđăng nhập WeChat
     * @param Request $request
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function appAuth(Request $request)
    {
        [$userInfo, $phone, $captcha] = $request->postMore([
            ['userInfo', []],
            ['phone', ''],
            ['code', '']
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
        $token = $this->services->appAuth($userInfo, $phone);
        if ($token) {
            return app('json')->success('Đăng nhập thành công', $token);
        } else if ($token === false) {
            return app('json')->success('Đăng nhập thành công', ['isbind' => true]);
        } else {
            return app('json')->fail('Đăng nhập không thành công');
        }
    }

    /**
     * Theo dõi mã QR
     * @return mixed
     * @throws \Exception
     */    public function follow()
    {
        $data = $this->services->follow();
        if ($data) {
            return app('json')->success($data);
        } else {
            return app('json')->fail('Không thể lấy được');
        }

    }
}
