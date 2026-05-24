<?php
declare(strict_types=1);

namespace crmeb\services\pay\storage;

use app\services\pay\PayServices;
use crmeb\exceptions\PayException;
use crmeb\services\pay\BasePay;
use crmeb\services\pay\extend\zalopay\ZaloPayClient;
use crmeb\services\pay\PayInterface;
use think\facade\Event;
use think\facade\Log;

class ZalopayPay extends BasePay implements PayInterface
{
    /** @var ZaloPayClient */
    protected $client;

    protected function initialize(array $config)
    {
        $siteUrl = rtrim((string)sys_config('site_url'), '/');
        $this->client = new ZaloPayClient([
            'appId' => (int)sys_config('vn_zalopay_app_id'),
            'key1' => sys_config('vn_zalopay_key1'),
            'key2' => sys_config('vn_zalopay_key2'),
            'returnUrl' => $siteUrl . '/pages/goods/order_pay_status/index',
            'ipnUrl' => $siteUrl . '/api/pay/notify/zalopay',
            'sandbox' => (int)sys_config('vn_zalopay_sandbox', 1) === 1,
        ]);
    }

    public function create(string $orderId, string $totalFee, string $attach, string $body, string $detail, array $options = [])
    {
        $result = $this->client->createPayment($orderId, $totalFee, $body ?: ('Thanh toan don ' . $orderId));
        return ['pay_url' => $result['orderUrl']];
    }

    public function merchantPay(string $openid, string $orderId, string $amount, array $options = [])
    {
        return false;
    }

    public function refund(string $outTradeNo, array $options = [])
    {
        throw new PayException('ZaloPay hoàn tiền tự động chưa hỗ trợ — xử lý thủ công trên cổng ZaloPay');
    }

    public function queryRefund(string $outTradeNo, string $outRequestNo, array $other = [])
    {
        return false;
    }

    public function handleNotify()
    {
        $input = app()->request->param();
        try {
            $result = $this->client->verifyCallback($input);
            if (!$result['valid']) {
                return json_encode(['return_code' => -1, 'return_message' => 'mac not equal']);
            }
            $data = [
                'attach' => 'product',
                'out_trade_no' => $result['order_id'] ?? '',
                'transaction_id' => $result['transaction_id'] ?? '',
            ];
            if (Event::until('NotifyListener', [$data, PayServices::VN_ZALOPAY])) {
                return json_encode(['return_code' => 1, 'return_message' => 'success']);
            }
        } catch (\Throwable $e) {
            Log::error('ZaloPay callback: ' . $e->getMessage());
        }
        return json_encode(['return_code' => 0, 'return_message' => 'failed']);
    }
}
