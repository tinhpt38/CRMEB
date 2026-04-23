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
namespace app\adminapi\controller\v1\product;

use app\adminapi\controller\AuthController;
use app\services\product\sku\StoreProductRuleServices;
use think\facade\App;

/**
 * Quản lý quy tắc
 * Class StoreProductRule
 * @package app\adminapi\controller\v1\product
 */
class StoreProductRule extends AuthController
{

    public function __construct(App $app, StoreProductRuleServices $service)
    {
        parent::__construct($app);
        $this->services = $service;
    }

    /**
     * Danh sách đặc điểm kỹ thuật
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['rule_name', '']
        ]);
        $list = $this->services->getList($where);
        return app('json')->success($list);
    }

    /**
     * lưu thông số kỹ thuật
     * @param $id
     * @return mixed
     */
    public function save($id)
    {
        $data = $this->request->postMore([
            ['rule_name', ''],
            ['spec', []]
        ]);
        $this->services->save($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Nhận thông tin đặc điểm kỹ thuật
     * @param $id
     * @return mixed
     */
    public function read($id)
    {
        $info = $this->services->getInfo($id);
        return app('json')->success($info);
    }

    /**
     * Xóa tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete()
    {
        [$ids] = $this->request->postMore([
            ['ids', '']
        ], true);
        $this->services->del((string)$ids);
        return app('json')->success('Xóa thành công');
    }
}
