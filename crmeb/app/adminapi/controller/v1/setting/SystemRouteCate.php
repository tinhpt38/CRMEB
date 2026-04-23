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
use app\services\system\SystemRouteCateServices;
use app\services\system\SystemRouteServices;
use think\facade\App;
use think\Request;

/**
 * Class SystemRouteCate
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRouteCate extends AuthController
{

    /**
     * SystemRouteCate constructor.
     * @param App $app
     * @param SystemRouteCateServices $services
     */
    public function __construct(App $app, SystemRouteCateServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function index()
    {
        return app('json')->success($this->services->getAllList());
    }

    /**
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function create()
    {
        return app('json')->success($this->services->getFrom(0, $this->request->get('app_name', 'adminapi')));
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function save(Request $request)
    {
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return app('json')->fail('Tên phân loại giao diện không được để trống');
        }

        $data['add_time'] = time();
        $data['pid'] = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->save($data);


        return app('json')->success('Đã lưu thành công');

    }

    /**
     * @param $id
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function edit($id)
    {
        return app('json')->success($this->services->getFrom($id, $this->request->get('app_name', 'adminapi')));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function update(Request $request, $id)
    {
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return app('json')->fail('Tên phân loại giao diện không được để trống');
        }

        $data['pid'] = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->update($id, $data);

        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * @param SystemRouteServices $service
     * @param $id
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function delete(SystemRouteServices $service, $id)
    {
        if (!$id) {
            return app('json')->fail('giao diện không tồn tại');
        }

        if ($service->count(['cate_id' => $id])) {
            return app('json')->fail('Có các giao diện thuộc danh mục này và không thể xóa được.');
        }

        $this->services->delete($id);

        return app('json')->success('Xóa thành công');
    }
}
