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

namespace app\services\system\log;


use app\dao\system\log\SystemLogDao;
use app\services\BaseServices;
use app\services\system\admin\SystemAdminServices;
use app\services\system\SystemMenusServices;
use app\services\system\SystemRouteServices;

/**
 * Nhật ký hệ thống
 * Class SystemLogServices
 * @package app\services\system\log
 * @method deleteLog() Xóa nhật ký thường xuyên
 */
class SystemLogServices extends BaseServices
{

    /**
     * Người xây dựng
     * SystemLogServices constructor.
     * @param SystemLogDao $dao
     */
    public function __construct(SystemLogDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Ghi lại nhật ký truy cập
     * @param int $adminId
     * @param string $adminName
     * @param string $type
     * @return bool
     */
    public function recordAdminLog(int $adminId, string $adminName, string $type)
    {
        $request = app()->request;
        $module = app('http')->getName();
        $rule = trim(strtolower($request->rule()->getRule()));

        /** @var SystemMenusServices $service */
        $service = app()->make(SystemMenusServices::class);
        $data = [
            'method' => $module,
            'admin_id' => $adminId,
            'add_time' => time(),
            'admin_name' => $adminName,
            'path' => $rule,
            'page' => $service->getVisitName($rule) ?: 'không rõ',
            'ip' => $request->ip(),
            'type' => $type
        ];
        if ($this->dao->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Nhận danh sách nhật ký hệ thống
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLogList(array $where, int $level)
    {
        [$page, $limit] = $this->getPageValue();
        if (!$where['admin_id']) {
            /** @var SystemAdminServices $service */
            $service = app()->make(SystemAdminServices::class);
            $where['admin_id'] = $service->getAdminIds($level);
        }
        $routeArr = app()->make(SystemRouteServices::class)->getNameList('adminapi');
        $list = $this->dao->getLogList($where, $page, $limit);
        $count = $this->dao->count($where);
        foreach($list as &$item){
            $item['path_name'] = $routeArr[$item['path']] ?? 'không rõ';
        }
        return compact('list', 'count');
    }
}
