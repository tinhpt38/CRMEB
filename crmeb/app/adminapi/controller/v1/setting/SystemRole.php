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
use app\services\system\admin\SystemAdminServices;
use app\services\system\admin\SystemRoleServices;
use app\services\system\SystemMenusServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Class SystemRole
 * @package app\adminapi\controller\v1\setting
 */
class SystemRole extends AuthController
{
    /**
     * SystemRole constructor.
     * @param App $app
     * @param SystemRoleServices $services
     */
    public function __construct(App $app, SystemRoleServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['role_name', ''],
        ]);
        $where['level'] = $this->adminInfo['level'] + 1;
        return app('json')->success($this->services->getRoleList($where));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên
     * @param SystemMenusServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create(SystemMenusServices $services)
    {
        $menus = $services->getmenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles']);
        return app('json')->success(compact('menus'));
    }

    /**
     * Lưu tài nguyên mới
     *
     * @return \think\Response
     */
    public function save($id)
    {
        $data = $this->request->postMore([
            'role_name',
            ['status', 0],
            ['checked_menus', [], '', 'rules']
        ]);
        if (!$data['role_name']) return app('json')->fail('Vui lòng nhập tên nhận dạng của bạn');
        if (!is_array($data['rules']) || !count($data['rules']))
            return app('json')->fail('Vui lòng chọn ít nhất một quyền');

        $data['rules'] = implode(',', $data['rules']);
        if ($id) {
            if (!$this->services->update($id, $data)) return app('json')->fail('Sửa đổi không thành công');
            CacheService::clear();
            return app('json')->success('Sửa đổi thành công');
        } else {
            $data['level'] = $this->adminInfo['level'] + 1;
            if (!$this->services->save($data)) return app('json')->fail('Không thêm được danh tính');
            CacheService::clear();
            return app('json')->success('Thêm danh tính thành công');
        }
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa
     * @param SystemMenusServices $services
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(SystemMenusServices $services, $id)
    {
        $role = $this->services->get($id);
        if (!$role) {
            return app('json')->fail('Lỗi tham số');
        }
        $menus = $services->getMenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles'], explode(',', $role['rules']));
        return app('json')->success(['role' => $role->toArray(), 'menus' => $menus]);
    }

    /**
     * Xóa tài nguyên được chỉ định
     * @param SystemAdminServices $adminServices
     * @param $id
     * @return mixed
     */
    public function delete(SystemAdminServices $adminServices, $id)
    {
        if ($adminServices->checkRoleUse($id)) {
            return app('json')->fail('Danh tính đang được sử dụng và không thể xóa được.');
        }
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else {
            CacheService::clear();
            return app('json')->success('Xóa thành công');
        }
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status($id, $status)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }
        $role = $this->services->get($id);
        if (!$role) {
            return app('json')->fail('Danh tính này không được tìm thấy');
        }
        $role->status = $status;
        if ($role->save()) {
            CacheService::clear();
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }
}
