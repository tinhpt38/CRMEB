<?php
declare(strict_types=1);

namespace crmeb\services\pay\extend\vnpay;

use crmeb\exceptions\PayException;

/**
 * VNPay — tạo URL thanh toán & xác minh IPN (HMAC SHA512).
 * @see https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/
 */
class VnPayClient
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $returnUrl;
    protected string $ipnUrl;
    protected bool $sandbox;

    public function __construct(array $config)
    {
        $this->tmnCode = (string)($config['tmnCode'] ?? '');
        $this->hashSecret = (string)($config['hashSecret'] ?? '');
        $this->returnUrl = (string)($config['returnUrl'] ?? '');
        $this->ipnUrl = (string)($config['ipnUrl'] ?? '');
        $this->sandbox = (bool)($config['sandbox'] ?? true);
        if ($this->tmnCode === '' || $this->hashSecret === '') {
            throw new PayException('VNPay chưa cấu hình TMN Code hoặc Hash Secret');
        }
    }

    public function createPaymentUrl(string $orderId, string $amount, string $orderInfo, string $ipAddr): string
    {
        $vnpAmount = (int)bcmul((string)$amount, '100', 0);
        if ($vnpAmount <= 0) {
            throw new PayException('Số tiền thanh toán VNPay không hợp lệ');
        }
        $params = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => (string)$vnpAmount,
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $orderId,
            'vnp_OrderInfo' => mb_substr($orderInfo, 0, 255),
            'vnp_OrderType' => 'other',
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_IpAddr' => $ipAddr ?: '127.0.0.1',
            'vnp_CreateDate' => date('YmdHis'),
        ];
        ksort($params);
        $hashData = $this->buildHashData($params);
        $params['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $this->hashSecret);
        return $this->endpoint() . '?' . http_build_query($params);
    }

    /**
     * @return array{valid:bool,order_id?:string,transaction_id?:string,amount?:int,response_code?:string}
     */
    public function verifyIpn(array $input): array
    {
        $secureHash = $input['vnp_SecureHash'] ?? '';
        unset($input['vnp_SecureHash'], $input['vnp_SecureHashType']);
        ksort($input);
        $hashData = $this->buildHashData($input);
        $calc = hash_hmac('sha512', $hashData, $this->hashSecret);
        if (!hash_equals($calc, (string)$secureHash)) {
            return ['valid' => false];
        }
        $responseCode = (string)($input['vnp_ResponseCode'] ?? '');
        $txnRef = (string)($input['vnp_TxnRef'] ?? '');
        $transactionId = (string)($input['vnp_TransactionNo'] ?? '');
        $amount = (int)($input['vnp_Amount'] ?? 0);
        return [
            'valid' => $responseCode === '00',
            'order_id' => $txnRef,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'response_code' => $responseCode,
        ];
    }

    public function ipnSuccessResponse(): string
    {
        return json_encode(['RspCode' => '00', 'Message' => 'Confirm Success'], JSON_UNESCAPED_UNICODE);
    }

    public function ipnFailResponse(string $message = 'Confirm Failed'): string
    {
        return json_encode(['RspCode' => '97', 'Message' => $message], JSON_UNESCAPED_UNICODE);
    }

    protected function endpoint(): string
    {
        return $this->sandbox
            ? 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'
            : 'https://vnpayment.vn/paymentv2/vpcpay.html';
    }

    protected function buildHashData(array $params): string
    {
        $parts = [];
        foreach ($params as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            $parts[] = urlencode((string)$key) . '=' . urlencode((string)$value);
        }
        return implode('&', $parts);
    }
}
