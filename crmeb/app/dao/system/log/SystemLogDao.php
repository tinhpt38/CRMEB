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

namespace app\dao\system\log;


use app\dao\BaseDao;
use app\model\system\log\SystemLog;

/**
 * Nhật ký hệ thống
 * Class SystemLogDao
 * @package app\dao\system\log
 */class SystemLogDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemLog::class;
    }

    /**
     * Xóa nhật ký hết hạn
     * @throws \Exception
     */    public function deleteLog()
    {
        $this->getModel()->where('add_time', '<', time() - 7776000)->delete();
    }

    /**
     * Nhận danh sách nhật ký hệ thống
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getLogList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('add_time DESC')->select()->toArray();
    }

}
