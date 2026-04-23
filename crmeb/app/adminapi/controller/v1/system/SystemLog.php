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
namespace app\adminapi\controller\v1\system;

use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\log\SystemLogServices;
use app\services\system\admin\SystemAdminServices;

/**
 * Bộ điều khiển bảng ghi hoạt động của quản trị viên
 * Class SystemLog
 * @package app\adminapi\controller\v1\system
 */
class SystemLog extends AuthController
{
    /**
     * Người xây dựng
     * SystemLog constructor.
     * @param App $app
     * @param SystemLogServices $services
     */
    public function __construct(App $app, SystemLogServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
        $this->services->deleteLog();
    }

    /**
     * Hiển thị lịch sử hoạt động
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['pages', ''],
            ['path', ''],
            ['ip', ''],
            ['admin_id', ''],
            ['data', '', '', 'time'],
        ]);
        return app('json')->success($this->services->getLogList($where, (int)$this->adminInfo['level']));
    }

    public function search_admin(SystemAdminServices $services)
    {
        $info = $services->getOrdAdmin('id,real_name', $this->adminInfo['level']);
        return app('json')->success(compact('info'));
    }

}

