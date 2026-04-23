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

namespace app\model\activity\lottery;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *
 * Class LuckPrizeDao
 * @package app\model\activity\lottery
 */
class LuckPrize extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'luck_prize';


    /**
     * Xổ số liên quan
     * @return \think\model\relation\HasOne
     */
    public function lottery()
    {
        return $this->hasOne(LuckLottery::class, 'id', 'lottery_id');
    }

    /**
     * Người tìm kiếm id xổ số
     * @param $query Model
     * @param $value
     */
    public function searchLotteryIdAttr($query, $value)
    {
        if ($value) $query->where('lottery_id', $value);
    }

    /**
     * Công cụ tìm loại giải thưởng
     * @param $query Model
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value) $query->where('type', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param $query Model
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param $query Model
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }
}
