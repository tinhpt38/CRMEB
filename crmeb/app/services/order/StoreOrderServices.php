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

namespace app\services\order;

use app\dao\order\StoreOrderDao;
use app\jobs\AutoCommentJob;
use app\services\activity\advance\StoreAdvanceServices;
use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\combination\StorePinkServices;
use app\services\activity\coupon\StoreCouponUserServices;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\BaseServices;
use app\services\other\PosterServices;
use app\services\other\QrcodeServices;
use app\services\other\UploadService;
use app\services\pay\OrderPayServices;
use app\services\pay\PayServices;
use app\services\product\product\StoreProductLogServices;
use app\services\serve\ServeServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\store\SystemStoreServices;
use app\services\system\SystemTicketServices;
use app\services\user\UserInvoiceServices;
use app\services\user\UserRechargeServices;
use app\services\user\UserServices;
use app\services\product\product\StoreProductReplyServices;
use app\services\user\UserAddressServices;
use app\services\user\UserBillServices;
use app\services\user\UserLevelServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\exceptions\PayException;
use crmeb\services\app\MiniProgramService;
use crmeb\services\CacheService;
use crmeb\services\easywechat\orderShipping\MiniOrderService;
use crmeb\services\FormBuilder as Form;
use crmeb\services\printer\Printer;
use crmeb\services\SystemConfigService;
use crmeb\utils\Arr;
use crmeb\utils\VnBankPayHelper;
use Guzzle\Http\EntityBody;
use think\facade\Log;

