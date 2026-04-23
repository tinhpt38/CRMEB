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

namespace app\jobs;

use app\services\activity\live\LiveGoodsServices;
use app\services\activity\live\LiveRoomServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class LiveJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Sau khi đồng bộ dữ liệu
     * @param $order
     * @return bool
     */
    public function doJob()
    {
        //Cập nhật trạng thái sản phẩm trực tiếp
        try {
            /** @var LiveGoodsServices $liveGoods */
            $liveGoods = app()->make(LiveGoodsServices::class);
            $liveGoods->syncGoodStatus(true);
        } catch (\Throwable $e) {
            Log::error('Không thể cập nhật trạng thái sản phẩm trực tiếp,Lý do thất bại:' . $e->getMessage());
        }
        //Cập nhật trạng thái phòng trực tiếp
        try {
            /** @var LiveRoomServices $liveRoom */
            $liveRoom = app()->make(LiveRoomServices::class);
            $liveRoom->syncRoomStatus(true);
        } catch (\Throwable $e) {
            Log::error('Không cập nhật được trạng thái phòng trực tiếp,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }
}
