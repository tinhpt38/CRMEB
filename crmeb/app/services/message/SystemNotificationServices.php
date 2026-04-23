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

use app\dao\system\SystemNotificationDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

/**
 * Lớp quản lý tin nhắn
 * Class SystemNotificationServices
 * @package app\services\system
 * @method value($where, $value) Điều kiện để lấy giá trị của một trường
 */
class SystemNotificationServices extends BaseServices
{

    protected $messageData = [

        //Mã xác minh SMS
        'verify_code' => [
            ['label' => 'Mã xác minh', 'value' => 'code'],
            ['label' => 'Thời gian hợp lệ', 'value' => 'time'],
        ],

        //Đăng nhập người dùng
        'login_success' => [
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Lần đăng nhập cuối cùng', 'value' => 'last_time'],
            ['label' => 'Số dư người dùng', 'value' => 'now_money'],
            ['label' => 'Hoa hồng người dùng', 'value' => 'brokerage_price'],
            ['label' => 'Điểm người dùng', 'value' => 'integral'],
            ['label' => 'trải nghiệm người dùng', 'value' => 'exp'],
            ['label' => 'Thời gian đăng nhập', 'value' => 'time'],
        ],

        //Mối quan hệ ràng buộc người dùng
        'spread_success' => [
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'thời gian ràng buộc', 'value' => 'time'],
        ],

        //Số tiền sửa đổi đơn hàng chưa thanh toán
        'price_change_price' => [
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Số lượng đặt hàng ban đầu', 'value' => 'pay_price'],
            ['label' => 'Số tiền được sửa đổi', 'value' => 'change_price'],
        ],

