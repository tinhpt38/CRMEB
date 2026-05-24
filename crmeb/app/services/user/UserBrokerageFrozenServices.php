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

namespace app\services\user;


use app\dao\user\UserBrokerageFrozenDao;
use app\services\BaseServices;

/**
 * Hoa hồng đóng băng
 * Class UserBrokerageFrozenServices
 * @package app\services\user
 * @method getUserFrozenPrice(int $uid, bool $isFrozen) Nhận hoa hồng bị đóng băng và hết hạn trong một tài khoản
 * @method updateFrozen(string $orderId) Sửa đổi trạng thái đóng băng hoa hồng
 * @method getFrozenBrokerage() Lấy mảng hoa hồng cố định của Khách hàng
 * @method getSumFrozenBrokerage() Nhận số tiền hoa hồng cố định
 */class UserBrokerageFrozenServices extends BaseServices
{
    /**
     * UserBrokerageFrozenServices constructor.
     * @param UserBrokerageFrozenDao $dao
     */    public function __construct(UserBrokerageFrozenDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Tiết kiệm số tiền đông lạnh
     * @param int $uid
     * @param string $price
     * @param int $uillId
     * @param string $orderId
     * @return mixed
     */    public function saveBrokage(int $uid, string $price, int $uillId, string $orderId)
    {
        $broken_time = intval(sys_config('extract_time'));
        $data = [
            'uid' => $uid,
            'price' => $price,
            'uill_id' => $uillId,
            'order_id' => $orderId,
            'add_time' => time(),
            'status' => 1,
            'frozen_time' => time() + $broken_time * 86400
        ];
        return $this->dao->save($data);
    }
}
