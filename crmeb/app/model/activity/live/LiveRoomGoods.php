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

namespace app\model\activity\live;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Class LiveRoomGoods
 * @package app\model\live
 */
class LiveRoomGoods extends BaseModel
{
    use ModelTrait;

    protected $name = 'live_room_goods';

    public function goods()
    {
        return $this->hasOne(LiveGoods::class, 'id', 'live_goods_id');
    }

    public function room()
    {
        return $this->hasOne(LiveRoom::class, 'id', 'live_room_id');
    }
}
