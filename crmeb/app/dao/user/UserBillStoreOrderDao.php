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

namespace app\dao\user;

use app\dao\BaseDao;
use app\model\order\StoreOrder;
use app\model\user\UserBill;

/**
 *
 * Class UserBillStoreOrderDao
 * @package app\dao\user
 */
class UserBillStoreOrderDao extends BaseDao
{

    protected $alias = '';
    protected $join_alis = '';

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return UserBill::class;
    }

    public function joinModel(): string
    {
        return StoreOrder::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */
    public function getModel(string $table = '', string $alias = 'b', string $join_alias = 'o', $join = 'left')
    {
        $this->alias = $alias;
        $this->join_alis = $join_alias;
        if (!$table) {
            /** @var StoreOrder $storeOrder */
            $storeOrder = app()->make($this->joinModel());
            $table = $storeOrder->getName();
        }
        return parent::getModel()->join($table . ' ' . $join_alias, $alias . '.link_id = ' . $join_alias . '.id', $join)->alias($alias);
    }

    /**
     * nhóm thời gian
     * @param array $where
     * @param array $whereOr
     * @param string $field
     * @param string $group
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getList(array $where, array $whereOr, array $times, string $field, $page, $limit)
    {
        return $this->getModel()->where($where)->where("FROM_UNIXTIME(b.add_time, '%Y-%m')", 'in', $times)
            ->where(function ($q) use ($whereOr) {
                $q->whereOr($whereOr);
            })
            ->with([
                'user' => function ($query) {
                    $query->field('uid,avatar,nickname')->bind(['avatar' => 'avatar', 'nickname' => 'nickname']);
                }])->field($field)->order('id desc')->page($page, $limit)->select()->toArray();
    }

    /**
     * nhóm thời gian
     * @param array $where
     * @param array $whereOr
     * @param string $field
     * @param string $group
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getListByGroup(array $where, array $whereOr, string $field, string $group, $page, $limit)
    {
        return $this->getModel()->where($where)->where(function ($q) use ($whereOr) {
            $q->whereOr($whereOr);
        })->field($field)->order($group . ' desc')->group($group)->page($page, $limit)->select()->toArray();
    }

    /**
     * nhóm thời gian
     * @param array $where
     * @param array $whereOr
     * @param string $field
     * @param string $group
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getListCount(array $where, array $whereOr)
    {
        return $this->getModel()->where($where)->where(function ($q) use ($whereOr) {
            $q->whereOr($whereOr);
        })->count('b.id');
    }
}
