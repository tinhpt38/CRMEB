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
use app\services\system\SystemRouteServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Class SystemRoute
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRoute extends AuthController
{

    /**
     * SystemRoute constructor.
     * @param App $app
     * @param SystemRouteServices $services
     */
    public function __construct(App $app, SystemRouteServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Đồng bộ hóa quyền định tuyến
     * @param string $appName
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function syncRoute(string $appName = 'adminapi')
    {
        $this->services->syncRoute($appName);

        return app('json')->success('Đồng bộ hóa thành công');
    }

    /**
     * Liệt kê dữ liệu
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi']
        ]);

        return app('json')->success($this->services->getList($where));
    }

    /**
     * treedữ liệu
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function tree()
    {
        [$name, $appName] = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi']
        ], true);

        return app('json')->success($this->services->getTreeList($appName, $name));
    }


    /**
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function save($id = 0)
    {
        $data = $this->request->postMore([
            ['cate_id', 0],
            ['name', ''],
            ['path', ''],
            ['method', ''],
            ['type', 0],
            ['app_name', ''],
            ['query', []],
            ['header', []],
            ['request', []],
            ['response', []],
            ['request_example', []],
            ['response_example', []],
            ['describe', ''],
            ['error_code', []],
        ]);

//        if (!$data['name']) {
//            return app('json')->fail('Tên giao diện không được để trống');
//        }
//        if (!$data['path']) {
//            return app('json')->fail('Địa chỉ giao diện không thể trống');
//        }
//        if (!$data['method']) {
//            return app('json')->fail('Phương thức yêu cầu không được để trống');
//        }
//        if (!$data['app_name']) {
//            return app('json')->fail('Phân loại mô-đun không được để trống');
//        }
        if ($id) {
            $this->services->update($id, $data);
        } else {
            $data['add_time'] = date('Y-m-d H:i:s');
            $this->services->save($data);
        }
        CacheService::clear();

        return app('json')->success($id ? 'Sửa đổi thành công' : 'Đã thêm thành công');
    }

    /**
     * @param $id
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function read($id)
    {
        return app('json')->success($this->services->getInfo((int)$id));
    }

    /**
     * @param $id
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function delete($id)
    {
        if (!$id) {
            return app('json')->fail('giao diện không tồn tại');
        }

        $this->services->destroy($id);

        return app('json')->success('Xóa thành công');
    }
}
