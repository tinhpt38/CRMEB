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

namespace app\dao\activity\bargain;

use app\dao\BaseDao;
use app\model\activity\bargain\StoreBargainUserHelp;

/**
 *
 * Class StoreBargainUserHelpDao
 * @package app\dao\activity
 */
class StoreBargainUserHelpDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreBargainUserHelp::class;
    }

    /**
     * Nhận số lượng người giúp đỡ
     * @return array
     */
    public function getHelpAllCount(array $where = [])
    {
        return $this->getModel()->where($where)->group('bargain_id')->column('count(*)', 'bargain_id');
    }

    /**
     * Lấy danh sách người giúp đỡ
     * @param int $bid
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getHelpList(int $bid, int $page = 0, int $limit = 0)
    {
        return $this->getModel()
            ->where('bargain_user_id', $bid)
            ->order('add_time desc')
            ->when($page, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->column("uid,price,from_unixtime(add_time,'%Y-%m-%d %H:%i:%s') as add_time", 'id');
    }

    /**
     * Lấy số lượng người đã giảm giá sản phẩm ở mức giá ưu đãi
     * @return array
     */
    public function getNums()
    {
        return $this->getModel()->field('count(id) as num,bargain_user_id')->group('bargain_user_id')->select()->toArray();
    }
}
