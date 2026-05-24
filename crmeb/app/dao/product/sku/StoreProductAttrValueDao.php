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

namespace app\dao\product\sku;

use app\dao\BaseDao;
use app\model\product\sku\StoreProductAttrValue;

/**
 * Class StoreProductAttrValueDao
 * @package app\dao\product\sku
 */class StoreProductAttrValueDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreProductAttrValue::class;
    }

    /**
     * Nhận thông số kỹ thuật dựa trên điều kiệnvalue
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */    public function getColumn(array $where, string $field = '*', string $key = 'suk')
    {
        return $this->search($where)->column($field, $key);
    }

    /**
     * Xóa thông số kỹ thuật dựa trên điều kiệnvalue
     * @param int $id
     * @param int $type
     * @return bool
     * @throws \Exception
     */    public function del(int $id, int $type)
    {
        return $this->search(['product_id' => $id, 'type' => $type])->delete();
    }

    /**
     * lưu dữ liệu
     * @param array $data
     * @return mixed|\think\Collection
     * @throws \Exception
     */    public function saveAll(array $data)
    {
        return $this->getModel()->saveAll($data);
    }

    /**
     * Nhận danh sách dữ liệu đặc tả dựa trên các điều kiện
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getProductAttrValue(array $where)
    {
        return $this->search($where)->order('id asc')->select()->toArray();
    }

    /**Nhận danh sách tài sản
     * @return mixed
     */    public function attrValue()
    {
        return $this->search()->field('product_id,sum(sales * price) as val')->with(['product'])->group('product_id')->limit(20)->select()->toArray();
    }

    /**Nhận kiểm kê tài sản
     * @param string $unique
     * @return int
     */    public function uniqueByStock(string $unique)
    {
        return $this->search(['unique' => $unique])->value('stock') ?: 0;
    }

    /**
     * Giảm hàng tồn kho, tăng doanh số bán hàng, giảm hạn chế mua hàng
     * @param array $where
     * @param int $num
     * @return mixed
     */    public function decStockIncSalesDecQuota(array $where, int $num)
    {
        return $this->getModel()->where($where)->dec('stock', $num)->dec('quota', $num)->inc('sales', $num)->update();
    }

    /**
     * Nhận một phần dữ liệu đặc tả dựa trên(Trung tâm mua sắm điểm)
     * @param string $unique
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function uniqueByField(string $unique, string $field = '*')
    {
        return $this->search(['unique' => $unique, 'type' => 4])->field($field)->with(['storeIntegral'])->find();
    }
}
