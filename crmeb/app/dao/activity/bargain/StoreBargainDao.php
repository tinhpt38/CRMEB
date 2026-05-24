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
declare (strict_types=1);

namespace app\dao\activity\bargain;

use app\dao\BaseDao;
use app\model\activity\bargain\StoreBargain;

/**
 *
 * Class StoreBargainDao
 * @package app\dao\activity
 */class StoreBargainDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreBargain::class;
    }

    /**
     * Nhận danh sách mặc cả
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, int $page = 0, int $limit = 0)
    {
        return $this->search($where, false)->where('is_del', 0)
            ->when(isset($where['start_status']) && $where['start_status'] !== '', function ($query) use ($where) {
                $time = time();
                switch ($where['start_status']) {
                    case -1:
                        $query->where('stop_time', '<', $time);
                        break;
                    case 0:
                        $query->where('start_time', '>', $time);
                        break;
                    case 1:
                        $query->where('start_time', '<=', $time)->where('stop_time', '>=', $time);
                        break;
                }
            })->when($page != 0 && $limit != 0, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->when(isset($where['product_id']) && $where['product_id'] != 0, function ($query) use ($where) {
                $query->where('product_id', $where['product_id']);
            })->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Nhận ID thương lượng khi hoạt động được bắt đầu và trả lại dưới dạng mảng
     * @param array $ids Trả lại Tất cả nếu trống
     * @param array $field
     * @return array
     */    public function getBargainIdsArray(array $ids = [], array $field = [])
    {
        return $this->search(['is_del' => 0, 'status' => 1])->where('start_time', '<=', time())
            ->where('stop_time', '>=', time())
            ->when($ids, function ($query) use ($ids) {
                $query->whereIn('product_id', $ids);
            })->column(implode(',', $field), 'product_id');
    }

    /**
     * Nhận dữ liệu thương lượng dựa trên id
     * @param array $ids
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function idByBargainList(array $ids, string $field)
    {
        return $this->getModel()->whereIn('id', $ids)->field($field)->select()->toArray();
    }

    /**
     * Hoạt động thương lượng đang bắt đầu ngay bây giờ
     * @param int $status
     * @return StoreBargain
     */    public function validWhere(int $status = 1)
    {
        return $this->getModel()->where('is_del', 0)->where('status', $status)->where('start_time', '<', time())->where('stop_time', '>', time());

    }

    /**
     * Lịch sử trả giá
     * @param int $page
     * @param int $limit
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function bargainList(int $page, int $limit, string $field = '*')
    {
        return $this->search(['is_del' => 0, 'status' => 1])
            ->where('start_time', '<=', time())
            ->where('stop_time', '>=', time())
            ->where('product_id', 'IN', function ($query) {
                $query->name('store_product')->where('is_del', 0)->field('id');
            })->with('product')->field($field)->page($page, $limit)->order('sort DESC,id DESC')->select()->toArray();
    }

    /**
     * Thiết kế trang phụ trợ để có được danh sách thương lượng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function DiyBargainList(array $where, int $page, int $limit)
    {
        return $this->search($where, false)
            ->when(isset($where['sid']) && $where['sid'], function ($query) use ($where) {
                $query->whereIn('id', function ($query) use ($where) {
                    $query->name('store_product_cate')->where('cate_id', $where['sid'])->field('product_id')->select();
                });
            })->when(isset($where['cid']) && $where['cid'], function ($query) use ($where) {
                $query->whereIn('id', function ($query) use ($where) {
                    $query->name('store_product_cate')->whereIn('cate_id', function ($query) use ($where) {
                        $query->name('store_category')->where('pid', $where['cid'])->field('id')->select();
                    })->field('product_id')->select();
                });
            })->where('start_time', '<=', time())
            ->where('stop_time', '>=', time())
            ->where('product_id', 'IN', function ($query) {
                $query->name('store_product')->where('is_show', 1)->where('is_del', 0)->field('id');
            })->with('product')->page($page, $limit)->order('sort DESC,id DESC')->select()->toArray();
    }

    /**
     * Trang chủ thương lượng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getHomeList(array $where, int $page, int $limit)
    {
        return $this->search($where, false)
            ->when(isset($where['sid']) && $where['sid'], function ($query) use ($where) {
                $query->whereIn('id', function ($query) use ($where) {
                    $query->name('store_product_cate')->where('cate_id', $where['sid'])->field('product_id')->select();
                });
            })->when(isset($where['cid']) && $where['cid'], function ($query) use ($where) {
                $query->whereIn('id', function ($query) use ($where) {
                    $query->name('store_product_cate')->whereIn('cate_id', function ($query) use ($where) {
                        $query->name('store_category')->where('pid', $where['cid'])->field('id')->select();
                    })->field('product_id')->select();
                });
            })->when($page != 0 && $limit != 0, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->when(isset($where['priceOrder']) && $where['priceOrder'] != '', function ($query) use ($where) {
                if ($where['priceOrder'] === 'desc') {
                    $query->order("price desc");
                } else {
                    $query->order("price asc");
                }
            })->when(isset($where['newsOrder']) && $where['newsOrder'] != '', function ($query) use ($where) {
                if ($where['newsOrder'] === 'news') {
                    $query->order("id desc");
                }
            })->when(isset($where['salesOrder']) && $where['salesOrder'] != '', function ($query) use ($where) {
                if ($where['salesOrder'] === 'desc') {
                    $query->order("sales desc");
                } else {
                    $query->order("sales asc");
                }
            })->when(isset($where['ids']) && $where['ids'], function ($query) use ($where) {
                if ((isset($where['priceOrder']) && $where['priceOrder'] != '') || (isset($where['salesOrder']) && $where['salesOrder'] != '')) {
                    $query->whereIn('id', $where['ids']);
                } else {
                    $query->whereIn('id', $where['ids'])->orderField('id', $where['ids'], 'asc');
                }
            })
            ->where('start_time', '<=', time())
            ->where('stop_time', '>=', time())
            ->where('product_id', 'IN', function ($query) {
                $query->name('store_product')->where('is_show', 1)->where('is_del', 0)->field('id');
            })->with('product')->select()->toArray();
    }

    /**
     * Số lượng mua lại có điều kiện
     * @param array $where
     * @return int
     */    public function getCount(array $where)
    {
        return $this->search($where, false)
            ->when(isset($where['sid']) && $where['sid'], function ($query) use ($where) {
                $query->whereIn('product_id', function ($query) use ($where) {
                    $query->name('store_product_cate')->where('cate_id', $where['sid'])->field('product_id')->select();
                });
            })->when(isset($where['cid']) && $where['cid'], function ($query) use ($where) {
                $query->whereIn('product_id', function ($query) use ($where) {
                    $query->name('store_product_cate')->whereIn('cate_id', function ($query) use ($where) {
                        $query->name('store_category')->where('pid', $where['cid'])->field('id')->select();
                    })->field('product_id')->select();
                });
            })->where('start_time', '<=', time())
            ->where('stop_time', '>=', time())
            ->where('product_id', 'IN', function ($query) {
                $query->name('store_product')->where('is_show', 1)->where('is_del', 0)->field('id');
            })->count();
    }

    /**
     * Sửa đổi trạng thái thương lượng
     * @param int $id
     * @param string $field
     * @return mixed
     */    public function addBargain(int $id, string $field)
    {
        return $this->getModel()->where('id', $id)->inc($field, 1)->update();
    }

    public function getProductExist($productIds)
    {
        return $this->getModel()->where('product_id', 'in', $productIds)->where('is_del', 0)
            ->group('product_id')->column('COUNT(*) as count', 'product_id');
    }
}
