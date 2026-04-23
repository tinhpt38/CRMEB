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
namespace app\adminapi\controller\v1\kefu;

use app\adminapi\controller\AuthController;
use app\services\kefu\LoginServices;
use app\services\kefu\service\StoreServiceLogServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\user\UserServices;
use app\services\user\UserWechatuserServices;
use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Quản lý dịch vụ khách hàng
 * Class StoreService
 * @package app\admin\controller\store
 */
class StoreService extends AuthController
{
    /**
     * StoreService constructor.
     * @param App $app
     * @param StoreServiceServices $services
     */
    public function __construct(App $app, StoreServiceServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        return app('json')->success($this->services->getServiceList([]));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên
     * @param UserWechatuserServices $services
     * @return mixed
     */
    public function create(UserWechatuserServices $services)
    {
        $where = $this->request->getMore([
            ['nickname', ''],
            ['data', '', '', 'time'],
            ['type', '', '', 'user_type'],
        ]);
        $where['is_del'] = 0;
        [$list, $count] = $services->getWhereUserList($where, 'u.nickname,u.uid,u.avatar as headimgurl,w.subscribe,w.province,w.country,w.city,w.sex,u.user_type,u.is_del,u.phone,u.add_time');
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d', $item['add_time']);
        }
        return app('json')->success(compact('list', 'count'));
    }

    /**
     * Thêm biểu mẫu dịch vụ khách hàng
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function add()
    {
        return app('json')->success($this->services->create());
    }

    /**
     * Lưu tài nguyên mới
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['image', ''],
            ['uid', 0],
            ['avatar', ''],
            ['customer', ''],
            ['notify', ''],
            ['phone', ''],
            ['account', ''],
            ['password', ''],
            ['true_password', ''],
            ['phone', ''],
            ['nickname', ''],
            ['status', 1],
        ]);
        if ($data['image'] == '') return app('json')->fail('Vui lòng chọn người dùng');
        $data['uid'] = $data['image']['uid'];
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);
        $userInfo = $userService->get($data['uid']);
        if ($data['phone'] == '') {
            if (!$userInfo['phone']) {
                throw new AdminException('Người dùng này không có số điện thoại di động bị ràng buộc, vui lòng điền thủ công');
            } else {
                $data['phone'] = $userInfo['phone'];
            }
        } else {
            if (!check_phone($data['phone'])) {
                throw new AdminException('Lỗi định dạng số điện thoại di động');
            }
        }
        if ($data['nickname'] == '') $data['nickname'] = $userInfo['nickname'];
        $data['avatar'] = $data['image']['image'];
        if ($this->services->count(['uid' => $data['uid']])) {
            return app('json')->fail('Dịch vụ khách hàng đã tồn tại');
        }
        unset($data['image']);
        $data['add_time'] = time();
        if (!$data['account']) {
            return app('json')->fail('Vui lòng nhập số tài khoản');
        }
        if (!preg_match('/^[a-zA-Z0-9]{4,30}$/', $data['account'])) {
            return app('json')->fail('Số tài khoản phải là sự kết hợp của các số hoặc chữ cái từ 4-30 chữ số');
        }
        if (!$data['password']) {
            return app('json')->fail('Vui lòng nhập mật khẩu');
        }
        if (!preg_match('/^[0-9a-z_$]{6,20}$/i', $data['password'])) {
            return app('json')->fail('Mật khẩu phải là sự kết hợp của số hoặc chữ cái từ 6-20 ký tự');
        }
        if ($this->services->count(['phone' => $data['phone']])) {
            return app('json')->fail('Dịch vụ khách hàng cho số điện thoại di động này đã tồn tại');
        }
        if ($this->services->count(['account' => $data['account']])) {
            return app('json')->fail('Tài khoản dịch vụ khách hàng này đã tồn tại');
        }
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $res = $this->services->save($data);
        if ($res) {
            return app('json')->success('Đã thêm dịch vụ khách hàng thành công');
        } else {
            return app('json')->fail('Bổ sung dịch vụ khách hàng không thành công');
        }
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit($id)
    {
        return app('json')->success($this->services->edit((int)$id));
    }

    /**
     * Lưu tài nguyên mới
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $data = $this->request->postMore([
            ['avatar', ''],
            ['nickname', ''],
            ['account', ''],
            ['phone', ''],
            ['status', 1],
            ['notify', 1],
            ['customer', 1],
            ['password', ''],
            ['true_password', ''],
        ]);
        $customer = $this->services->get((int)$id);
        if (!$customer) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        if ($data["nickname"] == '') {
            return app('json')->fail('Tên dịch vụ khách hàng không được để trống');
        }
        if (!check_phone($data['phone'])) {
            return app('json')->fail('Lỗi định dạng số điện thoại di động');
        }
        if ($customer['phone'] != $data['phone'] && $this->services->count(['phone' => $data['phone']])) {
            return app('json')->fail('Dịch vụ khách hàng cho số điện thoại di động này đã tồn tại');
        }
        if ($data['password']) {
            if (!preg_match('/^[0-9a-z_$]{6,16}$/i', $data['password'])) {
                return app('json')->fail('Mật khẩu phải là sự kết hợp của số hoặc chữ cái từ 6-20 ký tự');
            }
            if (!$data['true_password']) {
                return app('json')->fail('Vui lòng nhập mật khẩu xác nhận');
            }
            if ($data['password'] != $data['true_password']) {
                return app('json')->fail('Mật khẩu nhập hai lần không nhất quán');
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->services->update($id, $data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else
            return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param UserServices $services
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status(UserServices $services, $id, $status)
    {
        if ($status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        $info = $this->services->get($id, ['status', 'uid']);
        if (!$services->count(['uid' => $info['uid']])) {
            $info->status = 1;
            $info->save();
            return app('json')->fail('Nếu người dùng không tồn tại, dịch vụ khách hàng sẽ buộc phải vô hiệu hóa đăng nhập.');
        }
        $info->status = $status;
        $info->save();
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Lịch sử trò chuyện
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function chat_user($id)
    {
        $uid = $this->services->value(['id' => $id], 'uid');
        if (!$uid) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        return app('json')->success($this->services->getChatUser((int)$uid));
    }


    /**
     * Lịch sử trò chuyện
     * @param StoreServiceLogServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function chat_list(StoreServiceLogServices $services)
    {
        $data = $this->request->getMore([
            ['uid', 0],
            ['to_uid', 0],
            ['id', 0]
        ]);
        if ($data['uid']) {
            CacheService::set('admin_chat_list' . $this->adminId, $data);
        }
        $data = CacheService::get('admin_chat_list' . $this->adminId);
        if ($data['uid']) {
            $where = [
                'chat' => [$data['uid'], $data['to_uid']],
            ];
        } else {
            $where = [];
        }
        $list = $services->getChatLogList($where);
        return app('json')->success($list);
    }

    /**
     * Đăng nhập dịch vụ khách hàng
     * @param LoginServices $services
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function keufLogin(LoginServices $services, $id)
    {
        $serviceInfo = $services->get($id);
        if (!$serviceInfo) {
            return app('json')->fail('Đăng nhập dịch vụ khách hàng không tồn tại');
        }
        if (!$serviceInfo->account || !$serviceInfo->password) {
            return app('json')->fail('Vui lòng điền tài khoản và mật khẩu dịch vụ khách hàng trước khi thử vào nền tảng dịch vụ khách hàng');
        }
        return app('json')->success($services->authLogin($serviceInfo->account));
    }

}
