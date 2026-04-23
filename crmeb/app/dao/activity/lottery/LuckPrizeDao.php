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

namespace app\dao\activity\lottery;

use app\dao\BaseDao;
use app\model\activity\lottery\LuckPrize;

/**
 *
 * Class LuckPrizeDao
 * @package app\dao\activity\lottery
 */
class LuckPrizeDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return LuckPrize::class;
    }

    /**
     * Nhận tất cả các giải thưởng từ một sự kiện
     * @param int $lottery_id
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPrizeList(int $lottery_id, string $field = '*')
    {
        $where = ['is_del' => 0, 'status' => 1];
        return $this->search($where + ['lottery_id' => $lottery_id])->field($field)->order('sort desc,id desc')->select()->toArray();
    }
}
