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

namespace app\services\system\admin;

use app\dao\system\admin\SystemRoleDao;

use app\Request;
use app\services\BaseServices;
use app\services\system\SystemMenusServices;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;

/**
 * Class SystemRoleServices
 * @package app\services\system\admin
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 * @method save(array $data) lưu dữ liệu
 * @method get(int $id, ?array $field = []) Nhận dữ liệu
 * @method delete(int $id, ?string $key = null) Xóa dữ liệu
 */
class SystemRoleServices extends BaseServices
{

    /**
     * Tiền tố bộ đệm đặc quyền của quản trị viên hiện tại
     */
    const ADMIN_RULES_LEVEL = 'Admin_rules_level_';

    /**
     * SystemRoleServices constructor.
     * @param SystemRoleDao $dao
     */
    public function __construct(SystemRoleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận quyền
     * @return mixed
     */
    public function getRoleArray(array $where = [], string $field = '', string $key = '')
    {
        return $this->dao->getRoule($where, $field, $key);
    }

    /**
     * Nhận danh sách tên quyền theo yêu cầu của biểu mẫu
     * @param int $level
     * @return array
     */
    public function getRoleFormSelect(int $level)
    {
        $list = $this->getRoleArray(['level' => $level, 'status' => 1]);
        $options = [];
        foreach ($list as $id => $roleName) {
            $options[] = ['label' => $roleName, 'value' => $id];
        }
        return $options;
    }

    /**
     * Danh sách quản lý danh tính
     * @param array $where
     * @return array
     */
    public function getRoleList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getRouleList($where, $page, $limit);
        $count = $this->dao->count($where);
        /** @var SystemMenusServices $service */
        $service = app()->make(SystemMenusServices::class);
        foreach ($list as &$item) {
            $item['rules'] = implode(',', array_merge($service->column(['id' => $item['rules']], 'menu_name', 'id')));
        }
        return compact('count', 'list');
    }

    /**
     * Quyền xác minh lý lịch
     * @param Request $request
     * @return bool|void
     * @throws \throwable
     */
    public function verifyAuth(Request $request)
    {
        // Lấy giao diện hiện tại và loại giao diện
        $rule = trim(strtolower($request->rule()->getRule()));
        $method = trim(strtolower($request->method()));

        // Hãy bỏ qua khi người ta đánh giá giao diện thuộc một trong hai loại sau.
        if (in_array($rule, ['setting/admin/logout', 'menuslist'])) {
            return true;
        }

        // Nhận tất cả các loại giao diện và giao diện tương ứng
        $allAuth = CacheService::remember('all_auth', function () {
            /** @var SystemMenusServices $menusService */
            $menusService = app()->make(SystemMenusServices::class);
            $allList = $menusService->getColumn([['api_url', '<>', ''], ['auth_type', '=', 2]], 'api_url,methods');
            $allAuth = [];
            foreach ($allList as $item) {
                $allAuth[trim(strtolower($item['methods']))][] = trim(strtolower(str_replace(' ', '', $item['api_url'])));
            }
            return $allAuth;
        });

        // Phát hành khi menu quyền không được thêm vào
        if (!in_array($rule, $allAuth[$method])) return true;

        // Nếu là giao diện thô thiển thì sẽ được phát hành
        if (strpos($rule, 'crud/') === 0) return true;

        // Nhận danh sách quyền giao diện của quản trị viên và cho phép nó nếu nó tồn tại.
        $auth = $this->getRolesByAuth($request->adminInfo()['roles'], 2);
        if (isset($auth[$method]) && in_array($rule, $auth[$method])) {
            return true;
        } else {
            return true;
        }
    }

    /**
     * Nhận quyền được chỉ định
     * @param array $rules
     * @param int $type
     * @param string $cachePrefix
     * @return array|mixed
     * @throws \throwable
     */
    public function getRolesByAuth(array $rules, int $type = 1, string $cachePrefix = self::ADMIN_RULES_LEVEL)
    {
        if (empty($rules)) return [];
        $cacheName = md5($cachePrefix . '_' . $type . '_' . implode('_', $rules));
        return CacheService::remember($cacheName, function () use ($rules, $type) {
            /** @var SystemMenusServices $menusService */
            $menusService = app()->make(SystemMenusServices::class);
            $authList = $menusService->getColumn([['id', 'IN', $this->getRoleIds($rules)], ['auth_type', '=', $type]], 'api_url,methods');
            $rolesAuth = [];
            foreach ($authList as $item) {
                $rolesAuth[trim(strtolower($item['methods']))][] = trim(strtolower(str_replace(' ', '', $item['api_url'])));
            }
            return $rolesAuth;
        });
    }

    /**
     * Nhận quyềnid
     * @param array $rules
     * @return array
     */
    public function getRoleIds(array $rules)
    {
        $rules = $this->dao->getColumn([['id', 'IN', $rules], ['status', '=', '1']], 'rules', 'id');
        return array_unique(explode(',', implode(',', $rules)));
    }
}
