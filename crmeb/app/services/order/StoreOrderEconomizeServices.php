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


use app\dao\order\StoreOrderEconomizeDao;
use app\services\BaseServices;
use crmeb\exceptions\ApiException;

/**
 * Class StoreOrderInvoiceServices
 * @package app\services\order
 */
class StoreOrderEconomizeServices extends BaseServices
{
    /**
     * LiveAnchorServices constructor.
     * @param StoreOrderInvoiceDao $dao
     */
    public function __construct(StoreOrderEconomizeDao $dao)
    {
        $this->dao = $dao;
    }

    /**Thêm dữ liệu tiết kiệm
     * @param array $add
     * @return mixed
     */
    public function addEconomize(array $add)
    {
        if (!$add) throw new ApiException('Dữ liệu không tồn tại');
        return $this->dao->save($add);
    }

    /**
     * @param array $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOne(array $where)
    {
        if (!$where) throw new ApiException('Lỗi tham số');
        return $this->dao->getOne($where);
    }

    /**Tổng số tiền tiết kiệm dành cho thành viên trả phí
     * @param $uid
     * @return bool|float
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sumEconomizeMoney($uid)
    {
        if (!$uid) return false;
        $list = $this->dao->getList(['uid' => $uid]);
        $economizeMoney = 0.00;
        if ($list) {
            foreach ($list as $k => $v) {
                $economizeMoney += $v['postage_price'];
                $economizeMoney += $v['member_price'];
                $economizeMoney += $v['offline_price'];
                $economizeMoney += $v['coupon_price'];
            }
        }
       return sprintf("%.2f",$economizeMoney);
    }
}
