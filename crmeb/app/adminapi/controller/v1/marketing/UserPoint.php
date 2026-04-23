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
namespace app\adminapi\controller\v1\marketing;

use app\adminapi\controller\AuthController;
use app\services\user\UserBillServices;
use think\facade\App;

/**
 * bộ điều khiển tích hợp
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class UserPoint extends AuthController
{

    /**
     * Finance constructor.
     * @param App $app
     * @param UserBillServices $services
     */
    public function __construct(App $app, UserBillServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách ghi điểm
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['start_time', ''],
            ['end_time', ''],
            ['nickname', ''],
            ['page', 1],
            ['limit', 10],
        ]);
        return app('json')->success($this->services->getPointList($where));
    }

    /**
     * Nhận thông tin tiêu đề của nhật ký điểm
     * @return mixed
     */
    public function integral_statistics()
    {
        $where = $this->request->getMore([
            ['start_time', ''],
            ['end_time', ''],
            ['nickname', ''],
        ]);
        return app('json')->success(['res' => $this->services->getUserPointBadgelist($where)]);
    }

}
