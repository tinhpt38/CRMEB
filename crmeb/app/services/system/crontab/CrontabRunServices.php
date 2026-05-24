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
namespace app\services\system\crontab;

use app\services\activity\combination\StorePinkServices;
use app\services\activity\live\LiveGoodsServices;
use app\services\activity\live\LiveRoomServices;
use app\services\agent\AgentManageServices;
use app\services\order\StoreOrderInvoiceServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderTakeServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\user\UserSignServices;
use think\facade\Log;

/**
 * Thực hiện các nhiệm vụ theo lịch trình
 * @tác giả Ngô triều
 * @email 442384644@qq.com
 * @date 2023/03/01
 */class CrontabRunServices
{
    /**
     * Các loại nhiệm vụ theo lịch trình. Mỗi kiểu được xác định tương ứng với một phương thức trong lớp CrontabRunServices.
     * @var string[]
     */    public $markList = [
        'orderCancel' => 'Tự động hủy đơn hàng nếu chưa thanh toán',
        'pinkExpiration' => 'Xử lý đơn hàng nhóm nhóm đã hết hạn',
        'agentUnbind' => 'Tự động hủy ràng buộc cấp trên khi hết hạn',
        'liveProductStatus' => 'Tự động cập nhật trạng thái sản phẩm trực tiếp',
        'liveRoomStatus' => 'Tự động cập nhật trạng thái phòng trực tiếp',
        'takeDelivery' => 'Tự động nhận đơn hàng',
        'advanceOff' => 'Các mặt hàng bán trước sẽ tự động bị xóa khỏi kệ khi hết hạn',
        'productReplay' => 'Các mặt hàng được đặt hàng tự động nhận được đánh giá tích cực',
        'clearPoster' => 'Xóa áp phích ngày hôm qua',
        'autoInvoice' => 'Tự động xuất hóa đơn và hoàn tiền tự động',
        'signRemind' => 'Không có lời nhắc đăng nhập',
        'customTimer' => 'Nhiệm vụ theo lịch trình tùy chỉnh',
    ];

    /**
     * Gọi một phương thức không tồn tại
     * @param $name
     * @param $arguments
     * @return mixed|void
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function __call($name, $arguments)
    {
        $this->crontabLog($name . 'phương pháp không tồn tại');
    }

    /**
     * Nhật ký nhiệm vụ theo lịch trình
     * @param $msg
     */    protected function crontabLog($msg)
    {
        $timer_log_open = config("log.timer_log", false);
        if ($timer_log_open) {
            $date = date('Y-m-d H:i:s', time());
            Log::write($date . $msg, 'crontab');
        }
    }

    /**
     * Tự động hủy đơn hàng nếu không thanh toán
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function orderCancel()
    {
        try {
            app()->make(StoreOrderServices::class)->orderUnpaidCancel();
            $this->crontabLog(' Thực hiện tự động hủy đơn hàng mà không cần thanh toán');
        } catch (\Throwable $e) {
            $this->crontabLog('Tự động hủy đơn hàng không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Xử lý đơn hàng nhóm nhóm đã hết hạn
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function pinkExpiration()
    {
        try {
            app()->make(StorePinkServices::class)->statusPink();
            $this->crontabLog(' Thực hiện xử lý lệnh hết hạn nhóm nhóm');
        } catch (\Throwable $e) {
            $this->crontabLog('Xử lý các đơn đặt hàng nhóm nhóm đã hết hạn không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Tự động hủy liên kết với cấp trên
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function agentUnbind()
    {
        try {
            app()->make(AgentManageServices::class)->removeSpread();
            $this->crontabLog(' Thực hiện tự động hủy liên kết của liên kết cấp trên');
        } catch (\Throwable $e) {
            $this->crontabLog('Không thể tự động hủy liên kết cấp trên,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái sản phẩm trực tiếp
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function liveProductStatus()
    {
        try {
            app()->make(LiveGoodsServices::class)->syncGoodStatus();
            $this->crontabLog(' Thực hiện cập nhật trạng thái sản phẩm trực tiếp');
        } catch (\Throwable $e) {
            $this->crontabLog('Không thể cập nhật trạng thái sản phẩm trực tiếp,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái phòng trực tiếp
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function liveRoomStatus()
    {
        try {
            app()->make(LiveRoomServices::class)->syncRoomStatus();
            $this->crontabLog(' Thực hiện cập nhật trạng thái phòng phát sóng trực tiếp');
        } catch (\Throwable $e) {
            $this->crontabLog('Không cập nhật được trạng thái phòng trực tiếp,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Tự động nhận
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function takeDelivery()
    {
        try {
            app()->make(StoreOrderTakeServices::class)->autoTakeOrder();
            $this->crontabLog(' Thực hiện nhận tự động');
        } catch (\Throwable $e) {
            $this->crontabLog('Tự động nhận không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Sản phẩm hết hạn bán trước sẽ tự động bị loại khỏi kệ
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function advanceOff()
    {
        try {
            app()->make(StoreProductServices::class)->downAdvance();
            $this->crontabLog(' Thực hiện các sản phẩm đã hết hạn bán trước để tự động bị loại khỏi kệ');
        } catch (\Throwable $e) {
            $this->crontabLog('Các sản phẩm đã hết hạn bán trước không thể tự động bị xóa khỏi kệ,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Khen ngợi tự động
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function productReplay()
    {
        try {
            app()->make(StoreOrderServices::class)->autoComment();
            $this->crontabLog(' Thực hiện đánh giá tích cực tự động');
        } catch (\Throwable $e) {
            $this->crontabLog('Lời khen ngợi tự động không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Xóa áp phích ngày hôm qua
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function clearPoster()
    {
        try {
            app()->make(SystemAttachmentServices::class)->emptyYesterdayAttachment();
            $this->crontabLog(' Thực hiện Xóa Poster Ngày Hôm Qua');
        } catch (\Throwable $e) {
            $this->crontabLog('Không thể xóa áp phích của ngày hôm qua,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Thực hiện tự động phát hành/mua lại hóa đơn điện tử
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function autoInvoice()
    {
        try {
            $invoiceServices = app()->make(StoreOrderInvoiceServices::class);
            $invoiceServices->autoInvoice();
            $invoiceServices->autoInvoiceRed();
            $this->crontabLog(' Thực hiện tự động phát hành/mua lại hóa đơn điện tử');
        } catch (\Throwable $e) {
            $this->crontabLog('Tự động phát hành/mua lại hóa đơn điện tử không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Không có lời nhắc đăng nhập
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2023/9/30
     */    public function signRemind()
    {
        try {
            app()->make(UserSignServices::class)->sendSignRemind();
            $this->crontabLog(' Triển khai lời nhắc không đăng ký');
        } catch (\Throwable $e) {
            $this->crontabLog('Không thể nhắc nhở đăng ký,Lý do thất bại:' . $e->getMessage());
        }
    }

    /**
     * Hẹn giờ tùy chỉnh
     * @param string $customCode
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/6
     */    public function customTimer($customCode = '')
    {
        try {
            eval($customCode);
            $this->crontabLog(' Hẹn giờ tùy chỉnh được thực hiện thành công');
        } catch (\Throwable $e) {
            $this->crontabLog('Thực hiện hẹn giờ tùy chỉnh không thành công,Lý do thất bại:' . $e->getMessage());
        }
    }
}
