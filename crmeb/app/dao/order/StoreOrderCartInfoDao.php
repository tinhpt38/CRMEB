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

namespace app\dao\order;


use app\dao\BaseDao;
use app\model\order\StoreOrderCartInfo;

/**
 * Chi tiết đơn hàng
 * Class StoreOrderCartInfoDao
 * @package app\dao\order
 */class StoreOrderCartInfoDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreOrderCartInfo::class;
    }

    /**
     * Lấy danh sách chi tiết giỏ hàng
     * @param array $where
     * @param array $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getCartInfoList(array $where, array $field)
    {
        return $this->search($where)->field($field)->select()->toArray();
    }

    /**
     * Nhận thông tin giỏ hàng và trả về dưới dạng mảng
     * @param array $where
     * @param string $field
     * @param string $key
     */    public function getCartColunm(array $where, string $field, string $key = '')
    {
        return $this->search($where)->column($field, $key);
    }

    public function getSplitCartNum($cart_ids)
    {
        $res = $this->getModel()->whereIn('old_cart_id', $cart_ids)->field('sum(cart_num) as num,old_cart_id')->group('old_cart_id')->select()->toArray();
        $data = [];
        foreach ($res as $value) {
            $data[$value['old_cart_id']] = $value['num'];
        }
        return $data;
    }
}
