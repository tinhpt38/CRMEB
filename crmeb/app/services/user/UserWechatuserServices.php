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

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserWechatUserDao;

/**
 *
 * Class UserWechatuserServices
 * @package app\services\user
 */class UserWechatuserServices extends BaseServices
{

    /**
     * UserWechatuserServices constructor.
     * @param UserWechatUserDao $dao
     */    public function __construct(UserWechatUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Tổng số truy vấn đơn giản tùy chỉnh
     * @param array $where
     * @return int
     */    public function getCount(array $where): int
    {
        return $this->dao->getCount($where);
    }

    /**
     * Danh sách tìm kiếm điều kiện phức tạp
     * @param array $where
     * @param string $field
     * @return array
     */    public function getWhereUserList(array $where, string $field): array
    {
        [$page, $limit] = $this->getPageValue();
        $order_string = '';
        $order_arr = ['ascending', 'descending'];
        if (isset($where['now_money']) && in_array($where['now_money'], $order_arr)) {
            $order_string = $where['now_money'] == 'ascending' ? 'now_money asc' : 'now_money desc';
        }
        $list = $this->dao->getListByModel($where, $field, $order_string, $page, $limit);
        $count = $this->dao->getCountByWhere($where);
        return [$list, $count];
    }
}
