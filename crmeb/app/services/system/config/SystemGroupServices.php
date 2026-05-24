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

use app\dao\system\config\SystemGroupDao;
use app\services\BaseServices;

/**
 * Dữ liệu kết hợp
 * Class SystemGroupServices
 * @package app\services\system\config
 * @method getConfigNameId(string $configName) Nhận cấu hìnhid
 * @method save(array $data) Thêm dữ liệu mới
 * @method get(int $id, ?array $field = []) Lấy một phần dữ liệu
 * @method count(array $where = []): int Lấy số lượng vật phẩm dựa trên điều kiện
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 * @method delete($id, ?string $key = null) Xóa dữ liệu
 * @method value(array $where, ?string $field = '') Nhận một giá trị
 */class SystemGroupServices extends BaseServices
{

    /**
     * SystemGroupServices constructor.
     * @param SystemGroupDao $dao
     */    public function __construct(SystemGroupDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách dữ liệu kết hợp
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getGroupList(array $where, array $field = ['*'])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getGroupList($where, $field, $page, $limit);
        $count = $this->dao->count($where);
        foreach ($list as $key => $value) {
            if (isset($value['fields'])) {
                $list[$key]['typelist'] = $value['fields'];
                unset($list[$key]['fields']);
            }
        }
        return compact('list', 'count');
    }

    /**
     * Lấy tiêu đề dưới tab dữ liệu kết hợp
     * @param int $id
     * @return array
     */    public function getGroupDataTabHeader(int $id)
    {
        $data = $this->getValueFields($id);
        $header = [];
        foreach ($data as $key => $item) {
            if ($item['type'] == 'upload' || $item['type'] == 'uploads') {
                $header[$key]['key'] = $item['title'];
                $header[$key]['minWidth'] = 60;
                $header[$key]['type'] = 'img';
            } elseif ($item['title'] == 'url' || $item['title'] == 'wap_url' || $item['title'] == 'link' || $item['title'] == 'wap_link') {
                $header[$key]['key'] = $item['title'];
                $header[$key]['minWidth'] = 200;
            } else {
                $header[$key]['key'] = $item['title'];
                $header[$key]['minWidth'] = 100;
            }
            $header[$key]['title'] = $item['name'];
        }
        array_unshift($header, ['key' => 'id', 'title' => 'số seri', 'minWidth' => 60]);
        array_push($header, ['slot' => 'status', 'title' => 'Nó có sẵn không', 'minWidth' => 80], ['key' => 'sort', 'title' => 'loại', 'minWidth' => 80], ['slot' => 'action', 'fixed' => 'right', 'title' => 'Thao tác', 'minWidth' => 120]);
        return compact('header');
    }

    /**
     * Lấy các trường dữ liệu kết hợp
     * @param int $id
     * @return array|mixed
     */    public function getValueFields(int $id)
    {
        return json_decode($this->dao->value(['id' => $id], 'fields'), true) ?: [];
    }

}
