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
use app\services\serve\ServeServices;
use app\services\shipping\ExpressServices;
use think\facade\App;

/**
 * Dịch vụ hậu cần nền tảng Yihaotong
 * Class Export
 * @package app\adminapi\controller\v1\serve
 */
class Export extends AuthController
{

    /**
     * Export constructor.
     * @param App $app
     * @param ExpressServices $services
     */
    public function __construct(App $app, ExpressServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Công ty hậu cần
     * @return mixed
     */
    public function getExportAll()
    {
        return app('json')->success($this->services->expressList());
    }

    /**
     *
     * Nhận thông tin thứ tự khuôn mặt
     * @param string $com
     * @return mixed
     */
    public function getExportTemp(ServeServices $services)
    {
        [$com] = $this->request->getMore([
            ['com', ''],
        ], true);
        return app('json')->success($services->express()->temp($com));
    }

    /**
     * Việc in các biểu mẫu điện tử có được bật không?
     * @return mixed
     */
    public function dumpIsOpen(ServeServices $services)
    {
        $userInfo = $services->user()->getUser();
        $res = false;
        if ($userInfo['dump']['open']) {
            $res = true;
            if (!sys_config('config_export_siid')
                && !sys_config('config_export_com')
                && !sys_config('config_export_to_name')
                && !sys_config('config_export_to_tel')
                && !sys_config('config_export_to_address')
            ) {
                $res = false;
            }
        }
        return app('json')->success(['isOpen' => $res]);
    }

    /**
     * @param ServeServices $services
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/15
     */
    public function getShipmentOrderList(ServeServices $services)
    {
        $where = $this->request->getMore([
            ['page', 1],
            ['limit', 10],
        ]);

        return app('json')->success($services->express()->getShipmentOrderList($where));
    }
}
