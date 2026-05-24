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

namespace app\services\message;

use app\dao\system\MessageSystemDao;
use app\services\BaseServices;
use crmeb\exceptions\ApiException;

/**
 * Danh mục dịch vụ tin nhắn trang web
 * Class MessageSystemServices
 * @package app\services\system
 * @method save(array $data) lưu dữ liệu
 * @method mixed saveAll(array $data) Lưu dữ liệu theo lô
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 *
 */class MessageSystemServices extends BaseServices
{

    /**
     * SystemNotificationServices constructor.
     * @param MessageSystemDao $dao
     */    public function __construct(MessageSystemDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách tin nhắn trang web
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getMessageSystemList($uid)
    {
        [$page, $limit] = $this->getPageValue();
        $where['is_del'] = 0;
        $where['uid'] = $uid;
        $list = $this->dao->getMessageList($where, '*', $page, $limit);
        $count = $this->dao->getCount($where);
        if (!$list) return ['list' => [], 'count' => 0];
        foreach ($list as &$item) {
            $item['add_time'] = time_tran($item['add_time']);
            if ($item['data'] != '' && $this->getMsg($item['mark']) != 000000) {
                $item['content'] = getLang($this->getMsg($item['mark']), json_decode($item['data'], true));
            }
        }
        return ['list' => $list, 'count' => $count];
    }

    /**
     * Chi tiết tin nhắn trang web
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getInfo($where)
    {
        $info = $this->dao->getOne($where);
        if (!$info || $info['is_del'] == 1) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        $info = $info->toArray();
        if ($info['look'] == 0) {
            $this->update($info['id'], ['look' => 1]);
        }
        if ($info['data'] != '' && $this->getMsg($info['mark']) != 000000) {
            $info['content'] = getLang($this->getMsg($info['mark']), json_decode($info['data'], true));
        }
        $info['add_time'] = time_tran($info['add_time']);
        return $info;
    }

    public function getMsg($mark)
    {
        switch ($mark) {
            case 'admin_pay_success_code':
                $code = 'Bạn có một đơn hàng đã thanh toán thành công cần được xử lý, mã số đơn hàng{:order_id}！';
                break;
            case 'bind_spread_uid':
                $code = 'Xin chúc mừng, một thành viên mạnh mẽ khác sẽ gắn bó vĩnh viễn với nhóm của bạn, Khách hàng{:nickname}Tham gia nhóm của bạn！';
                break;
            case 'order_pay_success':
                $code = 'Hàng bạn mua đã được thanh toán thành công, số tiền thanh toán {:pay_price} đ, số đơn hàng {:order_id}, cảm ơn bạn đã ghé thăm！';
                break;
            case 'order_take':
                $code = 'Kính gửi, đơn đặt hàng của bạn{:order_id},sản phẩm{:store_name}Biên nhận đã được xác nhận,cảm ơn bạn đã ghé thăm！';
                break;
            case 'price_revision':
                $code = 'đơn đặt hàng của bạn{:order_id}，Số tiền Thanh toán thực tế đã được sửa đổi thành{:pay_price}';
                break;
            case 'order_refund':
                $code = 'đơn đặt hàng của bạn {:order_id} đã đồng ý hoàn tiền, số tiền hoàn lại {:refund_price} đ。';
                break;
            case 'recharge_success':
                $code = 'Bạn đã nạp tiền thành công {:price} đ，Số dư hiện tại còn lại {:now_money} đ';
                break;
            case 'integral_accout':
                $code = 'Bạn thân mến, bạn đã lấy được điểm thành công.{:gain_integral}，Điểm hiện có{:integral}';
                break;
            case 'order_brokerage':
                $code = 'Thân mến, xin chúc mừng hoa hồng thành công của bạn {:brokerage_price} đ';
                break;
            case 'bargain_success':
                $code = 'Em yêu, anh mệt quá! Bạn bè đã giúp bạn thương lượng giá thấp nhất, tên sản phẩm{:title}，Giá dự trữ{:min_price}';
                break;
            case 'order_user_groups_success':
                $code = 'Kính gửi, đặt phòng theo nhóm của bạn đã hoàn tất, tên đặt phòng theo nhóm{:title}，lãnh đạo{:nickname}';
                break;
            case 'send_order_pink_fial':
                $code = 'Bạn thân mến, việc mua nhóm của bạn không thành công, tên hoạt động là{:title}';
                break;
            case 'can_pink_success':
            case 'open_pink_success':
                $code = 'Bạn thân mến, bạn đã tham gia đặt phòng theo nhóm thành công. Tên của sự kiện là{:title}';
                break;
            case 'user_extract':
                $code = 'Kính gửi, bạn đã rút tiền hoa hồng thành công {:extract_number} đ';
                break;
            case 'user_balance_change':
                $code = 'Bạn thân mến, việc rút tiền mà bạn thực hiện đã bị từ chối và hoa hồng sẽ được trả lại {:extract_number} đ';
                break;
            case 'recharge_order_refund_status':
                $code = 'Bạn thân mến, số tiền bạn nạp đã được hoàn trả, khoản hoàn trả này {:refund_price} đ';
                break;
            case 'send_order_refund_no_status':
                $code = 'Xin chào! đơn đặt hàng của bạn{:order_id}Hoàn tiền bị từ chối。';
                break;
            case 'send_order_apply_refund':
                $code = 'Bạn có một lệnh hoàn tiền đang chờ xử lý, số đơn hàng{:order_id}!';
                break;
            case 'order_deliver_success':
            case 'order_postage_success':
                $code = 'Kính gửi Khách hàng{:nickname}sản phẩm của bạn{:store_name}，Số đơn hàng{:order_id}Đã gửi hàng rồi, bạn kiểm tra nhé';
                break;
            case 'send_order_pink_clone':
                $code = 'Bạn thân mến, chuyến tham quan theo nhóm của bạn đã bị hủy, tên sự kiện là{:title}';
                break;
            case 'kefu_send_extract_application':
                $code = 'Bạn có yêu cầu rút tiền đang chờ xử lý, số tiền rút{:money}!';
                break;
            case 'send_admin_confirm_take_over':
                $code = 'Bạn có một đơn đặt hàng đã được xác nhận để giao hàng. Số thứ tự là{:order_id}!';
                break;
            case 'order_pay_false':
                $code = 'Bạn có một đơn hàng chưa thanh toán,Số thứ tự là:{:order_id}，Số lượng hàng có hạn, vui lòng thanh toán kịp thời。';
                break;
            default:
                $code = 000000;
                break;
        }
        return $code;
    }
}
