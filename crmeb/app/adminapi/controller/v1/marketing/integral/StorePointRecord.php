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
namespace app\adminapi\controller\v1\marketing\integral;

use app\adminapi\controller\AuthController;
use app\services\activity\integral\StorePointRecordServices;
use think\facade\App;

/**
 * Kỷ lục điểm
 */class StorePointRecord extends AuthController
{
    /**
     * @param App $app
     * @param StorePointRecordServices $services
     */    public function __construct(App $app, StorePointRecordServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Kỷ lục điểm
     * @return mixed
     */    public function pointRecord()
    {
        $where = $this->request->getMore([
            ['time', ''],
            ['trading_type', '']
        ]);
        $date = $this->services->pointRecord($where);
        return app('json')->success($date);
    }

    /**
     * Ghi chú ghi điểm
     * @return mixed
     */    public function pointRecordRemark($id = 0)
    {
        [$mark] = $this->request->postMore([
            ['mark', '']
        ], true);
        $this->services->recordRemark($id, $mark);
        return app('json')->success('Bình luận thành công');
    }

    /**
     * Thông tin cơ bản về thống kê điểm
     * @return mixed
     */    public function getBasic()
    {
        $where = $this->request->getMore([
            ['time', '']
        ]);
        $data = $this->services->getBasic($where);
        return app('json')->success($data);
    }

    /**
     * Biểu đồ xu hướng thống kê điểm
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
     * Nguồn điểm
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
     * Tiêu thụ điểm
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
