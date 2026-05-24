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
use crmeb\services\CacheService;
use think\facade\{App, Config};

/**
 * Class SystemAdmin
 * @package app\adminapi\controller\v1\setting
 */class SystemAdmin extends AuthController
{
    /**
     * SystemAdmin constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên quản trị viên
     *
     * @return \think\Response
     */    public function index()
    {
        $where = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['roles', ''],
            ['is_del', 1],
            ['status', '']
        ]);
        $where['level'] = $this->adminInfo['level'] + 1;
        return app('json')->success($this->services->getAdminList($where));
    }

    /**
     * Tạo biểu mẫu
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function create()
    {
        return app('json')->success($this->services->createForm($this->adminInfo['level'] + 1));
    }

    /**
     * lưu quản trị viên
     * @return mixed
     */    public function save()
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['roles', []],
            ['status', 0],
        ]);

        $this->validate($data, \app\adminapi\validate\setting\SystemAdminValidata::class);

        $data['level'] = $this->adminInfo['level'] + 1;
        $this->services->create($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa.
     *
     * @param int $id
     * @return \think\Response
     */    public function edit($id)
    {
        if (!$id) {
            return app('json')->fail('Không đọc được thông tin quản trị viên');
        }

        return app('json')->success($this->services->updateForm($this->adminInfo['level'] + 1, (int)$id));
    }

    /**
     * Sửa đổi thông tin quản trị viên
     * @param $id
     * @return mixed
     */    public function update($id)
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['roles', []],
            ['status', 0],
        ]);

        $this->validate($data, \app\adminapi\validate\setting\SystemAdminValidata::class, 'update');

        if ($this->services->save((int)$id, $data)) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }

    /**
     * Xóa quản trị viên
     * @param $id
     * @return mixed
     */    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        if ($this->services->update((int)$id, ['is_del' => 1, 'status' => 0]))
            return app('json')->success('Xóa thành công');
        else
            return app('json')->fail('Xóa không thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */    public function set_status($id, $status)
    {
        $this->services->update((int)$id, ['status' => $status]);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Nhận thông tin về quản trị viên hiện đang đăng nhập
     * @return mixed
     */    public function info()
    {
        return app('json')->success($this->adminInfo);
    }

    /**
     * Sửa đổi thông tin quản trị viên đăng nhập hiện tại
     * @return mixed
     */    public function update_admin()
    {
        $data = $this->request->postMore([
            ['real_name', ''],
            ['head_pic', ''],
            ['pwd', ''],
            ['new_pwd', ''],
            ['conf_pwd', ''],
        ]);

        if ($data['pwd']) {
            if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['new_pwd'])) {
                return app('json')->fail('Mật khẩu quá đơn giản, vui lòng nhập mật khẩu phức tạp hơn');
            }
        }

        if ($this->services->updateAdmin($this->adminId, $data))
            return app('json')->success('Sửa đổi thành công');
        else
            return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Sửa đổi mật khẩu quản lý tập tin của quản trị viên hiện đang đăng nhập
     * @return mixed
     */    public function set_file_password()
    {
        $data = $this->request->postMore([
            ['file_pwd', ''],
            ['conf_file_pwd', ''],
        ]);
        if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['file_pwd'])) {
            return app('json')->fail('Mật khẩu quá đơn giản, vui lòng nhập mật khẩu phức tạp hơn');
        }
        if ($this->services->setFilePassword($this->adminId, $data))
            return app('json')->success('Sửa đổi thành công');
        else
            return app('json')->fail('Sửa đổi không thành công');
    }

    /**
     * Đăng xuất
     * @return mixed
     */    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return app('json')->success();
    }
}
