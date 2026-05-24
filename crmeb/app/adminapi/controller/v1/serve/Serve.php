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
use app\adminapi\validate\serve\ExpressValidata;
use app\adminapi\validate\serve\MealValidata;
use app\adminapi\validate\serve\ServeValidata;
use app\services\system\config\SystemConfigServices;
use crmeb\services\CacheService;
use app\services\serve\ServeServices;
use think\facade\App;

/**
 * Class Serve
 * @package app\adminapi\controller\v1\serve
 */class Serve extends AuthController
{
    /**
     * Serve constructor.
     * @param App $app
     * @param ServeServices $services
     */    public function __construct(App $app, ServeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Phát hiện đăng nhập
     * @return mixed
     */    public function is_login()
    {
        $sms_info = CacheService::get('sms_account');
        if ($sms_info) {
            return app('json')->success(['status' => true, 'info' => $sms_info]);
        } else {
            return app('json')->success(['status' => false]);
        }
    }

    /**
     * Nhận danh sách gói
     * @param string $type
     * @return mixed
     */    public function mealList(string $type)
    {
        $res = $this->services->user()->mealList($type);
        if ($res) {
            return app('json')->success($res);
        } else {
            return app('json')->fail('Không thể lấy được danh sách gói');
        }
    }

    /**
     * Nhận mã thanh toán
     * @return mixed
     */    public function payMeal()
    {
        $data = $this->request->postMore([
            ['meal_id', ''],
            ['price', ''],
            ['num', ''],
            ['type', ''],
            ['pay_type', ''],
        ]);
        $openInfo = $this->services->user()->getUser();
        if (!$openInfo) app('json')->fail('Không lấy được mã thanh toán');
        switch ($data['type']) {
            case "sms" :
                if (!$openInfo['sms']['open']) return app('json')->fail('Vui lòng kích hoạt dịch vụ SMS trước');
                break;
            case "query" :
                if (!$openInfo['query']['open']) return app('json')->fail('Vui lòng kích hoạt dịch vụ yêu cầu hậu cần trước');
                break;
            case "dump" :
                if (!$openInfo['dump']['open']) return app('json')->fail('Vui lòng kích hoạt dịch vụ in biểu mẫu điện tử trước');
                break;
            case "copy" :
                if (!$openInfo['copy']['open']) return app('json')->fail('Vui lòng kích hoạt dịch vụ thu thập sản phẩm trước');
                break;
        }
        $this->validate($data, MealValidata::class);

        $res = $this->services->user()->payMeal($data);
        if ($res) {
            return app('json')->success($res);
        } else {
            return app('json')->fail('Không lấy được mã thanh toán');
        }
    }

    /**
     * Cho phép in các biểu mẫu điện tử
     * @return mixed
     */    public function openExpress()
    {
        $data = $this->request->postMore([
            ['com', ''],
            ['temp_id', ''],
            ['to_name', ''],
            ['to_tel', ''],
            ['to_address', ''],
            ['siid', ''],
        ]);

        $this->validate($data, ExpressValidata::class);

        /** @var SystemConfigServices $systemConfigService */        $systemConfigService = app()->make(SystemConfigServices::class);
        $systemConfigService->saveExpressInfo($data);
        $this->services->express()->open();
        return app('json')->success('Kích hoạt thành công');

    }

    /**
     * Lấy thông tin Khách hàng. Thông tin Khách hàng chứa trường có nên kích hoạt dịch vụ hay không.
     * @return mixed
     */    public function getUserInfo()
    {
        return app('json')->success($this->services->user()->getUser());
    }

    /**
     * Bản ghi truy vấn
     * @return mixed
     */    public function getRecord()
    {
        [$page, $limit, $type] = $this->request->getMore([
            [['page', 'd'], 0],
            [['limit', 'd'], 10],
            [['type', 'd'], 0],
        ], true);

        return app('json')->success($this->services->user()->record($page, $limit, $type));
    }

    /**
     * Kích hoạt dịch vụ
     * @param int $type
     * @return mixed
     */    public function openServe($type = 0)
    {
        if ($type) {
            $this->services->copy()->open();
        } else {
            $this->services->express()->open();
        }

        return app('json')->success('Kích hoạt thành công');
    }

    /**
     * Thay đổi mật khẩu
     * @return mixed
     */    public function modify()
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['password', ''],
            ['phone', ''],
            ['verify_code', ''],
        ]);

        $this->validate($data, ServeValidata::class);

        $data['password'] = md5($data['password']);
        $this->services->user()->modify($data);
        CacheService::delete('sms_account');
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Sửa đổi số điện thoại di động
     * @return mixed
     */    public function updatePhone()
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['phone', ''],
            ['verify_code', ''],
        ]);

        $this->validate($data, ServeValidata::class, 'phone');

        $this->services->user()->modifyPhone($data);
        CacheService::delete('sms_account');
        return app('json')->success('Sửa đổi thành công');
    }
}
