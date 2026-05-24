<?php
declare(strict_types=1);

namespace crmeb\services\pay\storage;

use app\services\pay\PayServices;
use crmeb\exceptions\PayException;
use crmeb\services\pay\BasePay;
use crmeb\services\pay\extend\vnpay\VnPayClient;
use crmeb\services\pay\PayInterface;
use think\facade\Event;
use think\facade\Log;

class VnpayPay extends BasePay implements PayInterface
{
    /** @var VnPayClient */
    protected $client;

    protected function initialize(array $config)
    {
        $siteUrl = rtrim((string)sys_config('site_url'), '/');
        $this->client = new VnPayClient([
            'tmnCode' => sys_config('vn_vnpay_tmn_code'),
            'hashSecret' => sys_config('vn_vnpay_hash_secret'),
            'returnUrl' => $siteUrl . '/pages/goods/order_pay_status/index',
            'ipnUrl' => $siteUrl . '/api/pay/notify/vnpay',
            'sandbox' => (int)sys_config('vn_vnpay_sandbox', 1) === 1,
        ]);
    }

    public function create(string $orderId, string $totalFee, string $attach, string $body, string $detail, array $options = [])
    {
        $ip = request()->ip() ?: '127.0.0.1';
        return $this->client->createPaymentUrl($orderId, $totalFee, $body ?: ('Thanh toan don ' . $orderId), $ip);
    }

    public function merchantPay(string $openid, string $orderId, string $amount, array $options = [])
    {
        return false;
    }

    public function refund(string $outTradeNo, array $options = [])
    {
        throw new PayException('VNPay hoàn tiền tự động chưa hỗ trợ — xử lý thủ công trên cổng VNPay');
    }

    public function queryRefund(string $outTradeNo, string $outRequestNo, array $other = [])
    {
        return false;
    }

    public function handleNotify()
    {
        app()->request->filter(['trim']);
        $input = app()->request->param();
        try {
            $result = $this->client->verifyIpn($input);
            if (!$result['valid']) {
                return $this->client->ipnFailResponse('Invalid signature or payment failed');
            }
            $data = [
                'attach' => 'product',
                'out_trade_no' => $result['order_id'] ?? '',
                'transaction_id' => $result['transaction_id'] ?? '',
            ];
            if (Event::until('NotifyListener', [$data, PayServices::VN_VNPAY])) {
                return $this->client->ipnSuccessResponse();
            }
        } catch (\Throwable $e) {
            Log::error('VNPay IPN: ' . $e->getMessage());
        }
        return $this->client->ipnFailResponse();
    }
}
