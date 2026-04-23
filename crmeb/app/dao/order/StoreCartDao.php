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

namespace app\dao\order;

use app\dao\BaseDao;
use app\model\order\StoreCart;

/**
 *
 * Class StoreCartDao
 * @package app\dao\order
 */
class StoreCartDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreCart::class;
    }

    /**
     * @param array $where
     * @param array $unique
     * @return array
     */
    public function getUserCartNums(array $where, array $unique)
    {
        return $this->search($where)->whereIn('product_attr_unique', $unique)->column('cart_num', 'product_attr_unique');
    }

    /**
     * tìm kiếm
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     * @throws \ReflectionException
     */
    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)->when(isset($where['id']) && $where['id'], function ($query) use ($where) {
            $query->whereIn('id', $where['id']);
        })->when(isset($where['status']), function ($query) use ($where) {
            //Tương thích với các giá trị mặc định của cơ sở dữ liệu người dùng cũnull
            if ($where['status'] == 1) {
                $query->where(function ($or) {
                    $or->where('status', 1)->whereOr('status', 'exp', 'is null');
                });
            } else {
                $query->where('status', $where['status']);
            }

        });
    }

    /**
     * Lấy số lượng giỏ hàng dựa trên id sản phẩm
     * @param array $ids
     * @param int $uid
     * @return mixed
     */
    public function productIdByCartNum(array $ids, int $uid)
    {
        return $this->search(['product_id' => $ids, 'is_pay' => 0, 'is_del' => 0, 'is_new' => 0, 'uid' => $uid])->group('product_attr_unique')->column('cart_num,product_id', 'product_attr_unique');
    }

    /**
     * Nhận danh sách giỏ hàng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCartList(array $where, int $page = 0, int $limit = 0, array $with = [])
    {
        return $this->search($where)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->order('add_time DESC')->select()->toArray();
    }

    /**
     * Dữ liệu giỏ hàng đã sửa đổi chưa bị xóa
     * @param array $id
     * @param array $data
     * @return \crmeb\basic\BaseModel
     */
    public function updateDel(array $id)
    {
        return $this->getModel()->whereIn('id', $id)->update(['is_del' => 1]);
    }

    /**
     * Xóa giỏ hàng
     * @param int $uid
     * @param array $ids
     * @return bool
     * @throws \Exception
     */
    public function removeUserCart(int $uid, array $ids)
    {
        return $this->getModel()->where('uid', $uid)->whereIn('id', $ids)->delete();
    }

    /**
     * Lấy số lượng giỏ hàng
     * @param $uid
     * @param $type
     * @param $numType
     */
    public function getUserCartNum($uid, $type, $numType)
    {
        $model = $this->getModel()->where(['uid' => $uid, 'type' => $type, 'is_pay' => 0, 'is_new' => 0, 'is_del' => 0]);
        if ($numType) {
            return $model->count();
        } else {
            return $model->sum('cart_num');
        }
    }

    /**
     * Thống kê giỏ hàng của người dùng
     * @param $uid
     * @param $type
     * @param string $field
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserCartList($uid, string $field = '*', array $with = [])
    {
        return $this->getModel()->where(['uid' => $uid, 'is_pay' => 0, 'is_new' => 0, 'is_del' => 0])->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->order('add_time DESC')->field($field)->select()->toArray();
    }

    /**
     * Sửa đổi số lượng giỏ hàng
     * @param $cartId
     * @param $cartNum
     * @param $uid
     */
    public function changeUserCartNum(array $where, int $carNum)
    {
        return $this->getModel()->where($where)->update(['cart_num' => $carNum]);
    }

    /**
     * Sửa đổi trạng thái giỏ hàng
     * @param $cartIds
     * @return \crmeb\basic\BaseModel
     */
    public function deleteCartStatus($cartIds)
    {
        return $this->getModel()->where('id', 'IN', $cartIds)->delete();
    }

    /**
     * Nhận giỏ hàng lớn nhấtid
     * @return mixed
     */
    public function getCartIdMax()
    {
        return $this->getModel()->max('id');
    }

    /**
     * Tổng
     * @param $where
     * @param $field
     * @return float
     */
    public function getSum($where, $field)
    {
        return $this->search($where)->sum($field);
    }

    /**
     * xu hướng giỏ hàng
     * @param $time
     * @param $timeType
     * @param $str
     * @return mixed
     */
    public function getProductTrend($time, $timeType, $str)
    {
        return $this->getModel()->where(function ($query) use ($time) {
            if ($time[0] == $time[1]) {
                $query->whereDay('add_time', $time[0]);
            } else {
                $time[1] = date('Y/m/d', strtotime($time[1]) + 86400);
                $query->whereTime('add_time', 'between', $time);
            }
        })->field("FROM_UNIXTIME(add_time,'$timeType') as days,$str as num")->group('days')->select()->toArray();
    }
}
