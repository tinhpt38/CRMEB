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

namespace app\services\system;


use app\dao\system\SystemRouteCateDao;
use app\services\BaseServices;
use crmeb\services\FormBuilder;

/**
 * Class SystemRouteCateServices
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */class SystemRouteCateServices extends BaseServices
{

    /**
     * SystemRouteCateServices constructor.
     * @param SystemRouteCateDao $dao
     */    public function __construct(SystemRouteCateDao $dao)
    {
        $this->dao = $dao;
    }

    public function getPathValue(array $path)
    {
        $pathAttr = explode('/', $path);
        $pathData = [];
        foreach ($pathAttr as $item) {
            if (!$item) {
                $pathData[] = $item;
            }
        }
        return $pathAttr;
    }

    /**
     * @param array $path
     * @param int $id
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */    public function setPathValue(array $path, int $id)
    {
        return ($path ? '/' . implode('/', $path) : '') . '/' . $id . '/';
    }

    /**
     * @param string $appName
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */    public function getAllList(string $appName = 'outapi', string $field = '*', string $order = '')
    {
        $list = $this->dao->selectList(['app_name' => $appName], $field, 0, 0, $order)->toArray();
        return get_tree_children($list);
    }

    /**
     * @param int $id
     * @param string $appName
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/6
     */    public function getFrom(int $id = 0, string $appName = 'outapi')
    {
        $url = '/system/route_cate';
        $cateInfo = [];
        $path = [];
        if ($id) {
            $cateInfo = $this->dao->get($id);
            $cateInfo = $cateInfo ? $cateInfo->toArray() : [];
            $url .= '/' . $id;
            $path = explode('/', $cateInfo['path']);
            $newPath = [];
            foreach ($path as $item) {
                if ($item) {
                    $newPath[] = $item;
                }
            }
            $path = $newPath;
        }
        $options = $this->dao->selectList(['app_name' => $appName], 'name as label,id as value,id,pid')->toArray();
        $rule = [
//            FormBuilder::cascader('path', 'Phân loại cao cấp', $path)->data(get_tree_children($options)),
            FormBuilder::input('name', 'Tên danh mục', $cateInfo['name'] ?? '')->required(),
            FormBuilder::number('sort', 'loại', (int)($cateInfo['sort'] ?? 0)),
            FormBuilder::hidden('app_name', $appName)
        ];

        return create_form($id ? 'Sửa danh mục' : 'Thêm danh mục', $rule, $url, $id ? 'PUT' : 'POST');
    }
}
