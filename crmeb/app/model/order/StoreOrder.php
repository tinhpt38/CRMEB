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

namespace app\model\order;

use app\model\activity\combination\StorePink;
use app\model\system\store\SystemStore;
use app\model\system\store\SystemStoreStaff;
use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Đơn hàngModel
 * Class StoreOrder
 * @package app\model\order
 */class StoreOrder extends BaseModel
{
    use ModelTrait;

    /**
     * Hình thức thanh toán
     * @var string[]
     */    protected $pay_type = [
        1 => 'weixin',
        2 => 'yue',
        3 => 'offline',
        4 => 'alipay'
    ];

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_order';

    protected $insert = ['add_time'];

    /**
     * Thời gian cập nhật
     * @var bool | string | int
     */    protected $updateTime = false;

    /**
     * Tạo công cụ sửa đổi thời gian
     * @return int
     */    protected function setAddTimeAttr($time = 0)
    {
        if ($time) return $time;
        return time();
    }

    /**
     * Công cụ sửa đổi biểu mẫu tùy chỉnh
     * @param $value
     * @return array|mixed
     */    public function setCustomFormAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Trình lấy biểu mẫu tùy chỉnh
     * @param $value
     * @return array|mixed
     */    public function getCustomFormAttr($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : [];
    }

    /**
     * Đơn hàng phụ truy vấn liên quan một đến nhiều
     * @return \think\model\relation\HasMany
     */    public function split()
    {
        return $this->hasMany(StoreOrder::class, 'pid', 'id');
    }

    /**
     * Liên kết một-một của các bảng Khách hàng
     * @return \think\model\relation\HasOne
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname', 'phone', 'avatar', 'spread_uid'])->bind([
            'nickname' => 'nickname',
            'phone' => 'phone',
            'avatar' => 'avatar',
        ]);
    }

    public function division()
    {
        return $this->hasOne(User::class, 'uid', 'division_id')->field(['uid', 'nickname'])->bind([
            'division_name' => 'nickname'
        ]);
    }

    /**
     * Liên kết một-một với thông tin Khách hàng vượt trội
     * @return \think\model\relation\HasOne
     */    public function spread()
    {
        return $this->hasOne(User::class, 'uid', 'spread_uid')->field(['uid', 'nickname'])->bind([
            'spread_nickname' => 'nickname'
        ]);
    }

    /**
     * Cuộc chiến nhóm một chọi một để giành được địa vị
     * @return \think\model\relation\HasOne
     */    public function pink()
    {
        return $this->hasOne(StorePink::class, 'id', 'pink_id')->field(['id', 'order_id_key', 'status'])->bind([
            'pinkStatus' => 'status'
        ]);
    }

    /**
     * Lưu trữ mối quan hệ một-một
     * @return \think\model\relation\HasOne
     */    public function store()
    {
        return $this->hasOne(SystemStore::class, 'id', 'store_id')->field(['id', 'name'])->bind([
            'store_name' => 'name'
        ]);
    }

    /**
     * Nhân viên liên quan đặt hàng
     * @return \think\model\relation\HasOne
     */    public function staff()
    {
        return $this->hasOne(SystemStoreStaff::class, 'uid', 'clerk_id')->field(['id', 'uid', 'store_id', 'staff_name'])->bind([
            'staff_uid' => 'uid',
            'staff_store_id' => 'store_id',
            'clerk_name' => 'staff_name'
        ]);
    }

    /**
     * Người dùng liên quan đến thư ký cửa hàng
     * @return \think\model\relation\HasOne
     */    public function staffUser()
    {
        return $this->hasOne(User::class, 'uid', 'staff_uid')->field(['uid', 'nickname'])->bind([
            'clerk_name' => 'nickname'
        ]);
    }

    /**
     * Hóa đơn đặt hàng liên quan
     * @return \think\model\relation\HasOne
     */    public function invoice()
    {
        return $this->hasOne(StoreOrderInvoice::class, 'order_id', 'id');
    }

    /**
     * Lệnh hoàn tiền liên quan một đến nhiều
     * @return \think\model\relation\hasMany
     */    public function refund()
    {
        return $this->hasMany(StoreOrderRefund::class, 'store_order_id', 'id')->where('refund_type', '<>', 3)->where('is_cancel', 0);
    }

    /**
     * Công cụ sửa đổi ID giỏ hàng
     * @param $value
     * @return false|string
     */    protected function setCartIdAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Trình thu thập giỏ hàng
     * @param $value
     * @param $data
     * @return mixed
     */    protected function getCartIdAttr($value, $data)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Trình tìm kiếm ID đơn hàng
     * @param Model $query
     * @param $value
     */    public function searchOrderIdAttr($query, $value)
    {
        $query->where('order_id', $value);
    }

    /**
     * Trình tìm kiếm ID lớp cha
     * @param Model $query
     * @param $value
     */    public function searchPidAttr($query, $value)
    {
        if ($value === 0) {
            $query->where('pid', '>=', 0);
        } else {
            $query->where('pid', $value);
        }
    }

    /**
     * Không phân chia đơn hàng và đơn hàng phụ(0:Đối với lệnh tách -1: Lệnh chính đã được tách >0 :Đơn hàng phụ sau khi tách)
     * @param Model $query
     * @param $value
     */    public function searchNotPidAttr($query, $value)
    {
        $query->where('pid', '<>', -1);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }

    /**
     * Công cụ tìm phương thức thanh toán
     * @param $query
     * @param $value
     */    public function searchPayTypeAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('pay_type', $value);
        } else {
            if ($value !== '') {
                $pay_type = $this->pay_type;
                if (in_array($value, array_keys($pay_type)) && $type = $pay_type[$value] ?? '') {
                    $query->where('pay_type', $type);
                } else {
                    $query->where('pay_type', $value);
                }
            }
        }
    }

    /**
     * Không bằng Thanh toán bằng số dư
     * @param $query
     * @param $value
     */    public function searchPayTypeNoAttr($query, $value)
    {
        $query->where('pay_type', "<>", $value);
    }

    /**
     * ID đơn hàng hoặc người tìm kiếm tên Khách hàng
     * @param $query
     * @param $value
     */    public function searchOrderIdRealNameAttr($query, $value)
    {
        $query->where('order_id|real_name', $value);
    }

    /**
     * Trình tìm kiếm ID Khách hàng
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('uid|gift_uid', $value);
        else
            $query->where('uid|gift_uid', $value);
    }

    /**
     * Không bao gồm người tìm kiếm ID Khách hàng
     * @param Model $query
     * @param $value
     */    public function searchNotUidAttr($query, $value)
    {
        $query->where('uid', '<>', $value);
    }

    /**
     * Trình tìm trạng thái thanh toán
     * @param Model $query
     * @param $value
     */    public function searchPaidAttr($query, $value)
    {
        if (in_array($value, [0, 1])) {
            $query->where('paid', $value);
        }
    }

    /**
     * Công cụ tìm trạng thái hoàn tiền
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchRefundStatusAttr($query, $value, $data)
    {
        if ($value !== '') {
            if (is_array($value)) {
                $query->whereIn('refund_status', $value);
            } else {
                $query->where('refund_status', $value);
            }
        }
    }

    /**
     * Công cụ tìm trạng thái hoàn tiền
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchRefundStatusInAttr($query, $value)
    {
        $query->whereIn('refund_status', $value);
    }

    /**
     * Đây có phải là lệnh nhóm không?
     * @param Model $query
     * @param $value
     */    public function searchPinkIdAttr($query, $value)
    {
        $query->where('pink_id', $value);
    }

    /**
     * Trình tìm kiếm ID nhóm
     * @param Model $query
     * @param $value
     */    public function searchCombinationIdAttr($query, $value)
    {
        $query->where('combination_id', $value);
    }

    /**
     * Không có đơn hàng nhóm hoặc sản phẩm nhóm
     * @param Model $query
     * @param $value
     */    public function searchCpIdGtAttr($query, $value)
    {
        $query->where('combination_id|pink_id', '>', $value);
    }

    /**
     * Không phải là công cụ tìm kiếm flash sale
     * @param Model $query
     * @param $value
     */    public function searchSeckillIdGtAttr($query, $value)
    {
        $query->where('seckill_id', '>', $value);
    }

    /**
     * Trình tìm kiếm sản phẩm ID flash sale
     * @param Model $query
     * @param $value
     */    public function searchSeckillIdAttr($query, $value)
    {
        $query->where('seckill_id', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm mặc cả
     * @param Model $query
     * @param $value
     */    public function searchBargainIdAttr($query, $value)
    {
        $query->where('bargain_id', $value);
    }

    /**
     * Thuộc về người tìm kiếm mặc cả
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchBargainIdGtAttr($query, $value)
    {
        $query->where('bargain_id', '>', $value);
    }

    /**
     * Trình tìm kiếm mã xác minh
     * @param Model $query
     * @param $value
     */    public function searchVerifyCodeAttr($query, $value)
    {
        $query->where('verify_code', $value);
    }

    /**
     * Trình tìm trạng thái thanh toán
     * @param Model $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        if ($value != '') $query->where('is_del', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */    public function searchIsSystemDelAttr($query, $value)
    {
        if ($value != '') $query->where('is_system_del', $value);
    }

    /**
     * Công cụ tìm trạng thái hoàn tiền
     * @param $query
     * @param $value
     */    public function searchRefundTypeAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('refund_type', $value);
        } else {
            if ($value == -1) {
                $query->where('refund_type', 'in', '0,3');
            } else {
                if ($value == 0 || $value == '') {
                    $query->where('refund_type', '<>', 0);
                } else {
                    $query->where('refund_type', $value);
                }
            }
        }
    }

    /**
     * Nguồn Khách hàng
     * @param Model $query
     * @param $value
     */    public function searchChannelTypeAttr($query, $value)
    {
        if ($value != '') $query->where('channel_type', $value);
    }

    /**
     * Trình tìm ID hoàn tiền
     * @param Model $query
     * @param $value
     */    public function searchRefundIdAttr($query, $value)
    {
        if ($value) {
            $query->where('id', 'in', $value);
        }
    }

    /**
     * Thượng đẳng｜Nhà quảng bá vượt trội
     * @param $query
     * @param $value
     */    public function searchSpreadOrUidAttr($query, $value)
    {
        if ($value) $query->where('spread_uid|spread_two_uid', $value);
    }

    public function searchAllSpreadAttr($query, $value)
    {
        if ($value) $query->where('spread_uid|spread_two_uid|division_id|agent_id|staff_id', $value);
    }

    /**
     * Nhà quảng bá cấp cao
     * @param $query
     * @param $value
     */    public function searchSpreadUidAttr($query, $value)
    {
        if ($value) $query->where('spread_uid', $value);
    }

    /**
     * Nhà quảng bá vượt trội
     * @param $query
     * @param $value
     */    public function searchSpreadTwoUidAttr($query, $value)
    {
        if ($value) $query->where('spread_two_uid', $value);
    }

    /**
     * kênh thanh toán
     * @param $query
     * @param $value
     */    public function searchIsChannelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_channel', $value);
    }

    /**
     * Tìm kiếm hoạt động 0 bình thường, 1 flash sale, 2 mặc cả, 3 mua theo nhóm, 4 bán trước
     * @param $query
     * @param $value
     * @param $data
     */    public function searchActivityTypeAttr($query, $value, $data)
    {
        if ($value !== '') {
            switch ($value) {
                case 0:
                    $query->where('combination_id', 0)->where('seckill_id', 0)->where('bargain_id', 0)->where('advance_id', 0);
                    break;
                case 1:
                    $query->where('seckill_id', '>', 0);
                    break;
                case 2:
                    $query->where('bargain_id', '>', 0);
                    break;
                case 3:
                    $query->where('combination_id', '>', 0);
                    break;
                case 4:
                    $query->where('advance_id', '>', 0);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Lệnh khuyến mãi của bộ phận kinh doanh
     * @param $query
     * @param $value
     */    public function searchDivisionIdAttr($query, $value)
    {
        if ($value !== '') $query->where('division_id', $value);
    }

    /**
     * Lệnh khuyến mãi đại lý
     * @param $query
     * @param $value
     */    public function searchAgentIdAttr($query, $value)
    {
        if ($value !== '') $query->where('agent_id', $value);
    }

    /**
     * Lệnh khuyến mãi đại lý
     * @param $query
     * @param $value
     */    public function searchStaffIdAttr($query, $value)
    {
        if ($value !== '') $query->where('staff_id', $value);
    }

    /**
     * @param $query
     * @param $value
     */    public function searchIdsAttr($query, $value)
    {
        if (is_string($value)) $value = explode(',', $value);
        if (count($value)) $query->whereIn('id', $value);
    }

    public function searchDivisionBrokerageGreaterAttr($query, $value)
    {
        $query->where('division_brokerage', '>', $value);
    }

    public function searchAgentBrokerageGreaterAttr($query, $value)
    {
        $query->where('agent_brokerage', '>', $value);
    }

    public function searchVirtualTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('virtual_type', $value);
    }
}
