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
use app\model\order\DeliveryService;

/**Vận chuyểndao
 * Class DeliveryServiceDao
 * @package app\dao\service
 */class DeliveryServiceDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return DeliveryService::class;
    }

    /**
     * Lấy danh sách người giao hàng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getServiceList(array $where, int $page, int $limit)
    {
        return $this->search($where, false)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->when(isset($where['noId']), function ($query) use ($where) {
            $query->where('id', '<>', $where['noId']);
        })->order('id DESC')->select()->toArray();
    }

    /**Nhận danh sách Tất cả các nhà chuyển phát nhanh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(int $page, int $limit)
    {
        $list = $this->getModel()->where(['status' => 1])->order('id DESC')->limit($page, $limit)->select()->toArray();
        foreach ($list as &$item) {
            $item['wx_name'] = $item['nickname'];
        }
        $count = $this->getModel()->where(['status' => 1])->count();
        return [$list, $count];
    }


}
