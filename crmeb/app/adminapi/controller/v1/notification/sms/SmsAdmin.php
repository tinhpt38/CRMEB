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
namespace app\adminapi\controller\v1\notification\sms;

use app\adminapi\controller\AuthController;
use app\services\yihaotong\SmsAdminServices;
use think\facade\App;

/**
 * tài khoản SMS
 * Class SmsAdmin
 * @package app\adminapi\controller\v1\sms
 */
class SmsAdmin extends AuthController
{
    /**
     * Người xây dựng
     * SmsAdmin constructor.
     * @param App $app
     * @param SmsAdminServices $services
     */
    public function __construct(App $app, SmsAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Gửi mã xác minh
     * @return mixed
     */
    public function captcha()
    {
        if (!request()->isPost()) {
            return app('json')->fail('Gửi không thành công');
        }
        $phone = request()->param('phone');
        if (!trim($phone)) {
            return app('json')->fail('Vui lòng điền số điện thoại di động của bạn');
        }
        return app('json')->success($this->services->captcha($phone));
    }

    /**
     * Sửa đổi/đăng ký tài khoản nền tảng SMS
     * @return mixed
     */
    public function save()
    {
        [$account, $password, $phone, $code, $url, $sign] = $this->request->postMore([
            ['account', ''],
            ['password', ''],
            ['phone', ''],
            ['code', ''],
            ['url', ''],
            ['sign', ''],
        ], true);
        $signLen = mb_strlen(trim($sign));
        if (!strlen(trim($account))) return app('json')->fail('Vui lòng điền số tài khoản');
        if (!strlen(trim($password))) return app('json')->fail('Vui lòng điền mật khẩu');
        if (!$signLen) return app('json')->fail('Vui lòng điền chữ ký SMS');
        if ($signLen > 8) return app('json')->fail('Chữ ký SMS có thể dài tối đa 8 ký tự');
        if (!strlen(trim($code))) return app('json')->fail('Vui lòng điền mã xác minh');
        if (!strlen(trim($url))) return app('json')->fail('Vui lòng điền tên miền');
        $status = $this->services->register($account, $password, $url, $phone, $code, $sign);
        return app('json')->success($status['msg']);
    }
}
