<?php
declare(strict_types=1);

namespace crmeb\services\pay\extend\momo;

use crmeb\exceptions\PayException;
use think\facade\Log;

/**
 * MoMo Payment Gateway v2.
 * @see https://developers.momo.vn/
 */
class MoMoClient
{
    protected string $partnerCode;
    protected string $accessKey;
    protected string $secretKey;
    protected string $returnUrl;
    protected string $ipnUrl;
    protected bool $sandbox;

    public function __construct(array $config)
    {
        $this->partnerCode = (string)($config['partnerCode'] ?? '');
        $this->accessKey = (string)($config['accessKey'] ?? '');
        $this->secretKey = (string)($config['secretKey'] ?? '');
        $this->returnUrl = (string)($config['returnUrl'] ?? '');
        $this->ipnUrl = (string)($config['ipnUrl'] ?? '');
        $this->sandbox = (bool)($config['sandbox'] ?? true);
        if ($this->partnerCode === '' || $this->accessKey === '' || $this->secretKey === '') {
            throw new PayException('MoMo chưa cấu hình Partner Code / Access Key / Secret Key');
        }
    }

    /**
     * @return array{payUrl:string,requestId:string,orderId:string}
     */
    public function createPayment(string $orderId, string $amount, string $orderInfo): array
    {
        $requestId = $orderId . '_' . time();
        $amountInt = (int)round((float)$amount);
        if ($amountInt <= 0) {
            throw new PayException('Số tiền thanh toán MoMo không hợp lệ');
        }
        $extraData = base64_encode(json_encode(['attach' => 'product'], JSON_UNESCAPED_UNICODE));
        $rawSignature = 'accessKey=' . $this->accessKey
            . '&amount=' . $amountInt
            . '&extraData=' . $extraData
            . '&ipnUrl=' . $this->ipnUrl
            . '&orderId=' . $orderId
            . '&orderInfo=' . $orderInfo
            . '&partnerCode=' . $this->partnerCode
            . '&redirectUrl=' . $this->returnUrl
            . '&requestId=' . $requestId
            . '&requestType=captureWallet';
        $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);
        $payload = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => (string)$amountInt,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->ipnUrl,
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature,
            'lang' => 'vi',
        ];
        $response = $this->postJson($this->endpoint(), $payload);
        if (($response['resultCode'] ?? -1) != 0) {
            throw new PayException('MoMo: ' . ($response['message'] ?? 'Không tạo được giao dịch'));
        }
        $payUrl = (string)($response['payUrl'] ?? '');
        if ($payUrl === '') {
            throw new PayException('MoMo không trả về payUrl');
        }
        return [
            'payUrl' => $payUrl,
            'requestId' => $requestId,
            'orderId' => $orderId,
        ];
    }

    /**
     * @return array{valid:bool,order_id?:string,transaction_id?:string}
     */
    public function verifyIpn(array $input): array
    {
        $signature = (string)($input['signature'] ?? '');
        $rawSignature = 'accessKey=' . $this->accessKey
            . '&amount=' . ($input['amount'] ?? '')
            . '&extraData=' . ($input['extraData'] ?? '')
            . '&message=' . ($input['message'] ?? '')
            . '&orderId=' . ($input['orderId'] ?? '')
            . '&orderInfo=' . ($input['orderInfo'] ?? '')
            . '&orderType=' . ($input['orderType'] ?? '')
            . '&partnerCode=' . ($input['partnerCode'] ?? '')
            . '&payType=' . ($input['payType'] ?? '')
            . '&requestId=' . ($input['requestId'] ?? '')
            . '&responseTime=' . ($input['responseTime'] ?? '')
            . '&resultCode=' . ($input['resultCode'] ?? '')
            . '&transId=' . ($input['transId'] ?? '');
        $calc = hash_hmac('sha256', $rawSignature, $this->secretKey);
        if (!hash_equals($calc, $signature)) {
            return ['valid' => false];
        }
        $resultCode = (int)($input['resultCode'] ?? -1);
        return [
            'valid' => $resultCode === 0,
            'order_id' => (string)($input['orderId'] ?? ''),
            'transaction_id' => (string)($input['transId'] ?? ''),
        ];
    }

    protected function endpoint(): string
    {
        return $this->sandbox
            ? 'https://test-payment.momo.vn/v2/gateway/api/create'
            : 'https://payment.momo.vn/v2/gateway/api/create';
    }

    protected function postJson(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 30,
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        if ($errno) {
            Log::error('MoMo curl error: ' . $errno);
            throw new PayException('Không kết nối được MoMo');
        }
        $decoded = json_decode((string)$body, true);
        return is_array($decoded) ? $decoded : [];
    }
}
