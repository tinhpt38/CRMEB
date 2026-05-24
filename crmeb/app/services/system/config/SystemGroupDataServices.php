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

namespace app\services\system\config;

use app\dao\system\config\SystemGroupDataDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;

/**
 * Bộ dữ liệu kết hợp
 * Class SystemGroupDataServices
 * @package app\services\system\config
 * @method delete($id, ?string $key = null) Xóa dữ liệu
 * @method get(int $id, ?array $field = []) Lấy một phần dữ liệu
 * @method save(array $data) lưu dữ liệu
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 */class SystemGroupDataServices extends BaseServices
{
    /**
     * SystemGroupDataServices constructor.
     * @param SystemGroupDataDao $dao
     */    public function __construct(SystemGroupDataDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy dữ liệu theo một cấu hình nhất định và kết hợp lại thành dữ liệu mới để trả về
     * @param string $configName
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getConfigNameValue(string $configName, int $limit = 0)
    {
        /** @var SystemGroupServices $systemGroupServices */        $systemGroupServices = app()->make(SystemGroupServices::class);
        $value = $this->dao->getGroupDate((int)$systemGroupServices->getConfigNameId($configName), $limit);
        $data = [];
        foreach ($value as $key => $item) {
            $data[$key]["id"] = $item["id"];
            if (isset($item['status'])) $data[$key]["status"] = $item["status"];
            $fields = json_decode($item["value"], true) ?: [];
            foreach ($fields as $index => $field) {
                if ($field['type'] == 'upload') {
                    $data[$key][$index] = set_file_url($field['value']);
                } else {
                    $data[$key][$index] = $field["value"];
                }
            }
        }
        return $data;
    }

    /**
     * Nhận danh sách dữ liệu kết hợp
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getGroupDataList(array $where, $type = 'limit')
    {
        [$page, $limit] = $this->getPageValue();
        if ($type == 'all') $page = $limit = 0;
        $list = $this->dao->getGroupDataList($where, $page, $limit);
        $count = $this->dao->count($where);
        $type = '';
        $gid = (int)$where['gid'];
        /** @var SystemGroupServices $services */        $services = app()->make(SystemGroupServices::class);
        $group = $services->getOne(['id' => $gid], 'id,config_name,fields');

        $header = json_decode($group['fields'], true) ?? [];
        $title = [];
        $param = [];
        foreach ($header as $item) {
            if ($group['config_name'] == 'order_details_images' && $item['title'] == 'order_status') {
                $status = str_replace("\r\n", "\n", $item["param"]);//Ngăn chặn sự không tương thích
                $status = explode("\n", $status);
                if (is_array($status) && !empty($status)) {
                    foreach ($status as $index => $v) {
                        $vl = explode('=>', $v);
                        if (isset($vl[0]) && isset($vl[1])) {
                            $param[$vl[0]] = $vl[1];
                        }
                    }
                }
            }
            if ($item['type'] == 'upload' || $item['type'] == 'uploads') {
                $title[$item['title']] = [];
                $type = $item['title'];
            } else {
                $title[$item['title']] = '';
            }
        }
        foreach ($list as $key => $value) {
            $list[$key] = array_merge($value, $title);
            $infos = json_decode($value['value'], true) ?: [];
            foreach ($infos as $index => $info) {
                if ($group['config_name'] == 'order_details_images' && $index == 'order_status') {
                    $list[$key][$index] = ($param[$info['value']] ?? '') . '/' . $info['value'];
                } else {
                    if ($info['type'] == 'upload') {
                        $list[$key][$index] = [set_file_url($info['value'])];
                    } elseif ($info['type'] == 'checkbox') {
                        $list[$key][$index] = implode(",", $info["value"]);
                    } else {
                        $list[$key][$index] = $info['value'];
                    }
                }
            }
            unset($list[$key]['value']);
        }
        return compact('list', 'count', 'type');
    }

    /**
     * Xác định xem dữ liệu kết hợp có thể được thêm lại hay không dựa trên gid
     * @param int $gid
     * @param int $count
     * @param string $key
     * @return bool
     */    public function isGroupGidSave(int $gid, int $count, string $key): bool
    {
        /** @var SystemGroupServices $services */        $services = app()->make(SystemGroupServices::class);
        $configName = $services->value(['id' => $gid], 'config_name');
        if ($configName == $key) {
            return $this->dao->count(['gid' => $gid]) >= $count;
        } else {
            return false;
        }
    }

    /**
     * Tạo biểu mẫu
     * @param int $gid
     * @param array $groupData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function createGroupForm(int $gid, array $groupData = [])
    {
        $groupDataValue = isset($groupData["value"]) ? json_decode($groupData["value"], true) : [];
        /** @var SystemGroupServices $services */        $services = app()->make(SystemGroupServices::class);
        $fields = $services->getValueFields($gid);
        $f[] = Form::hidden('gid', $gid);
        foreach ($fields as $key => $value) {
            $info = [];
            if (isset($value["param"])) {
                $value["param"] = str_replace("\r\n", "\n", $value["param"]);//Ngăn chặn sự không tương thích
                $params = explode("\n", $value["param"]);
                if (is_array($params) && !empty($params)) {
                    foreach ($params as $index => $v) {
                        $vl = explode('=>', $v);
                        if (isset($vl[0]) && isset($vl[1])) {
                            $info[$index]["value"] = $vl[0];
                            $info[$index]["label"] = $vl[1];
                        }
                    }
                }
            }
            $fvalue = $groupDataValue[$value['title']]['value'] ?? '';
            switch ($value["type"]) {
                case 'input':
                    if ($gid == 55 && $value['title'] == 'sign_num') {
                        $f[] = Form::number($value["title"], $value["name"], (int)$fvalue)->precision(0);
                    } else {
                        $f[] = Form::input($value["title"], $value["name"], $fvalue);
                    }
                    break;
                case 'textarea':
                    $f[] = Form::input($value["title"], $value["name"], $fvalue)->type('textarea')->placeholder($value['param']);
                    break;
                case 'radio':
                    $f[] = Form::radio($value["title"], $value["name"], $fvalue ?: (int)$info[0]["value"] ?? '')->options($info);
                    break;
                case 'checkbox':
                    $f[] = Form::checkbox($value["title"], $value["name"], $fvalue ?: $info[0] ?? '')->options($info);
                    break;
                case 'select':
                    $f[] = Form::select($value["title"], $value["name"], $fvalue !== '' ? $fvalue : $info[0] ?? '')->options($info)->multiple(false);
                    break;
                case 'upload':
                    if (!empty($fvalue)) {
                        $image = is_string($fvalue) ? $fvalue : $fvalue[0];
                    } else {
                        $image = '';
                    }
                    $f[] = Form::frameImage($value["title"], $value["name"], $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', ['fodder' => $value["title"], 'big' => 1], true), $image)->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false]);
                    break;
                case 'uploads':
                    if ($fvalue) {
                        if (is_string($fvalue)) $fvalue = [$fvalue];
                        $images = !empty($fvalue) ? $fvalue : [];
                    } else {
                        $images = [];
                    }
                    $f[] = Form::frameImages($value["title"], $value["name"], $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', ['fodder' => $value["title"], 'big' => 1, 'type' => 'many', 'maxLength' => 5], true), $images)->maxLength(5)->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false])->spin(0);
                    break;
                default:
                    $f[] = Form::input($value["title"], $value["name"], $fvalue);
                    break;

            }
        }
        $f[] = Form::number('sort', 'loại', (int)($groupData["sort"] ?? 1))->precision(0);
        $f[] = Form::radio('status', 'Trạng thái', (int)($groupData["status"] ?? 1))->options([['value' => 1, 'label' => 'trình diễn'], ['value' => 0, 'label' => 'trốn']]);
        return $f;
    }

    /**
     * Nhận biểu mẫu thêm dữ liệu kết hợp
     * @param int $gid
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function createForm(int $gid)
    {
        return create_form('Thêm dữ liệu', $this->createGroupForm($gid), $this->url('/setting/group_data'));
    }

    /**
     * Nhận mẫu dữ liệu kết hợp được sửa đổi
     * @param int $gid
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function updateForm(int $gid, int $id)
    {
        $groupData = $this->dao->get($id);
        if (!$groupData) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        return create_form('Chỉnh sửa dữ liệu', $this->createGroupForm($gid, $groupData->toArray()), $this->url('/setting/group_data/' . $id), 'PUT');
    }

    /**
     * Lấy dữ liệu trong bản ghi hiện tại dựa trên id
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */    public function getDateValue($id)
    {
        $value = $this->dao->get($id);
        $data["id"] = $value["id"];
        $data["status"] = $value["status"];
        $fields = json_decode($value["value"], true);
        foreach ($fields as $index => $field) {
            $data[$index] = $field["value"];
        }
        return $data;
    }

    /**
     * Nhận dữ liệu dựa trên id
     * @param array $ids
     * @param string $field
     */    public function getGroupDataColumn(array $ids)
    {
        $systemGroup = [];
        if (!empty($ids)) {
            $systemGroupData = $this->dao->idByGroupList($ids, '*');
            if (!empty($systemGroupData))
                $systemGroup = array_combine(array_column($systemGroupData, 'id'), $systemGroupData);
        }
        return $systemGroup;
    }

    /**
     * Xóa dữ liệu dựa trên gid
     * @param int $gid
     * @return mixed
     */    public function delGroupDate(int $gid)
    {
        return $this->dao->delGroupDate($gid);
    }

    /**
     * Lưu theo đợt
     * @param array $params
     * @param string $config_name
     * @return bool
     * @throws \Exception
     */    public function saveAllData(array $params, string $config_name)
    {
        /** @var SystemGroupServices $systemGroupServices */        $systemGroupServices = app()->make(SystemGroupServices::class);
        $gid = $systemGroupServices->value(['config_name' => $config_name], 'id');
        if (!$gid) throw new AdminException('Dữ liệu không tồn tại');
        $group = $systemGroupServices->getOne(['id' => $gid], 'id,config_name,fields');
        $fields = json_decode($group['fields'], true) ?? [];
        $this->transaction(function () use ($gid, $params, $fields) {
            $this->delGroupDate($gid);
            $data = [];
            $sort = count($params);
            foreach ($params as $k => $v) {
                $value = [];
                foreach ($v as $key => $param) {
                    foreach ($fields as $index => $field) {
                        if ($key == $field["title"]) {
                            if ($param == "") {
                                throw new AdminException('{:name}không thể trống', ['name' => $field["name"]]);
                            } else {
                                $value[$key]["type"] = $field["type"];
                                $value[$key]["value"] = $param;
                            }
                        }
                    }
                }
                $data[$k] = [
                    "gid" => $gid,
                    "add_time" => time(),
                    "value" => json_encode($value),
                    "sort" => $sort,
                    "status" => $v["status"] ?? 1
                ];
                $sort--;
            }
            $this->dao->saveAll($data);
        });
        \crmeb\services\CacheService::clear();
        return true;
    }

    /**
     * Kiểm tra khoảng thời gian flash sale
     * @param SystemGroupServices $services
     * @param $gid
     * @param $params
     * @param int $id
     * @return mixed
     */    public function checkSeckillTime(SystemGroupServices $services, $gid, $params, $id = 0)
    {
        $name = $services->value(['id' => $gid], 'config_name');
        if ($name == 'routine_seckill_time') {
            if ($params['time'] == '') {
                throw new AdminException('Vui lòng nhập thời gian bắt đầu');
            }
            if (!$params['continued']) {
                throw new AdminException('Vui lòng nhập thời lượng');
            }
            if (!preg_match('/^(\d|1\d|2[0-3])$/', $params['time'])) {
                throw new AdminException('Vui lòng nhập toàn bộ số điểm trước 0-23 giờ');
            }
            if (!preg_match('/^([1-9]|1\d|2[0-4])$/', $params['continued'])) {
                throw new AdminException('Vui lòng nhập toàn bộ số điểm trước 1-24h');
            }
            if (($params['time'] + $params['continued']) > 24) throw new AdminException('Thời gian bắt đầu + thời lượng không thể lớn hơn 24 giờ');
            $list = $this->dao->getColumn(['gid' => $gid], 'value', 'id');
            if ($id) unset($list[$id]);
            $times = $time = [];
            foreach ($list as $item) {
                $info = json_decode($item, true);
                for ($i = 0; $i < $info['continued']['value']; $i++) {
                    $times[] = $info['time']['value'] + $i;
                }
            }
            for ($i = 0; $i < $params['continued']; $i++) {
                $time[] = $params['time'] + $i;
            }
            foreach ($time as $v) {
                if (in_array($v, $times)) throw new AdminException('khe thời gian đã bị chiếm dụng');
            }
        }
    }
}
