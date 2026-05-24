<?php
declare(strict_types=1);

namespace crmeb\services\pay\storage;

use app\services\pay\PayServices;
use crmeb\exceptions\PayException;
use crmeb\services\pay\BasePay;
use crmeb\services\pay\extend\momo\MoMoClient;
use crmeb\services\pay\PayInterface;
use think\facade\Event;
use think\facade\Log;

class MomoPay extends BasePay implements PayInterface
{
    /** @var MoMoClient */
    protected $client;

    protected function initialize(array $config)
    {
        $siteUrl = rtrim((string)sys_config('site_url'), '/');
        $this->client = new MoMoClient([
            'partnerCode' => sys_config('vn_momo_partner_code'),
            'accessKey' => sys_config('vn_momo_access_key'),
            'secretKey' => sys_config('vn_momo_secret_key'),
            'returnUrl' => $siteUrl . '/pages/goods/order_pay_status/index',
            'ipnUrl' => $siteUrl . '/api/pay/notify/momo',
            'sandbox' => (int)sys_config('vn_momo_sandbox', 1) === 1,
        ]);
    }

    public function create(string $orderId, string $totalFee, string $attach, string $body, string $detail, array $options = [])
    {
        $result = $this->client->createPayment($orderId, $totalFee, $body ?: ('Thanh toan don ' . $orderId));
        return ['pay_url' => $result['payUrl']];
    }

    public function merchantPay(string $openid, string $orderId, string $amount, array $options = [])
    {
        return false;
    }

    public function refund(string $outTradeNo, array $options = [])
    {
        throw new PayException('MoMo hoàn tiền tự động chưa hỗ trợ — xử lý thủ công trên cổng MoMo');
    }

    public function queryRefund(string $outTradeNo, string $outRequestNo, array $other = [])
    {
        return false;
    }

    public function handleNotify()
    {
        $raw = file_get_contents('php://input');
        $input = json_decode((string)$raw, true);
        if (!is_array($input)) {
            $input = app()->request->param();
        }
        try {
            $result = $this->client->verifyIpn($input);
            if (!$result['valid']) {
                return json_encode(['message' => 'Invalid signature']);
            }
            $data = [
                'attach' => 'product',
                'out_trade_no' => $result['order_id'] ?? '',
                'transaction_id' => $result['transaction_id'] ?? '',
            ];
            if (Event::until('NotifyListener', [$data, PayServices::VN_MOMO])) {
                return json_encode(['message' => 'Success']);
            }
        } catch (\Throwable $e) {
            Log::error('MoMo IPN: ' . $e->getMessage());
        }
        return json_encode(['message' => 'Failed']);
    }
}
