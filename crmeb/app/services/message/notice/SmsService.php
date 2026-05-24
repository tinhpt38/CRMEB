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

namespace app\services\message\notice;

use app\jobs\notice\SmsJob;
use app\jobs\TaskJob;
use app\services\message\NoticeService;
use app\services\kefu\service\StoreServiceServices;
use app\services\message\SystemNotificationServices;
use app\services\serve\ServeServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use think\facade\Log;


/**
 * danh sách tin nhắn SMS
 * Created by PhpStorm.
 * User: xurongyao <763569752@qq.com>
 * Date: 2021/9/22 1:23 PM
 */class SmsService extends NoticeService
{
    /**
     * loại tin nhắn
     * @var string[]
     */    private $smsType = ['yihaotong', 'aliyun', 'tencent'];

    /**
     * Gửi tin nhắn SMS
     * @param $phone
     * @param array $data
     * @return bool|void
     */    public function sendSms($phone, array $data)
    {
        try {
            if ($this->noticeInfo['is_sms'] == 1) {
                try {
                    $this->send(true, $phone, $data, $this->noticeInfo['mark']);
                    return true;
                } catch (\Throwable $e) {
                    Log::error('Không gửi được SMS,Lý do thất bại:' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return true;
        }
    }

    /**
     * gửi tin nhắn văn bản
     * @param bool $switch
     * @param $phone
     * @param array $data
     * @param string $mark
     * @return bool
     */    public function send(bool $switch, $phone, array $data, string $mark)
    {
        if ($switch && $phone) {
            //Nhận loại trình điều khiển để gửi SMS
            $type = $this->smsType[sys_config('sms_type', 0)];
            if ($type == 'tencent') {
                $data = $this->handleTencent($mark, $data);
            }
            $smsMake = app()->make(ServeServices::class)->sms($type);
            $smsId = $mark == 'verify_code' ? app()->make(SystemNotificationServices::class)->value(['mark' => 'verify_code'], 'sms_id') : $this->noticeInfo['sms_id'];
            //gửi tin nhắn văn bản
            $res = $smsMake->send($phone, $smsId, $data);
            if ($res === false) {
                throw new ApiException($smsMake->getError());
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Hoàn tiền Gửi tin nhắn của quản trị viên Nhiệm vụ
     * @param $order
     * @return bool
     */    public function sendAdminRefund($order)
    {
        if ($this->noticeInfo['is_sms'] == 1) {
            /** @var StoreServiceServices $StoreServiceServices */            $StoreServiceServices = app()->make(StoreServiceServices::class);
            $adminList = $StoreServiceServices->getStoreServiceOrderNotice();

            foreach ($adminList as $item) {
                $data = ['order_id' => $order['order_id'], 'admin_name' => $item['nickname']];
                $this->sendSms($item['phone'], $data);
            }
        }
        return true;
    }

    /**
     * Người dùng xác nhận lời nhắc SMS của quản trị viên giao hàng
     * @param $switch
     * @param $adminList
     * @param $order
     * @return bool
     */    public function sendAdminConfirmTakeOver($order)
    {
        if ($this->noticeInfo['is_sms'] == 1) {
            /** @var StoreServiceServices $StoreServiceServices */            $StoreServiceServices = app()->make(StoreServiceServices::class);
            $adminList = $StoreServiceServices->getStoreServiceOrderNotice();
            foreach ($adminList as $item) {
                $data = ['order_id' => $order['order_id'], 'admin_name' => $item['nickname']];
                $this->sendSms($item['phone'], $data);
            }
        }
        return true;
    }

    /**
     * Nếu đơn hàng được đặt thành công, hãy gửi tin nhắn văn bản cho quản trị viên CSKH
     * @param $switch
     * @param $adminList
     * @param $order
     * @return bool
     */    public function sendAdminPaySuccess($order)
    {
        if ($this->noticeInfo['is_sms'] == 1) {
            /** @var StoreServiceServices $StoreServiceServices */            $StoreServiceServices = app()->make(StoreServiceServices::class);
            $adminList = $StoreServiceServices->getStoreServiceOrderNotice();
            foreach ($adminList as $item) {
                $data = ['order_id' => $order['order_id'], 'admin_name' => $item['nickname']];
                $this->sendSms($item['phone'], $data);
            }
        }
        return true;
    }

    /**
     * Xử lý các tham số của Tencent Cloud
     * @param $mark
     * @param $data
     * @return array
     */    public function handleTencent($mark, $data)
    {
        $result = [];
        switch ($mark) {
            case 'verify_code':
                $result = [(string)$data['code'], (string)$data['time']];
                break;
            case 'send_order_refund_no_status':
            case 'order_pay_false':
                $result = [$data['order_id']];
                break;
            case 'price_revision':
                $result = [$data['order_id'], (string)$data['pay_price']];
                break;
            case 'order_pay_success':
                $result = [(string)$data['pay_price'], $data['order_id']];
                break;
            case 'order_take':
                $result = [$data['order_id'], $data['store_name']];
                break;
            case 'send_order_apply_refund':
            case 'admin_pay_success_code':
            case 'send_admin_confirm_take_over':
                $result = [$data['admin_name'], $data['order_id']];
                break;
            case 'order_deliver_success':
            case 'order_postage_success':
                $result = [$data['nickname'], $data['store_name'], $data['order_id']];
                break;
            case 'order_refund':
                $result = [$data['order_id'], $data['refund_price']];
                break;
            case 'recharge_success':
                $result = [$data['price'], $data['now_money']];
                break;
            case 'sign_remind':
                $result = [$data['site_name']];
                break;
        }
        return $result;
    }
}
