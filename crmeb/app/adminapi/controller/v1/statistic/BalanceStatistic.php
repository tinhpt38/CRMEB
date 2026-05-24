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
namespace app\adminapi\controller\v1\statistic;

use app\adminapi\controller\AuthController;
use app\services\user\UserMoneyServices;
use think\facade\App;

class BalanceStatistic extends AuthController
{
    /**
     * @param App $app
     * @param UserMoneyServices $services
     */    public function __construct(App $app, UserMoneyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Thông tin cơ bản về thống kê số dư
     * @return mixed
     */    public function getBasic()
    {
        $data = $this->services->getBasic();
        return app('json')->success($data);
    }

    /**
     * Biểu đồ xu hướng thống kê số dư
     * @return mixed
     */    public function getTrend()
    {
        $where = $this->request->getMore([
            ['time', '']
        ]);
        $data = $this->services->getTrend($where);
        return app('json')->success($data);
    }

    /**
     * Nguồn cân bằng
     * @return mixed
     */    public function getChannel()
    {
        $where = $this->request->getMore([
            ['time', '']
        ]);
        $data = $this->services->getChannel($where);
        return app('json')->success($data);
    }

    /**
     * Loại số dư
     * @return mixed
     */    public function getType()
    {
        $where = $this->request->getMore([
            ['time', '']
        ]);
        $data = $this->services->getType($where);
        return app('json')->success($data);
    }
}
