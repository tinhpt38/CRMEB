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
namespace app\api\controller\v1;

use app\services\activity\combination\StorePinkServices;
use app\services\activity\live\LiveGoodsServices;
use app\services\activity\live\LiveRoomServices;
use app\services\agent\AgentManageServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderTakeServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\crontab\SystemCrontabServices;

/**
 * Bộ điều khiển tác vụ theo lịch trình
 * @tác giả Ngô triều
 * @email 442384644@qq.com
 * @date 2023/02/21
 */class CrontabController
{
    /**
     * Giao diện gọi nhiệm vụ theo lịch trình
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/02/17
     */    public function crontabRun()
    {
        app()->make(SystemCrontabServices::class)->crontabApiRun();
    }

    /**
     * Kiểm tra xem tác vụ đã lên lịch có bình thường hay không và phải được thực thi 6 giây một lần.
     */    public function crontabCheck()
    {
        file_put_contents(root_path() . 'runtime/.timer', time());
    }

    /**
     * Tự động hủy đơn hàng nếu chưa thanh toán
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function orderUnpaidCancel()
    {
        /** @var StoreOrderServices $orderServices */        $orderServices = app()->make(StoreOrderServices::class);
        $orderServices->orderUnpaidCancel();
    }

    /**
     * Xử lý đơn hàng nhóm nhóm đã hết hạn
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function pinkExpiration()
    {
        /** @var StorePinkServices $storePinkServices */        $storePinkServices = app()->make(StorePinkServices::class);
        $storePinkServices->statusPink();
    }

    /**
     * Tự động hủy liên kết ràng buộc cấp trên
     */    public function agentUnbind()
    {
        /** @var AgentManageServices $agentManage */        $agentManage = app()->make(AgentManageServices::class);
        $agentManage->removeSpread();
    }

    /**
     * Cập nhật trạng thái sản phẩm trực tiếp
     */    public function syncGoodStatus()
    {
        /** @var LiveGoodsServices $liveGoods */        $liveGoods = app()->make(LiveGoodsServices::class);
        $liveGoods->syncGoodStatus();
    }

    /**
     * Cập nhật trạng thái phòng trực tiếp
     */    public function syncRoomStatus()
    {
        /** @var LiveRoomServices $liveRoom */        $liveRoom = app()->make(LiveRoomServices::class);
        $liveRoom->syncRoomStatus();
    }

    /**
     * Tự động nhận
     */    public function autoTakeOrder()
    {
        /** @var StoreOrderTakeServices $services */        $services = app()->make(StoreOrderTakeServices::class);
        $services->autoTakeOrder();
    }

    /**
     * Kiểm tra xem các sản phẩm đã hết hạn bán trước có tự động bị loại khỏi kệ hay không
     */    public function downAdvance()
    {
        /** @var StoreProductServices $product */        $product = app()->make(StoreProductServices::class);
        $product->downAdvance();
    }

    /**
     * Khen ngợi tự động
     */    public function autoComment()
    {
        /** @var StoreOrderServices $orderServices */        $orderServices = app()->make(StoreOrderServices::class);
        $orderServices->autoComment();
    }

    /**
     * Xóa áp phích ngày hôm qua
     * @throws \Exception
     */    public function emptyYesterdayAttachment()
    {
        /** @var SystemAttachmentServices $attach */        $attach = app()->make(SystemAttachmentServices::class);
        $attach->emptyYesterdayAttachment();
    }
}
