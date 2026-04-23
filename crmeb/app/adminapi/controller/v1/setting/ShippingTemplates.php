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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\shipping\ShippingTemplatesServices;
use app\services\shipping\SystemCityServices;
use think\facade\App;

/**
 * Mẫu vận chuyển hàng hóa
 * Class ShippingTemplates
 * @package app\adminapi\controller\v1\setting
 */
class ShippingTemplates extends AuthController
{
    /**
     * Người xây dựng
     * ShippingTemplates constructor.
     * @param App $app
     * @param ShippingTemplatesServices $services
     */
    public function __construct(App $app, ShippingTemplatesServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách mẫu vận chuyển hàng hóa
     * @return mixed
     */
    public function temp_list()
    {
        $where = $this->request->getMore([
            [['name', 's'], '']
        ]);
        return app('json')->success($this->services->getShippingList($where));
    }

    /**
     * Ôn lại
     * @return string
     * @throws \Exception
     */
    public function edit($id)
    {
        return app('json')->success($this->services->getShipping((int)$id));
    }

    /**
     * Lưu hoặc sửa đổi
     * @param int $id
     */
    public function save($id = 0)
    {
        $data = $this->request->postMore([
            [['region_info', 'a'], []],
            [['appoint_info', 'a'], []],
            [['no_delivery_info', 'a'], []],
            [['sort', 'd'], 0],
            [['type', 'd'], 0],
            [['name', 's'], ''],
            [['appoint', 'd'], 0],
            [['no_delivery', 'd'], 0]
        ]);
        $this->validate($data, \app\adminapi\validate\setting\ShippingTemplatesValidate::class, 'save');
        $temp['name'] = $data['name'];
        $temp['type'] = $data['type'];
        $temp['appoint'] = $data['appoint'] && $data['appoint_info'] ? 1 : 0;
        $temp['no_delivery'] = $data['no_delivery'] && $data['no_delivery_info'] ? 1 : 0;
        $temp['sort'] = $data['sort'];
        $temp['add_time'] = time();
        $this->services->save((int)$id, $temp, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa mẫu vận chuyển
     */
    public function delete()
    {
        [$id] = $this->request->getMore([
            [['id', 'd'], 0],
        ], true);
        if ($id == 1) {
            return app('json')->fail('Không thể xóa mẫu mặc định');
        } else {
            $this->services->detete($id);
            return app('json')->success('Xóa thành công');
        }
    }

    /**
     * dữ liệu thành phố
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function city_list(SystemCityServices $services)
    {
        return app('json')->success($services->getShippingCity());
    }
}
