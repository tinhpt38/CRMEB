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

use app\services\system\config\SystemConfigServices;
use app\services\yihaotong\SmsAdminServices;
use app\services\serve\ServeServices;
use app\adminapi\controller\AuthController;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * cấu hình tin nhắn
 * Class SmsConfig
 * @package app\admin\controller\sms
 */
class SmsConfig extends AuthController
{
    /**
     * Người xây dựng
     * SmsConfig constructor.
     * @param App $app
     * @param SmsAdminServices $services
     */
    public function __construct(App $app, SmsAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lưu cấu hình SMS
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function save_basics()
    {
        [$account, $token] = $this->request->postMore([
            ['sms_account', ''],
            ['sms_token', '']
        ], true);

        $this->validate(['sms_account' => $account, 'sms_token' => $token], \app\adminapi\validate\notification\SmsConfigValidate::class);

        if ($this->services->login($account, $token)) {
            return app('json')->success('Đăng nhập thành công');
        } else {
            return app('json')->fail('Tài khoản hoặc mật khẩu không chính xác');
        }
    }

    /**
     * Phát hiện đăng nhập
     * @param ServeServices $services
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function is_login(ServeServices $services)
    {
        $configServices = app()->make(SystemConfigServices::class);
        $sms_info = CacheService::get('sms_account');
        $data = ['status' => false, 'info' => ''];
        if ($sms_info) {
            try {
                $result = $services->user()->getUser();
            } catch (\Throwable $e) {
                $result = [];
            }
            if (!$result) {
                $this->logout();
            } else {
                $data['status'] = true;
                $data['info'] = $sms_info;
            }
            return app('json')->success($data);
        } else {
            CacheService::clear();
            $account = sys_config('sms_account');
            $password = sys_config('sms_token');
            //Không cần đăng xuất, hãy xóa hai dữ liệu này và đăng nhập tự động.
            if ($account && $password) {
                $res = $services->user()->login($account, $password);
                if ($res) {
                    CacheService::set('sms_account', $account);
                    $data['status'] = true;
                    $data['info'] = $account;
                }
            }
        }
        return app('json')->success($data);
    }

    /**
     * từ bỏ
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout()
    {
        CacheService::delete('sms_account');
        $this->services->updateSmsConfig('', '');
        CacheService::clear();
        return app('json')->success('Thoát thành công');
    }

    /**
     * Bản ghi gửi SMS
     * @param ServeServices $services
     * @return mixed
     */
    public function record(ServeServices $services)
    {
        [$page, $limit, $status] = $this->request->getMore([
            [['page', 'd'], 0],
            [['limit', 'd'], 10],
            ['type', '', '', 'status'],
        ], true);
        return app('json')->success($services->user()->record($page, $limit, 1, $status));
    }

    /**
     * Nhận thông tin tài khoản SMS hiện đang đăng nhập
     * @return mixed
     */
    public function data()
    {
        return app('json')->success($this->services->getSmsData());
    }
}
