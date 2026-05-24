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
use app\services\activity\integral\StoreIntegralServices;

class StoreIntegralController
{
    protected $services;

    public function __construct(StoreIntegralServices $services)
    {
        $this->services = $services;
    }

    /**
     * Dữ liệu trang chủ của trung tâm mua sắm Points
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $data['banner'] = sys_data('integral_shop_banner') ?? [];//TODO Trung tâm mua sắm điểmbanner
        $where = ['is_show' => 1];
        $where['is_host'] = 1;
        $data['list'] = $this->services->getIntegralList($where);
        return app('json')->success(get_thumb_water($data, 'big'));
    }

    /**
     * Danh sách sản phẩm
     * @param Request $request
     * @return mixed
     */    public function lst(Request $request)
    {
        $where = $request->getMore([
            ['store_name', ''],
            ['priceOrder', ''],
            ['salesOrder', ''],
        ]);
        $where['is_show'] = 1;
        $list = $this->services->getIntegralList($where);
        return app('json')->success(get_thumb_water($list, 'mid'));
    }

    /**
     * Chi tiết sản phẩm điểm
     * @param Request $request
     * @param $id
     * @return mixed
     */    public function detail(Request $request, $id)
    {
        $data = $this->services->integralDetail($request, $id);
        return app('json')->success($data);
    }
}
