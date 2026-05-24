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
use app\services\system\log\ClearServices;
use app\adminapi\controller\AuthController;

/**
 * Bộ điều khiển gia đình
 * Class Clear
 * @package app\admin\controller
 *
 */class Clear extends AuthController
{
    public function __construct(App $app, ClearServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Làm mới bộ đệm dữ liệu
     */    public function refresh_cache()
    {
        $this->services->refresCache();
        return app('json')->success('Đã làm mới bộ đệm dữ liệu thành công');
    }


    /**
     * Xóa nhật ký
     */    public function delete_log()
    {
        $this->services->deleteLog();
        return app('json')->success('Xóa thành công');
    }
}


