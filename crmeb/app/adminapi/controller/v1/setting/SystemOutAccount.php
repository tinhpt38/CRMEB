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
use app\outapi\validate\StoreOutAccountValidate;
use app\services\out\OutAccountServices;
use app\services\out\OutInterfaceServices;
use think\facade\App;

/**
 * Tài khoản giao diện bên ngoài
 * Class SystemOutAccount
 * @package app\adminapi\controller\v1\setting
 */
class SystemOutAccount extends AuthController
{
    /**
     * Người xây dựng
     * SystemOut constructor.
     * @param App $app
     * @param OutAccountServices $services
     */
    public function __construct(App $app, OutAccountServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Thông tin tài khoản
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['name', '', ''],
            ['status', ''],
        ]);
        return app('json')->success($this->services->getList($where));
    }

    /**
     * Sửa đổi trạng thái
     * @param string $status
     * @param string $id
     * @return mixed
     */
    public function set_status($id = '', $status = '')
    {
        if ($status == '' || $id == '') return app('json')->fail('Lỗi tham số');
        $this->services->update($id, ['status' => $status]);
        return app('json')->success($status == 1 ? 'Cập nhật thành công' : 'Cập nhật không thành công');
    }

    /**
     * xóa bỏ
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($id == '') return app('json')->fail('Lỗi tham số');
        $this->services->update($id, ['is_del' => 1]);
        return app('json')->success('Xóa thành công');
    }

    /**
     * cứu
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        $data = $this->request->postMore([
            [['appid', 's'], ''],
            [['appsecret', 's'], ''],
            [['title', 's'], ''],
            ['rules', []],
        ]);
        $this->validate($data, StoreOutAccountValidate::class, 'save');
        if ($this->services->getOne(['appid' => $data['appid']])) return app('json')->fail('Tài khoản trùng lặp');
        $data['apppwd'] = $data['appsecret'];
        $data['appsecret'] = password_hash($data['appsecret'], PASSWORD_DEFAULT);
        $data['add_time'] = time();
        $data['rules'] = implode(',', $data['rules']);
        if (!$this->services->save($data)) {
            return app('json')->fail('Lưu không thành công');
        } else {
            return app('json')->success('Đã lưu thành công');
        }
    }

    /**
     * Ôn lại
     * @param string $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id = '')
    {
        $data = $this->request->postMore([
            [['appsecret', 's'], ''],
            [['title', 's'], ''],
            ['rules', []],
        ]);

        $this->validate($data, StoreOutAccountValidate::class, 'update');
        if (!$this->services->getOne(['id' => $id])) return app('json')->fail('Không có tài khoản như vậy');
        $data['apppwd'] = $data['appsecret'];
        $data['appsecret'] = password_hash($data['appsecret'], PASSWORD_DEFAULT);
        $data['rules'] = implode(',', $data['rules']);
        $res = $this->services->update($id, $data);
        if (!$res) {
            return app('json')->fail('Lưu không thành công');
        } else {
            return app('json')->success('Đã lưu thành công');
        }
    }

    /**
     * Thiết lập giao diện đẩy tài khoản
     * @param $id
     * @return mixed
     */
    public function outSetUpSave($id)
    {
        $data = $this->request->postMore([
            ['push_open', 0],
            ['push_account', ''],
            ['push_password', ''],
            ['push_token_url', ''],
            ['user_update_push', ''],
            ['order_create_push', ''],
            ['order_pay_push', ''],
            ['refund_create_push', ''],
            ['refund_cancel_push', ''],
        ]);
        $this->services->outSetUpSave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Danh sách giao diện bên ngoài
     * @param OutInterfaceServices $service
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function outInterfaceList(OutInterfaceServices $service)
    {
        return app('json')->success($service->outInterfaceList());
    }

    /**
     * Lưu tài liệu giao diện
     * @param $id
     * @param OutInterfaceServices $service
     * @return mixed
     */
    public function saveInterface($id, OutInterfaceServices $service)
    {
        $data = $this->request->postMore([
            ['pid', 0], //Thượng đẳngid
            ['type', 0], //Loại 0 Menu 1 Giao diện
            ['name', ''], //tên
            ['describe', ''], //minh họa
            ['method', ''], //phương pháp
            ['url', ''], //Địa chỉ liên kết
            ['request_params', []], //Thông số yêu cầu
            ['return_params', []], //Trả về tham số
            ['request_example', ''], //Yêu cầu ví dụ
            ['return_example', ''], //Trả về ví dụ
            ['error_code', []] //mã lỗi
        ]);
        $service->saveInterface((int)$id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Tài liệu giao diện bên ngoài
     * @param $id
     * @param OutInterfaceServices $service
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function interfaceInfo($id, OutInterfaceServices $service)
    {
        return app('json')->success($service->interfaceInfo($id));
    }

    /**
     * Sửa đổi tên giao diện
     * @param OutInterfaceServices $service
     * @return mixed
     */
    public function editInterfaceName(OutInterfaceServices $service)
    {
        $data = $this->request->postMore([
            ['id', 0], //Thượng đẳngid
            ['name', ''], //tên
        ]);
        if (!$data['id'] || !$data['name']) {
            return app('json')->success('Lỗi tham số');
        }
        $service->editInterfaceName($data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa giao diện
     * @param $id
     * @param OutInterfaceServices $service
     * @return mixed
     */
    public function delInterface($id, OutInterfaceServices $service)
    {
        if (!$id) return app('json')->success('Lỗi tham số');
        $service->delInterface($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Kiểm tra giao diện mã thông báo
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function textOutUrl()
    {
        $data = $this->request->postMore([
            ['push_account', 0],
            ['push_password', 0],
            ['push_token_url', '']
        ]);
        return app('json')->success('Thiết lập thành công', $this->services->textOutUrl($data));
    }
}
