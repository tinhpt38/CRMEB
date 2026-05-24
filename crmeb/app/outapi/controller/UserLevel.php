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
namespace app\outapi\controller;

use app\services\user\OutUserLevelServices;
use think\facade\App;

/**
 * Cấp độ thành viên
 * Class UserLevel
 * @package app\outapi\controller
 */class UserLevel extends AuthController
{

    /**
     * UserLevel constructor.
     * @param App $app
     * @param OutUserLevelServices $services
     */    public function __construct(App $app, OutUserLevelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách bậc
     * @return void
     */    public function lst()
    {
        $where = $this->request->getMore([
            ['title', ''],
            ['is_show', ''],
        ]);

        return app('json')->success($this->services->levelList($where));
    }

}