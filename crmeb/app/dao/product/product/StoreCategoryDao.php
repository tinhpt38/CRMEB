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

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreCategory;

/**
 * Class StoreCategoryDao
 * @package app\dao\product\product
 */class StoreCategoryDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreCategory::class;
    }

    /**
     * Nhận danh sách danh mục
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where)
    {
        return $this->search($where)->with('children')->order('sort desc,id desc')->select()->toArray();
    }

    /**
     *
     * @param array $where
     * @param array $field
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTierList(array $where = [], array $field = ['*'])
    {
        return $this->search($where)->field($field)->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Thêm và sửa đổi danh sách danh mục ưu việt đã chọn
     * @param array $where
     * @return array
     * @throws \ReflectionException
     */    public function getMenus(array $where)
    {
        return $this->search($where)->order('sort desc,id desc')->column('cate_name,id');
    }

    /**
     * Nhận phân loại dựa trên id
     * @param string $cateIds
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCateArray(string $cateIds)
    {
        return $this->search(['id' => $cateIds])->field('cate_name,id')->select()->toArray();
    }

    /**
     * Danh sách phân tách trang danh mục mặt trước
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCategory()
    {
        return $this->getModel()->with('children')->where('is_show', 1)->where('pid', 0)->order('sort desc,id desc')->hidden(['add_time', 'is_show', 'sort', 'children.sort', 'children.add_time', 'children.pid', 'children.is_show'])->select()->toArray();
    }

    /**
     * Nhận được sự vượt trội dựa trên id danh mụcid
     * @param array $cateId
     * @return array
     */    public function cateIdByPid(array $cateId)
    {
        return $this->getModel()->whereIn('id', $cateId)->column('pid');
    }

    /**
     * Nhận phân loại cấp hai được hiển thị trên trang chủ. Việc sắp xếp mặc định theo thứ tự giảm dần.
     * @param int $limit
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function byIndexList($limit = 4, $field = 'id,cate_name,pid,pic')
    {
        return $this->getModel()->where('pid', '>', 0)->where('is_show', 1)->field($field)->order('sort DESC')->limit($limit)->select()->toArray();
    }

    /**
     * Nhận bộ sưu tập bao gồm phân loại cấp một và phân loại cấp hai
     * @param $cateId
     * @return mixed
     */    public function getCateParentAndChildName(string $cateId)
    {
        return $this->getModel()->alias('c')->leftJoin('StoreCategory b', 'b.id = c.pid')
            ->where('c.id', 'IN', $cateId)->field('c.cate_name as two,b.cate_name as one,c.id')
            ->select()->toArray();
    }

    /**
     * Nhận các danh mục sản phẩm thuộc danh mục cấp một theo số lượngID
     * @param $page
     * @param $limit
     * @return array
     */    public function getCid($page, $limit)
    {
        return $this->getModel()
            ->where('is_show', 1)
            ->where('pid', 0)
            ->where('id', 'in', function ($query) {
                $query->name('store_product_cate')->where('status', 1)->group('cate_pid')->field('cate_pid')->select()->toArray();
            })
            ->page($page, $limit)
            ->order('sort DESC,id DESC')
            ->select()->toArray();
    }

    /**
     * Lấy số lượng danh mục có sản phẩm thuộc danh mục cấp 1 theo số
     * @param $page
     * @param $limit
     * @return int
     */    public function getCidCount()
    {
        return $this->getModel()
            ->where('is_show', 1)
            ->where('pid', 0)
            ->where('id', 'in', function ($query) {
                $query->name('store_product_cate')->where('status', 1)->group('cate_pid')->field('cate_pid')->select()->toArray();
            })
            ->count();
    }

    /**
     * Nhận Tất cả các danh mục (riêng và cấp dưới) theo id danh mục
     * @param $id
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getAllById($id, string $field = 'id')
    {
        if (is_array($id)) {
            return $this->getModel()->where(function ($query) use ($id) {
                $query->whereIn('id', $id)->whereOr('pid', 'in', $id);
            })->where('is_show', 1)->field($field)->select()->toArray();
        } else {
            return $this->getModel()->where(function ($query) use ($id) {
                $query->where('id', $id)->whereOr('pid', $id);
            })->where('is_show', 1)->field($field)->select()->toArray();
        }
    }

    /**
     * Có thể tìm kiếm để có được Tất cả các danh mục phụ
     * @param array $where
     * @param string $field
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getALlByIndex(array $where, string $field = 'id,cate_name,pid,pic', $limit = 0)
    {
        $pid = $where['pid'] ?? -1;
        return $this->getModel()->where('is_show', 1)->field($field)
            ->when(in_array($pid, [0, -1]), function ($query) use ($pid) {
                switch ($pid) {
                    case -1://Tất cả các cấp
                        $query->where('pid', 0);
                        break;
                    case 0://Tất cả thứ cấp
                        $query->where('pid', '>', 0);
                }
            })->when((int)$pid > 0, function ($query) use ($pid) {
                $query->where('pid', $pid);
            })->when(isset($where['name']) && $where['name'], function ($query) use ($where) {
                $query->whereLike('id|cate_name', '%' . $where['name'] . '%');
            })->when($limit > 0, function ($query) use ($limit) {
                $query->limit($limit);
            })->order('sort DESC')->select()->toArray();
    }
}