/**
 * Class StoreOrderServices
 * @package app\services\order
 * @method getOrderIdsCount(array $ids) Lấy số lượng đơn hàng chưa bị xóa theo id đơn hàng
 * @method StoreOrderDao getUserOrderDetail(string $key, int $uid, array $with) Nhận chi tiết đơn hàng
 * @method chartTimePrice($start, $stop) Nhận số tiền thanh toán từ thời điểm hiện tại đến thời điểm quy định
 * @method chartTimeNumber($start, $stop) Lấy số lượng lệnh thanh toán từ thời điểm hiện tại đến thời điểm quy định
 * @method together(array $where, string $field, string $together = 'sum') Tìm kiếm tổng hợp
 * @method getBuyCount($uid, $type, $typeId) Lấy số lượng vật phẩm Khách hàng đã mua cho sự kiện này
 * @method getDistinctCount(array $where, $field, ?bool $search = true)
 * @method getTrendData($time, $type, $timeType, $str) Xu hướng Khách hàng
 * @method getRegion($time, $channelType) thống kê địa lý
 * @method getProductTrend($time, $timeType, $field, $str) Xu hướng sản phẩm
 * @method getList(array $where, array $field, int $page = 0, int $limit = 0, array $with = [])
 */class StoreOrderServices extends BaseServices
{

    /**
     * Loại vận chuyển
     * @var string[]
     */    public $deliveryType = [
        'send' => 'giao hàng của người bán',
        'express' => 'chuyển phát nhanh',
        'fictitious' => 'giao hàng ảo',
        'delivery_part_split' => 'Chia lô hàng từng phần',
        'delivery_split' => 'Đã hoàn thành việc chia lô hàng'
    ];

    /**
     * StoreOrderProductServices constructor.
     * @param StoreOrderDao $dao
     */    public function __construct(StoreOrderDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param array $field
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOrderList(array $where, array $field = ['*'], array $with = [])
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getOrderList($where, $field, $page, $limit, $with);
        $count = $this->dao->count($where, false);
        $data = $this->tidyOrderList($data);
        foreach ($data as &$item) {
            $refund_num = array_sum(array_column($item['refund'], 'refund_num'));
            $cart_num = 0;
            $vipTruePrice = 0;
            foreach ($item['_info'] as $items) {
                $cart_num += $items['cart_info']['cart_num'];
                $vipTruePrice = bcadd((string)$vipTruePrice, bcmul((string)$items['cart_info']['vip_truePrice'], (string)$items['cart_info']['cart_num'], 2), 2);
            }
            $item['total_price'] = bcadd($item['total_price'], $vipTruePrice, 2);
            $item['is_all_refund'] = $refund_num == $cart_num;
            $item['pay_price'] = (float)$item['pay_price'];
        }
        return compact('data', 'count');
    }

    /**
     * Danh sách đơn hàng phía trước
     * @param array $where
     * @param array|string[] $field
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOrderApiList(array $where, array $field = ['*'], array $with = [])
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getOrderList($where, $field, $page, $limit, $with);
        foreach ($data as &$item) {
            $item = $this->tidyOrder($item, true);
            foreach ($item['cartInfo'] ?: [] as $key => $product) {
                if ($item['_status']['_type'] == 3) {
                    $item['cartInfo'][$key]['add_time'] = isset($product['add_time']) ? date('Y-m-d H:i', (int)$product['add_time']) : 'lỗi thời gian';
                }
                $item['cartInfo'][$key]['productInfo']['price'] = $product['truePrice'] ?? 0;
            }
            if (count($item['refund'])) {
                $refund_num = array_sum(array_column($item['refund'], 'refund_num'));
                $cart_num = array_sum(array_column($item['cartInfo'], 'cart_num'));
                $item['is_all_refund'] = $refund_num == $cart_num ? true : false;
            } else {
                $item['is_all_refund'] = false;
            }
        }
        return $data;
    }

    /**
     * Nhận số lượng đặt hàng
     * @param int $uid
     * @return array
     * @throws \ReflectionException
     */    public function getOrderData(int $uid = 0)
    {
        $data['order_count'] = (string)$this->dao->count(['uid' => $uid, 'refund_status' => [0, 3], 'pid' => 0, 'is_del' => 0, 'is_system_del' => 0]);
        $data['sum_price'] = (string)$this->dao->sum([
            ['uid', '=', $uid],
            ['paid', '=', 1],
            ['refund_status', '=', 0],
            ['pid', '>=', 0]
        ], 'pay_price', false);
        $countWhere = ['is_del' => 0, 'is_system_del' => 0];
        if ($uid) {
            $countWhere['uid'] = $uid;
        }
        $data['unpaid_count'] = (string)$this->dao->count(['status' => 0] + $countWhere);
        $data['unshipped_count'] = (string)$this->dao->count(['status' => 1] + $countWhere + ['pid' => 0]);
        $data['received_count'] = (string)$this->dao->count(['status' => 2] + $countWhere + ['pid' => 0]);
        $data['evaluated_count'] = (string)$this->dao->count(['status' => 3] + $countWhere + ['pid' => 0]);
        $data['complete_count'] = (string)$this->dao->count(['status' => 4] + $countWhere + ['pid' => 0]);

        /** @var StoreOrderRefundServices $storeOrderRefundServices */        $storeOrderRefundServices = app()->make(StoreOrderRefundServices::class);
        $refund_where = ['is_cancel' => 0];
        if ($uid) $refund_where['uid'] = $uid;
        $data['refunding_count'] = (string)$storeOrderRefundServices->count($refund_where + ['refund_type' => [1, 2, 4, 5]]);
        $data['no_refund_count'] = (string)$storeOrderRefundServices->count($refund_where + ['refund_type' => 3]);
        $data['refunded_count'] = (string)$storeOrderRefundServices->count($refund_where + ['refund_type' => 6]);
        $data['refund_count'] = bcadd(bcadd($data['refunding_count'], $data['refunded_count'], 0), $data['no_refund_count'], 0);
        $data['yue_pay_status'] = (int)sys_config('balance_func_status') && (int)sys_config('yue_pay_status') == 1 ? (int)1 : (int)2;//Thanh toán số dư 1 tặng 2
        $data['pc_order_count'] = $data['order_count'] + $data['refunding_count'] + $data['refunded_count'];
        $data['pay_weixin_open'] = sys_config('pay_weixin_open', '0') != '0';//WeChat Trả 1 Bật 0 Tắt
        $data['ali_pay_status'] = sys_config('ali_pay_status', '0') != '0';//Gói thanh toán thanh toán 1 tặng 0 giảm
        $data['friend_pay_status'] = (int)sys_config('friend_pay_status') ?? 0;//Bạn bè thanh toán thay mặt 1 Trên 0 Tắt
        return $data;
    }

    /**
     * Định dạng thời điểm giao/nhận cho `_status._msg` (unix timestamp).
     */    protected function formatOrderStatusTime(int $timestamp): string
    {
        if ($timestamp <= 0) {
            return '';
        }
        return date('d/m/Y H:i', $timestamp);
    }

    /**
     * Định dạng dữ liệu chi tiết đơn hàng
     * @param $order
     * @param bool $detail Bạn có cần đặt hàng chi tiết sản phẩm?
     * @param bool $isPic Bạn có cần một hình ảnh trạng thái đơn hàng?
     * @return mixed
     */    public function tidyOrder($order, bool $detail = false, $isPic = false)
    {
        if ($detail == true && isset($order['id'])) {
            /** @var StoreOrderCartInfoServices $cartServices */            $cartServices = app()->make(StoreOrderCartInfoServices::class);
            $cartInfos = $cartServices->getCartColunm(['oid' => $order['id']], 'cart_num,surplus_num,cart_info,refund_num', 'unique');
            $info = [];
            /** @var StoreProductReplyServices $replyServices */            $replyServices = app()->make(StoreProductReplyServices::class);
            foreach ($cartInfos as $k => $cartInfo) {
                $cart = json_decode($cartInfo['cart_info'], true);
                $cart['cart_num'] = $cartInfo['cart_num'];
                $cart['surplus_num'] = $cartInfo['surplus_num'];
                $cart['refund_num'] = $cartInfo['refund_num'];
                $cart['surplus_refund_num'] = $cartInfo['surplus_num'] - $cartInfo['refund_num'];
                $cart['unique'] = $k;
                //Đã thêm liệu có đánh giá trường hay không
                $cart['is_reply'] = $replyServices->count(['unique' => $k]);
                if (isset($cart['productInfo']['attrInfo'])) {
                    $cart['productInfo']['attrInfo'] = get_thumb_water($cart['productInfo']['attrInfo']);
                }
                $cart['productInfo'] = get_thumb_water($cart['productInfo']);
                //Mua nhiều mặt hàng của một sản phẩm và tính tổng chiết khấu
                $cart['vip_sum_truePrice'] = bcmul($cart['vip_truePrice'], $cart['cart_num'] ? $cart['cart_num'] : 1, 2);
                $cart['is_valid'] = 1;
                array_push($info, $cart);
                unset($cart);
            }
            $order['cartInfo'] = $info;
        }
        /** @var StoreOrderStatusServices $statusServices */        $statusServices = app()->make(StoreOrderStatusServices::class);
        $status = [];
        if ($order['is_cancel']) {
            $status['_type'] = 4;
            $status['_title'] = 'Đã hủy';
            $status['_msg'] = 'Bạn đã hủy đơn đặt hàng của mình,Cảm ơn bạn đã sử dụng';
            $status['_class'] = 'nobuy';
        } else {
            if (!$order['paid']) {
                $payType = (string)($order['pay_type'] ?? '');
                if ($payType === PayServices::VN_BANK) {
                    $status['_type'] = 9;
                    $status['_title'] = 'Chờ xác nhận chuyển khoản';
                    $status['_msg'] = 'Vui lòng chuyển khoản theo hướng dẫn. Shop sẽ xác nhận sau khi nhận được tiền.';
                    $status['_class'] = 'nobuy';
                } elseif ($payType === PayServices::VN_COD) {
                    $status['_type'] = 9;
                    $status['_title'] = 'Đơn hàng thành công (COD)';
                    $status['_msg'] = 'Đơn hàng đã được ghi nhận. Bạn thanh toán khi nhận hàng.';
                    $status['_class'] = 'nobuy';
                } elseif ($payType === PayServices::OFFLINE_PAY && (int)$order['status'] < 2) {
                    $status['_type'] = 9;
                    $status['_title'] = 'Chờ xác nhận thanh toán';
                    $status['_msg'] = 'Đang chờ cửa hàng xác nhận thanh toán ngoại tuyến.';
                    $status['_class'] = 'nobuy';
                } else {
                    $status['_type'] = 0;
                    $status['_title'] = 'Chờ thanh toán';
                    //Hệ thống Cài đặt trước khoảng thời gian hủy đơn hàng
                    $keyValue = ['order_cancel_time', 'order_activity_time', 'order_bargain_time', 'order_seckill_time', 'order_pink_time'];
                    //Nhận cấu hình
                    $systemValue = SystemConfigService::more($keyValue);
                    //Định dạng dữ liệu
                    $systemValue = Arr::setValeTime($keyValue, is_array($systemValue) ? $systemValue : []);
                    if ($order['pink_id'] || $order['combination_id']) {
                        $order_pink_time = $systemValue['order_pink_time'] ?: $systemValue['order_activity_time'];
                        $time = $order['add_time'] + $order_pink_time * 3600;
                        $status['_msg'] = 'Vui lòng hoàn tất thanh toán trước ' . date('d/m/Y H:i', (int)$time) . '.';
                    } else if ($order['seckill_id']) {
                        $order_seckill_time = $systemValue['order_seckill_time'] ?: $systemValue['order_activity_time'];
                        $time = $order['add_time'] + $order_seckill_time * 3600;
                        $status['_msg'] = 'Vui lòng hoàn tất thanh toán trước ' . date('d/m/Y H:i', (int)$time) . '.';
                    } else if ($order['bargain_id']) {
                        $order_bargain_time = $systemValue['order_bargain_time'] ?: $systemValue['order_activity_time'];
                        $time = $order['add_time'] + $order_bargain_time * 3600;
                        $status['_msg'] = 'Vui lòng hoàn tất thanh toán trước ' . date('d/m/Y H:i', (int)$time) . '.';
                    } else {
                        $time = $order['add_time'] + $systemValue['order_cancel_time'] * 3600;
                        $status['_msg'] = 'Vui lòng hoàn tất thanh toán trước ' . date('d/m/Y H:i', (int)$time) . '.';
                    }
                    $status['_class'] = 'nobuy';
                }
            } else if ($order['status'] == 4) {
                if ($order['delivery_type'] == 'send') {//TODO giao hàng
                    $status['_type'] = 1;
                    $status['_title'] = 'Đang giao hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Shop đã bàn giao Đơn vị giao hàng.';
                    $status['_class'] = 'state-ysh';
                } elseif ($order['delivery_type'] == 'express') {//TODO  vận chuyển
                    $status['_type'] = 1;
                    $status['_title'] = 'Đang giao hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_goods'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Đơn vị vận chuyển đã lấy hàng.';
                    $status['_class'] = 'state-ysh';
                } elseif ($order['delivery_type'] == 'split') {//Chia lô hàng
                    $status['_type'] = 1;
                    $status['_title'] = 'Đang giao hàng (chia lô)';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_part_split'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Một phần kiện hàng đã được gửi.';
                    $status['_class'] = 'state-ysh';
                } else {
                    $status['_type'] = 1;
                    $status['_title'] = 'Đang xử lý';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_fictitious'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Đơn hàng ảo đã được kích hoạt.';
                    $status['_class'] = 'state-ysh';
                }
            } else if ($order['refund_status'] == 1) {
                if (in_array($order['refund_type'], [0, 1, 2])) {
                    $status['_type'] = -1;
                    $status['_title'] = 'Đang hoàn tiền';
                    $status['_msg'] = 'Shop đang xử lý yêu cầu hoàn tiền / đổi trả của bạn.';
                    $status['_class'] = 'state-sqtk';
                } elseif ($order['refund_type'] == 4) {
                    $status['_type'] = -1;
                    $status['_title'] = 'Đang hoàn tiền';
                    $status['_msg'] = 'Shop đã đồng ý. Vui lòng gửi trả hàng và điền mã vận đơn.';
                    $status['_class'] = 'state-sqtk';
                    $status['refund_name'] = sys_config('refund_name', '');
                    $status['refund_phone'] = sys_config('refund_phone', '');
                    $status['refund_address'] = sys_config('refund_address', '');
                } elseif ($order['refund_type'] == 5) {
                    $status['_type'] = -1;
                    $status['_title'] = 'Đang hoàn tiền';
                    $status['_msg'] = 'Đã gửi hàng trả. Shop đang chờ nhận.';
                    $status['_class'] = 'state-sqtk';
                    $status['refund_name'] = sys_config('refund_name', '');
                    $status['refund_phone'] = sys_config('refund_phone', '');
                    $status['refund_address'] = sys_config('refund_address', '');
                }
            } else if ($order['refund_status'] == 2 || $order['refund_type'] == 6) {
                $status['_type'] = -2;
                $status['_title'] = 'Đã hoàn tiền';
                $status['_msg'] = 'Đơn hàng đã được hoàn tiền. Cảm ơn bạn đã mua sắm!';
                $status['_class'] = 'state-sqtk';
            } else if ($order['refund_status'] == 3) {
                $status['_type'] = -1;
                $status['_title'] = 'Đang hoàn tiền (một phần)';
                $status['_msg'] = 'Đơn chia lô: đã hoàn một phần tiền.';
                $status['_class'] = 'state-sqtk';
            } else if ($order['refund_status'] == 4) {
                $status['_type'] = -1;
                $status['_title'] = 'Đang hoàn tiền';
                $status['_msg'] = 'Đơn chia lô: các kiện phụ đang trong quy trình hoàn.';
                $status['_class'] = 'state-sqtk';
            } else if (!$order['status']) {
                if ($order['pink_id']) {
                    /** @var StorePinkServices $pinkServices */                    $pinkServices = app()->make(StorePinkServices::class);
                    if ($pinkServices->getCount(['id' => $order['pink_id'], 'status' => 1])) {
                        $status['_type'] = 1;
                        $status['_title'] = 'Tham gia nhóm';
                        $status['_msg'] = 'Đang chờ người khác tham gia nhóm';
                        $status['_class'] = 'state-nfh';
                    } else {
                        $status['_type'] = 1;
                        $status['_title'] = 'Đang xử lý';
                        $status['_msg'] = 'Đơn mua chung đã đủ người. Shop đang chuẩn bị hàng.';
                        $status['_class'] = 'state-nfh';
                    }
                } else {
                    if ($order['shipping_type'] === 1) {
                        $status['_type'] = 1;
                        $status['_title'] = 'Đang xử lý';
                        if ($order['advance_id']) {
                            $status['_msg'] = 'Đặt trước: sau ' . date('d/m/Y', (int)$order['cartInfo'][0]['productInfo']['presale_end_time']) . ', shop giao trong ' . ($order['cartInfo'][0]['productInfo']['presale_day'] ?? '') . ' ngày.';
                        } else {
                            $status['_msg'] = 'Shop đang chuẩn bị và gói hàng.';
                        }
                        $status['_class'] = 'state-nfh';
                    } elseif ($order['shipping_type'] === 2) {
                        $status['_type'] = 1;
                        $status['_title'] = 'Đang xử lý';
                        $status['_msg'] = 'Đơn nhận tại cửa hàng — chờ xác nhận.';
                        $status['_class'] = 'state-nfh';
                    } else {
                        $status['_type'] = 1;
                        $status['_title'] = 'Đang xử lý';
                        $status['_msg'] = 'Đang chờ gửi quà.';
                        $status['_class'] = 'state-nfh';
                    }
                }
            } else if ($order['status'] == 1) {
                if ($order['delivery_type'] == 'send') {//TODO giao hàng
                    $status['_type'] = 2;
                    $status['_title'] = 'Chờ nhận hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Đơn đang được giao tới bạn. Kiểm tra đơn hàng và nhấn "Đã nhận" khi nhận đủ hàng.';
                    $status['_class'] = 'state-ysh';
                } elseif ($order['delivery_type'] == 'express') {//TODO  vận chuyển
                    $status['_type'] = 2;
                    $status['_title'] = 'Chờ nhận hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_goods'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Đơn đang được giao tới bạn. Kiểm tra đơn hàng và nhấn "Đã nhận" khi nhận đủ hàng.';
                    $status['_class'] = 'state-ysh';
                } elseif ($order['delivery_type'] == 'split') {//Chia lô hàng
                    $status['_type'] = 2;
                    $status['_title'] = 'Chờ nhận hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_split'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Đơn chia nhiều kiện — chờ nhận đủ.';
                    $status['_class'] = 'state-ysh';
                } else {
                    $status['_type'] = 2;
                    $status['_title'] = 'Chờ nhận hàng';
                    $ts = (int)$statusServices->value(['oid' => $order['id'], 'change_type' => 'delivery_fictitious'], 'change_time');
                    $fmt = $this->formatOrderStatusTime($ts);
                    $status['_msg'] = ($fmt !== '' ? $fmt . ' — ' : '') . 'Hàng đã gửi (đơn ảo). Kiểm tra và xác nhận.';
                    $status['_class'] = 'state-ysh';
                }
            } else if ($order['status'] == 2) {
                $status['_type'] = 3;
                $status['_title'] = 'Chờ đánh giá';
                $status['_msg'] = 'Bạn đã nhận hàng. Hãy Đánh giá sản phẩm.';
                $status['_class'] = 'state-ypj';
            } else if ($order['status'] == 3) {
                $status['_type'] = 4;
                $status['_title'] = 'Hoàn tất';
                $status['_msg'] = 'Đơn hàng đã hoàn tất. Cảm ơn bạn!';
                $status['_class'] = 'state-ytk';
            }
        }
        if (isset($order['pay_type']))
            $status['_payType'] = $status['_type'] == 0 ? '' : PayServices::PAY_TYPE[$order['pay_type']] ?? 'những cách khác';
        if (isset($order['delivery_type']))
            $status['_deliveryType'] = $this->deliveryType[$order['delivery_type']] ?? 'những cách khác';
        $order['_status'] = $status;
        $order['_pay_time'] = isset($order['pay_time']) && $order['pay_time'] != null ? date('Y-m-d H:i:s', $order['pay_time']) : '';
        $order['_add_time'] = isset($order['add_time']) ? (strstr((string)$order['add_time'], '-') === false ? date('Y-m-d H:i:s', $order['add_time']) : $order['add_time']) : '';

        //Hệ thống Cài đặt trước khoảng thời gian hủy đơn hàng
        $keyValue = ['order_cancel_time', 'order_activity_time', 'order_bargain_time', 'order_seckill_time', 'order_pink_time'];
        //Nhận cấu hình
        $systemValue = SystemConfigService::more($keyValue);
        //Định dạng dữ liệu
        $systemValue = Arr::setValeTime($keyValue, is_array($systemValue) ? $systemValue : []);
        if ($order['seckill_id']) {
            $secs = $systemValue['order_seckill_time'] ? $systemValue['order_seckill_time'] : $systemValue['order_activity_time'];
        } elseif ($order['bargain_id']) {
            $secs = $systemValue['order_bargain_time'] ? $systemValue['order_bargain_time'] : $systemValue['order_activity_time'];
        } elseif ($order['combination_id']) {
            $secs = $systemValue['order_pink_time'] ? $systemValue['order_pink_time'] : $systemValue['order_activity_time'];
        } else {
            $secs = $systemValue['order_cancel_time'];
        }
        $order['stop_time'] = $secs * 3600 + $order['add_time'];
        $order['status_pic'] = '';
        //Nhận hình ảnh trạng thái sản phẩm
        if ($isPic) {
            $order_details_images = sys_data('order_details_images') ?: [];
            foreach ($order_details_images as $image) {
                if (isset($image['order_status']) && $image['order_status'] == $order['_status']['_type']) {
                    $order['status_pic'] = $image['pic'];
                    break;
                }
            }
        }
        if ($order['seckill_id'] || $order['bargain_id'] || $order['combination_id'] || $order['advance_id']) {
            if ($order['seckill_id']) $order['type'] = 1;
            if ($order['bargain_id']) $order['type'] = 2;
            if ($order['combination_id']) $order['type'] = 3;
            if ($order['advance_id']) $order['type'] = 4;
        }
        $order['offlinePayStatus'] = (int)sys_config('offline_pay_status') ?? (int)2;
        $order['vn_cod_pay_status'] = (int)sys_config('vn_cod_pay_status', 2);
        $order['vn_bank_pay_status'] = (int)sys_config('vn_bank_pay_status', 2);
        $guideTemplate = (string)sys_config('vn_bank_pay_guide', '');
        $order['vn_bank_transfer_content'] = VnBankPayHelper::transferContent((string)$order['order_id']);
        $order['vn_bank_pay_guide'] = $guideTemplate !== ''
            ? VnBankPayHelper::renderGuide($guideTemplate, (string)$order['order_id'], $order['pay_price'])
            : '';
        $order['vn_bank_pay_qr_image'] = VnBankPayHelper::resolveQrImage((string)$order['order_id'], $order['pay_price']);
        $log = $statusServices->getColumn(['oid' => $order['id']], 'change_time', 'change_type');
        if (isset($log['delivery'])) {
            $delivery = date('Y-m-d', $log['delivery']);
        } elseif (isset($log['delivery_goods'])) {
            $delivery = date('Y-m-d', $log['delivery_goods']);
        } elseif (isset($log['delivery_fictitious'])) {
            $delivery = date('Y-m-d', $log['delivery_fictitious']);
        } else {
            $delivery = '';
        }
        $order['order_log'] = [
            'create' => isset($log['cache_key_create_order']) ? date('Y-m-d', $log['cache_key_create_order']) : '',
            'pay' => isset($log['pay_success']) ? date('Y-m-d', $log['pay_success']) : '',
            'delivery' => $delivery,
            'take' => isset($log['take_delivery']) ? date('Y-m-d', $log['take_delivery']) : '',
            'complete' => isset($log['check_order_over']) ? date('Y-m-d', $log['check_order_over']) : '',
        ];

        $order['gift_user_info'] = [
            'gift_uid' => $order['gift_uid'],
            'gift_nickname' => '',
            'gift_avatar' => '',
        ];
        if ($order['gift_uid'] != 0) {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $giftUser = $userServices->get($order['gift_uid'], ['nickname', 'avatar']);
            $order['gift_user_info'] = [
                'gift_uid' => $order['gift_uid'],
                'gift_nickname' => $giftUser['nickname'],
                'gift_avatar' => $giftUser['avatar'],
            ];
        }
        return $order;
    }

    /**
     * chuyển đổi dữ liệu
     * @param array $data
     * @return array
     */    public function tidyOrderList(array $data)
    {
        /** @var StoreOrderCartInfoServices $services */        $services = app()->make(StoreOrderCartInfoServices::class);
        foreach ($data as &$item) {
            $item['_info'] = $services->getOrderCartInfo((int)$item['id']);
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            $item['_refund_time'] = isset($item['refund_reason_time']) && $item['refund_reason_time'] ? date('Y-m-d H:i:s', $item['refund_reason_time']) : '';
            $item['_pay_time'] = isset($item['pay_time']) && $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : '';
            if (($item['pink_id'] || $item['combination_id']) && isset($item['pinkStatus'])) {
                switch ($item['pinkStatus']) {
                    case 1:
                        $item['pink_name'] = '[Đơn hàng mua chung]đang tiến hành';
                        $item['color'] = '#f00';
                        break;
                    case 2:
                        $item['pink_name'] = '[Đơn hàng mua chung]Hoàn thành';
                        $item['color'] = '#00f';
                        break;
                    case 3:
                        $item['pink_name'] = '[Đơn hàng mua chung]Chưa hoàn thành';
                        $item['color'] = '#f0f';
                        break;
                    default:
                        $item['pink_name'] = '[Đơn hàng mua chung]Lệnh lịch sử';
                        $item['color'] = '#FF7D00';
                        break;
                }
            } elseif ($item['combination_id']) {
                $item['pink_name'] = '[Đơn hàng mua chung]';
                $item['color'] = '#FF7D00';
            } elseif ($item['seckill_id']) {
                $item['pink_name'] = '[Đơn hàng Flash Sale]';
                $item['color'] = '#3491FA';
            } elseif ($item['bargain_id']) {
                $item['pink_name'] = '[Đơn hàng mặc cả]';
                $item['color'] = '#F7BA1E';
            } elseif ($item['advance_id']) {
                $item['pink_name'] = '[Đơn đặt trước]';
                $item['color'] = '#B27FEB';
            } else {
                if ($item['shipping_type'] == 1) {
                    $item['pink_name'] = '[Đơn hàng thông thường]';
                    $item['color'] = '#333';
                } else if ($item['shipping_type'] == 2) {
                    $item['pink_name'] = '[Xác nhận đơn hàng]';
                    $item['color'] = '#8956E8';
                }
            }
            if ($item['paid'] == 1) {
                switch ($item['pay_type']) {
                    case PayServices::WEIXIN_PAY:
                        $item['pay_type_name'] = 'Thanh toán WeChat';
                        break;
                    case PayServices::YUE_PAY:
                        $item['pay_type_name'] = 'Thanh toán bằng số dư';
                        break;
                    case PayServices::OFFLINE_PAY:
                        $item['pay_type_name'] = 'Thanh toán ngoại tuyến';
                        break;
                    case PayServices::ALIAPY_PAY:
                        $item['pay_type_name'] = 'thanh toán Alipay';
                        break;
                    case PayServices::ALLIN_PAY:
                        $item['pay_type_name'] = 'Thanh toán Tonglian';
                        break;
                    case PayServices::VN_COD:
                        $item['pay_type_name'] = PayServices::PAY_TYPE[PayServices::VN_COD];
                        break;
                    case PayServices::VN_BANK:
                        $item['pay_type_name'] = PayServices::PAY_TYPE[PayServices::VN_BANK];
                        break;
                    case PayServices::VN_VNPAY:
                    case PayServices::VN_MOMO:
                    case PayServices::VN_ZALOPAY:
                        $item['pay_type_name'] = PayServices::PAY_TYPE[$item['pay_type']] ?? '';
                        break;
                    default:
                        $item['pay_type_name'] = 'Các khoản thanh toán khác';
                        break;
                }
            } else {
                switch ($item['pay_type']) {
                    case PayServices::OFFLINE_PAY:
                    case PayServices::VN_COD:
                    case PayServices::VN_BANK:
                        $item['pay_type_name'] = PayServices::PAY_TYPE[$item['pay_type']] ?? '';
                        $item['pay_type_info'] = 1;
                        break;
                    default:
                        $item['pay_type_name'] = '';
                        break;
                }
            }
            $status_name = ['status_name' => '', 'pics' => []];
            if ($item['paid'] == 0 && $item['status'] == 0) {
                $status_name['status_name'] = $item['is_cancel'] == 0 ? 'Chưa thanh toán' : 'Đã hủy';
            } else if ($item['paid'] == 1 && $item['status'] == 0 && $item['shipping_type'] == 1 && $item['refund_status'] == 0) {
                $status_name['status_name'] = $item['combination_id'] && isset($item['pinkStatus']) && $item['pinkStatus'] == 1 ? 'Không được vận chuyển(Tham gia nhóm)' : 'Không được vận chuyển';
            } else if ($item['paid'] == 1 && $item['status'] == 4 && $item['shipping_type'] == 1 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Giao hàng một phần';
            } else if ($item['paid'] == 1 && $item['status'] == 0 && $item['shipping_type'] == 2 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Không được viết tắt';
            } else if ($item['paid'] == 1 && $item['status'] == 1 && $item['shipping_type'] == 1 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Đang chờ nhận';
            } else if ($item['paid'] == 1 && $item['status'] == 1 && $item['shipping_type'] == 2 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Không được viết tắt';
            } else if ($item['paid'] == 1 && $item['status'] == 2 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Đang chờ đánh giá';
            } else if ($item['paid'] == 1 && $item['status'] == 3 && $item['refund_status'] == 0) {
                $status_name['status_name'] = 'Hoàn thành';
            } else if ($item['paid'] == 1 && $item['refund_status'] == 1) {
                $refundReasonTime = date('Y-m-d H:i', $item['refund_reason_time']);
                $refundReasonWapImg = json_decode($item['refund_reason_wap_img'], true);
                $refundReasonWapImg = $refundReasonWapImg ?: [];
                $img = [];
                if (count($refundReasonWapImg)) {
                    foreach ($refundReasonWapImg as $itemImg) {
                        if (strlen(trim($itemImg)))
                            $img[] = $itemImg;
                    }
                }
                $status_name['status_name'] = 'Đang hoàn tiền';
                $status_name['pics'] = $img;
            } else if ($item['paid'] == 1 && $item['refund_status'] == 2) {
                $status_name['status_name'] = 'Đã hoàn tiền';
            } else if ($item['paid'] == 1 && $item['refund_status'] == 3) {
                $status_name['status_name'] = <<<HTML
<b style="color:#f124c7">Hoàn tiền một phần</b><br/>
HTML;
            } else if ($item['paid'] == 1 && $item['refund_status'] == 4) {
                $status_name['status_name'] = <<<HTML
<b style="color:#f124c7">Đang hoàn tiền</b><br/>
HTML;
            }
            $item['status_name'] = $status_name;
            if ($item['paid'] == 0 && $item['status'] == 0 && $item['refund_status'] == 0) {
                $item['_status'] = 1;//Chưa thanh toán
            } else if ($item['paid'] == 1 && $item['status'] == 0 && $item['refund_status'] == 0) {
                $item['_status'] = 2;//Đã thanh toán nhưng chưa giao hàng
            } else if ($item['paid'] == 1 && $item['status'] == 4 && $item['refund_status'] == 0) {
                $item['_status'] = 8;//Đã thanh toán Đã vận chuyển một phần
            } else if ($item['paid'] == 1 && $item['refund_status'] == 1) {
                $item['_status'] = 3;//Đã thanh toán, xin hoàn tiền
            } else if ($item['paid'] == 1 && $item['status'] == 1 && $item['refund_status'] == 0) {
                $item['_status'] = 4;//Đã thanh toán chờ nhận
            } else if ($item['paid'] == 1 && $item['status'] == 2 && $item['refund_status'] == 0) {
                $item['_status'] = 5;//Đã thanh toán nhưng chưa được xem xét
            } else if ($item['paid'] == 1 && $item['status'] == 3 && $item['refund_status'] == 0) {
                $item['_status'] = 6;//Đã hoàn thành
            } else if ($item['paid'] == 1 && $item['refund_status'] == 2) {
                $item['_status'] = 7;//Đã thanh toán Đã hoàn lại tiền
            } else if ($item['paid'] == 1 && $item['refund_status'] == 3 && $item['status'] == 4) {
                $item['_status'] = 9;//Chia đơn hàng và vận chuyển, xin hoàn lại một phần
            } else if ($item['paid'] == 1 && $item['refund_status'] == 4) {
                $item['_status'] = 10;//Tất cả đơn hàng đã được chia nhỏ và vận chuyển. Tất cả các khoản hoàn trả đã được áp dụng.
            } else if ($item['paid'] == 1 && $item['refund_status'] == 3 && $item['status'] == 0) {
                $item['_status'] = 11;//Tách đơn hàng và hoàn tiền không được vận chuyển
            }
            if ($item['clerk_id'] == 0 && !isset($item['clerk_name'])) {
                $item['clerk_name'] = 'tổng nền tảng';
            }

            if ($item['store_id']) {
                $store = app()->make(SystemStoreServices::class);
                $storeOne = $store->value(['id' => $item['store_id']], 'name');
                if ($storeOne) $item['store_name'] = $storeOne;
            }

            //Thay đổi theo người bảo lãnhstore_name
            if ($item['clerk_id'] && isset($item['staff_store_id']) && $item['staff_store_id']) {
                /** @var SystemStoreServices $store */                $store = app()->make(SystemStoreServices::class);
                $storeOne = $store->value(['id' => $item['staff_store_id']], 'name');
                if ($storeOne) $item['store_name'] = $storeOne;
            }
        }
        return $data;
    }

    /**
     * Xử lý số tiền đặt hàng
     * @param $where
     * @return array
     */    public function getOrderPrice($where)
    {
        if (isset($where['refund_type']) && $where['refund_type']) unset($where['refund_type']);
        $where['is_del'] = 0;//Xóa đơn hàng không được tính
        $price['today_pay_price'] = 0;//Số tiền thanh toán hôm nay
        $price['pay_price'] = 0;//Số tiền thanh toán
        $price['refund_price'] = 0;//Số tiền hoàn lại
        $price['pay_price_wx'] = 0;//Số tiền thanh toán WeChat
        $price['pay_price_yue'] = 0;//Số tiền Thanh toán bằng số dư
        $price['pay_price_offline'] = 0;//Số tiền thanh toán ngoại tuyến
        $price['pay_price_other'] = 0;//Số tiền thanh toán khác
        $price['use_integral'] = 0;//Điểm Khách hàng
        $price['back_integral'] = 0;//Tổng số điểm được hoàn trả
        $price['deduction_price'] = 0;//Số tiền khấu trừ
        $price['total_num'] = 0; //Tổng số mặt hàng
        $price['today_count_sum'] = 0; //Tổng số đơn hàng hôm nay
        $price['count_sum'] = 0; //Tổng số đơn đặt hàng
        $price['brokerage'] = 0;
        $price['pay_postage'] = 0;
        $whereData = ['is_del' => 0];
        if ($where['status'] == '' && $where['pay_type'] != 3) {
            $whereData['paid'] = 1;
        }
        $ids = $this->dao->column($where + $whereData, 'id');
        if (count($ids)) {
            /** @var UserBillServices $services */            $services = app()->make(UserBillServices::class);
            $price['brokerage'] = $services->getBrokerageNumSum($ids);
        }
        $price['refund_price'] = $this->dao->together($where + ['is_del' => 0, 'paid' => 1, 'refund_status' => 2], 'refund_price');
        $sumNumber = $this->dao->search($where + $whereData)->field([
            'sum(total_num) as sum_total_num',
            'count(id) as count_sum',
            'sum(pay_price) as sum_pay_price',
            'sum(pay_postage) as sum_pay_postage',
            'sum(use_integral) as sum_use_integral',
            'sum(back_integral) as sum_back_integral',
            'sum(deduction_price) as sum_deduction_price'
        ])->find();
        if ($sumNumber) {
            $price['count_sum'] = $sumNumber['count_sum'];
            $price['total_num'] = $sumNumber['sum_total_num'];
            $price['pay_price'] = $sumNumber['sum_pay_price'];
            $price['pay_postage'] = $sumNumber['sum_pay_postage'];
            $price['use_integral'] = $sumNumber['sum_use_integral'];
            $price['back_integral'] = $sumNumber['sum_back_integral'];
            $price['deduction_price'] = $sumNumber['sum_deduction_price'];
        }
        $list = $this->dao->column($where + $whereData, 'sum(pay_price) as sum_pay_price,pay_type', 'id', 'pay_type');
        foreach ($list as $v) {
            if ($v['pay_type'] == 'weixin') {
                $price['pay_price_wx'] = $v['sum_pay_price'];
            } elseif ($v['pay_type'] == 'yue') {
                $price['pay_price_yue'] = $v['sum_pay_price'];
            } elseif ($v['pay_type'] == 'offline') {
                $price['pay_price_offline'] = $v['sum_pay_price'];
            } else {
                $price['pay_price_other'] = $v['sum_pay_price'];
            }
        }
        $where['time'] = 'today';
        $sumNumber = $this->dao->search($where + $whereData)->field([
            'count(id) as today_count_sum',
            'sum(pay_price) as today_pay_price',
        ])->find();
        if ($sumNumber) {
            $price['today_count_sum'] = $sumNumber['today_count_sum'];
            $price['today_pay_price'] = $where['status'] !== 0 ? $sumNumber['today_pay_price'] : 0;
        }
        return $price;
    }

    /**
     * Nhận thống kê trang danh sách đơn hàng
     * @param $where
     * @return array
     */    public function getBadge($where)
    {
        $price = $this->getOrderPrice($where);
        return [
            [
                'name' => 'Số lượng đặt hàng',
                'field' => 'miếng',
                'count' => $price['count_sum'],
                'className' => 'md-basket',
                'col' => 6
            ],
            [
                'name' => 'Số tiền đặt hàng',
                'field' => 'đ',
                'count' => $price['pay_price'],
                'className' => 'md-pricetags',
                'col' => 6
            ],
            [
                'name' => 'Số lượng đặt hàng hôm nay',
                'field' => 'miếng',
                'count' => $price['today_count_sum'],
                'className' => 'ios-chatbubbles',
                'col' => 6
            ],
            [
                'name' => 'Số tiền thanh toán hôm nay',
                'field' => 'đ',
                'count' => $price['today_pay_price'],
                'className' => 'ios-cash',
                'col' => 6
            ],
        ];
    }

    /**
     *
     * @param array $where
     * @return mixed
     */    /**
     * @param array $where
     * @return array
     * @throws \ReflectionException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/3/14
     */    public function orderCount(array $where)
    {
        $where['is_system_del'] = 0;
        $where['pid'] = 0;
        $data['un_paid'] = $this->dao->count($where + ['status' => 0], false);
        $data['un_send'] = $this->dao->count($where + ['status' => 1, 'shipping_type' => 1], false);
        $data['cancelled'] = $this->dao->count($where + ['status' => -5], false);
        return $data;
    }

    /**
     * Tạo mẫu đơn đặt hàng sửa đổi
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function updateForm(int $id)
    {
        $product = $this->dao->get($id);
        if (!$product) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $f = [];
        $f[] = Form::input('order_id', 'số thứ tự', $product->getData('order_id'))->disabled(true);
        $f[] = Form::hidden('total_price', (float)$product->getData('total_price'));
        $f[] = Form::hidden('pay_postage', (float)$product->getData('pay_postage') ?: 0);
        $f[] = Form::number('pay_price', 'số tiền Thanh toán thực tế', (float)$product->getData('pay_price'))->min(0);
        $f[] = Form::number('gain_integral', 'Tặng điểm', (float)$product->getData('gain_integral') ?: 0)->min(0);
        return create_form('Sửa đổi thứ tự', $f, $this->url('/order/update/' . $id), 'PUT');
    }

    /**
     * Sửa đổi thứ tự
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     */    public function updateOrder(int $id, array $data)
    {
        $order = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
        if (!$order) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        /** @var StoreOrderCreateServices $createServices */        $createServices = app()->make(StoreOrderCreateServices::class);
        $data['order_id'] = $createServices->getNewOrderId('cp');
        if (sys_config('user_brokerage_type') == 1) {
            $percent = $order['pay_price'] != 0 ? bcdiv((string)$data['pay_price'], (string)$order['pay_price'], 6) : $order['pay_price'];
            if ($order['one_brokerage'] > 0) {
                $data['one_brokerage'] = bcmul((string)$order['one_brokerage'], $percent, 2);
            }
            if ($order['two_brokerage'] > 0) {
                $data['two_brokerage'] = bcmul((string)$order['two_brokerage'], $percent, 2);
            }
            if ($order['staff_brokerage'] > 0) {
                $data['staff_brokerage'] = bcmul((string)$order['staff_brokerage'], $percent, 2);
            }
            if ($order['agent_brokerage'] > 0) {
                $data['agent_brokerage'] = bcmul((string)$order['agent_brokerage'], $percent, 2);
            }
            if ($order['division_brokerage'] > 0) {
                $data['division_brokerage'] = bcmul((string)$order['division_brokerage'], $percent, 2);
            }
        }
        /** @var StoreOrderStatusServices $services */        $services = app()->make(StoreOrderStatusServices::class);
        return $this->transaction(function () use ($id, $data, $services) {
            $res = $this->dao->update($id, $data);
            $res = $res && $services->save([
                    'oid' => $id,
                    'change_type' => 'order_edit',
                    'change_time' => time(),
                    'change_message' => 'Sửa đổi tổng giá của sản phẩm thành：' . $data['total_price'] . ' số tiền Thanh toán thực tế' . $data['pay_price']
                ]);
            if (isset($data['gain_integral'])) {
                $res = $res && $services->save([
                        'oid' => $id,
                        'change_type' => 'order_edit',
                        'change_time' => time(),
                        'change_message' => 'Sửa đổi điểm thưởng đơn hàng thành：' . $data['gain_integral']
                    ]);
            }
            if ($res) {
                $order = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
                //SMS nhắc nhở thay đổi giá
                event('NoticeListener', [['order' => $order, 'pay_price' => $data['pay_price']], 'price_revision']);
                //Thay đổi giá đơn hàng theo tin nhắn tùy chỉnh
                $order['change_price'] = $data['pay_price'];
                event('NoticeListener', [$order['uid'], $order, 'price_change_price']);

                //Thay đổi giá theo thứ tự sự kiện tùy chỉnh
                event('CustomEventListener', ['admin_order_change', [
                    'uid' => $order['uid'],
                    'order_id' => $data['order_id'],
                    'pay_price' => $data['pay_price'],
                    'gain_integral' => $data['gain_integral'] ?? $order['gain_integral'],
                    'change_time' => date('Y-m-d H:i:s'),
                ]]);

                return $data['order_id'];
            } else {
                throw new AdminException('Sửa đổi không thành công');
            }
        });
    }

    /**
     * Biểu đồ đặt hàng
     * @param $cycle
     * @return array
     */    public function orderCharts($cycle)
    {
        $datalist = [];
        switch ($cycle) {
            case 'thirtyday':
                $datebefor = date('Y-m-d', strtotime('-30 day'));
                $dateafter = date('Y-m-d 23:59:59');
                //Số cuối cùng
                $pre_datebefor = date('Y-m-d', strtotime('-60 day'));
                $pre_dateafter = date('Y-m-d', strtotime('-30 day'));
                for ($i = -29; $i <= 0; $i++) {
                    $datalist[date('m-d', strtotime($i . ' day'))] = date('m-d', strtotime($i . ' day'));
                }
                $order_list = $this->dao->orderAddTimeList($datebefor, $dateafter, '30');
                if (empty($order_list)) return ['yAxis' => [], 'legend' => [], 'xAxis' => [], 'serise' => [], 'pre_cycle' => [], 'cycle' => []];
                foreach ($order_list as $k => &$v) {
                    $order_list[$v['day']] = $v;
                }
                $cycle_list = [];
                foreach ($datalist as $dk => $dd) {
                    if (!empty($order_list[$dd])) {
                        $cycle_list[$dd] = $order_list[$dd];
                    } else {
                        $cycle_list[$dd] = ['count' => 0, 'day' => $dd, 'price' => ''];
                    }
                }
                $chartdata = [];
                $data = [];//tạm thời
                $chartdata['yAxis']['maxnum'] = 0;//Số lượng giá trị tối đa
                $chartdata['yAxis']['maxprice'] = 0;//Số tiền tối đa
                foreach ($cycle_list as $k => $v) {
                    $data['day'][] = $v['day'];
                    $data['count'][] = $v['count'];
                    $data['price'][] = round($v['price'], 2);
                    if ($chartdata['yAxis']['maxnum'] < $v['count'])
                        $chartdata['yAxis']['maxnum'] = $v['count'];//Số lượng đơn hàng tối đa mỗi ngày
                    if ($chartdata['yAxis']['maxprice'] < $v['price'])
                        $chartdata['yAxis']['maxprice'] = $v['price'];//Số tiền tối đa mỗi ngày
                }
                $chartdata['legend'] = ['Số tiền đặt hàng', 'Số lượng đơn đặt hàng'];//Phân loại
                $chartdata['xAxis'] = $data['day'];//Xgiá trị trục
                $series1 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series2 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $chartdata['series'][] = ['name' => $chartdata['legend'][0], 'type' => 'bar', 'itemStyle' => $series1, 'data' => $data['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][1], 'type' => 'line', 'itemStyle' => $series2, 'data' => $data['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2
                // Thống kê tổng số số phát hành cuối cùng
                $pre_total = $this->dao->preTotalFind($pre_datebefor, $pre_dateafter);
                if ($pre_total) {
                    $chartdata['pre_cycle']['count'] = [
                        'data' => $pre_total['count'] ?: 0
                    ];
                    $chartdata['pre_cycle']['price'] = [
                        'data' => $pre_total['price'] ?: 0
                    ];
                }
                //Tổng số thống kê
                $total = $this->dao->preTotalFind($datebefor, $dateafter);
                if ($total) {
                    $cha_count = intval($pre_total['count']) - intval($total['count']);
                    $pre_total['count'] = $pre_total['count'] == 0 ? 1 : $pre_total['count'];
                    $chartdata['cycle']['count'] = [
                        'data' => $total['count'] ?: 0,
                        'percent' => round((abs($cha_count) / intval($pre_total['count']) * 100), 2),
                        'is_plus' => $cha_count > 0 ? -1 : ($cha_count == 0 ? 0 : 1)
                    ];
                    $cha_price = round($pre_total['price'], 2) - round($total['price'], 2);
                    $pre_total['price'] = $pre_total['price'] == 0 ? 1 : $pre_total['price'];
                    $chartdata['cycle']['price'] = [
                        'data' => $total['price'] ?: 0,
                        'percent' => round(abs($cha_price) / $pre_total['price'] * 100, 2),
                        'is_plus' => $cha_price > 0 ? -1 : ($cha_price == 0 ? 0 : 1)
                    ];
                }
                return $chartdata;
            case 'week':
                $weekarray = array(['Chủ nhật'], ['vào thứ Hai'], ['Thứ ba'], ['Thứ Tư'], ['Thứ năm'], ['Thứ sáu'], ['Thứ bảy']);
                $datebefor = date('Y-m-d', strtotime('-1 week Monday'));
                $dateafter = date('Y-m-d', strtotime('-1 week Sunday'));
//                $order_list = $this->dao->orderAddTimeList($datebefor, $dateafter, 'week');
                //Xử lý lại truy vấn dữ liệu
                $new_order_list = [];
//                foreach ($order_list as $k => $v) {
//                    $new_order_list[$v['day']] = $v;
//                }
                $now_datebefor = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $now_dateafter = date('Y-m-d', strtotime("+1 day"));
                $now_order_list = $this->dao->nowOrderList($now_datebefor, $now_dateafter, 'week');
                //Khóa xử lý lại truy vấn dữ liệu thay đổi thành giá trị hiện tại
                $new_now_order_list = [];
                foreach ($now_order_list as $k => $v) {
                    $new_now_order_list[$v['day']] = $v;
                }
                foreach ($weekarray as $dk => $dd) {
                    if (!empty($new_order_list[$dk])) {
                        $weekarray[$dk]['pre'] = $new_order_list[$dk];
                    } else {
                        $weekarray[$dk]['pre'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                    if (!empty($new_now_order_list[$dk])) {
                        $weekarray[$dk]['now'] = $new_now_order_list[$dk];
                    } else {
                        $weekarray[$dk]['now'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                }
                $chartdata = [];
                $data = [];//tạm thời
                $chartdata['yAxis']['maxnum'] = 0;//Số lượng giá trị tối đa
                $chartdata['yAxis']['maxprice'] = 0;//Số tiền tối đa
                foreach ($weekarray as $k => $v) {
                    $data['day'][] = $v[0];
                    $data['pre']['count'][] = $v['pre']['count'];
                    $data['pre']['price'][] = round($v['pre']['price'], 2);
                    $data['now']['count'][] = $v['now']['count'];
                    $data['now']['price'][] = round($v['now']['price'], 2);
                    if ($chartdata['yAxis']['maxnum'] < $v['pre']['count'] || $chartdata['yAxis']['maxnum'] < $v['now']['count']) {
                        $chartdata['yAxis']['maxnum'] = $v['pre']['count'] > $v['now']['count'] ? $v['pre']['count'] : $v['now']['count'];//Số lượng đơn hàng tối đa mỗi ngày
                    }
                    if ($chartdata['yAxis']['maxprice'] < $v['pre']['price'] || $chartdata['yAxis']['maxprice'] < $v['now']['price']) {
                        $chartdata['yAxis']['maxprice'] = $v['pre']['price'] > $v['now']['price'] ? $v['pre']['price'] : $v['now']['price'];//Số tiền tối đa mỗi ngày
                    }
                }
                $chartdata['legend'] = ['Số tiền tuần trước', 'Số tiền của tuần này', 'Số đơn hàng tuần trước', 'Số lượng đặt hàng trong tuần này'];//Phân loại
                $chartdata['xAxis'] = $data['day'];//Xgiá trị trục
                $series1 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series2 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $series3 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series4 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $chartdata['series'][] = ['name' => $chartdata['legend'][0], 'type' => 'bar', 'itemStyle' => $series1, 'data' => $data['pre']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][1], 'type' => 'bar', 'itemStyle' => $series2, 'data' => $data['now']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][2], 'type' => 'line', 'itemStyle' => $series3, 'data' => $data['pre']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2
                $chartdata['series'][] = ['name' => $chartdata['legend'][3], 'type' => 'line', 'itemStyle' => $series4, 'data' => $data['now']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2

                // Thống kê tổng số số phát hành cuối cùng
                $pre_total = $this->dao->preTotalFind($datebefor, $dateafter);
                if ($pre_total) {
                    $chartdata['pre_cycle']['count'] = [
                        'data' => $pre_total['count'] ?: 0
                    ];
                    $chartdata['pre_cycle']['price'] = [
                        'data' => $pre_total['price'] ?: 0
                    ];
                }
                //Tổng số thống kê
                $total = $this->dao->preTotalFind($now_datebefor, $now_dateafter);
                if ($total) {
                    $cha_count = intval($pre_total['count']) - intval($total['count']);
                    $pre_total['count'] = $pre_total['count'] == 0 ? 1 : $pre_total['count'];
                    $chartdata['cycle']['count'] = [
                        'data' => $total['count'] ?: 0,
                        'percent' => round((abs($cha_count) / intval($pre_total['count']) * 100), 2),
                        'is_plus' => $cha_count > 0 ? -1 : ($cha_count == 0 ? 0 : 1)
                    ];
                    $cha_price = round($pre_total['price'], 2) - round($total['price'], 2);
                    $pre_total['price'] = $pre_total['price'] == 0 ? 1 : $pre_total['price'];
                    $chartdata['cycle']['price'] = [
                        'data' => $total['price'] ?: 0,
                        'percent' => round(abs($cha_price) / $pre_total['price'] * 100, 2),
                        'is_plus' => $cha_price > 0 ? -1 : ($cha_price == 0 ? 0 : 1)
                    ];
                }
                return $chartdata;
            case 'month':
                $weekarray = array('01' => ['1'], '02' => ['2'], '03' => ['3'], '04' => ['4'], '05' => ['5'], '06' => ['6'], '07' => ['7'], '08' => ['8'], '09' => ['9'], '10' => ['10'], '11' => ['11'], '12' => ['12'], '13' => ['13'], '14' => ['14'], '15' => ['15'], '16' => ['16'], '17' => ['17'], '18' => ['18'], '19' => ['19'], '20' => ['20'], '21' => ['21'], '22' => ['22'], '23' => ['23'], '24' => ['24'], '25' => ['25'], '26' => ['26'], '27' => ['27'], '28' => ['28'], '29' => ['29'], '30' => ['30'], '31' => ['31']);

                $datebefor = date('Y-m-01', strtotime('-1 month'));
                $dateafter = date('Y-m-d', strtotime(date('Y-m-01')));
                $order_list = $this->dao->orderAddTimeList($datebefor, $dateafter, "month");
                //Xử lý lại truy vấn dữ liệu
                $new_order_list = [];
                foreach ($order_list as $k => $v) {
                    $new_order_list[$v['day']] = $v;
                }
                $now_datebefor = date('Y-m-01');
                $now_dateafter = date('Y-m-d', strtotime("+1 day"));
                $now_order_list = $this->dao->nowOrderList($now_datebefor, $now_dateafter, "month");
                //Khóa xử lý lại truy vấn dữ liệu thay đổi thành giá trị hiện tại
                $new_now_order_list = [];
                foreach ($now_order_list as $k => $v) {
                    $new_now_order_list[$v['day']] = $v;
                }
                foreach ($weekarray as $dk => $dd) {
                    if (!empty($new_order_list[$dk])) {
                        $weekarray[$dk]['pre'] = $new_order_list[$dk];
                    } else {
                        $weekarray[$dk]['pre'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                    if (!empty($new_now_order_list[$dk])) {
                        $weekarray[$dk]['now'] = $new_now_order_list[$dk];
                    } else {
                        $weekarray[$dk]['now'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                }
                $chartdata = [];
                $data = [];//tạm thời
                $chartdata['yAxis']['maxnum'] = 0;//Số lượng giá trị tối đa
                $chartdata['yAxis']['maxprice'] = 0;//Số tiền tối đa
                foreach ($weekarray as $k => $v) {
                    $data['day'][] = $v[0];
                    $data['pre']['count'][] = $v['pre']['count'];
                    $data['pre']['price'][] = round($v['pre']['price'], 2);
                    $data['now']['count'][] = $v['now']['count'];
                    $data['now']['price'][] = round($v['now']['price'], 2);
                    if ($chartdata['yAxis']['maxnum'] < $v['pre']['count'] || $chartdata['yAxis']['maxnum'] < $v['now']['count']) {
                        $chartdata['yAxis']['maxnum'] = $v['pre']['count'] > $v['now']['count'] ? $v['pre']['count'] : $v['now']['count'];//Số lượng đơn hàng tối đa mỗi ngày
                    }
                    if ($chartdata['yAxis']['maxprice'] < $v['pre']['price'] || $chartdata['yAxis']['maxprice'] < $v['now']['price']) {
                        $chartdata['yAxis']['maxprice'] = $v['pre']['price'] > $v['now']['price'] ? $v['pre']['price'] : $v['now']['price'];//Số tiền tối đa mỗi ngày
                    }

                }
                $chartdata['legend'] = ['Số tiền tháng trước', 'Số tiền tháng này', 'Số đơn hàng tháng trước', 'Số lượng đơn hàng trong tháng này'];//Phân loại
                $chartdata['xAxis'] = $data['day'];//Xgiá trị trục
                $series1 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series2 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $series3 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series4 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $chartdata['series'][] = ['name' => $chartdata['legend'][0], 'type' => 'bar', 'itemStyle' => $series1, 'data' => $data['pre']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][1], 'type' => 'bar', 'itemStyle' => $series2, 'data' => $data['now']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][2], 'type' => 'line', 'itemStyle' => $series3, 'data' => $data['pre']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2
                $chartdata['series'][] = ['name' => $chartdata['legend'][3], 'type' => 'line', 'itemStyle' => $series4, 'data' => $data['now']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2

                // Thống kê tổng số số phát hành cuối cùng
                $pre_total = $this->dao->preTotalFind($datebefor, $dateafter);
                if ($pre_total) {
                    $chartdata['pre_cycle']['count'] = [
                        'data' => $pre_total['count'] ?: 0
                    ];
                    $chartdata['pre_cycle']['price'] = [
                        'data' => $pre_total['price'] ?: 0
                    ];
                }
                //Tổng số thống kê
                $total = $this->dao->preTotalFind($now_datebefor, $now_dateafter);
                if ($total) {
                    $cha_count = intval($pre_total['count']) - intval($total['count']);
                    $pre_total['count'] = $pre_total['count'] == 0 ? 1 : $pre_total['count'];
                    $chartdata['cycle']['count'] = [
                        'data' => $total['count'] ?: 0,
                        'percent' => round((abs($cha_count) / intval($pre_total['count']) * 100), 2),
                        'is_plus' => $cha_count > 0 ? -1 : ($cha_count == 0 ? 0 : 1)
                    ];
                    $cha_price = round($pre_total['price'], 2) - round($total['price'], 2);
                    $pre_total['price'] = $pre_total['price'] == 0 ? 1 : $pre_total['price'];
                    $chartdata['cycle']['price'] = [
                        'data' => $total['price'] ?: 0,
                        'percent' => round(abs($cha_price) / $pre_total['price'] * 100, 2),
                        'is_plus' => $cha_price > 0 ? -1 : ($cha_price == 0 ? 0 : 1)
                    ];
                }
                return $chartdata;
            case 'year':
                $weekarray = array('01' => ['Tháng Một'], '02' => ['Tháng hai'], '03' => ['Bước đều'], '04' => ['Tháng tư'], '05' => ['Có thể'], '06' => ['Tháng sáu'], '07' => ['Tháng bảy'], '08' => ['Tháng tám'], '09' => ['Tháng 9'], '10' => ['tháng mười'], '11' => ['Tháng mười một'], '12' => ['Tháng 12']);
                $datebefor = date('Y-01-01', strtotime('-1 year'));
                $dateafter = date('Y-12-31', strtotime('-1 year'));
                $order_list = $this->dao->orderAddTimeList($datebefor, $dateafter, 'year');
                //Xử lý lại truy vấn dữ liệu
                $new_order_list = [];
                foreach ($order_list as $k => $v) {
                    $new_order_list[$v['day']] = $v;
                }
                $now_datebefor = date('Y-01-01');
                $now_dateafter = date('Y-12-31 23:59:59');
                $now_order_list = $this->dao->nowOrderList($now_datebefor, $now_dateafter, 'year');
                //Khóa xử lý lại truy vấn dữ liệu thay đổi thành giá trị hiện tại
                $new_now_order_list = [];
                foreach ($now_order_list as $k => $v) {
                    $new_now_order_list[$v['day']] = $v;
                }
                foreach ($weekarray as $dk => $dd) {
                    if (!empty($new_order_list[$dk])) {
                        $weekarray[$dk]['pre'] = $new_order_list[$dk];
                    } else {
                        $weekarray[$dk]['pre'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                    if (!empty($new_now_order_list[$dk])) {
                        $weekarray[$dk]['now'] = $new_now_order_list[$dk];
                    } else {
                        $weekarray[$dk]['now'] = ['count' => 0, 'day' => $weekarray[$dk][0], 'price' => '0'];
                    }
                }
                $chartdata = [];
                $data = [];//tạm thời
                $chartdata['yAxis']['maxnum'] = 0;//Số lượng giá trị tối đa
                $chartdata['yAxis']['maxprice'] = 0;//Số tiền tối đa
                foreach ($weekarray as $k => $v) {
                    $data['day'][] = $v[0];
                    $data['pre']['count'][] = $v['pre']['count'];
                    $data['pre']['price'][] = round($v['pre']['price'], 2);
                    $data['now']['count'][] = $v['now']['count'];
                    $data['now']['price'][] = round($v['now']['price'], 2);
                    if ($chartdata['yAxis']['maxnum'] < $v['pre']['count'] || $chartdata['yAxis']['maxnum'] < $v['now']['count']) {
                        $chartdata['yAxis']['maxnum'] = $v['pre']['count'] > $v['now']['count'] ? $v['pre']['count'] : $v['now']['count'];//Số lượng đơn hàng tối đa mỗi ngày
                    }
                    if ($chartdata['yAxis']['maxprice'] < $v['pre']['price'] || $chartdata['yAxis']['maxprice'] < $v['now']['price']) {
                        $chartdata['yAxis']['maxprice'] = $v['pre']['price'] > $v['now']['price'] ? $v['pre']['price'] : $v['now']['price'];//Số tiền tối đa mỗi ngày
                    }
                }
                $chartdata['legend'] = ['Số tiền năm ngoái', 'Số tiền năm nay', 'Số lượng đơn hàng năm ngoái', 'Số lượng đơn hàng năm nay'];//Phân loại
                $chartdata['xAxis'] = $data['day'];//Xgiá trị trục
                $series1 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series2 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $series3 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#69cdff'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#3eb3f7'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#1495eb'
                        ]
                    ]
                ]]
                ];
                $series4 = ['normal' => ['color' => [
                    'x' => 0, 'y' => 0, 'x2' => 0, 'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => '#6fdeab'
                        ],
                        [
                            'offset' => 0.5,
                            'color' => '#44d693'
                        ],
                        [
                            'offset' => 1,
                            'color' => '#2cc981'
                        ]
                    ]
                ]]
                ];
                $chartdata['series'][] = ['name' => $chartdata['legend'][0], 'type' => 'bar', 'itemStyle' => $series1, 'data' => $data['pre']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][1], 'type' => 'bar', 'itemStyle' => $series2, 'data' => $data['now']['price']];//Giá trị loại 1
                $chartdata['series'][] = ['name' => $chartdata['legend'][2], 'type' => 'line', 'itemStyle' => $series3, 'data' => $data['pre']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2
                $chartdata['series'][] = ['name' => $chartdata['legend'][3], 'type' => 'line', 'itemStyle' => $series4, 'data' => $data['now']['count'], 'yAxisIndex' => 1];//Giá trị phân loại 2

                // Thống kê tổng số số phát hành cuối cùng
                $pre_total = $this->dao->preTotalFind($datebefor, $dateafter);
                if ($pre_total) {
                    $chartdata['pre_cycle']['count'] = [
                        'data' => $pre_total['count'] ?: 0
                    ];
                    $chartdata['pre_cycle']['price'] = [
                        'data' => $pre_total['price'] ?: 0
                    ];
                }
                //Tổng số thống kê
                $total = $this->dao->preTotalFind($now_datebefor, $now_dateafter);
                if ($total) {
                    $cha_count = intval($pre_total['count']) - intval($total['count']);
                    $pre_total['count'] = $pre_total['count'] == 0 ? 1 : $pre_total['count'];
                    $chartdata['cycle']['count'] = [
                        'data' => $total['count'] ?: 0,
                        'percent' => round((abs($cha_count) / intval($pre_total['count']) * 100), 2),
                        'is_plus' => $cha_count > 0 ? -1 : ($cha_count == 0 ? 0 : 1)
                    ];
                    $cha_price = round($pre_total['price'], 2) - round($total['price'], 2);
                    $pre_total['price'] = $pre_total['price'] == 0 ? 1 : $pre_total['price'];
                    $chartdata['cycle']['price'] = [
                        'data' => $total['price'] ?: 0,
                        'percent' => round(abs($cha_price) / $pre_total['price'] * 100, 2),
                        'is_plus' => $cha_price > 0 ? -1 : ($cha_price == 0 ? 0 : 1)
                    ];
                }
                return $chartdata;
            default:
                break;
        }
    }

    /**
     * Nhận số lượng đặt hàng
     * @return int
     */    public function storeOrderCount()
    {
        return $this->dao->storeOrderCount();
    }

    /**
     * trật tự mớiID
     * @param $status
     * @return array
     */    public function newOrderId($status)
    {
        return $this->dao->search(['status' => $status, 'is_remind' => 0])->column('order_id', 'id');
    }

    /**
     * Sửa đổi đơn hàng mới
     * @param $newOrderId
     * @return \crmeb\basic\BaseModel
     */    public function newOrderUpdate($newOrderId)
    {
        return $this->dao->newOrderUpdates($newOrderId);
    }

    /**
     * tốc độ tăng trưởng
     * @param $left
     * @param $right
     * @return int|string
     */    public function growth($nowValue, $lastValue)
    {
        if ($lastValue == 0 && $nowValue == 0) return 0;
        if ($lastValue == 0) return bcmul((string)$nowValue, '100', 2);
        if ($nowValue == 0) return bcdiv(bcsub($nowValue, $lastValue, 2), $lastValue, 4) * 100;
        return bcmul(bcdiv((bcsub($nowValue, $lastValue, 2)), $lastValue, 4), 100, 2);
    }

    /**
     * Thống kê ở đầu trang chủ nền
     * @return mảng
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/04/03
     */    public function homeStatics()
    {
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        /** @var StoreProductLogServices $productLogServices */        $productLogServices = app()->make(StoreProductLogServices::class);
        //TODO bán hàng
        // Doanh thu hôm nay
        $today_sales = $this->dao->todaySales('today');
        //Bán hàng của ngày hôm qua
        $yesterday_sales = $this->dao->todaySales('yesterday');
        //Hàng năm
        $sales_today_ratio = $this->growth($today_sales, $yesterday_sales);
        //tổng doanh thu
        $total_sales = $this->dao->totalSales('month');
        $sales = [
            'today' => $today_sales,
            'yesterday' => $yesterday_sales,
            'today_ratio' => $sales_today_ratio,
            'total' => $total_sales . 'đ',
            'date' => 'Hôm nay'
        ];
        //TODO:Lượt truy cập của Khách hàng
        //Chuyến thăm hôm nay
        $today_visits = $productLogServices->count(['time' => 'today', 'type' => 'visit']);
        //Lượt truy cập ngày hôm qua
        $yesterday_visits = $productLogServices->count(['time' => 'yesterday', 'type' => 'visit']);
        //Hàng năm
        $visits_today_ratio = $this->growth($today_visits, $yesterday_visits);
        //tổng số lượt truy cập
        $total_visits = $productLogServices->count(['time' => 'month', 'type' => 'visit']);
        $visits = [
            'today' => $today_visits,
            'yesterday' => $yesterday_visits,
            'today_ratio' => $visits_today_ratio,
            'total' => $total_visits . 'Pv',
            'date' => 'Hôm nay'
        ];
        //TODO Số lượng đặt hàng
        //Lượng đơn hàng hôm nay
        $today_order = $this->dao->todayOrderVisit('today', 1);
        //Lượng đặt hàng của ngày hôm qua
        $yesterday_order = $this->dao->todayOrderVisit('yesterday', 1);
        //Ngày đặt hàng hàng năm
        $order_today_ratio = $this->growth($today_order, $yesterday_order);
        //Tổng số lượng đặt hàng
        $total_order = $this->dao->count(['time' => 'month', 'paid' => 1, 'refund_status' => 0, 'pid' => 0]);
        $order = [
            'today' => $today_order,
            'yesterday' => $yesterday_order,
            'today_ratio' => $order_today_ratio,
            'total' => $total_order . 'một',
            'date' => 'Hôm nay'
        ];
        //TODO Khách hàng
        //Người dùng mới hôm nay
        $today_user = $userService->todayAddVisits('today', 1);
        //Người dùng mới ngày hôm qua
        $yesterday_user = $userService->todayAddVisits('yesterday', 1);
        //Người dùng mới hàng ngày hàng năm
        $user_today_ratio = $this->growth($today_user, $yesterday_user);
        //Tất cả Khách hàng
        $total_user = $userService->count(['time' => 'month']);
        $user = [
            'today' => $today_user,
            'yesterday' => $yesterday_user,
            'today_ratio' => $user_today_ratio,
            'total' => $total_user . 'mọi người',
            'date' => 'Hôm nay'
        ];
        $info = array_values(compact('sales', 'visits', 'order', 'user'));
        $info[0]['title'] = 'việc bán hàng';
        $info[1]['title'] = 'Lượt truy cập của Khách hàng';
        $info[2]['title'] = 'Số lượng đặt hàng';
        $info[3]['title'] = 'Thêm khách hàng mới';
        $info[0]['total_name'] = 'doanh số tháng này';
        $info[1]['total_name'] = 'Lượt truy cập trong tháng này';
        $info[2]['total_name'] = 'Số lượng đặt hàng trong tháng này';
        $info[3]['total_name'] = 'Người dùng mới trong tháng này';
        return $info;
    }

    /**
     * In hóa đơn đặt hàng
     * @param int $id
     * @param bool $start
     * @return bool|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */    public function orderPrintTicket(int $id, $print_type)
    {
        $order = $this->get($id);
        if (!$order) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        /** @var StoreOrderCartInfoServices $cartServices */        $cartServices = app()->make(StoreOrderCartInfoServices::class);
        $product = $cartServices->getCartInfoPrintProduct($order['id']);
        if (!$product) {
            throw new AdminException('Không thể lấy được các mặt hàng đặt hàng,Không thể in');
        }
//        $switch = (bool)sys_config('pay_success_printing_switch');
//        if (!$switch) {
//            throw new AdminException('Tính năng in biên lai chưa được bật');
//        }

        app()->make(SystemTicketServices::class)->startPrint(
            is_object($order) ? $order->toArray() : $order,
            $product,
            $print_type
        );
        return true;

//        if (sys_config('print_type', 1) == 1) {
//            $name = 'yi_lian_yun';
//            $configData = [
//                'clientId' => sys_config('printing_client_id', ''),
//                'apiKey' => sys_config('printing_api_key', ''),
//                'partner' => sys_config('develop_id', ''),
//                'terminal' => sys_config('terminal_number', '')
//            ];
//            if (!$configData['clientId'] || !$configData['apiKey'] || !$configData['partner'] || !$configData['terminal']) {
//                throw new AdminException('Trước tiên hãy định cấu hình nhà phát triển in biên lai');
//            }
//        } else {
//            $name = 'fei_e_yun';
//            $configData = [
//                'feyUser' => sys_config('fey_user', ''),
//                'feyUkey' => sys_config('fey_ukey', ''),
//                'feySn' => sys_config('fey_sn', '')
//            ];
//            if (!$configData['feyUser'] || !$configData['feyUkey'] || !$configData['feySn']) {
//                throw new AdminException('Trước tiên hãy định cấu hình nhà phát triển in biên lai');
//            }
//        }
//        $printer = new Printer($name, $configData);
//        $res = $printer->setPrinterContent([
//            'name' => sys_config('site_name'),
//            'url' => sys_config('site_url'),
//            'orderInfo' => is_object($order) ? $order->toArray() : $order,
//            'product' => $product
//        ])->startPrinter();
//        if (!$res) {
//            throw new AdminException($printer->getError());
//        }
//        return true;
    }

    /**
     * Nhận dữ liệu xác nhận đơn hàng
     * @param array $user
     * @param $cartId
     * @param bool $new
     * @param int $addressId
     * @param int $shipping_type
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOrderConfirmData(array $user, $cartId, bool $new, int $addressId, int $shipping_type = 1, int $is_gift = 0)
    {
        $addr = [];
        /** @var UserAddressServices $addressServices */        $addressServices = app()->make(UserAddressServices::class);
        if ($addressId) {
            $addr = $addressServices->getAddress($addressId);
        }
        //Id địa chỉ không được gửi hoặc địa chỉ đã bị xóa và không tìm thấy. ||Nhận địa chỉ mặc định
        if (!$addr) {
            $addr = $addressServices->getUserDefaultAddress((int)$user['uid']);
        }
        if ($addr) {
            $addr = $addr->toArray();
        } else {
            $addr = [];
        }
        if ($shipping_type == 2) $addr = [];
        if ($is_gift == 1) {
            $addr = [];
            $shipping_type = 0;
        }
        /** @var StoreCartServices $cartServices */        $cartServices = app()->make(StoreCartServices::class);
        $cartGroup = $cartServices->getUserProductCartListV1($user['uid'], $cartId, $new, $addr, $shipping_type, $is_gift);
        $data = [];
        $data['storeFreePostage'] = $storeFreePostage = floatval(sys_config('store_free_postage')) ?: 0;//Miễn phí vận chuyển cho toàn bộ số tiền
        $validCartInfo = $cartGroup['valid'];
        /** @var StoreOrderComputedServices $computedServices */        $computedServices = app()->make(StoreOrderComputedServices::class);
        $priceGroup = $computedServices->getOrderPriceGroup($storeFreePostage, $validCartInfo, $addr, $user, $shipping_type, $is_gift);
        $validCartInfo = $priceGroup['cartInfo'] ?? $validCartInfo;
        $other = [
            'offlinePostage' => sys_config('offline_postage'),
            'integralRatio' => sys_config('integral_ratio')
        ];
        $cartIdA = explode(',', $cartId);
        $seckill_id = 0;
        $combination_id = 0;
        $bargain_id = 0;
        $advance_id = 0;
        if (count($cartIdA) == 1) {
            $seckill_id = $cartGroup['deduction']['seckill_id'] ?? 0;
            $combination_id = $cartGroup['deduction']['combination_id'] ?? 0;
            $bargain_id = $cartGroup['deduction']['bargain_id'] ?? 0;
            $advance_id = $cartGroup['deduction']['advance_id'] ?? 0;
        }
        $data['valid_count'] = count($validCartInfo);
        $data['virtual_type'] = $data['valid_count'] ? (int)$validCartInfo[0]['productInfo']['virtual_type'] > 0 : 0;
        $data['deduction'] = $seckill_id || $combination_id || $bargain_id || $advance_id;
        $data['addressInfo'] = $addr;
        $data['seckill_id'] = $seckill_id;
        $data['combination_id'] = $combination_id;
        $data['bargain_id'] = $bargain_id;
        $data['advance_id'] = $advance_id;
        $data['cartInfo'] = $cartGroup['cartInfo'];
        $data['custom_form'] = json_decode($cartGroup['cartInfo'][0]['productInfo']['custom_form'], true) ?? [];
        if (!is_array($data['custom_form'])) $data['custom_form'] = [];
        $data['priceGroup'] = $priceGroup;
        $data['orderKey'] = $this->cacheOrderInfo($user['uid'], $validCartInfo, $priceGroup, $other);
        $data['offlinePostage'] = $other['offlinePostage'];
        /** @var UserLevelServices $levelServices */        $levelServices = app()->make(UserLevelServices::class);
        $userLevel = $levelServices->getUerLevelInfoByUid($user['uid']);
        if (isset($user['pwd'])) unset($user['pwd']);
        $user['vip'] = $userLevel !== false;
        if ($user['vip']) {
            $user['vip_id'] = $userLevel['id'] ?? 0;
            $user['discount'] = $userLevel['discount'] ?? 0;
        }
        $data['userInfo'] = $user;
        $data['integralRatio'] = $other['integralRatio'];
        $data['offline_pay_status'] = (int)sys_config('offline_pay_status') ?? (int)2;
        $data['vn_cod_pay_status'] = (int)sys_config('vn_cod_pay_status', 2);
        $data['vn_bank_pay_status'] = (int)sys_config('vn_bank_pay_status', 2);
        $data['vn_bank_pay_guide'] = (string)sys_config('vn_bank_pay_guide', '');
        $data['vn_bank_pay_qr_image'] = (string)sys_config('vn_bank_pay_qr_image', '');
        $data['yue_pay_status'] = (int)sys_config('balance_func_status') && (int)sys_config('yue_pay_status') == 1 ? (int)1 : (int)2;//Thanh toán số dư 1 tặng 2
        $data['pay_weixin_open'] = sys_config('pay_weixin_open', '0') != '0';//WeChat Trả 1 Bật 0 Tắt
        $data['friend_pay_status'] = (int)sys_config('friend_pay_status') ?? 0;//Bạn bè thanh toán thay mặt 1 Trên 0 Tắt
        $data['store_self_mention'] = (int)sys_config('store_self_mention') ?? 0;//Có bật tính năng nhận tại cửa hàng không?
        /** @var SystemStoreServices $systemStoreServices */        $systemStoreServices = app()->make(SystemStoreServices::class);
        $store_count = $systemStoreServices->count(['type' => 0]);
        $data['store_self_mention'] = $data['store_self_mention'] && $store_count;

        $data['ali_pay_status'] = sys_config('ali_pay_status', '0') != '0';//Gói thanh toán thanh toán 1 tặng 0 giảm
        $data['system_store'] = [];//lưu trữ thông tin
        /** @var UserInvoiceServices $userInvoice */        $userInvoice = app()->make(UserInvoiceServices::class);
        $invoice_func = $userInvoice->invoiceFuncStatus();
        $data['invoice_func'] = $invoice_func['invoice_func'];
        $data['special_invoice'] = $invoice_func['special_invoice'];

        /** @var UserBillServices $userBillServices */        $userBillServices = app()->make(UserBillServices::class);
        $data['usable_integral'] = bcsub((string)$user['integral'], (string)$userBillServices->getBillSum(['uid' => $user['uid'], 'is_frozen' => 1]), 0);
        $data['integral_open'] = sys_config('integral_ratio', 0) > 0;

        //Tự động nhận phiếu giảm giá
        app()->make(StoreCouponUserServices::class)->autoReceiveCoupon($user['uid'], $cartGroup);
        return $data;
    }

    /**
     * Thông tin đơn hàng được lưu vào bộ nhớ đệm
     * @param $uid
     * @param $cartInfo
     * @param $priceGroup
     * @param array $other
     * @param int $cacheTime
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function cacheOrderInfo($uid, $cartInfo, $priceGroup, $other = [], $cacheTime = 600)
    {
        $key = $this->getCacheKey();
        CacheService::set('user_order_' . $uid . $key, compact('cartInfo', 'priceGroup', 'other'), $cacheTime);
        return $key;
    }

    /**
     * Tạo đơn hàng bằng thuật toán bông tuyếtID
     * @return string
     * @throws \Exception
     */    public function getCacheKey(string $prefix = '')
    {
        $snowflake = new \Godruoyi\Snowflake\Snowflake();
        //32Chút
        if (PHP_INT_SIZE == 4) {
            $id = abs($snowflake->id());
        } else {
            $id = $snowflake->setStartTimeStamp(strtotime('2020-06-05') * 1000)->id();
        }
        return $prefix . $id;
    }

    /**Lấy số lần Khách hàng mua sản phẩm đang hoạt động
     * @param $uid
     * @param $seckill_id
     * @return int
     */    public function activityProductCount(array $where)
    {
        return $this->dao->count($where);
    }

    /**
     * Nhận thông tin bộ nhớ đệm của đơn hàng
     * @param int $uid
     * @param string $key
     * @return |null
     */    public function getCacheOrderInfo(int $uid, string $key)
    {
        $cacheName = 'user_order_' . $uid . $key;
        if (!CacheService::has($cacheName)) return null;
        return CacheService::get($cacheName);
    }

    /**
     * Nhận đơn đặt hàng nhómid
     * @param int $pid
     * @param int $uid
     * @return mixed
     */    public function getStoreIdPink(int $pid, int $uid)
    {
        return $this->dao->value(['uid' => $uid, 'pink_id' => $pid, 'is_del' => 0], 'order_id');
    }

    /**
     * Xác định xem có nhóm nhóm nào theo thứ tự hiện tại không
     * @param int $pid
     * @param int $uid
     * @return int
     */    public function getIsOrderPink($pid = 0, $uid = 0)
    {
        return $this->dao->count(['uid' => $uid, 'pink_id' => $pid, 'refund_status' => 0, 'is_del' => 0]);
    }

    /**
     * Xác định xem phương thức thanh toán có được bật hay không
     * @param $payType
     * @return bool
     */    public function checkPaytype(string $payType)
    {
        $res = false;
        switch ($payType) {
            case PayServices::WEIXIN_PAY:
                $res = sys_config('pay_weixin_open', '0') != '0';
                break;
            case PayServices::YUE_PAY:
                $res = sys_config('balance_func_status') && sys_config('yue_pay_status') == 1;
                break;
            case 'offline':
                $res = sys_config('offline_pay_status') == 1;
                break;
            case PayServices::ALIAPY_PAY:
                $res = sys_config('ali_pay_status', '0') != '0';
                break;
            case PayServices::FRIEND:
                $res = sys_config('friend_pay_status', 1) == 1;
                break;
            case PayServices::ALLIN_PAY:
                $res = sys_config('allin_pay_status') == 1;
                break;
            case PayServices::VN_COD:
                $res = (int)sys_config('vn_cod_pay_status', 2) === 1;
                break;
            case PayServices::VN_BANK:
                $res = (int)sys_config('vn_bank_pay_status', 2) === 1;
                break;
            case PayServices::VN_VNPAY:
                $res = (int)sys_config('vn_vnpay_pay_status', 2) === 1
                    && trim((string)sys_config('vn_vnpay_tmn_code', '')) !== ''
                    && trim((string)sys_config('vn_vnpay_hash_secret', '')) !== '';
                break;
            case PayServices::VN_MOMO:
                $res = (int)sys_config('vn_momo_pay_status', 2) === 1
                    && trim((string)sys_config('vn_momo_partner_code', '')) !== ''
                    && trim((string)sys_config('vn_momo_access_key', '')) !== ''
                    && trim((string)sys_config('vn_momo_secret_key', '')) !== '';
                break;
            case PayServices::VN_ZALOPAY:
                $res = (int)sys_config('vn_zalopay_pay_status', 2) === 1
                    && (int)sys_config('vn_zalopay_app_id', 0) > 0
                    && trim((string)sys_config('vn_zalopay_key1', '')) !== '';
                break;
        }
        return $res;
    }


    /**
     * Gán đơn hàng cho các phương thức không cổng (offline / COD / chuyển khoản VN).
     * Giữ đơn unpaid để cửa hàng đối soát hoặc thu tiền khi giao.
     *
     * @param string $orderId
     * @param string $payType offline|vn_cod|vn_bank
     * @return bool|\crmeb\basic\BaseModel
     */    public function setOrderTypePayOffline(string $orderId, string $payType = PayServices::OFFLINE_PAY)
    {
        $allowDeferred = [
            PayServices::OFFLINE_PAY,
            PayServices::VN_COD,
            PayServices::VN_BANK,
        ];
        if (!in_array($payType, $allowDeferred, true)) {
            $payType = PayServices::OFFLINE_PAY;
        }
        if (($count = strpos($orderId, '_')) !== false) {
            $orderId = substr($orderId, $count + 1);
        }
        $waivePostage = sys_config('offline_postage', 0) == 1
            && in_array($payType, [PayServices::OFFLINE_PAY, PayServices::VN_BANK], true);

        if ($waivePostage) {
            $orderInfo = $this->dao->get(['order_id' => $orderId]);
            $cartInfoService = app()->make(StoreOrderCartInfoServices::class);
            $cartInfo = $cartInfoService->getColumn(['oid' => $orderInfo['id']], 'cart_info', 'id');
            foreach ($cartInfo as $key => &$item) {
                $item_arr = json_decode($item, true);
                $item_arr['postage_price'] = $item_arr['origin_postage_price'] = 0;
                $cartInfoService->update(['id' => $key], ['cart_info' => json_encode($item_arr)]);
            }
            return $this->dao->update($orderId, [
                'pay_type' => $payType,
                'pay_price' => bcsub((string)$orderInfo['pay_price'], (string)$orderInfo['pay_postage'], 2),
                'pay_postage' => 0
            ], 'order_id');
        }
        return $this->dao->update($orderId, ['pay_type' => $payType], 'order_id');
    }

    /**
     * Xóa đơn hàng
     * @param string $uni
     * @param int $uid
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function removeOrder(string $uni, int $uid)
    {
        $order = $this->getUserOrderDetail($uni, $uid);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        $order = $this->tidyOrder($order);
        if ($order['_status']['_type'] != 0 && $order['_status']['_type'] != -2 && $order['_status']['_type'] != 4)
            throw new ApiException('Lệnh này không thể bị xóa');

        $order->is_del = 1;
        /** @var StoreOrderStatusServices $statusService */        $statusService = app()->make(StoreOrderStatusServices::class);
        $res = $statusService->save([
            'oid' => $order['id'],
            'change_type' => 'remove_order',
            'change_message' => 'Xóa đơn hàng',
            'change_time' => time()
        ]);
        if ($order->save() && $res) {
            return true;
        } else
            throw new ApiException('Hủy không thành công');
    }

    /**
     * Hủy đơn hàng
     * @param $order_id
     * @param $uid
     * @return bool|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function cancelOrder($order_id, int $uid)
    {
        $order = $this->dao->getOne(['order_id' => $order_id, 'uid' => $uid, 'is_del' => 0]);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        if ($order->is_cancel == 1) {
            throw new ApiException('Đơn hàng đã bị hủy, vui lòng không lặp lại thao tác！');
        }
        if ($order->paid) {
            throw new ApiException('Đơn hàng đã được thanh toán và không thể hủy được');
        }
        /** @var StoreOrderRefundServices $refundServices */        $refundServices = app()->make(StoreOrderRefundServices::class);

        $this->transaction(function () use ($refundServices, $order) {
            $res = $refundServices->integralAndCouponBack($order, 'cancel') && $refundServices->regressionStock($order);
            $order->is_cancel = 1;
            if (!($res && $order->save())) {
                throw new ApiException('Hủy không thành công');
            }
        });

        //Hủy đơn hàng sự kiện tùy chỉnh
        event('CustomEventListener', ['order_cancel', [
            'uid' => $uid,
            'id' => $order['id'],
            'order_id' => $order_id,
            'real_name' => $order['id'],
            'user_phone' => $order['id'],
            'user_address' => $order['id'],
            'total_num' => $order['id'],
            'pay_price' => $order['id'],
            'deduction_price' => $order['id'],
            'coupon_price' => $order['id'],
            'cancel_time' => date('Y-m-d H:i:s'),
        ]]);

        return true;
    }

    /**
     * Xác định hoàn thành đơn hàng
     * @param StoreProductReplyServices $replyServices
     * @param array $uniqueList
     * @param $oid
     * @return mixed
     */    public function checkOrderOver($replyServices, array $uniqueList, $oid)
    {
        //Tất cả các đánh giá của các hạng mục đơn hàng đã hoàn thành
        $replyServices->count(['unique' => $uniqueList, 'oid' => $oid]);
        if ($replyServices->count(['unique' => $uniqueList, 'oid' => $oid]) >= count($uniqueList)) {
            $res = $this->dao->update(['id' => $oid, 'status' => 2], ['status' => 3]);
            if (!$res) throw new ApiException('Sửa đổi không thành công');
            /** @var StoreOrderStatusServices $statusService */            $statusService = app()->make(StoreOrderStatusServices::class);
            $statusService->save([
                'oid' => $oid,
                'change_type' => 'check_order_over',
                'change_message' => 'Đánh giá của Khách hàng',
                'change_time' => time()
            ]);
            $order = $this->dao->get((int)$oid, ['id,pid,status']);
            if ($order && $order['pid'] > 0) {
                $p_order = $this->dao->get((int)$order['pid'], ['id,pid,status']);
                //Tất cả các đơn hàng chính đã được nhận và không có đơn hàng phụ nào được đánh giá và một số đã được hoàn thành.
                if ($p_order['status'] == 2 && !$this->dao->count(['pid' => $order['pid'], 'status' => 3]) && $this->dao->count(['pid' => $order['pid'], 'status' => 4])) {
                    $this->dao->update($p_order['id'], ['status' => 3]);
                    $statusService->save([
                        'oid' => $p_order['id'],
                        'change_type' => 'check_order_over',
                        'change_message' => 'Đánh giá của Khách hàng',
                        'change_time' => time()
                    ]);
                }
            }
        }
    }

    /**
     * Đơn đặt hàng của Khách hàng
     * @param int $uid
     * @param UserServices $userServices
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserOrderList(int $uid)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid, 'uid');
        if (!$user) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        [$page, $limit] = $this->getPageValue();
        $where = ['uid' => $uid, 'paid' => 1, 'refund_status' => 0, 'pid' => 0];
        $list = $this->dao->getStairOrderList($where, 'order_id,real_name,total_num,total_price,pay_price,FROM_UNIXTIME(pay_time,"%Y-%m-%d") as pay_time,paid,pay_type,pink_id,seckill_id,bargain_id', $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }


    /**
     * Nhận danh sách đặt hàng khuyến mãi
     * @param int $uid
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserStairOrderList(int $uid, $where)
    {
        $where_data = [];
        if (isset($where['type'])) {
            switch ((int)$where['type']) {
                case 1:
                    $where_data['spread_uid'] = $uid;
                    break;
                case 2:
                    $where_data['spread_two_uid'] = $uid;
                    break;
                case 3:
                    $where_data['division_id'] = $uid;
                    break;
                case 4:
                    $where_data['agent_id'] = $uid;
                    break;
                default:
                    $where_data['all_spread'] = $uid;
                    break;
            }
        }
        if (isset($where['data']) && $where['data']) {
            $where_data['time'] = $where['data'];
        }
        if (isset($where['order_id']) && $where['order_id']) {
            $where_data['order_id'] = $where['order_id'];
        }
        //Đơn hàng Affiliate chỉ hiển thị đơn hàng đã thanh toán và chưa được hoàn tiền
        $where_data['paid'] = 1;
        $where_data['refund_status'] = 0;
        $where_data['pid'] = 0;
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getStairOrderList($where_data, '*', $page, $limit);
        $count = $this->dao->count($where_data);
        return compact('list', 'count');
    }

    /**
     * Xuất đơn hàng
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExportList(array $where)
    {
        $list = $this->dao->search($where)->order('id desc')->select()->toArray();
        foreach ($list as &$item) {
            /** @var StoreOrderCartInfoServices $orderCart */            $orderCart = app()->make(StoreOrderCartInfoServices::class);
            $_info = $orderCart->getCartColunm(['oid' => $item['id']], 'cart_info', 'unique');
            foreach ($_info as $k => $v) {
                $cart_info = is_string($v) ? json_decode($v, true) : $v;
                if (!isset($cart_info['productInfo'])) $cart_info['productInfo'] = [];
                $_info[$k] = $cart_info;
                unset($cart_info);
            }
            $item['_info'] = $_info;
            /** @var WechatUserServices $wechatUserService */            $wechatUserService = app()->make(WechatUserServices::class);
            $item['sex'] = $wechatUserService->value(['uid' => $item['uid']], 'sex');
            if ($item['pink_id'] || $item['combination_id']) {
                /** @var StorePinkServices $pinkService */                $pinkService = app()->make(StorePinkServices::class);
                $pinkStatus = $pinkService->value(['order_id_key' => $item['id']], 'status');
                switch ($pinkStatus) {
                    case 1:
                        $item['pink_name'] = '[Đơn hàng mua chung]đang tiến hành';
                        $item['color'] = '#f00';
                        break;
                    case 2:
                        $item['pink_name'] = '[Đơn hàng mua chung]Hoàn thành';
                        $item['color'] = '#00f';
                        break;
                    case 3:
                        $item['pink_name'] = '[Đơn hàng mua chung]Chưa hoàn thành';
                        $item['color'] = '#f0f';
                        break;
                    default:
                        $item['pink_name'] = '[Đơn hàng mua chung]Lệnh lịch sử';
                        $item['color'] = '#457856';
                        break;
                }
            } elseif ($item['seckill_id']) {
                $item['pink_name'] = '[Đơn hàng Flash Sale]';
                $item['color'] = '#32c5e9';
            } elseif ($item['bargain_id']) {
                $item['pink_name'] = '[Đơn hàng mặc cả]';
                $item['color'] = '#12c5e9';
            } else {
                if ($item['shipping_type'] == 1) {
                    $item['pink_name'] = '[Đơn hàng thông thường]';
                    $item['color'] = '#895612';
                } else if ($item['shipping_type'] == 2) {
                    $item['pink_name'] = '[Xác nhận đơn hàng]';
                    $item['color'] = '#8956E8';
                }
            }
        }
        return $list;
    }

    /**
     * Tự động hủy đơn hàng
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function orderUnpaidCancel()
    {
        //Hệ thống Cài đặt trước khoảng thời gian hủy đơn hàng
        $keyValue = ['order_cancel_time', 'order_activity_time', 'order_bargain_time', 'order_seckill_time', 'order_pink_time'];
        //Nhận cấu hình
        $systemValue = SystemConfigService::more($keyValue);
        //Định dạng dữ liệu
        $systemValue = Arr::setValeTime($keyValue, is_array($systemValue) ? $systemValue : []);
        $list = $this->dao->getOrderUnPaidList();
        /** @var StoreOrderRefundServices $refundServices */        $refundServices = app()->make(StoreOrderRefundServices::class);
        foreach ($list as $order) {
            if ($order['pink_id'] || $order['combination_id']) {
                $secs = $systemValue['order_pink_time'] ?: $systemValue['order_activity_time'];
            } elseif ($order['seckill_id']) {
                $secs = $systemValue['order_seckill_time'] ?: $systemValue['order_activity_time'];
            } elseif ($order['bargain_id']) {
                $secs = $systemValue['order_bargain_time'] ?: $systemValue['order_activity_time'];
            } else {
                $secs = $systemValue['order_cancel_time'];
            }
            if ($secs == 0) return true;
            if (($order['add_time'] + bcmul($secs, '3600', 0)) < time()) {
                try {
                    $this->transaction(function () use ($order, $refundServices) {
                        //Trả lại điểm và phiếu giảm giá
                        $res = $refundServices->integralAndCouponBack($order, 'cancel');
                        //Khôi phục hàng tồn kho và doanh số bán hàng
                        $res = $res && $refundServices->regressionStock($order);
                        //Sửa đổi trạng thái đơn hàng
                        $res = $res && $this->dao->update($order['id'], ['is_cancel' => 1, 'mark' => 'Đơn hàng chưa được thanh toán quá thời gian quy định của hệ thống']);
                        if (!$res) {
                            Log::error('Số đơn hàng' . $order['order_id'] . 'Tự động hủy đơn hàng không thành công');
                        }
                        return true;
                    });

                    /** @var StoreOrderCartInfoServices $cartServices */                    $cartServices = app()->make(StoreOrderCartInfoServices::class);
                    $cartInfo = $cartServices->getOrderCartInfo((int)$order['id']);

                } catch (\Throwable $e) {
                    Log::error('Tự động hủy đơn hàng không thành công,Lý do thất bại:' . $e->getMessage(), $e->getTrace());
                }
            }
        }
    }

    /**Lấy doanh số đơn hàng hôm nay hoặc hôm qua theo thời gian
     * @param array $where
     * @return float|int
     */    public function getOrderMoneyByWhere(array $where, string $sum_field, string $selectType, string $group = "")
    {

        switch ($selectType) {
            case "sum" :
                return $this->dao->getDayTotalMoney($where, $sum_field);
            case "group" :
                return $this->dao->getDayGroupMoney($where, $sum_field, $group);
        }
    }

    /**Số lượng đơn hàng Trong khoảng thời gian thống kê
     * @param array $where
     * @param string $sum_field
     */    public function getOrderCountByWhere(array $where)
    {
        return $this->dao->getDayOrderCount($where);
    }

    /**Số lượng đơn hàng Trong khoảng thời gian thống kê nhóm
     * @param $where
     * @return mixed
     */    public function getOrderGroupCountByWhere($where)
    {
        return $this->dao->getOrderGroupCount($where);
    }

    /** Số người thanh toán đơn hàng Trong khoảng thời gian
     * @param $where
     * @return mixed
     */    public function getPayOrderPeopleByWhere($where)
    {
        return $this->dao->getPayOrderPeople($where);
    }

    /**Thống kê nhóm khoảng thời gian về số người thanh toán đơn hàng
     * @param $where
     * @return mixed
     */    public function getPayOrderGroupPeopleByWhere($where)
    {
        return $this->dao->getPayOrderGroupPeople($where);
    }

    /**
     * Danh sách đơn hàng hoàn tiền
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function refundList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        if ($where['refund_reason_time'] != '') $where['refund_reason_time'] = explode('-', $where['refund_reason_time']);
        $data = $this->dao->getRefundList($where, $page, $limit);
        if ($data['list']) $data['list'] = $this->tidyOrderList($data['list']);
        $data['num'] = [
            0 => ['name' => 'Tất cả', 'num' => $this->dao->count(['refund_type' => 0, 'is_system_del' => 0])],
            1 => ['name' => 'Chỉ hoàn tiền', 'num' => $this->dao->count(['refund_type' => 1, 'is_system_del' => 0])],
            2 => ['name' => 'Trả lại và hoàn tiền', 'num' => $this->dao->count(['refund_type' => 2, 'is_system_del' => 0])],
            3 => ['name' => 'Từ chối hoàn tiền', 'num' => $this->dao->count(['refund_type' => 3, 'is_system_del' => 0])],
            4 => ['name' => 'Hàng chờ trả lại', 'num' => $this->dao->count(['refund_type' => 4, 'is_system_del' => 0])],
            5 => ['name' => 'Trả lại chờ nhận', 'num' => $this->dao->count(['refund_type' => 5, 'is_system_del' => 0])],
            6 => ['name' => 'Đã hoàn tiền', 'num' => $this->dao->count(['refund_type' => 6, 'is_system_del' => 0])]
        ];
        return $data;
    }

    /**
     * Người bán đồng ý hoàn tiền và chờ khách hàng trả lại hàng
     * @param $order_id
     * @return bool
     */    public function agreeRefund($order_id)
    {
        $res = $this->dao->update(['id' => $order_id], ['refund_type' => 4]);
        /** @var StoreOrderStatusServices $statusService */        $statusService = app()->make(StoreOrderStatusServices::class);
        $statusService->save([
            'oid' => $order_id,
            'change_type' => 'refund_express',
            'change_message' => 'Đang chờ Khách hàng quay lại',
            'change_time' => time()
        ]);
        if ($res) return true;
        throw new AdminException('Thao tác không thành công');
    }

    /**
     * @param array $where
     * @param array|string[] $field
     * @param array $with
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSplitOrderList(array $where, array $field = ['*'], array $with = [], $page = 0, $limit = 0, $order = 'pay_time DESC,id DESC')
    {
        $data = $this->dao->getOrderList($where, $field, $page, $limit, $with, $order);
        if ($data) {
            $data = $this->tidyOrderList($data);
        }
        return $data;
    }

    /**
     * Chi tiết thanh toán
     * @param $orderId
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getFriendDetail($orderId, $uid)
    {
        $orderInfo = $this->dao->getOne(['id' => $orderId, 'is_del' => 0]);
        if ($orderInfo) {
            $orderInfo = $orderInfo->toArray();
        } else {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        $orderInfo = $this->tidyOrder($orderInfo, true);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->get($orderInfo['uid']);
        $friendInfo = $userServices->get($orderInfo['pay_uid']);
        $info = [
            'id' => $orderInfo['id'],
            'order_id' => $orderInfo['order_id'],
            'uid' => $orderInfo['uid'],
            'avatar' => $userInfo['avatar'],
            'nickname' => $userInfo['nickname'],
            'cartInfo' => $orderInfo['cartInfo'],
            'paid' => $orderInfo['paid'],
            'total_num' => $orderInfo['total_num'],
            'pay_price' => $orderInfo['pay_price'],
            'type' => $uid == $orderInfo['uid'] ? 0 : 1,
            'pay_uid' => isset($friendInfo) ? $friendInfo['uid'] : 0,
            'pay_nickname' => isset($friendInfo) ? $friendInfo['nickname'] : '',
            'pay_avatar' => isset($friendInfo) ? $friendInfo['avatar'] : '',
        ];
        return $info;
    }

    /**
     * Lấy danh sách các mặt hàng bị trả lại
     * @param array $cart_ids
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function refundCartInfoList(array $cart_ids = [], int $id = 0)
    {
        $orderInfo = $this->dao->get($id);
        if (!$orderInfo) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        $orderInfo = $this->tidyOrder($orderInfo, true);
        $cartInfo = $orderInfo['cartInfo'] ?? [];
        $data = [];
        if ($cart_ids) {
            foreach ($cart_ids as $cart) {
                if (!isset($cart['cart_id']) || !$cart['cart_id'] || !isset($cart['cart_num']) || !$cart['cart_num'] || $cart['cart_num'] <= 0) {
                    throw new ApiException('Vui lòng chọn lại sản phẩm hoàn tiền hoặc số lượng sản phẩm');
                }
            }
            $cart_ids = array_combine(array_column($cart_ids, 'cart_id'), $cart_ids);
            $i = 0;
            foreach ($cartInfo as $item) {
                if (isset($cart_ids[$item['id']])) {
                    $data['cartInfo'][$i] = $item;
                    if (isset($cart_ids[$item['id']]['cart_num'])) $data['cartInfo'][$i]['cart_num'] = $cart_ids[$item['id']]['cart_num'];
                    $i++;
                }
            }
        }
        $data['_status'] = $orderInfo['_status'] ?? [];
        $data['_status']['_is_back'] = $orderInfo['delivery_type'] != 'fictitious' && $orderInfo['virtual_type'] == 0;
        $data['cartInfo'] = $data['cartInfo'] ?? $cartInfo;
        return $data;
    }

    /**
     * Đơn hàng lại
     * @param string $uni
     * @param int $uid
     * @return array
     */    public function againOrder(StoreCartServices $services, string $uni, int $uid): array
    {
        if (!$uni) throw new ApiException('Lỗi tham số');
        $order = $this->getUserOrderDetail($uni, $uid);
        if (!$order) throw new ApiException('Đơn hàng không tồn tại');
        $order = $this->tidyOrder($order, true);
        $cateId = [];

        foreach ($order['cartInfo'] as $v) {
            if ($v['combination_id']) throw new ApiException('Bạn không thể đặt hàng khác cho các sản phẩm đã tham gia nhóm. Vui lòng tự mình đặt hàng trong các sản phẩm đã tham gia nhóm.');
            elseif ($v['bargain_id']) throw new ApiException('Các mặt hàng đã mặc cả không thể đặt lại được, vui lòng tự mình đặt hàng trong số các mặt hàng đã mặc cả.');
            elseif ($v['seckill_id']) throw new ApiException('Các mặt hàng flash sale không thể được đặt cho đơn hàng khác. Vui lòng đặt hàng trong mục flash sale.');
            elseif ($v['advance_id']) throw new ApiException('Các mặt hàng bán trước không thể được đặt lại, vui lòng đặt hàng trong các mặt hàng bán trước.');
            else $cateId[] = $services->setCart($uid, (int)$v['product_id'], (int)$v['cart_num'], $v['productInfo']['attrInfo']['unique'] ?? '', '0', true);
        }
        if (!$cateId) throw new ApiException('Nếu đơn hàng khác không thành công, vui lòng đặt đơn hàng khác.');
        return $cateId;
    }

    /**
     * Alipay thanh toán riêng
     * @param OrderPayServices $payServices
     * @param OtherOrderServices $services
     * @param string $key
     * @param string $quitUrl
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function aliPayOrder(OrderPayServices $payServices, OtherOrderServices $services, string $key, string $quitUrl)
    {
        if (!$key) {
            throw new ApiException('Lỗi tham số');
        }
        if (!$quitUrl) {
            throw new ApiException('Lỗi tham số');
        }

        $orderCache = CacheService::get($key);
        if (!$orderCache || !isset($orderCache['order_id'])) {
            throw new ApiException('Đơn hàng không thể được thanh toán');
        }

        $payType = isset($orderCache['other_pay_type']) && $orderCache['other_pay_type'] == true;
        if ($payType) {
            $orderInfo = $services->getOne(['order_id' => $orderCache['order_id'], 'is_del' => 0, 'paid' => 0]);
        } else {
            $orderInfo = $this->get(['order_id' => $orderCache['order_id'], 'paid' => 0, 'is_del' => 0]);
        }

        if (!$orderInfo) {
            throw new ApiException('Trạng thái thanh toán đơn hàng không chính xác và không thể thực hiện thanh toán.');
        }
        return $payServices->beforePay($orderInfo->toArray(), PayServices::ALIAPY_PAY, ['quitUrl' => $quitUrl]);
    }

    /**
     * Thông tin đơn hàng của Khách hàng
     * @param StoreOrderEconomizeServices $services
     * @param string $uni
     * @param int $uid
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserOrderByKey(StoreOrderEconomizeServices $services, string $uni, int $uid): array
    {
        $order = $this->getUserOrderDetail($uni, $uid, ['split', 'invoice', 'user']);
        if (!$order) throw new ApiException('Sản phẩm không tồn tại');
        $order = $order->toArray();
        $splitNum = [];
        //Có bật tính năng nhận hàng tại cửa hàng hay không
        $store_self_mention = sys_config('store_self_mention');
        //Sau khi đóng cửa hàng tự lấy hàng, thông tin cửa hàng bị ẩn trong đơn hàng
        if ($store_self_mention == 0) $order['shipping_type'] = 1;
        if ($order['verify_code']) {
            $verify_code = $order['verify_code'];
            $verify[] = substr($verify_code, 0, 4);
            $verify[] = substr($verify_code, 4, 4);
            $verify[] = substr($verify_code, 8);
            $order['_verify_code'] = implode(' ', $verify);
        }
        $order['add_time_y'] = date('Y-m-d', $order['add_time']);
        $order['add_time_h'] = date('H:i:s', $order['add_time']);
        $order['system_store'] = false;
        if ($order['store_id']) {
            /** @var SystemStoreServices $storeServices */            $storeServices = app()->make(SystemStoreServices::class);
            $order['system_store'] = $storeServices->getStoreDispose($order['store_id']);
        }
        $order['code'] = '';
        if (($order['shipping_type'] === 2 || $order['delivery_uid'] != 0) && $order['verify_code']) {
//            $name = $order['verify_code'] . '.jpg';
//            /** @var SystemAttachmentServices $attachmentServices */
//            $attachmentServices = app()->make(SystemAttachmentServices::class);
//            $imageInfo = $attachmentServices->getInfo(['name' => $name]);
//            $siteUrl = sys_config('site_url');
//            if (!$imageInfo) {
//                $imageInfo = PosterServices::getQRCodePath($order['verify_code'], $name);
//                if (is_array($imageInfo)) {
//                    $attachmentServices->attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
//                    $url = $imageInfo['dir'];
//                } else
//                    $url = '';
//            } else $url = $imageInfo['att_dir'];
//            if (isset($imageInfo['image_type']) && $imageInfo['image_type'] == 1) $url = $siteUrl . $url;
//            $order['code'] = $url;
            try {
                $verifyName = 'verify_code_' . $order['verify_code'] . '.jpg';
                $data = 'verify_code=' . $order['verify_code'];
                /** @var SystemAttachmentServices $systemAttachmentService */                $systemAttachmentService = app()->make(SystemAttachmentServices::class);
                $imageInfo = $systemAttachmentService->getOne(['name' => $verifyName]);
                $siteUrl = sys_config('site_url');
                if (!$imageInfo) {
                    $res = MiniProgramService::appCodeUnlimitService($data, 'pages/admin/order_cancellation/index', 280);
                    if (!$res) throw new ApiException('Việc tạo mã xác minh chương trình nhỏ không thành công');
                    $uploadType = (int)sys_config('upload_type', 1);
                    $upload = UploadService::init();
                    $res = (string)EntityBody::factory($res);
                    $res = $upload->to('routine/product')->validate()->setAuthThumb(false)->stream($res, $verifyName);
                    if ($res === false) throw new ApiException('Việc tạo mã xác minh chương trình nhỏ không thành công');
                    $imageInfo = $upload->getUploadInfo();
                    $imageInfo['image_type'] = $uploadType;
                    if ($imageInfo['image_type'] == 1) $remoteImage = PosterServices::remoteImage($siteUrl . $imageInfo['dir']);
                    else $remoteImage = PosterServices::remoteImage($imageInfo['dir']);
                    if (!$remoteImage['status']) throw new ApiException('Việc tạo mã xác minh chương trình nhỏ không thành công');
                    $systemAttachmentService->save([
                        'name' => $imageInfo['name'],
                        'att_dir' => $imageInfo['dir'],
                        'satt_dir' => $imageInfo['thumb_path'],
                        'att_size' => $imageInfo['size'],
                        'att_type' => $imageInfo['type'],
                        'image_type' => $imageInfo['image_type'],
                        'module_type' => 2,
                        'time' => time(),
                        'pid' => 1,
                        'type' => 2
                    ]);
                    $url = $imageInfo['dir'];
                } else $url = $imageInfo['att_dir'];
                if ($imageInfo['image_type'] == 1) $url = $siteUrl . $url;
                $order['code'] = $url;
            } catch (\Exception $e) {
            }
        }
        $order['mapKey'] = sys_config('tengxun_map_key');
        $order['yue_pay_status'] = (int)sys_config('balance_func_status') && (int)sys_config('yue_pay_status') == 1 ? (int)1 : (int)2;//Thanh toán số dư 1 tặng 2
        $order['pay_weixin_open'] = sys_config('pay_weixin_open') != '0';//WeChat Trả 1 Bật 0 Tắt
        $order['ali_pay_status'] = sys_config('ali_pay_status', '0') != '0';//Gói thanh toán thanh toán 1 tặng 0 giảm
        $order['friend_pay_status'] = (int)sys_config('friend_pay_status') ?? 0;//Bạn bè thanh toán thay mặt 1 Trên 0 Tắt
        $orderData = $this->tidyOrder($order, true, true);
        $vipTruePrice = $memberPrice = $levelPrice = 0;
        foreach ($orderData['cartInfo'] ?? [] as $key => $cart) {
            $vipTruePrice = bcadd((string)$vipTruePrice, (string)$cart['vip_sum_truePrice'], 2);
            if ($cart['price_type'] == 'member') $memberPrice = bcadd((string)$memberPrice, (string)$cart['vip_sum_truePrice'], 2);
            if ($cart['price_type'] == 'level') $levelPrice = bcadd((string)$levelPrice, (string)$cart['vip_sum_truePrice'], 2);
            if (isset($splitNum[$cart['id']])) {
                $orderData['cartInfo'][$key]['cart_num'] = $cart['cart_num'] - $splitNum[$cart['id']];
                if ($orderData['cartInfo'][$key]['cart_num'] == 0) unset($orderData['cartInfo'][$key]);
            }
        }
        $orderData['cartInfo'] = array_merge($orderData['cartInfo']);
        $orderData['vip_true_price'] = $vipTruePrice;
        $orderData['levelPrice'] = $levelPrice;
        $orderData['memberPrice'] = $memberPrice;
        $economize = $services->get(['order_id' => $order['order_id']], ['postage_price', 'member_price']);
        if ($economize) {
            $orderData['postage_price'] = $economize['postage_price'];
            $orderData['member_price'] = $economize['member_price'];
        } else {
            $orderData['postage_price'] = 0;
            $orderData['member_price'] = 0;
        }
        $orderData['routine_contact_type'] = sys_config('routine_contact_type', 0);
        /** @var UserInvoiceServices $userInvoice */        $userInvoice = app()->make(UserInvoiceServices::class);
        $invoice_func = $userInvoice->invoiceFuncStatus();
        $orderData['invoice_func'] = $invoice_func['invoice_func'];
        $orderData['special_invoice'] = $invoice_func['special_invoice'];
        $orderData['refund_cartInfo'] = $orderData['cartInfo'];
        $orderData['refund_total_num'] = $orderData['total_num'];
        $orderData['refund_pay_price'] = $orderData['pay_price'];
        $orderData['is_apply_refund'] = true;
        $orderData['help_info'] = [
            'pay_uid' => $orderData['pay_uid'],
            'pay_nickname' => '',
            'pay_avatar' => '',
            'help_status' => 0
        ];
        $orderData['gift_user_info'] = [
            'gift_uid' => $orderData['gift_uid'],
            'gift_nickname' => '',
            'gift_avatar' => '',
        ];
        if ($orderData['uid'] != $orderData['pay_uid']) {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $payUser = $userServices->get($orderData['pay_uid'], ['nickname', 'avatar']);
            $orderData['help_info'] = [
                'pay_uid' => $orderData['pay_uid'],
                'pay_nickname' => $payUser['nickname'],
                'pay_avatar' => $payUser['avatar'],
                'help_status' => 1
            ];
        }
        if ($orderData['gift_uid'] != 0) {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $giftUser = $userServices->get($orderData['gift_uid'], ['nickname', 'avatar']);
            $orderData['gift_user_info'] = [
                'gift_uid' => $orderData['gift_uid'],
                'gift_nickname' => $giftUser['nickname'],
                'gift_avatar' => $giftUser['avatar'],
            ];
        }
        // Xác định xem có bật quản lý đơn hàng chương trình nhỏ hay không
        $orderData['order_shipping_open'] = false;
        if (sys_config('order_shipping_open', 0) && $order['pay_price'] > 0 && $order['is_channel'] == 1 && $order['pay_type'] == 'weixin' && MiniOrderService::isManaged()) {
            // Xác định có đơn hàng phụ nào chưa nhận được hàng không
            if ($order['pid'] > 0) {
                if ($this->checkSubOrderNotTake((int)$order['pid'], (int)$order['id'])) {
                    $orderData['order_shipping_open'] = true;
                }
            } else {
                $orderData['order_shipping_open'] = true;
            }

        }
        $orderData['is_refund_available'] = $this->isRefundAvailable((int)$order['id']);

        $orderData['gift_key'] = $orderData['gift_code'] = '';
        if ($order['is_gift'] == 1) {
            $orderData['gift_key'] = md5($order['id'] . '_' . $order['order_id'] . '_' . $order['uid']);
            /** @var QrcodeServices $qrcodeService */            $qrcodeService = app()->make(QrcodeServices::class);
            $orderData['gift_code'] = $qrcodeService->getRoutineQrcodePath($order['id'], $order['uid'], 7, ['gift_key' => $orderData['gift_key']]);
        }
        $orderData['avatar'] = set_file_url($orderData['avatar']);
        return $orderData;
    }

    /**
     * Kiểm tra xem đơn hàng có thể được hoàn tiền hay không
     * @param $oid
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/10/11
     */    public function isRefundAvailable($oid)
    {
        $refundTimeAvailable = (int)sys_config('refund_time_available');
        if ($refundTimeAvailable == 0) return true;
        $statusInfo = app()->make(StoreOrderStatusServices::class)->get(['oid' => $oid, 'change_type' => 'take_delivery']);
        if (!$statusInfo) return true;
        $changeTime = preg_match('/^\d+$/', $statusInfo['change_time']) ? intval($statusInfo['change_time']) : strtotime($statusInfo['change_time']);
        if (($changeTime + ($refundTimeAvailable * 86400)) < time()) {
            return false;
        }
        return true;
    }

    /**
     * Biết liệu chuyển phát nhanh và nhận hàng tại cửa hàng có hiển thị trên trang xác nhận đơn hàng hay không
     * @param $uid
     * @param $cartIds
     * @param $new
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function checkShipping($uid, $cartIds, $new)
    {
        if ($new) {
            $cartIds = explode(',', $cartIds);
            $cartInfo = [];
            foreach ($cartIds as $key) {
                $info = CacheService::get($key);
                if ($info) {
                    $cartInfo[] = $info;
                }
            }
        } else {
            /** @var StoreCartServices $cartServices */            $cartServices = app()->make(StoreCartServices::class);
            $cartInfo = $cartServices->getCartList(['uid' => $uid, 'status' => 1, 'id' => $cartIds], 0, 0, ['productInfo', 'attrInfo']);
        }
        if (!$cartInfo) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        $arr = [];
        foreach ($cartInfo as $item) {
            $arr[] = $item['productInfo']['logistics'];
        }
        $res = array_unique(explode(',', implode(',', $arr)));
        if (count($res) == 2) {
            return ['type' => 0];
        } else {
            if ($res[0] == 2 && sys_config('store_self_mention') == 0) {
                return ['type' => 1];
            }
            return ['type' => (int)$res[0]];
        }
    }

    /**
     * Đánh giá tự động
     * @return bool
     */    public function autoComment()
    {
        //Ngày đánh giá tự động
        $systemCommentTime = sys_config('system_comment_time', 0);
        //0Để hủy chức năng khen ngợi mặc định tự động
        if ($systemCommentTime == 0) {
            return true;
        }
        $sevenDay = bcsub((string)time(), bcmul((string)$systemCommentTime, '86400'));
        /** @var StoreOrderStoreOrderStatusServices $service */        $service = app()->make(StoreOrderStoreOrderStatusServices::class);
        $orderList = $service->getTakeOrderIds([
            'change_time' => $sevenDay,
            'is_del' => 0,
            'paid' => 1,
            'status' => 2,
            'change_type' => ['take_delivery', 'user_take_delivery']
        ], 30);
        foreach ($orderList as $item) {
            AutoCommentJob::dispatch([$item['id'], $item['cart_id']]);
        }
        return true;
    }

    /**
     * @param int $uid
     * @param string $orderId
     * @param string $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/13
     */    public function getCashierInfo(int $uid, string $orderId, string $type)
    {
        //Chuyển đổi loại thanh toán
        $data = [
            'ali_pay_status' => sys_config('ali_pay_status', '0') != '0',
            'wechat_pay_status' => sys_config('pay_weixin_open', '0') != '0',
            'offline_pay_status' => (int)sys_config('offline_pay_status') == 1,
            'vn_cod_pay_status' => (int)sys_config('vn_cod_pay_status', 2) === 1,
            'vn_bank_pay_status' => (int)sys_config('vn_bank_pay_status', 2) === 1,
            'vn_bank_pay_guide' => (string)sys_config('vn_bank_pay_guide', ''),
            'vn_bank_pay_qr_image' => (string)sys_config('vn_bank_pay_qr_image', ''),
            'friend_pay_status' => (int)sys_config('friend_pay_status') == 1,
            'yue_pay_status' => (int)sys_config('balance_func_status') && (int)sys_config('yue_pay_status') == 1,
        ];

        $data['order_id'] = $orderId;
        $data['pay_price'] = '0';
        $data['now_money'] = app()->make(UserServices::class)->value(['uid' => $uid], 'now_money');

        switch ($type) {
            case 'order':
                $info = $this->dao->get(['order_id' => $orderId], ['id', 'pay_price', 'add_time', 'combination_id', 'seckill_id', 'bargain_id', 'pay_postage', 'is_gift']);
                if (!$info) {
                    throw new PayException('Đơn hàng bạn đã thanh toán không tồn tại');
                }
                $orderCancelTime = sys_config('order_cancel_time', 0);
                $orderActivityTime = sys_config('order_activity_time', 0);
                if ($info->combination_id) {
                    $time = (sys_config('order_pink_time', 0) ?: $orderActivityTime) * 60 * 60 + ((int)$info->add_time);
                } else if ($info->seckill_id) {
                    $time = (sys_config('order_seckill_time', 0) ?: $orderActivityTime) * 60 * 60 + ((int)$info->add_time);
                } else if ($info->bargain_id) {
                    $time = (sys_config('order_bargain_time', 0) ?: $orderActivityTime) * 60 * 60 + ((int)$info->add_time);
                } else {
                    $time = $orderCancelTime * 60 * 60 + ((int)$info->add_time);
                }

                if ($time < 0) {
                    $time = 0;
                }

                $data['pay_price'] = $info['pay_price'];
                $data['pay_postage'] = $info['pay_postage'];
                $data['offline_postage'] = (int)sys_config('offline_postage', 0);
                $data['invalid_time'] = $time;
                $data['oid'] = $info['id'];
                $data['is_gift'] = $info['is_gift'];

                break;
            case 'svip':
                $info = app()->make(OtherOrderServices::class)->get(['order_id' => $orderId], ['id', 'pay_price', 'add_time']);
                if (!$info) {
                    throw new PayException('Đơn hàng bạn đã thanh toán không tồn tại');
                }
                $data['pay_price'] = $info['pay_price'];
                $data['invalid_time'] = $info->add_time + 86400;
                break;
            case 'recharge':
                $info = app()->make(UserRechargeServices::class)->get(['order_id' => $orderId], ['id', 'price', 'add_time']);
                if (!$info) {
                    throw new PayException('Đơn hàng bạn đã thanh toán không tồn tại');
                }
                $data['pay_price'] = $info['price'];
                $data['invalid_time'] = $info->add_time + 86400;
                break;
            default:
                throw new PayException('Các loại thanh toán đơn hàng khác hiện không được hỗ trợ.');
        }

        return $data;
    }

    /**
     * Hủy vận chuyển của người bán
     * @param int $id
     * @param string $msg
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/15
     */    public function shipmentCancelOrder(int $id, string $msg)
    {
        $orderInfo = $this->dao->get($id);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng bị hủy không tồn tại');
        }
        if (!$orderInfo->kuaidi_task_id || !$orderInfo->kuaidi_order_id) {
            throw new AdminException('Thông tin đơn hàng vận chuyển của người bán không tồn tại và không thể hủy được');
        }
        if ($orderInfo->is_stock_up != 1) {
            throw new AdminException('Trạng thái đơn hàng không chính xác và lô hàng không thể bị hủy.');
        }

        //Bắt đầu hủy lô hàng của người bán
        app()->make(ServeServices::class)->express()->shipmentCancelOrder([
            'task_id' => $orderInfo->kuaidi_task_id,
            'order_id' => $orderInfo->kuaidi_order_id,
            'cancel_msg' => $msg,
        ]);

        //Đơn hàng trở về trạng thái ban đầu
        $this->transaction(function () use ($id, $msg, $orderInfo) {
            app()->make(StoreOrderStatusServices::class)->save([
                'oid' => $id,
                'change_time' => time(),
                'change_type' => 'delivery_goods_cancel',
                'change_message' => 'Lô hàng bị hủy, lý do hủy：' . $msg
            ]);

            $orderInfo->status = 0;
            $orderInfo->is_stock_up = 0;
            $orderInfo->kuaidi_task_id = '';
            $orderInfo->kuaidi_order_id = '';
            $orderInfo->express_dump = '';
            $orderInfo->kuaidi_label = '';
            $orderInfo->delivery_id = '';
            $orderInfo->delivery_code = '';
            $orderInfo->delivery_name = '';
            $orderInfo->delivery_type = '';
            $orderInfo->save();
        });

        return true;
    }

    /**
     * Xác định xem Tất cả các đơn đặt hàng đã được chuyển đi chưa
     * @param int $pid
     * @param int $order_id
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/31
     */    public function checkSubOrderNotSend(int $pid, int $order_id)
    {
        $order_count = $this->dao->getSubOrderNotSend($pid, $order_id);
        if ($order_count > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Xác định có đơn hàng phụ nào chưa nhận được hàng không
     * @param int $pid
     * @param int $order_id
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/31
     */    public function checkSubOrderNotTake(int $pid, int $order_id)
    {
        $order_count = $this->dao->getSubOrderNotTake($pid, $order_id);
        if ($order_count > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Dữ liệu lệnh phân phối
     * @param $oid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/10/11
     */    public function printShippingData($order_id)
    {
        $orderInfo = $this->dao->get(['order_id' => $order_id]);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        $orderInfo = $this->tidyOrder($orderInfo->toArray(), true);
        $data['user_name'] = $orderInfo['real_name'];
        $data['user_phone'] = $orderInfo['user_phone'];
        $data['user_address'] = $orderInfo['user_address'];
        $data['order_id'] = $orderInfo['order_id'];
        $data['pay_time'] = $orderInfo['_pay_time'];
        $data['pay_type'] = $orderInfo['_status']['_payType'];
        $data['pay_price'] = $orderInfo['pay_price'];
        $data['pay_postage'] = $orderInfo['pay_postage'];
        $data['deduction_price'] = $orderInfo['deduction_price'];
        $data['coupon_price'] = $orderInfo['coupon_price'];
        $data['mark'] = $orderInfo['mark'];
        $data['product_info'] = [];
        $data['vip_price'] = 0;
        foreach ($orderInfo['cartInfo'] as $item) {
            $data['product_info'][] = [
                'name' => $item['productInfo']['store_name'],
                'sku' => $item['attrInfo']['suk'],
                'price' => $item['sum_price'],
                'num' => $item['cart_num'],
                'sum_price' => bcmul((string)$item['sum_price'], (string)$item['cart_num'], 2)
            ];
            $data['vip_price'] = bcadd((string)$data['vip_price'], $item['vip_sum_truePrice'], 2);
        }
        return $data;
    }

    public function giftDetail($oid)
    {
        $orderInfo = $this->dao->getOne(['id' => $oid, 'is_del' => 0]);
        if ($orderInfo) {
            $orderInfo = $orderInfo->toArray();
        } else {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        $orderInfo = $this->tidyOrder($orderInfo, true);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->get($orderInfo['uid']);
        $arr = [];
        foreach ($orderInfo['cartInfo'] as $cartInfo) {
            $arr[] = $cartInfo['productInfo']['logistics'];
        }
        $res = array_unique(explode(',', implode(',', $arr)));
        if (count($res) == 2) {
            $type = 0;
        } else {
            if ($res[0] == 2 && sys_config('store_self_mention') == 0) {
                $type = 1;
            } else {
                $type = (int)$res[0];
            }
        }
        return [
            'id' => $orderInfo['id'],
            'order_id' => $orderInfo['order_id'],
            'uid' => $orderInfo['uid'],
            'avatar' => set_file_url($userInfo['avatar']),
            'nickname' => $userInfo['nickname'],
            'cartInfo' => $orderInfo['cartInfo'],
            'paid' => $orderInfo['paid'],
            'total_num' => $orderInfo['total_num'],
            'pay_price' => $orderInfo['pay_price'],
            'gift_key' => md5($orderInfo['id'] . '_' . $orderInfo['order_id'] . '_' . $orderInfo['uid']),
            'gift_mark' => $orderInfo['gift_mark'],
            'gift_uid' => $orderInfo['gift_uid'],
            'refund_status' => $orderInfo['refund_status'],
            'type' => $type,
            'store_self_mention' => (int)sys_config('store_self_mention') ?? 0,//Có bật tính năng nhận tại cửa hàng không?
        ];
    }

    public function receiveGift($uid, $oid, $gift_key, $shipping_type, $name, $phone, $address_id = 0, $store_id = 0)
    {
        $orderInfo = $this->dao->get($oid);
        if (!$orderInfo) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        if ($gift_key != md5($orderInfo['id'] . '_' . $orderInfo['order_id'] . '_' . $orderInfo['uid'])) {
            throw new AdminException('Không thể thu thập');
        }
        if ($orderInfo['refund_status'] != 0) {
            throw new AdminException('Đơn hàng đã được hoàn lại');
        }
        if ($orderInfo['uid'] == $uid) {
            throw new AdminException('Không thể nhận quà tặng của riêng bạn');
        }
        if ($orderInfo['gift_uid'] != 0 && $orderInfo['gift_uid'] != $uid) {
            return false;
        }
        $address = '';
        if ($shipping_type == 1 && $address_id) {
            $addressInfo = app()->make(UserAddressServices::class)->getOne(['uid' => $uid, 'id' => $address_id, 'is_del' => 0]);
            $name = $addressInfo['real_name'];
            $phone = $addressInfo['phone'];
            $address = $addressInfo['province'] . ' ' . $addressInfo['city'] . ' ' . $addressInfo['district'] . ' ' . $addressInfo['detail'];
        }
        $verify_code = '';
        if ($shipping_type == 2 && $store_id) {
            $store_id = app()->make(SystemStoreServices::class)->getStoreDispose($store_id, 'id');
            if (!$store_id) throw new ApiException('Chọn sai cửa hàng');
            $verify_code = app()->make(StoreOrderCreateServices::class)->getStoreCode();
        }
        $orderData = [
            'gift_uid' => $uid,
            'real_name' => $name,
            'user_phone' => $phone,
            'user_address' => $address,
            'shipping_type' => $shipping_type,
            'store_id' => $store_id,
            'verify_code' => $verify_code,
        ];
        $this->dao->update($oid, $orderData);
        return true;
    }

    /**
     * Sửa đổi địa chỉ đặt hàng
     * @param $id
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/9/8
     */    public function editAddress($id, $data)
    {
        $orderInfo = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
        if (!$orderInfo) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        if ($orderInfo['status'] > 0) {
            throw new ApiException('Đơn hàng đã được chuyển đi và địa chỉ không thể sửa đổi được.');
        }
        $this->dao->update($id, [
            'real_name' => $data['real_name'],
            'user_phone' => $data['user_phone'],
            'user_address' => $data['user_address'],
        ]);
        return true;
    }

    /**
     * Nhãn lý do hủy đơn (admin) — key cố định cho form
     */    public static function adminCancelReasonLabels(): array
    {
        return [
            'customer_change' => 'Khách đổi ý / không mua nữa',
            'wrong_product' => 'Đặt nhầm sản phẩm hoặc số lượng',
            'duplicate_order' => 'Đặt trùng đơn hàng',
            'out_of_stock' => 'Hết hàng / không đủ tồn kho',
            'cannot_deliver' => 'Không giao được đến địa chỉ',
            'payment_issue' => 'Không nhận được thanh toán',
            'other' => 'Khác (nhập Nội dung bên dưới)',
        ];
    }

    /**
     * @throws AdminException
     */    public function adminBuildCancelMessage(string $reasonKey, string $customReason = ''): string
    {
        $labels = self::adminCancelReasonLabels();
        if (!isset($labels[$reasonKey])) {
            throw new AdminException('Lý do hủy không hợp lệ');
        }
        if ($reasonKey === 'other') {
            $customReason = trim($customReason);
            if ($customReason === '') {
                throw new AdminException('Vui lòng nhập Nội dung hủy đơn');
            }
            if (mb_strlen($customReason) > 500) {
                throw new AdminException('Nội dung hủy tối đa 500 ký tự');
            }
            return $customReason;
        }
        return $labels[$reasonKey];
    }

    /**
     * Hủy đơn từ admin (chỉ đơn chưa thanh toán), ghi lý do vào mark + lịch sử đơn
     * @throws AdminException
     */    public function adminCancelOrder(int $id, string $reasonKey, string $customReason = ''): bool
    {
        $message = $this->adminBuildCancelMessage($reasonKey, $customReason);
        $orderModel = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
        if (!$orderModel) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        if ((int)$orderModel['pid'] !== 0) {
            throw new AdminException('Không hủy đơn con sau tách kiện tại đây');
        }
        if ((int)$orderModel['is_cancel'] === 1) {
            throw new AdminException('Đơn hàng đã bị hủy');
        }
        if ((int)$orderModel['paid'] === 1) {
            throw new AdminException('Đơn đã thanh toán — vui lòng dùng hoàn tiền / sau bán hàng');
        }
        if ((int)$orderModel['refund_status'] !== 0) {
            throw new AdminException('Đơn hàng đang trong quy trình hoàn tiền');
        }
        /** @var StoreOrderRefundServices $refundServices */        $refundServices = app()->make(StoreOrderRefundServices::class);
        $markLine = '[Hủy đơn admin] ' . $message . ' — ' . date('Y-m-d H:i:s');
        $uid = (int)$orderModel['uid'];
        $orderIdStr = (string)$orderModel['order_id'];
        $this->transaction(function () use ($refundServices, $orderModel, $markLine, $message, $id) {
            $res = $refundServices->integralAndCouponBack($orderModel, 'cancel') && $refundServices->regressionStock($orderModel);
            $orderModel->is_cancel = 1;
            $oldMark = (string)($orderModel->getData('mark') ?? '');
            $orderModel->mark = trim($oldMark === '' ? $markLine : $oldMark . "\n" . $markLine);
            if (!($res && $orderModel->save())) {
                throw new AdminException('Hủy đơn không thành công');
            }
            /** @var StoreOrderStatusServices $statusService */            $statusService = app()->make(StoreOrderStatusServices::class);
            $statusService->save([
                'oid' => $id,
                'change_type' => 'order_cancel_admin',
                'change_message' => 'Hủy đơn (quản trị): ' . $message,
                'change_time' => time(),
            ]);
        });
        event('CustomEventListener', ['order_cancel', [
            'uid' => $uid,
            'id' => $id,
            'order_id' => $orderIdStr,
            'cancel_time' => date('Y-m-d H:i:s'),
            'cancel_by' => 'admin',
            'cancel_reason' => $message,
        ]]);
        return true;
    }

    /**
     * Điều chỉnh kho theo chênh lệch số lượng một dòng đơn (delta = mới - cũ)
     */    protected function adjustStockDeltaForOrderLine(array $order, array $cart, int $delta): bool
    {
        if ($delta === 0) {
            return true;
        }
        $n = abs($delta);
        $dec = $delta > 0;
        $combinationId = (int)($order['combination_id'] ?? 0);
        $seckillId = (int)($order['seckill_id'] ?? 0);
        $bargainId = (int)($order['bargain_id'] ?? 0);
        $advanceId = (int)($order['advance_id'] ?? 0);
        $unique = isset($cart['productInfo']['attrInfo']['unique']) ? (string)$cart['productInfo']['attrInfo']['unique'] : '';
        $productId = (int)($cart['productInfo']['id'] ?? 0);

        if ($combinationId) {
            $svc = app()->make(StoreCombinationServices::class);
            return $dec ? $svc->decCombinationStock($n, $combinationId, $unique) : $svc->incCombinationStock($n, $combinationId, $unique);
        }
        if ($seckillId) {
            $svc = app()->make(StoreSeckillServices::class);
            return $dec ? $svc->decSeckillStock($n, $seckillId, $unique) : $svc->incSeckillStock($n, $seckillId, $unique);
        }
        if ($bargainId) {
            $svc = app()->make(StoreBargainServices::class);
            return $dec ? $svc->decBargainStock($n, $bargainId, $unique) : $svc->incBargainStock($n, $bargainId, $unique);
        }
        if ($advanceId) {
            $svc = app()->make(StoreAdvanceServices::class);
            return $dec ? $svc->decAdvanceStock($n, $advanceId, $unique) : $svc->incAdvanceStock($n, $advanceId, $unique);
        }
        $svc = app()->make(\app\services\product\product\StoreProductServices::class);
        return $dec ? $svc->decProductStock($n, $productId, $unique) : $svc->incProductStock($n, $productId, $unique);
    }

    /**
     * Sửa số lượng từng dòng chi tiết đơn (chỉ đơn chưa thanh toán, không đơn flash sale / nhóm / mặc cả)
     * @param int $id id đơn eb_store_order
     * @param array $items [['unique' => string, 'cart_num' => int], ...]
     * @return array tóm tắt cập nhật
     * @throws AdminException
     */    public function adminUpdateCartQuantities(int $id, array $items): array
    {
        if (!$items) {
            throw new AdminException('Không có dòng số lượng cần cập nhật');
        }
        $order = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
        if (!$order) {
            throw new AdminException('Đơn hàng không tồn tại');
        }
        $orderArr = is_array($order) ? $order : $order->toArray();
        if ((int)$orderArr['pid'] !== 0) {
            throw new AdminException('Không sửa số lượng đơn con sau tách kiện tại đây');
        }
        if ((int)$orderArr['is_cancel'] === 1) {
            throw new AdminException('Đơn đã hủy');
        }
        if ((int)$orderArr['paid'] === 1) {
            throw new AdminException('Chỉ sửa số lượng khi đơn chưa thanh toán');
        }
        if ((int)$orderArr['refund_status'] !== 0) {
            throw new AdminException('Đơn đang trong quy trình hoàn tiền');
        }
        if ((int)($orderArr['combination_id'] ?? 0) || (int)($orderArr['seckill_id'] ?? 0) || (int)($orderArr['bargain_id'] ?? 0) || (int)($orderArr['pink_id'] ?? 0)) {
            throw new AdminException('Đơn khuyến mãi / nhóm / mặc cả — không sửa số lượng tại đây');
        }

        /** @var StoreOrderCartInfoServices $cartServices */        $cartServices = app()->make(StoreOrderCartInfoServices::class);
        $rows = $cartServices->getCartInfoList(['oid' => $id], ['id', 'unique', 'cart_num', 'surplus_num', 'refund_num', 'cart_info']);
        $byUnique = [];
        foreach ($rows as $row) {
            $byUnique[$row['unique']] = $row;
        }

        $want = [];
        foreach ($items as $it) {
            $u = isset($it['unique']) ? (string)$it['unique'] : '';
            $num = isset($it['cart_num']) ? (int)$it['cart_num'] : 0;
            if ($u === '' || $num < 1 || $num > 999999) {
                throw new AdminException('Tham số số lượng không hợp lệ');
            }
            $want[$u] = $num;
        }

        $summary = [];
        $this->transaction(function () use ($id, $want, $byUnique, $orderArr, $cartServices, &$summary) {
            $detailLines = [];
            foreach ($want as $unique => $newNum) {
                if (!isset($byUnique[$unique])) {
                    throw new AdminException('Không tìm thấy dòng hàng: ' . $unique);
                }
                $row = $byUnique[$unique];
                $cart = is_string($row['cart_info']) ? json_decode($row['cart_info'], true) : $row['cart_info'];
                if (!is_array($cart)) {
                    throw new AdminException('Dữ liệu giỏ hàng lỗi');
                }
                $oldNum = (int)$row['cart_num'];
                $delta = $newNum - $oldNum;
                if ($delta !== 0) {
                    if (!$this->adjustStockDeltaForOrderLine($orderArr, $cart, $delta)) {
                        throw new AdminException('Không đủ tồn kho hoặc không điều chỉnh kho được');
                    }
                }
                $cart['cart_num'] = $newNum;
                $refundNum = (int)($row['refund_num'] ?? 0);
                $surplus = max(0, $newNum - $refundNum);
                $cartServices->update(
                    ['oid' => $id, 'unique' => $unique],
                    [
                        'cart_num' => $newNum,
                        'surplus_num' => $surplus,
                        'cart_info' => json_encode($cart),
                    ]
                );
                $detailLines[] = ($cart['productInfo']['store_name'] ?? 'SP') . ': ' . $oldNum . ' → ' . $newNum;
            }

            $allRows = $cartServices->getCartInfoList(['oid' => $id], ['cart_num', 'refund_num', 'cart_info']);
            $totalNum = 0;
            $totalPrice = '0';
            $gainIntegral = '0';
            $cost = '0';
            foreach ($allRows as $r) {
                $c = is_string($r['cart_info']) ? json_decode($r['cart_info'], true) : $r['cart_info'];
                $cn = (int)$r['cart_num'];
                $totalNum += $cn;
                $truePrice = (string)($c['truePrice'] ?? '0');
                $totalPrice = bcadd($totalPrice, bcmul($truePrice, (string)$cn, 2), 2);
                $give = isset($c['productInfo']['give_integral']) ? (string)$c['productInfo']['give_integral'] : '0';
                $gainIntegral = bcadd($gainIntegral, bcmul($give, (string)$cn, 0), 0);
                $pcost = isset($c['productInfo']['cost']) ? (string)$c['productInfo']['cost'] : '0';
                $cost = bcadd($cost, bcmul($pcost, (string)$cn, 2), 2);
            }

            $coupon = (string)($orderArr['coupon_price'] ?? '0');
            $deduction = (string)($orderArr['deduction_price'] ?? '0');
            $postage = (string)($orderArr['pay_postage'] ?? '0');
            $newPay = bcsub(bcadd($totalPrice, $postage, 2), bcadd($coupon, $deduction, 2), 2);
            if (bccomp($newPay, '0', 2) < 0) {
                $newPay = '0';
            }

            $dataUpdate = [
                'total_num' => $totalNum,
                'total_price' => $totalPrice,
                'pay_price' => $newPay,
                'gain_integral' => (float)$gainIntegral,
                'cost' => $cost,
            ];

            if ((int)sys_config('user_brokerage_type') == 1) {
                $oldPay = (string)($orderArr['pay_price'] ?? '0');
                $percent = bccomp($oldPay, '0', 6) !== 0 ? bcdiv($newPay, $oldPay, 6) : '1';
                foreach (['one_brokerage', 'two_brokerage', 'staff_brokerage', 'agent_brokerage', 'division_brokerage'] as $bf) {
                    if (!empty($orderArr[$bf]) && (float)$orderArr[$bf] > 0) {
                        $dataUpdate[$bf] = bcmul((string)$orderArr[$bf], $percent, 2);
                    }
                }
            }

            $this->dao->update($id, $dataUpdate);

            /** @var StoreOrderStatusServices $statusService */            $statusService = app()->make(StoreOrderStatusServices::class);
            $statusService->save([
                'oid' => $id,
                'change_type' => 'order_edit_cart_num',
                'change_message' => 'Sửa số lượng: ' . implode('; ', $detailLines) . ' | pay_price=' . $newPay,
                'change_time' => time(),
            ]);

            $cartServices->clearOrderCartInfo($id);

            $summary = [
                'total_num' => $totalNum,
                'total_price' => $totalPrice,
                'pay_price' => $newPay,
            ];
        });

        return $summary;
    }
}