        //Thanh toán đơn hàng thành công
        'order_pay_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
            ['label' => 'Tên sản phẩm', 'value' => 'storeName'],
            ['label' => 'thời gian đặt hàng', 'value' => 'time'],
        ],

        //Chuyển phát nhanh các đơn hàng
        'order_express_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
            ['label' => 'Tên sản phẩm', 'value' => 'storeName'],
            ['label' => 'công ty chuyển phát nhanh', 'value' => 'delivery_name'],
            ['label' => 'Số theo dõi nhanh', 'value' => 'delivery_id'],
            ['label' => 'thời gian vận chuyển', 'value' => 'time'],
        ],

        //Giao hàng theo đơn đặt hàng chuyển phát nhanh
        'order_send_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
            ['label' => 'Tên sản phẩm', 'value' => 'storeName'],
            ['label' => 'Tên người giao hàng', 'value' => 'delivery_name'],
            ['label' => 'Số điện thoại người giao hàng', 'value' => 'delivery_id'],
            ['label' => 'thời gian giao hàng', 'value' => 'time'],

        ],

        //Biên nhận đơn hàng
        'order_take' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
            ['label' => 'Tên sản phẩm', 'value' => 'storeTitle'],
            ['label' => 'Tên người giao hàng', 'value' => 'delivery_name'],
            ['label' => 'Số điện thoại người giao hàng', 'value' => 'delivery_id'],
            ['label' => 'Thời gian ký kết', 'value' => 'time'],
        ],

        //Hoàn tiền đơn hàng
        'order_initiated_refund' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
        ],

        //Đã hoàn tiền đơn hàng thành công
        'order_refund_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Đặt hàngorder_id', 'value' => 'order_id'],
            ['label' => 'Tên người dùng', 'value' => 'real_name'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'user_phone'],
            ['label' => 'Địa chỉ người dùng', 'value' => 'user_address'],
            ['label' => 'Tổng số mặt hàng', 'value' => 'total_num'],
            ['label' => 'Số tiền thanh toán', 'value' => 'pay_price'],
            ['label' => 'Trả bưu phí', 'value' => 'pay_postage'],
            ['label' => 'Số tiền trừ điểm', 'value' => 'deduction_price'],
            ['label' => 'Số tiền khấu trừ phiếu giảm giá', 'value' => 'coupon_price'],
            ['label' => 'Hình thức thanh toán', 'value' => 'pay_type'],
            ['label' => 'Loại lý do hoàn tiền', 'value' => 'refund_reason_wap'],
            ['label' => 'Lý do hoàn tiền', 'value' => 'refund_reason_wap_explain'],
            ['label' => 'Số tiền hoàn trả thực tế', 'value' => 'refund_price'],
        ],

        //Đơn hàng bị từ chối hoàn tiền
        'order_refund_fail' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Số tiền hoàn lại', 'value' => 'refund_price'],
            ['label' => 'Lý do từ chối hoàn tiền', 'value' => 'refuse_reason'],
            ['label' => 'thời gian từ chối', 'value' => 'time'],
        ],

        //Nạp tiền người dùng
        'recharge_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Số tiền nạp', 'value' => 'price'],
            ['label' => 'Số tiền quà tặng', 'value' => 'give_price'],
            ['label' => 'Số dư người dùng sau khi nạp tiền', 'value' => 'now_money'],
            ['label' => 'thời gian nạp tiền', 'value' => 'time'],
        ],

        //Người dùng nạp tiền và hoàn tiền
        'recharge_refund' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Số tiền hoàn lại', 'value' => 'price'],
            ['label' => 'Số dư của người dùng sau khi hoàn tiền', 'value' => 'now_money'],
            ['label' => 'Thời gian hoàn tiền', 'value' => 'time'],
        ],

        //Thẻ rút tiền của người dùng
        'extract_success' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Số tiền rút', 'value' => 'price'],
            ['label' => 'Thời gian rút tiền', 'value' => 'time'],
        ],

        //Rút tiền của người dùng không thành công
        'extract_fail' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Biệt hiệu của người dùng', 'value' => 'nickname'],
            ['label' => 'Lý do thất bại', 'value' => 'message'],
            ['label' => 'Số tiền rút', 'value' => 'price'],
            ['label' => 'thời gian thất bại', 'value' => 'time'],
        ],

        //Hoa hồng nhận được
        'brokerage_received' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Số tiền nhận được', 'value' => 'brokeragePrice'],
            ['label' => 'Tên sản phẩm', 'value' => 'goodsName'],
            ['label' => 'số lượng sản phẩm', 'value' => 'goodsPrice'],
            ['label' => 'Thời gian đến', 'value' => 'time'],
        ],

        //Điểm đến
        'point_received' => [
            ['label' => 'người dùnguid', 'value' => 'uid'],
            ['label' => 'Số điện thoại của người dùng', 'value' => 'phone'],
            ['label' => 'Số điểm', 'value' => 'give_integral'],
            ['label' => 'Tên sản phẩm', 'value' => 'storeTitle'],
            ['label' => 'Tổng số điểm', 'value' => 'integral'],
            ['label' => 'Thời gian đến', 'value' => 'time'],
        ],


    ];

    /**
     * SystemNotificationServices constructor.
     * @param SystemNotificationDao $dao
     */
    public function __construct(SystemNotificationDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * cấu hình đơn
     * @param int $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneNotce(array $where)
    {
        return $this->dao->getOne($where);
    }

    /**
     * Nhận danh sách ở chế độ nền
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNotList(array $where)
    {
        return $this->dao->getList($where);
    }

    /**
     * Thêm mẫu tin nhắn tùy chỉnh
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/2/19
     */
    public function getNotForm($id = 0)
    {
        if ($id) {
            $info = $this->dao->get($id);
            if ($info) $info = $info->toArray();
        } else {
            $info = [];
        }
        $data = [
            ['value' => 'login_success', 'label' => 'Kịch bản đăng nhập người dùng thành công'],
            ['value' => 'spread_success', 'label' => 'Kịch bản thành công của mối quan hệ thăng tiến ràng buộc'],
            ['value' => 'price_change_price', 'label' => 'Kịch bản sửa đổi giá đơn hàng chưa thanh toán'],
            ['value' => 'order_pay_success', 'label' => 'Kịch bản thành công thanh toán đơn hàng'],
            ['value' => 'order_express_success', 'label' => 'Kịch bản chuyển phát nhanh đơn hàng thành công'],
            ['value' => 'order_send_success', 'label' => 'Người giao hàng bắt đầu cảnh giao hàng'],
            ['value' => 'order_take', 'label' => 'Kịch bản nhận đơn hàng thành công'],
            ['value' => 'order_initiated_refund', 'label' => 'Tình huống hoàn tiền khi bắt đầu đặt hàng'],
            ['value' => 'order_refund_success', 'label' => 'Kịch bản thành công hoàn tiền đơn hàng'],
            ['value' => 'order_refund_fail', 'label' => 'Trường hợp không hoàn tiền đơn hàng'],
            ['value' => 'recharge_success', 'label' => 'Kịch bản nạp tiền thành công'],
            ['value' => 'recharge_refund', 'label' => 'Kịch bản nạp tiền và hoàn tiền'],
            ['value' => 'extract_success', 'label' => 'Kịch bản rút tiền thành công'],
            ['value' => 'extract_fail', 'label' => 'Kịch bản rút tiền thất bại'],
            ['value' => 'brokerage_received', 'label' => 'Kịch bản đến hoa hồng'],
            ['value' => 'point_received', 'label' => 'Kịch bản điểm đến'],
        ];
        $field = [];
        $field[] = Form::select('custom_trigger', 'vị trí kích hoạt', $info['custom_trigger'] ?? '')->options($data);
        $field[] = Form::input('name', 'tên', $info['name'] ?? '')->placeholder('Vui lòng điền tên tin nhắn, ví dụ: tin nhắn thanh toán thành công');
        $field[] = Form::input('mark', 'biểu tượng', $info['mark'] ?? '')->placeholder('Vui lòng điền ID tin nhắn, sử dụng tiếng Anh và gạch chân chẳng hạn：order_pay_success');
        return create_form('Thêm tin nhắn', $field, Url::buildUrl('/setting/notification/not_form_save/' . $id), 'POST');
    }

    /**
     * Lưu tin nhắn tùy chỉnh
     * @param $id
     * @param $data
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/2/20
     */
    public function notFormSave($id, $data)
    {
        if ($id) {
            $data['title'] = $data['name'];
            $res = $this->dao->update($id, $data);
        } else {
            $data['type'] = 3;
            $data['title'] = $data['name'];
            $data['is_system'] = $data['is_wechat'] = $data['is_routine'] = $data['is_sms'] = $data['is_ent_wechat'] = 2;
            $data['add_time'] = time();
            $res = $this->dao->save($data);
        }
        if ($res) return true;
        throw new AdminException('Lưu không thành công');
    }

    /**
     * Nhận một phần dữ liệu
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNotInfo(array $where)
    {
        $type = $where['type'];
        unset($where['type']);
        $info = $this->dao->getOne($where);
        if (!$info) return [];
        $info = $info->toArray();
        switch ($type) {
            case 'is_system':
                $info['content'] = $info['system_text'] ?? '';
                break;
            case 'is_sms':
                $info['content'] = $info['sms_text'];
                break;
            case 'is_wechat':
                $info['tempkey'] = $info['wechat_tempkey'] ?? '';
                $info['tempid'] = $info['wechat_tempid'] ?? '';
                $info['content'] = $info['wechat_content'] ?? '';
                $info['key_list'] = json_decode($info['wechat_data'], true) ?? [];
                break;
            case 'is_routine':
                $info['tempkey'] = $info['routine_tempkey'] ?? '';
                $info['tempid'] = $info['routine_tempid'] ?? '';
                $info['content'] = $info['routine_content'] ?? '';
                $info['key_list'] = json_decode($info['routine_data'], true) ?? [];
                break;
            case 'is_ent_wechat':
                $info['content'] = $info['ent_wechat_text'];
                break;
        }
        if ($info['type'] == 3) {
            $info['custom_variable'] = $this->messageData[$info['custom_trigger']];
            if (in_array($type, ['is_system', 'is_sms', 'is_ent_wechat'])) {
                foreach ($info['custom_variable'] as &$item) {
                    $item['value'] = '{' . $item['value'] . '}';
                }
            }
        }
        return $info;
    }

    /**
     * lưu dữ liệu
     * @param array $data
     * @return bool|\crmeb\basic\BaseModel|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveData(array $data)
    {
        $type = $data['type'];
        $id = $data['id'];
        $info = $this->dao->get($id);
        if (!$info) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $res = null;
        switch ($type) {
            case 'is_system':
                $update = [];
                $update['name'] = $data['name'];
                $update['title'] = $data['title'];
                $update['is_system'] = $data['is_system'];
                $update['is_app'] = $data['is_app'];
                $update['system_title'] = $data['system_title'];
                $update['system_text'] = $data['system_text'];
                $res = $this->dao->update((int)$id, $update);
                break;
            case 'is_sms':
                $update = [];
                $update['name'] = $data['name'];
                $update['title'] = $data['title'];
                $update['is_sms'] = $data['is_sms'];
                $update['sms_id'] = $data['sms_id'];
                $update['sms_text'] = $data['sms_text'];
                $res = $this->dao->update((int)$id, $update);
                break;
            case 'is_wechat':
                $update['is_wechat'] = $data['is_wechat'];
                $update['wechat_tempid'] = $data['tempid'];
                $update['wechat_tempkey'] = $data['tempkey'];
                $update['wechat_content'] = $data['content'];
                $update['wechat_link'] = $data['wechat_link'];
                $update['wechat_to_routine'] = $data['wechat_to_routine'];
                $update['wechat_data'] = json_encode($data['key_list']);
                $res = $this->dao->update((int)$id, $update);
                break;
            case 'is_routine':
                $update['is_routine'] = $data['is_routine'];
                $update['routine_tempid'] = $data['tempid'];
                $update['routine_tempkey'] = $data['tempkey'];
                $update['routine_content'] = $data['content'];
                $update['routine_data'] = json_encode($data['key_list']);
                $update['routine_link'] = $data['routine_link'];
                $res = $this->dao->update((int)$id, $update);
                break;
            case 'is_ent_wechat':
                $update['name'] = $data['name'];
                $update['title'] = $data['title'];
                $update['is_ent_wechat'] = $data['is_ent_wechat'];
                $update['ent_wechat_text'] = $data['ent_wechat_text'];
                $update['url'] = $data['url'];
                $res = $this->dao->update((int)$id, $update);
                break;
        }
        return $res;
    }

    /**
     * lấytempid
     * @param $type
     * @return array
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/16
     */
    public function getTempId($type)
    {
        return $this->dao->getTempId($type);
    }

    /**
     * lấytempkey
     * @param $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/16
     */
    public function getTempKey($type)
    {
        return $this->dao->getTempKey($type);
    }
}
