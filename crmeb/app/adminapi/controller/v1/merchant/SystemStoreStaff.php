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
namespace app\adminapi\controller\v1\merchant;

use app\services\system\store\SystemStoreServices;
use app\services\system\store\SystemStoreStaffServices;
use think\facade\App;
use app\adminapi\controller\AuthController;

/**
 * nhân viên văn phòng
 * Class SystemStoreStaff
 * @package app\adminapi\controller\v1\merchant
 */class SystemStoreStaff extends AuthController
{
    /**
     * Người xây dựng
     * SystemStoreStaff constructor.
     * @param App $app
     * @param SystemStoreStaffServices $services
     */    public function __construct(App $app, SystemStoreStaffServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lấy danh sách nhân viên
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $where = $this->request->getMore([
            [['store_id', 'd'], 0],
        ]);
        return app('json')->success($this->services->getStoreStaffList($where));
    }

    /**
     * Danh sách cửa hàng
     * @param SystemStoreServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function store_list(SystemStoreServices $services)
    {
        return app('json')->success($services->getStore());
    }

    /**
     * Nhân viên cửa hàng bổ sung mẫu đơn
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function create()
    {
        return app('json')->success($this->services->createForm());
    }

    /**
     * Thư ký sửa đổi mẫu đơn
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function edit()
    {
        [$id] = $this->request->getMore([
            [['id', 'd'], 0],
        ], true);
        return app('json')->success($this->services->updateForm($id));
    }

    /**
     * Lưu thông tin nhân viên cửa hàng
     * @param int $id
     * @return mixed
     */    public function save($id = 0)
    {
        $data = $this->request->postMore([
            ['image', ''],
            ['uid', 0],
            ['avatar', ''],
            ['store_id', ''],
            ['staff_name', ''],
            ['phone', ''],
            ['verify_status', 1],
            ['status', 1],
        ]);
        if (!$id) {
            if ($data['image'] == '') {
                return app('json')->fail('Vui lòng chọn Khách hàng');
            }
            if ($this->services->count(['uid' => $data['image']['uid']])) {
                return app('json')->fail('Người dùng bảo lãnh được thêm vào đã tồn tại');
            }
            $data['uid'] = $data['image']['uid'];
            $data['avatar'] = $data['image']['image'];
        } else {
            $data['avatar'] = $data['image'];
        }
        if ($data['uid'] == 0) {
            return app('json')->fail('Vui lòng chọn Khách hàng');
        }
        if ($data['store_id'] == '') {
            return app('json')->fail('Vui lòng chọn điểm đón của bạn');
        }
        if ($data['staff_name'] == ''){
            return app('json')->fail('Vui lòng điền tên người bảo lãnh');
        }
        if ($data['phone'] == ''){
            return app('json')->fail('Vui lòng điền số điện thoại của người bảo lãnh');
        }
        unset($data['image']);
        if ($id) {
            $res = $this->services->update($id, $data);
            if ($res) {
                return app('json')->success('Sửa đổi thành công');
            } else {
                return app('json')->fail('Sửa đổi không thành công');
            }
        } else {
            $data['add_time'] = time();
            $res = $this->services->save($data);
            if ($res) {
                return app('json')->success('Người bảo lãnh được thêm thành công');
            } else {
                return app('json')->fail('Không thể thêm người bảo lãnh');
            }
        }
    }

    /**
     * Đặt xem có bật một nhân viên bán hàng hay không
     * @param string $is_show
     * @param string $id
     * @return mixed
     */    public function set_show($is_show = '', $id = '')
    {
        if ($is_show == '' || $id == '') {
            app('json')->fail('Lỗi tham số');
        }
        $res = $this->services->update($id, ['status' => (int)$is_show]);
        if ($res) {
            return app('json')->success('Thiết lập thành công');
        } else {
            return app('json')->fail('Thiết lập không thành công');
        }
    }

    /**
     * Xóa thư ký
     * @param $id
     * @return mixed
     */    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else
            return app('json')->success('Xóa thành công');
    }
}
