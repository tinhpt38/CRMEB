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
use app\services\system\SystemMenusServices;
use app\services\system\SystemRouteCateServices;
use app\services\system\SystemRouteServices;
use think\facade\App;
use think\facade\Route;

/**
 * Quyền thực đơn
 * Class SystemMenus
 * @package app\adminapi\controller\v1\setting
 */class SystemMenus extends AuthController
{
    /**
     * SystemMenus constructor.
     * @param App $app
     * @param SystemMenusServices $services
     */    public function __construct(App $app, SystemMenusServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
        $this->request->filter(['addslashes', 'trim']);
    }

    /**
     * Danh sách hiển thị menu
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/05/06
     */    public function index()
    {
        $where = $this->request->getMore([
            ['is_show', ''],
            ['keyword', ''],
            ['auth_type', ''],
        ]);
        return app('json')->success($this->services->getList($where));
    }

    /**
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/14
     */    public function unique()
    {
        $adminInfo = $this->request->adminInfo();
        [$menus, $uniqueAuth] = app()->make(SystemMenusServices::class)->getMenusList($adminInfo['roles'], (int)$adminInfo['level']);
        return app('json')->success(compact('menus', 'uniqueAuth'));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên.
     *
     * @return \think\Response
     */    public function create()
    {

        return app('json')->success($this->services->createMenus());
    }

    /**
     * Lưu quyền thực đơn
     * @return mixed
     */    public function save()
    {
        $data = $this->request->getMore([
            ['menu_name', ''],
            ['controller', ''],
            ['module', 'admin'],
            ['action', ''],
            ['icon', ''],
            ['params', ''],
            ['path', []],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['header', ''],
            ['is_header', 0],
            ['pid', 0],
            ['sort', 0],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        $data['is_show_path'] = $data['is_show'];
        if (!$data['menu_name'])
            return app('json')->fail('Vui lòng điền tên nút');
        $data['path'] = implode('/', $data['path']);
        if ($this->services->save($data)) {
            return app('json')->success('Đã thêm thành công');
        } else {
            return app('json')->fail('Thêm không thành công');
        }
    }

    /**
     * Quyền lưu hàng loạt
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/11
     */    public function batchSave()
    {
        $menus = $this->request->post('menus', []);
        if (!$menus) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        $data = [];

        $uniqueAuthAll = $this->services->getColumn(['is_del' => 0, 'is_show' => 1], 'unique_auth');
        $uniqueAuthAll = array_filter($uniqueAuthAll, function ($item) {
            return !!$item;
        });
        $uniqueAuthAll = array_unique($uniqueAuthAll);

        $uniqueFn = function ($path) use ($uniqueAuthAll) {
            $attPath = explode('/', $path);
            $uniqueAuth = '';
            if ($attPath) {
                $pathData = [];
                foreach ($attPath as $vv) {
                    if (strstr($vv, '<') === false) {
                        $pathData[] = $vv;
                    }
                }
                $uniqueAuth = implode('-', $pathData);
            }

            if (in_array($uniqueAuth, $uniqueAuthAll)) {
                $uniqueAuth .= '-' . uniqid();
            }

            array_push($uniqueAuthAll, $uniqueAuth);

            return $uniqueAuth;
        };

        foreach ($menus as $menu) {
            if (empty($menu['menu_name'])) {
                return app('json')->fail('Vui lòng điền tên nút');
            }
            if (isset($menu['unique_auth']) && $menu['unique_auth']) {
                $menu['unique_auth'] = explode('/', $menu['api_url']);
            }
            $data[] = [
                'methods' => $menu['method'],
                'menu_name' => $menu['menu_name'],
                'unique_auth' => !empty($menu['unique_auth']) ? $menu['unique_auth'] : $uniqueFn($menu['api_url']),
                'api_url' => $menu['api_url'],
                'pid' => $menu['path'],
                'auth_type' => 2,
                'is_show' => 1,
                'is_show_path' => 1,
            ];
        }

        $this->services->saveAll($data);

        return app('json')->success('Đã thêm thành công');
    }

    /**
     * Nhận thông tin cho phép menu
     * @param int $id
     * @return \think\Response
     */    public function read($id)
    {

        if (!$id) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        return app('json')->success($this->services->find((int)$id));
    }

    /**
     * Sửa đổi việc mua lại biểu mẫu cấp phép menu
     * @param int $id
     * @return \think\Response
     */    public function edit($id)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }
        return app('json')->success($this->services->updateMenus((int)$id));
    }

    /**
     * Sửa đổi trình đơn
     * @param $id
     * @return mixed
     */    public function update($id)
    {
        if (!$id || !($menu = $this->services->get($id)))
            return app('json')->fail('Dữ liệu không tồn tại');
        $data = $this->request->postMore([
            'menu_name',
            'controller',
            ['module', 'admin'],
            'action',
            'params',
            ['icon', ''],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['path', []],
            ['sort', 0],
            ['pid', 0],
            ['is_header', 0],
            ['header', ''],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        if (!$data['menu_name'])
            return app('json')->fail('Vui lòng điền tên nút');
        $data['path'] = implode('/', $data['path']);
        if ($this->services->update($id, $data))
            return app('json')->success('Sửa đổi thành công');
        else
            return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }

        if (!$this->services->delete((int)$id)) {
            return app('json')->fail('Xóa không thành công');
        } else {
            return app('json')->success('Xóa thành công');
        }
    }

    /**
     * Bật và tắt quyền, hiển thị và ẩn chúng
     * @param $id
     * @return mixed
     */    public function show($id)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }

        [$isShow, $isShowPath] = $this->request->postMore([['is_show', 0], ['is_show_path', 0]], true);
        if ($isShow == -1) {
            $res = $this->services->update($id, ['is_show_path' => $isShowPath]);
        } else {
            $res = $this->services->update($id, ['is_show' => $isShow, 'is_show_path' => $isShow]);
        }

        if ($res) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }

    /**
     * Lấy dữ liệu thực đơn
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function menus()
    {
        [$menus, $unique] = $this->services->getMenusList($this->adminInfo['roles'], (int)$this->adminInfo['level']);
        return app('json')->success(['menus' => $menus, 'unique' => $unique]);
    }

    /**
     * Nhận phân loại tuyến đường
     * @param SystemRouteCateServices $service
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/25
     */    public function ruleCate(SystemRouteCateServices $service)
    {
        return app('json')->success($service->getAllList('adminapi'));
    }

    /**
     * Nhận danh sách giao diện
     * @return array
     */    public function ruleList(SystemRouteServices $services)
    {
        $cateId = request()->get('cate_id', 0);
        //Nhận Tất cả các tuyến đường
        $ruleList = $services->selectList(['cate_id' => $cateId, 'app_name' => 'adminapi'])->toArray();
        return app('json')->success($ruleList);
    }
}
