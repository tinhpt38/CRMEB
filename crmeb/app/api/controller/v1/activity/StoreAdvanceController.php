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

namespace app\api\controller\v1\activity;

use app\Request;
use app\services\activity\advance\StoreAdvanceServices;

/**
 * Bộ điều khiển trước khi bán
 * Class StoreAdvanceController
 * @package app\api\controller\v1\activity
 */class StoreAdvanceController
{
    /**
     * StoreAdvanceController constructor.
     * @param StoreAdvanceServices $services
     */    public function __construct(StoreAdvanceServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách bán trước
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index(Request $request)
    {
        $where = $request->getMore([
            ['time_type', 0]
        ]);
        $where['status'] = 1;
        $data = $this->services->getList($where);
        return app('json')->success($data);
    }

    /**
     * Chi tiết sản phẩm trước khi bán
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function detail(Request $request, $id)
    {
        $data = $this->services->getAdvanceinfo($request, $id);
        return app('json')->success($data);
    }
}
