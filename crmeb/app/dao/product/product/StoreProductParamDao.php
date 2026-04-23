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
use app\model\product\product\StoreProductParam;

/**
 * Thông số sản phẩm
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2024/12/17
 */
class StoreProductParamDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    protected function setModel(): string
    {
        return StoreProductParam::class;
    }

    /**
     * Tìm kiếm có điều kiện
     * @param $where
     * @return \crmeb\basic\BaseModel
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function conditionSearch($where)
    {
        return $this->getModel()
            ->when(isset($where['name']) && $where['name'] !== '', function ($query) use ($where) {
                $query->whereLike('name', '%' . $where['name'] . '%');
            })->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where) {
                $query->where('is_del', $where['is_del']);
            })->when(isset($where['status']) && $where['status'] !== '', function ($query) use ($where) {
                $query->where('status', $where['status']);
            });
    }

    /**
     * Lấy danh sách tham số
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function getParamList(array $where = [], string $field = '*', int $page = 0, int $limit = 0)
    {
        return $this->conditionSearch($where)->field($field)->order('sort DESC')->page($page, $limit)->select()->toArray();
    }

    /**
     * Lấy số lượng danh sách tham số
     * @param array $where
     * @return int
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function getParamCount(array $where = [])
    {
        return $this->conditionSearch($where)->count();
    }
}
