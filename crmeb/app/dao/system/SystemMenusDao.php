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

namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\SystemMenus;

/**
 * Cấp độ thực đơn
 * Class SystemMenusDao
 * @package app\dao\system
 */class SystemMenusDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemMenus::class;
    }

    /**
     * @param array $menusIds
     * @return bool
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/13
     */    public function deleteMenus(array $menusIds)
    {
        return $this->getModel()->whereIn('id', $menusIds)->delete();
    }

    /**
     * Nhận danh sách menu quyền
     * @param array $where
     * @param array $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getMenusRoule(array $where, ?array $field = [])
    {
        if (!$field) {
            $field = ['id', 'menu_name', 'icon', 'pid', 'sort', 'menu_path', 'is_show', 'header', 'is_header', 'is_show_path', 'is_show'];
        }
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search($where)->field($field)->order('sort DESC,id DESC')->failException(false)->select();
    }

    /**
     * Nhận quyền duy nhất trong menu
     * @param array $where
     * @return array
     */    public function getMenusUnique(array $where)
    {
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search($where)->where('unique_auth', '<>', '')->column('unique_auth', '');
    }

    /**
     * Lấy tên menu dựa trên địa chỉ truy cập
     * @param string $rule
     * @return mixed
     */    public function getVisitName(string $rule)
    {
        return $this->search(['url' => $rule])->value('menu_name');
    }

    /**
     * Lấy danh sách menu nền và phân trang nó
     * @param array $where
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getMenusList(array $where, array $field = ['*'])
    {
        $where = array_merge($where, ['is_del' => 0]);
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search($where)->field($field)->order('sort DESC,id ASC')->select();
    }

    /**
     * Tổng số thực đơn
     * @param array $where
     * @return int
     */    public function countMenus(array $where)
    {
        $where = array_merge($where, ['is_del' => 0]);
        return $this->count($where);
    }

    /**
     * Chỉ định các điều kiện để lấy tên của các menu nhất định và trả về chúng ở dạng mảng
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */    public function column(array $where, string $field, string $key = '')
    {
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search($where)->column($field, $key);
    }

    /**Danh sách thực đơn
     * @param array $where
     * @param int $type
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function menusSelect(array $where, $type = 1)
    {
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        if ($type == 1) {
            return $this->search($where)->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
        } else {
            return $this->search($where)->group('pid')->column('pid');
        }
    }

    /**
     * danh sách tìm kiếm
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSearchList()
    {
        $where['no_model'] = sys_config('model_checkbox', ['seckill', 'bargain', 'combination']);
        return $this->search(['is_show' => 1, 'auth_type' => 1, 'is_del' => 0, 'is_show_path' => 0])
            ->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/20
     */    public function deleteMenu(string $path, string $method)
    {
        return $this->getModel()->where('api_url', $path)->where('methods', $method)->delete();
    }
}
