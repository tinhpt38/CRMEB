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
namespace app\services\system;

use app\dao\system\SystemEventDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use think\facade\Db;

class SystemEventServices extends BaseServices
{
    public function __construct(SystemEventDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách cảnh
     * @return \string[][]
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */    public function getMarkList()
    {
//        $data = [
//            [
//                'label' => 'Đăng ký Khách hàng',
//                'value' => 'user_register',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại di động của Khách hàng',
//                    'add_time' => 'Thời gian đăng ký Khách hàng',
//                    'user_type' => 'Nguồn Khách hàng',
//                ]
//            ],
//            [
//                'label' => 'Đăng nhập Khách hàng',
//                'value' => 'user_login',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại di động của Khách hàng',
//                    'add_time' => 'Thời gian đăng ký Khách hàng',
//                    'login_time' => 'Thời gian đăng nhập của Khách hàng',
//                    'user_type' => 'Nguồn Khách hàng',
//                ]
//            ],
//            [
//                'label' => 'Đăng xuất Khách hàng',
//                'value' => 'user_cancel',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại di động của Khách hàng',
//                    'add_time' => 'Thời gian đăng ký Khách hàng',
//                    'cancel_time' => 'Thời gian đăng xuất của Khách hàng',
//                    'user_type' => 'Nguồn Khách hàng',
//                ]
//            ],
//            [
//                'label' => 'Thông tin Khách hàng sửa đổi',
//                'value' => 'user_change_info',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại di động của Khách hàng',
//                    'avatar' => 'Hình đại diện của Khách hàng',
//                    'add_time' => 'Thời gian đăng ký Khách hàng',
//                    'user_type' => 'Nguồn Khách hàng',
//                ]
//            ],
//            [
//                'label' => 'Mối quan hệ thăng tiến ràng buộc',
//                'value' => 'user_spread',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'spread_uid' => 'Người dùng cấp caouid',
//                    'spread_time' => 'Thời gian ràng buộc Khách hàng',
//                    'user_type' => 'Nguồn Khách hàng',
//                ]
//            ],
//            [
//                'label' => 'Đăng nhập Khách hàng',
//                'value' => 'user_sign',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'sign_point' => 'Điểm đăng nhập',
//                    'sign_exp' => 'Trải nghiệm đăng nhập',
//                    'sign_time' => 'Giờ nhận phòng',
//                ]
//            ],
//            [
//                'label' => 'Nạp tiền vào ví',
//                'value' => 'user_recharge',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Đơn hàngid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'price' => 'Số tiền nạp',
//                    'give_price' => 'Số tiền quà tặng',
//                    'now_money' => 'Số dư hiện tại',
//                    'recharge_time' => 'thời gian nạp tiền',
//                ]
//            ],
//            [
//                'label' => 'Người dùng rút tiền',
//                'value' => 'user_extract',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'extract_type' => 'Loại rút tiền',
//                    'extract_price' => 'Số tiền rút',
//                    'extract_fee' => 'Phí rút tiền',
//                    'extract_time' => 'Thời gian rút tiền',
//                ]
//            ],
//            [
//                'label' => 'Quyền truy cập sản phẩm của Khách hàng',
//                'value' => 'user_product_visit',
//                'data' => [
//                    'product_id' => 'ID sản phẩm',
//                    'uid' => 'Khách hànguid',
//                    'visit_time' => 'thời gian truy cập',
//                ]
//            ],
//            [
//                'label' => 'Sản phẩm yêu thích của Khách hàng',
//                'value' => 'user_product_collect',
//                'data' => [
//                    'product_id' => 'ID sản phẩm',
//                    'uid' => 'Khách hànguid',
//                    'collect_time' => 'thời gian truy cập',
//                ]
//            ],
//            [
//                'label' => 'Người dùng thêm vào giỏ hàng',
//                'value' => 'user_add_cart',
//                'data' => [
//                    'product_id' => 'ID sản phẩm',
//                    'uid' => 'Khách hànguid',
//                    'cart_num' => 'số lượng sản phẩm',
//                    'add_time' => 'Thêm thời gian',
//                ]
//            ],
//            [
//                'label' => 'Xổ số Khách hàng',
//                'value' => 'user_lottery',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'lottery_id' => 'xổ sốid',
//                    'prize_id' => 'phần thưởngid',
//                    'record_id' => 'Kỷ lục chiến thắngid',
//                    'lottery_time' => 'Thời gian vẽ',
//                ]
//            ],
//            [
//                'label' => 'Tạo đơn hàng',
//                'value' => 'order_create',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Đơn hàngid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'total_num' => 'Tổng số mặt hàng',
//                    'pay_price' => 'Số tiền thanh toán',
//                    'pay_postage' => 'Trả bưu phí',
//                    'deduction_price' => 'Số tiền trừ điểm',
//                    'coupon_price' => 'Số tiền khấu trừ phiếu giảm giá',
//                    'store_name' => 'Tên sản phẩm',
//                    'add_time' => 'Thời gian tạo đơn hàng',
//                ]
//            ],
//            [
//                'label' => 'Hủy đơn hàng',
//                'value' => 'order_cancel',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Đơn hàngid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'total_num' => 'Tổng số mặt hàng',
//                    'pay_price' => 'Số tiền thanh toán',
//                    'deduction_price' => 'Số tiền trừ điểm',
//                    'coupon_price' => 'Số tiền khấu trừ phiếu giảm giá',
//                    'cancel_time' => 'Thời gian hủy đơn hàng',
//                ]
//            ],
//            [
//                'label' => 'Thanh toán đơn hàng',
//                'value' => 'order_pay',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Đơn hàngid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'total_num' => 'Tổng số mặt hàng',
//                    'pay_price' => 'Số tiền thanh toán',
//                    'pay_postage' => 'Trả bưu phí',
//                    'deduction_price' => 'Số tiền trừ điểm',
//                    'coupon_price' => 'Số tiền khấu trừ phiếu giảm giá',
//                    'store_name' => 'Tên sản phẩm',
//                    'add_time' => 'Thời gian tạo đơn hàng',
//                ]
//            ],
//            [
//                'label' => 'Biên nhận/xóa đơn hàng',
//                'value' => 'order_take',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Đơn hàngid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'total_num' => 'Tổng số mặt hàng',
//                    'pay_price' => 'Số tiền thanh toán',
//                    'pay_postage' => 'Trả bưu phí',
//                    'deduction_price' => 'Số tiền trừ điểm',
//                    'coupon_price' => 'Số tiền khấu trừ phiếu giảm giá',
//                    'store_name' => 'Tên sản phẩm',
//                    'add_time' => 'Thời gian tạo đơn hàng',
//                ]
//            ],
//            [
//                'label' => 'Hoàn tiền đơn hàng',
//                'value' => 'order_initiated_refund',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'refund_order_id' => 'Lệnh hoàn tiềnorder_id',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'refund_num' => 'Số tiền hoàn lại',
//                    'refund_price' => 'Số tiền hoàn lại',
//                    'refund_time' => 'Thời gian bắt đầu hoàn tiền',
//                ]
//            ],
//            [
//                'label' => 'Hoàn tiền hủy của Khách hàng',
//                'value' => 'order_refund_cancel',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Lệnh hoàn tiềnid',
//                    'store_order_id' => 'Tương ứng với các đơn đặt hàng thông thườngid',
//                    'order_id' => 'Lệnh hoàn tiềnorder_id',
//                    'refund_num' => 'Số tiền hoàn lại',
//                    'refund_price' => 'Số tiền hoàn lại',
//                    'cancel_time' => 'thời gian từ chối',
//                ]
//            ],
//            [
//                'label' => 'Hoa hồng nhận được',
//                'value' => 'order_brokerage',
//                'data' => [
//                    'uid' => 'người quảng báuid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'phone' => 'Số điện thoại của nhà quảng cáo',
//                    'brokeragePrice' => 'số tiền hoa hồng',
//                    'goodsName' => 'Tên sản phẩm',
//                    'goodsPrice' => 'Số tiền đặt hàng',
//                    'add_time' => 'Thời gian đến',
//                ]
//            ],
//            [
//                'label' => 'Điểm đến',
//                'value' => 'order_point',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'storeTitle' => 'Tên sản phẩm',
//                    'give_integral' => 'Tặng điểm',
//                    'integral' => 'tổng điểm',
//                    'add_time' => 'thời gian tặng quà',
//                ]
//            ],
//            [
//                'label' => 'Yêu cầu lập hóa đơn',
//                'value' => 'order_invoice',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'invoice_id' => 'hóa đơnid',
//                    'add_time' => 'Thời điểm lập hóa đơn',
//                ]
//            ],
//            [
//                'label' => 'Đánh giá đơn hàng',
//                'value' => 'order_comment',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'oid' => 'Đơn hàngid',
//                    'unique' => 'Giá trị duy nhất của thông số kỹ thuật sản phẩm',
//                    'suk' => 'Thuộc tính sản phẩm',
//                    'product_id' => 'ID sản phẩm',
//                    'add_time' => 'Thời gian đánh giá',
//                ]
//            ],
//            [
//                'label' => 'Đăng nhập quản trị viên',
//                'value' => 'admin_login',
//                'data' => [
//                    'id' => 'quản trị viênid',
//                    'account' => 'Tài khoản quản trị viên',
//                    'head_pic' => 'Hình đại diện của quản trị viên',
//                    'real_name' => 'Tên quản trị viên',
//                    'login_time' => 'Thời gian đăng nhập',
//                ]
//            ],
//
//            [
//                'label' => 'Rút tiền phụ trợ thành công',
//                'value' => 'admin_extract_success',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'price' => 'Số tiền rút',
//                    'pay_type' => 'Loại rút tiền',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'success_time' => 'thời gian thành công'
//                ]
//            ],
//            [
//                'label' => 'Rút tiền phụ trợ không thành công',
//                'value' => 'admin_extract_fail',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'price' => 'Số tiền rút',
//                    'pay_type' => 'Loại rút tiền',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'fail_time' => 'thời gian thất bại'
//                ]
//            ],
//            [
//                'label' => 'Nạp tiền và hoàn tiền phụ trợ',
//                'value' => 'admin_recharge_refund',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'refund_price' => 'Số tiền hoàn lại',
//                    'now_money' => 'số dư còn lại',
//                    'nickname' => 'Biệt hiệu của Khách hàng',
//                    'phone' => 'Số điện thoại của Khách hàng',
//                    'refund_time' => 'Thời gian hoàn tiền',
//                ]
//            ],
//            [
//                'label' => 'Sửa đổi đơn đặt hàng và thay đổi giá trong nền',
//                'value' => 'admin_order_change',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'pay_price' => 'Số lượng đặt hàng đã sửa đổi',
//                    'gain_integral' => 'Điểm thưởng cho các đơn hàng được sửa đổi',
//                    'change_time' => 'thời gian sửa đổi',
//                ]
//            ],
//            [
//                'label' => 'Giao hàng phụ trợ',
//                'value' => 'admin_order_express',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'delivery_name' => 'Tên người giao hàng/tên người giao hàng',
//                    'delivery_id' => 'Số theo dõi chuyển phát nhanh/số điện thoại của người giao hàng',
//                    'express_time' => 'Sự kiện vận chuyển',
//                ]
//            ],
//            [
//                'label' => 'Hoàn tiền đơn hàng phụ trợ',
//                'value' => 'admin_order_refund_success',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'order_id' => 'Đơn hàngorder_id',
//                    'real_name' => 'Tên Khách hàng',
//                    'user_phone' => 'Số điện thoại của Khách hàng',
//                    'user_address' => 'Địa chỉ Khách hàng',
//                    'total_num' => 'Tổng số mặt hàng',
//                    'pay_price' => 'Số tiền thanh toán',
//                    'refund_reason_wap' => 'Loại lý do hoàn tiền',
//                    'refund_reason_wap_explain' => 'Lý do hoàn tiền',
//                    'refund_price' => 'Số tiền hoàn trả thực tế',
//                    'refund_time' => 'Thời gian hoàn tiền',
//                ]
//            ],
//            [
//                'label' => 'Lệnh hậu trường từ chối hoàn tiền',
//                'value' => 'admin_order_refund_fail',
//                'data' => [
//                    'uid' => 'Khách hànguid',
//                    'id' => 'Lệnh hoàn tiềnid',
//                    'store_order_id' => 'Tương ứng với các đơn đặt hàng thông thườngid',
//                    'order_id' => 'Lệnh hoàn tiềnorder_id',
//                    'refund_num' => 'Số tiền hoàn lại',
//                    'refund_price' => 'Số tiền hoàn lại',
//                    'refuse_reason' => 'Lý do từ chối hoàn tiền',
//                    'refuse_time' => 'thời gian từ chối',
//                ]
//            ],
//        ];
//        foreach ($data as &$item){
//            $item['data'] = json_encode($item['data']);
//        }
//        app()->make(SystemEventDataServices::class)->saveAll($data);

        $data = app()->make(SystemEventDataServices::class)->selectList([])->toArray();

        foreach ($data as &$item) {
            $str = '$data = ' . var_export(json_decode($item['data'], true), true);
            $item['data'] = str_replace(['array (', ')'], ['[', ']'], $str);
        }
        return $data;
    }

    /**
     * Nhận danh sách sự kiện
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */    public function getEventList()
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList(['is_del' => 0], 'id,name,mark,content,add_time,is_open', $page, $limit, 'id desc')->toArray();
        $count = $this->dao->getCount(['is_del' => 0]);
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            foreach ($this->getMarkList() as $markItem) {
                if ($markItem['value'] == $item['mark']) {
                    $item['mark_name'] = $markItem['label'];
                }
            }
        }
        return compact('list', 'count');
    }

    /**
     * Nhận chi tiết sự kiện
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */    public function getEventInfo($id)
    {
        $info = $this->dao->get($id);
        if (!$info) throw new AdminException('sự kiện không tồn tại');
        $info = $info->toArray();
        $info['add_time'] = date('Y-m-d H:i:s', $info['add_time']);
        $info['customCode'] = "<?php\n\n" . json_decode($info['customCode'], true);
        return $info;
    }

    public function saveEvent($data)
    {
        $data['add_time'] = time();
        $data['customCode'] = json_encode(preg_replace('/<\?php\s*\n/', '', $data['customCode']));
        if (!$data['id']) {
            unset($data['id']);
            $res = $this->dao->save($data);
        } else {
            $res = $this->dao->update(['id' => $data['id']], $data);
        }
        if (!$res) throw new AdminException('Lưu không thành công');
        return true;
    }

    /**
     * xóa sự kiện
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */    public function eventDel($id)
    {
        $info = $this->dao->get($id);
        if (!$info) throw new AdminException('sự kiện không tồn tại');
        $info->is_del = 1;
        $info->save();
        return true;
    }

    /**
     * Đặt trạng thái sự kiện
     * @param $id
     * @param $is_open
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */    public function setEventStatus($id, $is_open)
    {
        $res = $this->dao->update(['id' => $id], ['is_open' => $is_open]);
        if (!$res) throw new AdminException('Thiết lập thành công');
        return true;
    }
}