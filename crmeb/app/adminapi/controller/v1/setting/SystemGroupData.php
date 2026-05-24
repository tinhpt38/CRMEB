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

use crmeb\exceptions\AdminException;
use app\services\other\CacheServices;
use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\config\SystemGroupDataServices;
use app\services\system\config\SystemGroupServices;

/**
 * Quản lý dữ liệu
 * Class SystemGroupData
 * @package app\adminapi\controller\v1\setting
 */class SystemGroupData extends AuthController
{
    /**
     * Người xây dựng
     * SystemGroupData constructor.
     * @param App $app
     * @param SystemGroupDataServices $services
     */    public function __construct(App $app, SystemGroupDataServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lấy tiêu đề của danh sách dữ liệu
     * @return mixed
     */    public function header(SystemGroupServices $services)
    {
        [$gid, $config_name] = $this->request->getMore([
            ['gid', 0],
            ['config_name', '']
        ], true);
        if (!$gid && !$config_name) return app('json')->fail('Lỗi tham số');
        if (!$gid) {
            $gid = $services->value(['config_name' => $config_name], 'id');
        }
        return app('json')->success($services->getGroupDataTabHeader($gid));
    }

    /**
     * Hiển thị danh sách tài nguyên
     *
     * @return \think\Response
     */    public function index(SystemGroupServices $group)
    {
        $where = $this->request->getMore([
            ['gid', 0],
            ['status', ''],
            ['config_name', '']
        ]);
        if (!$where['gid'] && !$where['config_name']) return app('json')->fail('Lỗi tham số');
        if (!$where['gid']) {
            $where['gid'] = $group->value(['config_name' => $where['config_name']], 'id');
        }
        unset($where['config_name']);
        return app('json')->success($this->services->getGroupDataList($where));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên.
     *
     * @return \think\Response
     */    public function create()
    {
        $gid = $this->request->param('gid/d');
        if ($this->services->isGroupGidSave($gid, 4, 'index_categy_images')) {
            return app('json')->fail('Không quá bốn');
        }
        if ($this->services->isGroupGidSave($gid, 7, 'sign_day_num')) {
            return app('json')->fail('Số ngày nhận phòng không thể được cấu hình lớn hơn 7 ngày');
        }
        return app('json')->success($this->services->createForm($gid));
    }

    /**
     * Lưu tài nguyên mới
     *
     * @return \think\Response
     */    public function save(SystemGroupServices $services)
    {
        $params = request()->post();
        $gid = (int)$params['gid'];
        $group = $services->getOne(['id' => $gid], 'id,config_name,fields');
        if ($group && $group['config_name'] == 'order_details_images') {
            $groupDatas = $this->services->getColumn(['gid' => $gid], 'value', 'id');
            foreach ($groupDatas as $groupData) {
                $groupData = json_decode($groupData, true);
                if (isset($groupData['order_status']['value']) && $groupData['order_status']['value'] == $params['order_status']) {
                    return app('json')->fail('Vui lòng không thêm bản sao');
                }
            }
        }
        if ($group && $group['config_name'] == 'user_recharge_quota') {
            if ($params['price'] <= 0) return app('json')->fail('Giá bán phải lớn hơn0');
            if ($params['give_money'] < 0) return app('json')->fail('Phần quà không thể ít hơn0');
        }
        $this->services->checkSeckillTime($services, $gid, $params);
        $this->checkSign($services, $gid, $params);
        $fields = json_decode($group['fields'], true) ?? [];
        $value = [];
        foreach ($params as $key => $param) {
            foreach ($fields as $index => $field) {
                if ($key == $field["title"]) {
                    if ($param == "")
                        return app('json')->fail('Trường không thể trống');
                    else {
                        $value[$key]["type"] = $field["type"];
                        $value[$key]["value"] = $param;
                    }
                }
            }
        }
        $data = [
            "gid" => $params['gid'],
            "add_time" => time(),
            "value" => json_encode($value),
            "sort" => $params["sort"] ?: 0,
            "status" => $params["status"]
        ];
        $this->services->save($data);
        \crmeb\services\CacheService::clear();
        return app('json')->success('Dữ liệu được thêm thành công');
    }

    /**
     * Hiển thị tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */    public function read($id)
    {
        //
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa.
     *
     * @param int $id
     * @return \think\Response
     */    public function edit($id)
    {
        $gid = $this->request->param('gid/d');
        if (!$gid) {
            return app('json')->fail('Lỗi tham số');
        }
        return app('json')->success($this->services->updateForm((int)$gid, (int)$id));
    }

    /**
     * Lưu tài nguyên cập nhật
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */    public function update(SystemGroupServices $services, $id)
    {
        $groupData = $this->services->get($id);
        $fields = $services->getValueFields((int)$groupData["gid"]);
        $params = request()->post();
        $group = $services->getOne(['id' => $params['gid']], 'id,config_name,fields');
        if ($group && $group['config_name'] == 'user_recharge_quota') {
            if ($params['price'] <= 0) return app('json')->fail('Giá bán phải lớn hơn0');
            if ($params['give_money'] < 0) return app('json')->fail('Phần quà không thể ít hơn0');
        }
        $this->services->checkSeckillTime($services, $groupData["gid"], $params, $id);
        $this->checkSign($services, $groupData["gid"], $params);
        $value = [];
        foreach ($params as $key => $param) {
            foreach ($fields as $index => $field) {
                if ($key == $field["title"]) {
                    if ($param == '')
                        return app('json')->fail('Trường không thể trống');
                    else {
                        $value[$key]["type"] = $field["type"];
                        $value[$key]["value"] = $param;
                    }
                }
            }
        }
        $data = [
            "value" => json_encode($value),
            "sort" => $params["sort"],
            "status" => $params["status"]
        ];
        $this->services->update($id, $data);
        \crmeb\services\CacheService::clear();
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else {
            \crmeb\services\CacheService::clear();
            return app('json')->success('Xóa thành công');
        }
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */    public function set_status($id, $status)
    {
        if ($status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        $this->services->update($id, ['status' => $status]);
        \crmeb\services\CacheService::clear();
        return app('json')->success('Thiết lập thành công');
    }


    /**
     * Kiểm tra cấu hình đăng ký
     * @param SystemGroupServices $services
     * @param $gid
     * @param $params
     * @param int $id
     * @return mixed
     */    public function checkSign(SystemGroupServices $services, $gid, $params, $id = 0)
    {
        $name = $services->value(['id' => $gid], 'config_name');
        if ($name == 'sign_day_num') {
            if (!$params['sign_num']) {
                throw new AdminException('Vui lòng nhập điểm thưởng khi đăng nhập');
            }
            if (!preg_match('/^\+?[1-9]\d*$/', $params['sign_num'])) {
                throw new AdminException('Vui lòng nhập số nguyên lớn hơn hoặc bằng 0');
            }
        }
    }

    /**
     * Lấy Nội dung quảng cáo trên trang chăm sóc khách hàng
     * @return mixed
     */    public function getKfAdv()
    {
        /** @var CacheServices $cache */        $cache = app()->make(CacheServices::class);
        $content = $cache->getDbCache('kf_adv', '');
        return app('json')->success(compact('content'));
    }

    /**
     * Thiết lập Nội dung quảng cáo trang CSKH
     * @return mixed
     */    public function setKfAdv()
    {
        $content = $this->request->post('content');
        /** @var CacheServices $cache */        $cache = app()->make(CacheServices::class);
        $cache->setDbCache('kf_adv', $content);
        return app('json')->success('Thiết lập thành công');
    }

    public function saveAll()
    {
        $params = request()->post();
        if (!isset($params['config_name']) || !isset($params['data'])) {
            return app('json')->fail('Lỗi tham số');
        }
        $this->services->saveAllData($params['data'], $params['config_name']);
        return app('json')->success('Đã thêm nhóm dữ liệu thành công');
    }


    /**
     * Nhận Nội dung thỏa thuận Khách hàng
     * @return mixed
     */    public function getUserAgreement()
    {
        /** @var CacheServices $cache */        $cache = app()->make(CacheServices::class);
        $content = $cache->getDbCache('user_agreement', '');
        return app('json')->success(compact('content'));
    }

    /**
     * Đặt Nội dung thỏa thuận Khách hàng
     * @return mixed
     */    public function setUserAgreement()
    {
        $content = $this->request->post('content');
        /** @var CacheServices $cache */        $cache = app()->make(CacheServices::class);
        $cache->setDbCache('user_agreement', $content);
        return app('json')->success('Thiết lập thành công');
    }
}
