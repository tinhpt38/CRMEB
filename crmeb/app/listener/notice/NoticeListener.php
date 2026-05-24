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

namespace app\listener\notice;

use app\jobs\notice\PrintJob;
use app\services\message\NoticeService;
use app\services\message\notice\{
    EnterpriseWechatService,
    RoutineTemplateListService,
    SmsService,
    SystemMsgService,
    TelegramService,
    WechatTemplateListService
};
use app\services\order\StoreOrderCartInfoServices;
use app\services\user\UserServices;
use crmeb\interfaces\ListenerInterface;
use crmeb\utils\Str;

/**
 * Lớp tin nhắn
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/8/29
 */class NoticeListener implements ListenerInterface
{
    /**
     * @var array
     */    protected $services = [];

    /**
     * phương pháp
     * @var string[]
     */    protected $eventMethods = [
        'bind_spread_uid' => 'handleBindSpreadUid',
        'order_pay_success' => 'handleOrderPaySuccess',
        'order_deliver_success' => 'handleOrderDeliverSuccess',
        'order_postage_success' => 'handleOrderPostageSuccess',
        'order_take' => 'handleOrderTake',
        'price_revision' => 'handlePriceRevision',
        'order_refund' => 'handleOrderRefund',
        'send_order_refund_no_status' => 'handleSendOrderRefundNoStatus',
        'recharge_success' => 'handleRechargeSuccess',
        'recharge_order_refund_status' => 'handleRechargeOrderRefundStatus',
        'integral_accout' => 'handleIntegralAccout',
        'order_brokerage' => 'handleOrderBrokerage',
        'bargain_success' => 'handleBargainSuccess',
        'can_pink_success' => 'handlePinkSuccess',
        'open_pink_success' => 'handlePinkSuccess',
        'order_user_groups_success' => 'handleGroupsSuccess',
        'send_order_pink_fial' => 'handlePinkFail',
        'send_order_pink_clone' => 'handlePinkFail',
        'user_extract' => 'handleUserExtract',
        'user_balance_change' => 'handleUserBalanceChange',
        'order_pay_false' => 'handleOrderPayFalse',
        'admin_pay_success_code' => 'handleAdminPaySuccessCode',
        'send_admin_confirm_take_over' => 'handleSendAdminConfirmTakeOver',
        'send_order_apply_refund' => 'handleSendOrderApplyRefund',
        'kefu_send_extract_application' => 'handleKefuSendExtractApplication',
        'sign_remind' => 'handleSignRemind',
        'revenue_received' => 'handleRevenueReceived',
        // add more event-method mappings here...
    ];

    /**
     * Bắt đầu tải
     */    public function __construct()
    {
        $this->services = [
            'Wechat' => app()->make(WechatTemplateListService::class),
            'Routine' => app()->make(RoutineTemplateListService::class),
            'SysMsg' => app()->make(SystemMsgService::class),
            'WeWork' => app()->make(EnterpriseWechatService::class),
            'Sms' => app()->make(SmsService::class),
            'Telegram' => app()->make(TelegramService::class)
        ];
    }

    /**
     * Nhận đối tượng
     * @param $mark
     * @return NoticeService
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    private function getNoticeService($mark)
    {
        return $this->services[$mark];
    }

    /**
     * Phương pháp thực hiện
     * @param $event
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    public function handle($event): void
    {
        try {
            [$data, $mark] = $event;
            if ($mark) {
                $this->getNoticeService('SysMsg')->setEvent($mark);     //Thông báo trang web
                $this->getNoticeService('Sms')->setEvent($mark);        //Tin nhắn ngắn
                $this->getNoticeService('Wechat')->setEvent($mark);     //tin nhắn mẫu
                $this->getNoticeService('Routine')->setEvent($mark);    //Đăng ký nhận tin tức
                $this->getNoticeService('WeWork')->setEvent($mark);     //Tin nhắn WeChat doanh nghiệp
                $this->getNoticeService('Telegram')->setEvent($mark);   //Tin nhắn Telegram
                if (isset($this->eventMethods[$mark])) {
                    $method = $this->eventMethods[$mark];
                    call_user_func([$this, $method], $data);
                }
            }
        } catch (\Throwable $e) {
        }
    }

    /**
     * Quảng bá Khách hàng mới và gửi tin nhắn cho cấp trên
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleBindSpreadUid($data)
    {
        if (isset($data['spreadUid']) && $data['spreadUid']) {
            $name = $data['nickname'] ?? '';
            //Thông báo trang web
            $this->getNoticeService('SysMsg')->sendMsg($data['spreadUid'], ['nickname' => $name]);
        }
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng khi thanh toán thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderPaySuccess($data)
    {
        $pay_price = $data['pay_price'];
        $order_id = $data['order_id'];
        $data['is_channel'] = $data['is_channel'] ?? 2;
        $data['total_num'] = $data['total_num'] ?? 1;
        $data['storeName'] = Str::substrUTf8($data['storeName'], 20, 'UTF-8', '');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($data['uid'], ['order_id' => $data['order_id'], 'total_num' => $data['total_num'], 'pay_price' => $data['pay_price']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($data['user_phone'], compact('order_id', 'pay_price'));
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderPaySuccess($data['uid'], $data);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendOrderSuccess($data['uid'], $data['pay_price'], $data['order_id']);
        return true;
    }

    /**
     * Gửi tin nhắn tới Khách hàng
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderDeliverSuccess($data)
    {
        $orderInfo = $data['orderInfo'];
        $storeTitle = $data['storeName'];
        $order_id = $orderInfo->order_id;
        $store_name = $storeTitle;
        $storeTitle = Str::substrUTf8($storeTitle, 20, 'UTF-8', '');
        $nickname = app()->make(UserServices::class)->value(['uid' => $orderInfo->uid], 'nickname');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($orderInfo['uid'], ['nickname' => $nickname, 'store_name' => $storeTitle, 'order_id' => $orderInfo['order_id'], 'delivery_name' => $orderInfo['delivery_name'], 'delivery_id' => $orderInfo['delivery_id'], 'user_address' => $orderInfo['user_address']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($orderInfo->user_phone, compact('order_id', 'store_name', 'nickname'));
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderDeliver($orderInfo['uid'], $storeTitle, $orderInfo->toArray());
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendOrderPostage($orderInfo['uid'], $orderInfo->toArray(), $storeTitle, 0);
        return true;
    }

    /**
     * Gửi tin nhắn nhanh cho Khách hàng
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderPostageSuccess($data)
    {
        $orderInfo = $data['orderInfo'];
        $storeTitle = $data['storeName'];
        $order_id = $orderInfo->order_id;
        $store_name = $storeTitle;
        $storeTitle = Str::substrUTf8($storeTitle, 20, 'UTF-8', '');
        $nickname = app()->make(UserServices::class)->value(['uid' => $orderInfo->uid], 'nickname');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($orderInfo['uid'], ['nickname' => $nickname, 'store_name' => $storeTitle, 'order_id' => $orderInfo['order_id'], 'delivery_name' => $orderInfo['delivery_name'], 'delivery_id' => $orderInfo['delivery_id'], 'user_address' => $orderInfo['user_address']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($orderInfo->user_phone, compact('order_id', 'store_name', 'nickname'));
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderPostage($orderInfo['uid'], $orderInfo->toArray(), $storeTitle);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendOrderPostage($orderInfo['uid'], $orderInfo->toArray(), $storeTitle, 1);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng để xác nhận đã nhận
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderTake($data)
    {
        $order = is_object($data['order']) ? $data['order']->toArray() : $data['order'];
        $store_name = Str::substrUTf8($data['storeTitle'], 20, 'UTF-8', '');
        $order_id = $order['order_id'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'store_name' => $store_name]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($order['user_phone'], compact('store_name', 'order_id'));
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderTakeSuccess($order['uid'], $order, $store_name);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendOrderTakeOver($order['uid'], $order, $store_name);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng về việc thay đổi giá
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handlePriceRevision($data)
    {
        $order = $data['order'];
        $pay_price = $data['pay_price'];
        $order['storeName'] = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'pay_price' => $pay_price]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($order['user_phone'], ['order_id' => $order['order_id'], 'pay_price' => $pay_price]);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu hoàn tiền thành công
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderRefund($data)
    {
        $datas = $data['data'];
        $order = $data['order'];
        $order['refund_price'] = $datas['refund_price'];
        $order['refund_no'] = $datas['refund_no'];
        $storeName = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);
        $storeTitle = Str::substrUTf8($storeName, 20, 'UTF-8', '');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'pay_price' => $order['pay_price'], 'refund_price' => $datas['refund_price']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($order['user_phone'], ['order_id' => $order['order_id'], 'refund_price' => $order['refund_price']]);
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderRefund($order['uid'], $order, $storeTitle);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendOrderRefundSuccess($order['uid'], $order, $storeTitle, $datas);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu hoàn tiền không thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleSendOrderRefundNoStatus($data)
    {
        $order = $data['orderInfo'];
        $order['pay_price'] = $order['refund_price'];
        $order['refund_no'] = $order['order_id'];
        $storeTitle = Str::substrUTf8($order['cart_info'][0]['productInfo']['store_name'], 20, 'UTF-8', '');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'pay_price' => $order['refund_price'], 'store_name' => $storeTitle]);
        //tin nhắn mẫu
        $this->getNoticeService('Wechat')->sendOrderNoRefund($order['uid'], $order, $storeTitle);
        //Tin tức đăng ký chương trình nhỏ
        $this->getNoticeService('Routine')->sendOrderRefundFail($order['uid'], $order, $storeTitle);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu nạp tiền thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleRechargeSuccess($data)
    {
        $order = $data['order'];
        $order['now_money'] = $data['now_money'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'price' => $order['price'], 'now_money' => $order['now_money']]);
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendRechargeSuccess($order['uid'], $order);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendRechargeSuccess($order['uid'], $order, $order['now_money']);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng để nạp tiền và hoàn tiền
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleRechargeOrderRefundStatus($data)
    {
        $datas = $data['data'];
        $UserRecharge = $data['UserRecharge'];
        $now_money = $data['now_money'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($UserRecharge['uid'], ['refund_price' => $datas['refund_price'], 'order_id' => $UserRecharge['order_id'], 'price' => $UserRecharge['price']]);
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendOrderRefund($UserRecharge['uid'], ['refund_no' => $UserRecharge['order_id'], 'refund_price' => $UserRecharge['price']], 'Nạp tiền và hoàn tiền');
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendRechargeSuccess($UserRecharge['uid'], $UserRecharge, $now_money);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng khi nhận được điểm
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleIntegralAccout($data)
    {
        $order = $data['order'];
        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order['order_id'], 'store_name' => $data['storeTitle'], 'pay_price' => $order['pay_price'], 'gain_integral' => $data['give_integral'], 'integral' => $data['integral']]);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendUserIntegral($order['uid'], $data['order'], $data['storeTitle'], $data['give_integral'], $data['integral']);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng khi nhận được hoa hồng
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderBrokerage($data)
    {
        $brokeragePrice = $data['brokeragePrice'];
        $goodsName = $data['goodsName'];
        $goodsPrice = $data['goodsPrice'];
        $spread_uid = $data['spread_uid'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($spread_uid, ['goods_name' => $goodsName, 'goods_price' => $goodsPrice, 'brokerage_price' => $brokeragePrice]);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng sau khi thương lượng thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleBargainSuccess($data)
    {
        $uid = $data['uid'];
        $bargainInfo = $data['bargainInfo'];
        $bargainUserInfo = $data['bargainUserInfo'];
        $bargainInfo['title'] = Str::substrUTf8($bargainInfo['title'], 20, 'UTF-8', '');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($uid, ['title' => $bargainInfo['title'], 'min_price' => $bargainInfo['min_price']]);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendBargainSuccess($uid, $bargainInfo, $bargainUserInfo, $uid);
        return true;
    }

    /**
     * Nhóm bắt đầu thành công,Gửi tin nhắn cho Khách hàng sau khi tham gia nhóm thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handlePinkSuccess($data)
    {
        $orderInfo = $data['orderInfo'];
        $title = $data['title'];
        $pink = $data['pink'];
        $nickname = app()->make(UserServices::class)->value(['uid' => $orderInfo['uid']], 'nickname');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($orderInfo['uid'], ['title' => $title, 'nickname' => $nickname, 'count' => $pink['people'], 'pink_time' => date('Y-m-d H:i:s', $pink['add_time'])]);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu cuộc chiến nhóm thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleGroupsSuccess($data)
    {
        $list = $data['list'];
        $title = $data['title'];
        $url = '/pages/goods/order_details/index?order_id=' . $list['order_id'];
        $title = Str::substrUTf8($title, 20, 'UTF-8', '');

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($list['uid'], ['title' => $title, 'nickname' => $list['nickname'], 'count' => $list['people'], 'pink_time' => date('Y-m-d H:i:s', $list['add_time'])]);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendPinkSuccess($list['uid'], $title, $list['nickname'], $list['add_time'], $list['people'], $url);
        return true;
    }

    /**
     * Nếu mua nhóm không thành công, việc mua nhóm sẽ bị hủy và một tin nhắn sẽ được gửi đến Khách hàng.
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handlePinkFail($data)
    {
        $uid = $data['uid'];
        $pink = $data['pink'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($uid, ['title' => $pink->title, 'count' => $pink->people]);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu rút tiền thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleUserExtract($data)
    {
        $extractNumber = $data['extractNumber'];
        $nickname = $data['nickname'];
        $uid = $data['uid'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($uid, ['extract_number' => $extractNumber, 'nickname' => $nickname, 'date' => date('Y-m-d H:i:s', time())]);
        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendUserExtract($uid, $extractNumber);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendExtractSuccess($uid, $extractNumber, $nickname);
        return true;
    }

    /**
     * Gửi tin nhắn cho Khách hàng nếu rút tiền không thành công
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleUserBalanceChange($data)
    {
        $extract_number = $data['extract_number'];
        $message = $data['message'];
        $uid = $data['uid'];
        $nickname = $data['nickname'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($uid, ['extract_number' => $extract_number, 'nickname' => $nickname, 'date' => date('Y-m-d H:i:s', time()), 'message' => $message]);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendExtractFail($uid, $message, $extract_number, $nickname);
        return true;
    }

    /**
     * Gửi tin nhắn tới Khách hàng để nhắc nhở thanh toán
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleOrderPayFalse($data)
    {
        $order = $data['order'];
        $order_id = $order['order_id'];
        $order['storeName'] = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($order['uid'], ['order_id' => $order_id]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendSms($order['user_phone'], compact('order_id'));
        return true;
    }

    /**
     * Gửi tin nhắn đến bộ phận chăm sóc khách hàng để nhận đơn hàng mới
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleAdminPaySuccessCode($data)
    {
        $order = $data;
        $storeName = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);
        $title = 'Bạn ơi, đây là đơn hàng mới！';
        $status = 'trật tự mới';
        $link = '/pages/admin/orderDetail/index?id=' . $order['order_id'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->kefuSystemSend(['order_id' => $order['order_id']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendAdminPaySuccess($order);
        //tin nhắn mẫu
        $this->getNoticeService('Wechat')->sendAdminOrder($order['order_id'], $storeName, $title, $status, $link);
        //Thông báo WeChat doanh nghiệp
        $this->getNoticeService('WeWork')->weComSend(['order_id' => $order['order_id']]);
        //Thông báo Telegram cho nội bộ
        $this->getNoticeService('Telegram')->send([
            'order_id' => $order['order_id'],
            'pay_price' => $order['pay_price'] ?? '',
            'real_name' => $order['real_name'] ?? '',
            'user_phone' => $order['user_phone'] ?? '',
        ]);
        return true;
    }

    /**
     * Xác nhận đã nhận và gửi tin nhắn đến bộ phận chăm sóc khách hàng
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleSendAdminConfirmTakeOver($data)
    {
        $order = $data['order'];
        $storeTitle = $data['storeTitle'];
        $storeName = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);
        $title = 'Kính gửi, Khách hàng đã nhận được hàng.！';
        $status = 'Biên nhận đơn hàng';
        $link = '/pages/admin/orderDetail/index?id=' . $order['order_id'];

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->kefuSystemSend(['storeTitle' => $storeTitle, 'order_id' => $order['order_id']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendAdminConfirmTakeOver($order);
        //Tài khoản chính thức
        $this->getNoticeService('Wechat')->sendAdminOrder($order['order_id'], $storeName, $title, $status, $link);
        //Thông báo WeChat doanh nghiệp
        $this->getNoticeService('WeWork')->weComSend(['storeTitle' => $storeTitle, 'order_id' => $order['order_id']]);
        //Thông báo Telegram cho nội bộ
        $this->getNoticeService('Telegram')->send([
            'order_id' => $order['order_id'],
            'storeTitle' => $storeTitle,
            'real_name' => $order['real_name'] ?? '',
            'user_phone' => $order['user_phone'] ?? '',
        ]);
        return true;
    }

    /**
     * Gửi tin nhắn đến bộ phận chăm sóc khách hàng để yêu cầu hoàn tiền
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleSendOrderApplyRefund($data)
    {
        $order = $data['order'];
        $storeName = app()->make(StoreOrderCartInfoServices::class)->getCarIdByProductTitle((int)$order['id']);
        $title = 'Bạn thân mến, bạn có một yêu cầu hoàn tiền đang chờ xử lý.！';
        $status = 'Hoàn tiền đơn hàng';
        $link = '/pages/admin/orderDetail/index?id=' . $order['refund_no'] . '&types=-3';

        //Thông báo trang web
        $this->getNoticeService('SysMsg')->kefuSystemSend(['order_id' => $order['order_id']]);
        //Tin nhắn ngắn
        $this->getNoticeService('Sms')->sendAdminRefund($order);
        //Tài khoản chính thức
        $this->getNoticeService('Wechat')->sendAdminOrder($order['refund_no'], $storeName, $title, $status, $link);
        //Thông báo WeChat doanh nghiệp
        $this->getNoticeService('WeWork')->weComSend(['order_id' => $order['order_id']]);
        //Thông báo Telegram cho nội bộ
        $this->getNoticeService('Telegram')->send([
            'order_id' => $order['order_id'],
            'refund_no' => $order['refund_no'] ?? '',
            'refund_price' => $order['refund_price'] ?? '',
            'real_name' => $order['real_name'] ?? '',
            'user_phone' => $order['user_phone'] ?? '',
        ]);
        return true;
    }

    /**
     * Gửi tin nhắn đến bộ phận chăm sóc khách hàng để đăng ký rút tiền mặt
     * @param $data
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/29
     */    protected function handleKefuSendExtractApplication($data)
    {
        //Thông báo trang web
        $this->getNoticeService('SysMsg')->kefuSystemSend($data);
        //Thông báo WeChat doanh nghiệp
        $this->getNoticeService('WeWork')->weComSend($data);
        return true;
    }

    /**
     * Lời nhắc đăng ký
     * @param $data
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2023/9/30
     */    public function handleSignRemind($data)
    {
        //Thông báo trang web
        $this->getNoticeService('SysMsg')->sendMsg($data['uid'], ['site_name' => sys_config('site_name')]);
        //Tin nhắn ngắn
        if ($data['phone']) {
            $this->getNoticeService('Sms')->sendSms($data['phone'], ['site_name' => sys_config('site_name')]);
        }
        return true;
    }

    protected function handleRevenueReceived($data)
    {
        $extractNumber = $data['extractNumber'];
        $uid = $data['uid'];
        $order_id = $data['order_id'];
        $type = $data['type'];

        //Tin nhắn mẫu Tin nhắn mẫu tài khoản chính thức
        $this->getNoticeService('Wechat')->sendRevenueReceived($uid, $extractNumber, $order_id, $type);
        //Applet tin nhắn mẫu đăng ký tin nhắn
        $this->getNoticeService('Routine')->sendRevenueReceived($uid, $extractNumber, $order_id, $type);
        return true;
    }
}
