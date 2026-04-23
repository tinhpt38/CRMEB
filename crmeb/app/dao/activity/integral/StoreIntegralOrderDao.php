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

namespace app\dao\activity\integral;


use app\dao\BaseDao;
use app\model\activity\integral\StoreIntegralOrder;

/**
 * Đặt hàng
 * Class StoreOrderDao
 * @package app\dao\order
 */
class StoreIntegralOrderDao extends BaseDao
{

    /**
     * Giới hạn các trường truy vấn chính xác
     * @var string[]
     */
    protected $withField = ['uid', 'order_id', 'real_name', 'user_phone', 'store_name'];

    /**
     * @return string
     */
    protected function setModel(): string
    {
        return StoreIntegralOrder::class;
    }

    /**
     * Tìm kiếm đơn hàng
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     * @throws \ReflectionException
     */
    public function search(array $where = [], bool $search = false)
    {
        $isDel = isset($where['is_del']) && $where['is_del'] !== '' && $where['is_del'] != -1;
        $realName = $where['real_name'] ?? '';
        $fieldKey = $where['field_key'] ?? '';
        $fieldKey = $fieldKey == 'all' ? '' : $fieldKey;
        return parent::search($where, $search)->when($isDel, function ($query) use ($where) {
            $query->where('is_del', $where['is_del']);
        })->when(isset($where['is_system_del']), function ($query) {
            $query->where('is_system_del', 0);
        })->when($realName && $fieldKey && in_array($fieldKey, $this->withField), function ($query) use ($where, $realName, $fieldKey) {
            if ($fieldKey !== 'store_name') {
                $query->where(trim($fieldKey), trim($realName));
            } else {
                $query->whereLike('store_name', '%' . $realName . '%');
            }
        })->when($realName && !$fieldKey, function ($query) use ($where) {
            $query->where(function ($que) use ($where) {
                $que->whereLike('order_id|real_name|store_name', '%' . $where['real_name'] . '%')->whereOr('uid', 'in', function ($q) use ($where) {
                    $q->name('user')->whereLike('nickname|uid|phone', '%' . $where['real_name'] . '%')->field(['uid'])->select();
                });
            });
        });
    }

    /**
     * Danh sách tìm kiếm đơn hàng
     * @param array $where
     * @param array $field
     * @param int $page
     * @param int $limit
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderList(array $where, array $field, int $page, int $limit, array $with = [])
    {
        return $this->search($where)->field($field)->with(array_merge(['user'], $with))->page($page, $limit)->order('add_time DESC,id DESC')->select()->toArray();
    }

    /**
     * Lấy tổng số đơn hàng
     * @param array $where
     * @param bool $search
     * @return int
     * @throws \ReflectionException
     */
    public function count(array $where = [], bool $search = true)
    {
        return $this->search($where)->count();
    }

    /**
     * Tìm dữ liệu đơn hàng trong các điều kiện được chỉ định và trả về dưới dạng mảng
     * @param array $where
     * @param string $field
     * @param string $key
     * @param string $group
     * @return array
     */
    public function column(array $where, string $field, string $key = '', string $group = '')
    {
        return $this->search($where)->when($group, function ($query) use ($group) {
            $query->group($group);
        })->column($field, $key);
    }

    /**
     * Nhận chi tiết đơn hàng
     * @param $uid
     * @param $key
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserOrderDetail(string $key, int $uid)
    {
        return $this->getOne(['order_id' => $key, 'uid' => $uid, 'is_del' => 0]);
    }

    /**
     * Lấy số lượng vật phẩm người dùng đã mua cho sự kiện này
     * @param $uid
     * @param $productId
     * @return int
     */
    public function getBuyCount($uid, $productId): int
    {
        return $this->getModel()
                ->where('uid', $uid)
                ->where('is_del', 0)
                ->where('product_id', $productId)
                ->value('sum(total_num)') ?? 0;
    }

}
