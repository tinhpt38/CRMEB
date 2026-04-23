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
use app\model\user\User;
use app\model\user\UserBill;


/**
 *
 * Class UserUserBillDao
 * @package app\dao\user
 */
class UserUserBillDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return User::class;
    }

    public function joinModel(): string
    {
        return UserBill::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */
    public function getModel(string $alias = 'u', string $join_alias = 'b', $join = 'left')
    {
        $this->alias = $alias;
        $this->join_alis = $join_alias;
        /** @var $userBiil $userBiil */
        $userBiil = app()->make($this->joinModel());
        $table = $userBiil->getName();
        return parent::getModel()->join($table . ' ' . $join_alias, $alias . '.uid = ' . $join_alias . '.uid', $join)->alias($alias);
    }

    /**
     * Danh sách truy vấn mô hình điều kiện kết hợp
     * @param Model $model
     * @return array
     */
    public function getList(array $where, string $field = '', string $order = '', int $page = 0, int $limit = 0)
    {
        return $this->getModel()->where($where)->field($field)->group('u.uid')->order($order)->order('id desc')
            ->when($page && $limit, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->select()->toArray();
    }

    /**
     * Lấy số lượng mặt hàng
     * @param array $where
     * @return mixed
     */
    public function getCount(array $where)
    {
        return $this->getModel()->where($where)->group('u.uid')->count();
    }
}
