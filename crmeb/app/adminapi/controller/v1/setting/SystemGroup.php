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

use app\services\system\config\SystemGroupDataServices;
use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\config\SystemGroupServices;

/**
 * Dữ liệu kết hợp
 * Class SystemGroup
 * @package app\adminapi\controller\v1\setting
 */
class SystemGroup extends AuthController
{
    /**
     * Người xây dựng
     * SystemGroup constructor.
     * @param App $app
     * @param SystemGroupServices $services
     */
    public function __construct(App $app, SystemGroupServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     *
     * @return \think\Response
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['title', '']
        ]);
        return app('json')->success($this->services->getGroupList($where));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * Lưu tài nguyên mới
     *
     * @return \think\Response
     */
    public function save()
    {
        $params = $this->request->postMore([
            ['name', ''],
            ['config_name', ''],
            [['cate_id', 'd'], 0],
            ['info', ''],
            ['typelist', []],
        ]);

        //Phán quyết tên nhóm dữ liệu
        if (!$params['name']) {
            return app('json')->fail('Vui lòng nhập tên');
        }
        if (!$params['config_name']) {
            return app('json')->fail('Vui lòng nhập tên cấu hình');
        }
        $data["name"] = $params['name'];
        $data["config_name"] = $params['config_name'];
        $data["info"] = $params['info'];
        $data["cate_id"] = $params['cate_id'];
        //Đánh giá thông tin hiện trường
        if (!count($params['typelist']))
            return app('json')->fail('Có ít nhất một trường');
        else {
            $validate = ["name", "type", "title", "description"];
            foreach ($params["typelist"] as $key => $value) {
                foreach ($value as $name => $field) {
                    if (empty($field["value"]) && in_array($name, $validate))
                        return app('json')->fail("Cánh đồng" . ($key + 1) . "：" . $field["placeholder"] . "không thể trống！");
                    else
                        $data["fields"][$key][$name] = $field["value"];
                }
            }
        }
        $data["fields"] = json_encode($data["fields"]);
        $this->services->save($data);
        \crmeb\services\CacheService::clear();
        return app('json')->success('Đã thêm nhóm dữ liệu thành công');
    }

    /**
     * Hiển thị tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $info = $this->services->get($id);
        $fields = json_decode($info['fields'], true);
        $type_list = [];
        foreach ($fields as $key => $v) {
            $type_list[$key]['name']['value'] = $v['name'];
            $type_list[$key]['title']['value'] = $v['title'];
            $type_list[$key]['type']['value'] = $v['type'];
            $type_list[$key]['param']['value'] = $v['param'];
        }
        $info['typelist'] = $type_list;
        unset($info['fields']);
        return app('json')->success(compact('info'));
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Lưu tài nguyên cập nhật
     *
     * @param int $id
     * @return \think\Response
     */
    public function update($id)
    {
        $params = $this->request->postMore([
            ['name', ''],
            ['config_name', ''],
            [['cate_id', 'd'], 0],
            ['info', ''],
            ['typelist', []],
        ]);

        //Phán quyết tên nhóm dữ liệu
        if (!$params['name']) return app('json')->fail('Vui lòng nhập tên');
        if (!$params['config_name']) return app('json')->fail('Vui lòng nhập tên cấu hình');
        //Xác định xem ID có tồn tại hay không. Nếu nó tồn tại, hãy chỉnh sửa nó. Nếu nó không tồn tại, hãy thêm nó.
        if (!$id) {
            if ($this->services->count(['config_name' => $params['config_name']])) {
                return app('json')->fail('Khóa dữ liệu đã tồn tại');
            }
        }
        $data["name"] = $params['name'];
        $data["config_name"] = $params['config_name'];
        $data["info"] = $params['info'];
        $data["cate_id"] = $params['cate_id'];
        //Đánh giá thông tin hiện trường
        if (!count($params['typelist']))
            return app('json')->fail('Có ít nhất một trường');
        else {
            $validate = ["name", "type", "title", "description"];
            foreach ($params["typelist"] as $key => $value) {
                foreach ($value as $name => $field) {
                    if (empty($field["value"]) && in_array($name, $validate))
                        return app('json')->fail('Trường không thể trống');
                    else
                        $data["fields"][$key][$name] = $field["value"];
                }
            }
        }
        $data["fields"] = json_encode($data["fields"]);
        $this->services->update($id, $data);
        \crmeb\services\CacheService::clear();
        return app('json')->success('Đã thêm nhóm dữ liệu thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id, SystemGroupDataServices $services)
    {
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else {
            $services->delete($id, 'gid');
            return app('json')->success('Xóa thành công');
        }
    }

    /**
     * Nhận dữ liệu kết hợp
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroup()
    {
        return app('json')->success($this->services->getGroupList(['cate_id' => 1], ['id', 'name', 'config_name'])['list']);
    }
}
