<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\adminapi\controller\v1\application\zalo;

use app\adminapi\controller\AuthController;
use app\services\zalo\ZaloConfigServices;
use think\facade\App;

/**
 * Quản lý cấu hình xác thực Zalo Mini App
 *
 * Endpoints (đặt trong route group app/ với middleware admin auth):
 *  GET  app/zalo/config          - Lấy cấu hình hiện tại
 *  POST app/zalo/config          - Lưu cấu hình
 *  GET  app/zalo/test_connection - Test kết nối Zalo API
 *
 * Class ZaloConfig
 * @package app\adminapi\controller\v1\application\zalo
 */
class ZaloConfig extends AuthController
{
    public function __construct(App $app, ZaloConfigServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lấy cấu hình Zalo hiện tại
     *
     * Response:
     *  zalo_login_open      int     1/0
     *  zalo_app_id          string
     *  zalo_app_secret      string  (đã mask, vd: "abcd****efgh")
     *  zalo_callback_domain string
     *  zalo_bind_phone      int     1/0
     *  _meta                object  Thông tin tài liệu hướng dẫn
     *
     * @return mixed
     */
    public function getConfig()
    {
        $config = $this->services->getConfig();
        $config['_meta'] = [
            'doc_url'        => 'https://developers.zalo.me/docs/mini-app',
            'oauth_url'      => 'https://oauth.zaloapp.com',
            'graph_api_url'  => 'https://graph.zalo.me/v2.0/me',
            'callback_path'  => '/api/zalo/auth',
            'bind_phone_path'=> '/api/zalo/bind_phone',
            'required_scope' => 'id,name,picture',
        ];
        return app('json')->success($config);
    }

    /**
     * Lưu cấu hình Zalo
     *
     * Request body:
     *  zalo_login_open      int     required  1/0
     *  zalo_app_id          string  required khi login_open=1
     *  zalo_app_secret      string  bỏ qua nếu là masked value
     *  zalo_callback_domain string
     *  zalo_bind_phone      int     1/0
     *
     * @return mixed
     */
    public function saveConfig()
    {
        $data = $this->request->postMore([
            ['zalo_login_open',      0],
            ['zalo_app_id',          ''],
            ['zalo_app_secret',      ''],
            ['zalo_callback_domain', ''],
            ['zalo_bind_phone',      0],
        ]);

        $this->services->saveConfig($data);
        return app('json')->success('Lưu cấu hình Zalo thành công');
    }

    /**
     * Test kết nối với Zalo Open API
     * Dùng để xác nhận App ID và App Secret đã nhập đúng
     *
     * @return mixed
     */
    public function testConnection()
    {
        $result = $this->services->testConnection();
        if ($result['status']) {
            return app('json')->success($result['message'], $result['details'] ?? []);
        }
        return app('json')->fail($result['message']);
    }
}
