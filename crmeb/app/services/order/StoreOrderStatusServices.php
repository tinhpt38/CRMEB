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

namespace app\services\order;


use app\dao\order\StoreOrderStatusDao;
use app\services\BaseServices;

/**
 * Trạng thái đơn hàng
 * Class StoreOrderStatusServices
 * @package app\services\order
 */class StoreOrderStatusServices extends BaseServices
{

    /**
     * Người xây dựng
     * StoreOrderStatusServices constructor.
     * @param StoreOrderStatusDao $dao
     */    public function __construct(StoreOrderStatusDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Phân trang trạng thái đơn hàng
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getStatusList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getStatusList($where, $page, $limit);
        foreach ($list as &$item) {
            if (is_int($item['change_time'])) $item['change_time'] = date('Y-m-d H:i:s', $item['change_time']);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

}
