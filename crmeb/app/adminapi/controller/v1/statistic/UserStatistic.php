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
use app\services\statistic\UserStatisticServices;
use think\facade\App;

/**
 * Class UserStatistic
 * @package app\adminapi\controller\v1\statistic
 */class UserStatistic extends AuthController
{
    /**
     * UserStatistic constructor.
     * @param App $app
     * @param UserStatisticServices $services
     */    public function __construct(App $app, UserStatisticServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Thông tin cơ bản của Khách hàng
     * @return mixed
     */    public function getBasic()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        return app('json')->success($this->services->getBasic($where));
    }

    /**
     * Xu hướng Khách hàng
     * @return mixed
     */    public function getTrend()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getTrend($where));
    }

    /**
     * Thông tin Khách hàng WeChat
     * @return mixed
     */    public function getWechat()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getWechat($where));
    }

    /**
     * Xu hướng Khách hàng WeChat
     * @return mixed
     */    public function getWechatTrend()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getWechatTrend($where));
    }

    /**
     * Khu vực Khách hàng
     * @return mixed
     */    public function getRegion()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time'],
            ['sort', 'allNum']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getRegion($where));
    }

    /**
     * Giới tính Khách hàng
     * @return mixed
     */    public function getSex()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getSex($where));
    }

    /**
     * Xuất thống kê Khách hàng
     * @return mixed
     */    public function getExcel()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
            ['data', '', '', 'time']
        ]);
        $where['time'] = $this->getDay($where['time']);
        return app('json')->success($this->services->getTrend($where, true));
    }

    /**
     * Định dạng thời gian
     * @param $time
     * @return string
     */    public function getDay($time)
    {
        if (strstr($time, '-') !== false) {
            [$startTime, $endTime] = explode('-', $time);
            if (!$startTime || !$endTime) {
                return date("Y/m/d 00:00:00", strtotime("-30 days", time())) . '-' . date("Y/m/d 23:59:59", time());
            } else {
                return date('Y/m/d 00:00:00', strtotime($startTime)).'-'.date('Y/m/d 23:59:59', strtotime($endTime));
            }
        } else {
            return date("Y/m/d 00:00:00", strtotime("-30 days", time())) . '-' . date("Y/m/d 23:59:59", time());
        }
    }
}
