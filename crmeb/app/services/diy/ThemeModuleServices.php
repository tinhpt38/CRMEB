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
namespace app\services\diy;

use app\dao\diy\ThemeModuleDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Lớp dịch vụ thành phần chủ đề
 */
class ThemeModuleServices extends BaseServices
{
    /**
     * Người xây dựng
     * @param ThemeModuleDao $dao
     */
    public function __construct(ThemeModuleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy danh sách thành phần
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getModuleList(array $where)
    {
        $list = $this->dao->themeModuleList($where, '*', 1, 100, 'id desc');
        $count = $this->dao->themeModuleCount($where);
        return compact('list', 'count');
    }

    /**
     * Nhận chi tiết thành phần
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getModuleInfo(int $id)
    {
        $info = $this->dao->get($id);
        if (!$info) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $info = $info->toArray();
        $info['data'] = $info['data'] ? json_decode($info['data'], true) : [];
        return $info;
    }

    /**
     * Lưu dữ liệu thành phần (thêm/chỉnh sửa）
     * @param int $id
     * @param array $data
     * @return int
     */
    public function saveModule(int $id, array $data)
    {
        $save = [];
        if (isset($data['type'])) {
            $save['type'] = $data['type'];
        }
        if (isset($data['data'])) {
            $value = is_string($data['data']) ? $data['data'] : json_encode($data['data'], JSON_UNESCAPED_UNICODE);
            $save['data'] = $value;
        }

        if (!$save) {
            throw new AdminException('Lưu dữ liệu không được để trống');
        }

        if ($id) {
            $this->dao->update($id, $save);
        } else {
            $id = $this->dao->insertGetId($save);
        }

        return $id;
    }

    /**
     * Xóa thành phần
     * @param int $id
     * @return bool
     */
    public function deleteModule(int $id)
    {
        if (!$this->dao->get($id)) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        return (bool)$this->dao->delete($id);
    }
}
