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

namespace app\dao\activity\coupon;


use app\dao\BaseDao;
use app\model\activity\coupon\StoreCoupon;
use app\model\activity\coupon\StoreCouponUser;

/**
 * Class StoreCouponUserCouponDao
 * @package app\dao\coupon
 */class StoreCouponUserCouponDao extends BaseDao
{
    /**
     * Bí danh bảng chính
     * @var string
     */    protected $alias = 'a';

    /**
     * Tham gia bí danh bảng
     * @var string
     */    protected $joinAlis = 'b';

    /**
     * Mẫu bàn chính
     * @return string
     */    public function setModel(): string
    {
        return StoreCouponUser::class;
    }

    /**
     * Hiển thị bảng được kết nối
     * @return string
     */    public function setJoinModel(): string
    {
        return StoreCoupon::class;
    }

    /**
     * Thiết lập mô hình
     * @return \crmeb\basic\BaseModel
     */    public function getModel()
    {
        /** @var StoreCoupon $joinModel */        $joinModel = app()->make($this->setJoinModel());
        $name = $joinModel->getName();
        return parent::getModel()->alias($this->alias)->join($name . ' ' . $this->joinAlis, $this->joinAlis . '.id=' . $this->alias . '.cid');
    }

    /**
     * Nhận phiếu giảm giá mà Khách hàng có thể sử dụng dựa trên số lượng đặt hàng
     * @param int $uid
     * @param string $truePrice
     * @param int $productId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUidCouponList(int $uid, string $truePrice, int $productId)
    {
        return $this->getModel()
            ->where($this->alias . '.uid', $uid)
            ->where($this->alias . '.is_fail', 0)
            ->where($this->alias . '.status', 0)
            ->where($this->alias . '.use_min_price', '<=', $truePrice)
            ->whereFindinSet($this->joinAlis . '.product_id', $productId)
            ->where($this->joinAlis . '.type', 2)
            ->field($this->alias . '.*,' . $this->joinAlis . '.type')
            ->order($this->alias . '.coupon_price', 'DESC')
            ->select()
            ->hidden(['status', 'is_fail'])
            ->toArray();
    }

    /**
     * Nhận phiếu giảm giá trong số tiền mua tối thiểu
     * @param $uid
     * @param $price
     * @param $value
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUidCouponMinList($uid, $price, $value = '', int $type = 1)
    {
        return $this->getModel()->where($this->alias . '.uid', $uid)
            ->where($this->alias . '.is_fail', 0)
            ->where($this->alias . '.status', 0)
            ->where($this->alias . '.use_min_price', '<=', $price)
            ->when($value, function ($query) use ($value) {
                $query->whereFindinSet($this->joinAlis . '.category_id', $value);
            })
            ->where($this->joinAlis . '.type', $type)
            ->field($this->alias . '.*,' . $this->joinAlis . '.type')
            ->order($this->alias . '.coupon_price', 'DESC')
            ->select()
            ->hidden(['status', 'is_fail'])
            ->toArray();
    }
}
