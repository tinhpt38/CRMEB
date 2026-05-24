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

namespace app\dao\system\config;

use app\dao\BaseDao;
use app\model\system\config\SystemConfig;

/**
 * Cấu hình hệ thống
 * Class SystemConfigDao
 * @package app\dao\system\config
 */class SystemConfigDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemConfig::class;
    }

    /**
     * Nhận cấu hình hệ thống
     * @param string $configNmae
     * @return mixed
     * @throws \ReflectionException
     */    public function getConfigValue(string $configNmae)
    {
        return $this->search(['menu_name' => $configNmae])->value('value');
    }

    /**
     * Nhận Tất cả các cấu hình
     * @param array $configName
     * @return array
     * @throws \ReflectionException
     */    public function getConfigAll(array $configName = [])
    {
        if ($configName) {
            return $this->search(['menu_name' => $configName])->column('value', 'menu_name');
        } else {
            return $this->getModel()->column('value', 'menu_name');
        }
    }

    /**
     * Nhận phân trang danh sách cấu hình
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getConfigList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('sort desc,id asc')->select()->toArray();
    }

    /**
     * Nhận danh sách cấu hình theo cấu hình danh mục nhất định
     * @param int $tabId
     * @param int $status
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getConfigTabAllList(int $tabId, int $status = 1)
    {
        $where['tab_id'] = $tabId;
        if ($status == 1) $where['status'] = $status;
        return $this->search($where)->order('sort desc,id ASC')->select()->toArray();
    }

    /**
     * Nhận loại tải lên trong cấu hình tải lên
     * @param string $configName
     * @return array
     * @throws \ReflectionException
     */    public function getUploadTypeList(string $configName)
    {
        return $this->search(['menu_name' => $configName])->column('upload_type', 'type');
    }
}
