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
use app\services\serve\ServeServices;
use think\facade\App;

/**
 * Class Sms
 * @package app\adminapi\controller\v1\serve
 */
class Sms extends AuthController
{
    /**
     * Sms constructor.
     * @param App $app
     * @param ServeServices $services
     */
    public function __construct(App $app, ServeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }


    /**
     * Kích hoạt dịch vụ
     * @param string $sign
     * @return mixed
     */
    public function openServe(string $sign)
    {
        if (!$sign) {
            return app('json')->fail('Vui lòng thiết lập chữ ký SMS');
        }
        $this->services->sms()->setSign($sign)->open();
        return app('json')->success('Kích hoạt thành công');
    }

    /**
     * Sửa đổi chữ ký SMS
     * @param string $sign
     * @return mixed
     */
    public function editSign(string $sign)
    {
        [$sign, $phone, $code] = $this->request->postMore([
            ['sign', ''],
            ['phone', ''],
            ['code', ''],
        ], true);

        $this->validate(['phone' => $phone], ServeValidata::class, 'phone');

        if (!$sign) {
            return app('json')->fail('Vui lòng thiết lập chữ ký SMS');
        }
        $this->services->sms()->modify($sign, $phone, $code);
        return app('json')->success('Sửa chữ ký SMS thành công');
    }

    /**
     * Nhận mẫu SMS
     * @return mixed
     */
    public function temps()
    {
        [$page, $limit, $type] = $this->request->getMore([
            ['page', 1],
            ['limit', 10],
            ['temp_type', 0],
        ], true);

        return app('json')->success($this->services->getSmsTempsList((int)$page, (int)$limit, (int)$type));
    }

    /**
     * Mẫu đơn đăng ký
     * @return mixed
     */
    public function apply()
    {
        [$title, $content, $type] = $this->request->postMore([
            ['title', ''],
            ['content', ''],
            ['type', 0]
        ], true);

        if (!$title || !$content || !$type) {
            return app('json')->success('Vui lòng nhập nội dung mẫu');
        }
        return app('json')->success($this->services->sms()->apply($title, $content, (int)$type));
    }

    /**
     * Nhận hồ sơ ứng dụng
     * @return mixed
     */
    public function applyRecord()
    {
        [$page, $limit, $tempType] = $this->request->getMore([
            [['page', 'd'], 1],
            [['limit', 'd'], 10],
            [['temp_type', 'd'], 0],
        ], true);

        return app('json')->success($this->services->sms()->applys($tempType, $page, $limit));
    }
}
