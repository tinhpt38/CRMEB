<?php
declare(strict_types=1);

namespace crmeb\services\pay\extend\zalopay;

use crmeb\exceptions\PayException;
use think\facade\Log;

/**
 * ZaloPay Open API v2 — create order.
 * @see https://docs.zalopay.vn/
 */
class ZaloPayClient
{
    protected int $appId;
    protected string $key1;
    protected string $key2;
    protected string $returnUrl;
    protected string $ipnUrl;
    protected bool $sandbox;

    public function __construct(array $config)
    {
        $this->appId = (int)($config['appId'] ?? 0);
        $this->key1 = (string)($config['key1'] ?? '');
        $this->key2 = (string)($config['key2'] ?? '');
        $this->returnUrl = (string)($config['returnUrl'] ?? '');
        $this->ipnUrl = (string)($config['ipnUrl'] ?? '');
        $this->sandbox = (bool)($config['sandbox'] ?? true);
        if ($this->appId <= 0 || $this->key1 === '') {
            throw new PayException('ZaloPay chưa cấu hình App ID hoặc Key1');
        }
    }

    /**
     * @return array{orderUrl:string,appTransId:string}
     */
    public function createPayment(string $orderId, string $amount, string $description): array
    {
        $amountInt = (int)round((float)$amount);
        if ($amountInt <= 0) {
            throw new PayException('Số tiền thanh toán ZaloPay không hợp lệ');
        }
        $appTransId = date('ymd') . '_' . preg_replace('/[^a-zA-Z0-9_]/', '', $orderId);
        $appTime = round(microtime(true) * 1000);
        $embedData = json_encode(['redirecturl' => $this->returnUrl, 'order_id' => $orderId], JSON_UNESCAPED_UNICODE);
        $item = json_encode([['itemid' => $orderId, 'itemname' => $description, 'itemprice' => $amountInt, 'itemquantity' => 1]], JSON_UNESCAPED_UNICODE);
        $data = $this->appId . '|' . $appTransId . '|' . $embedData . '|' . $item . '|' . $appTime . '|' . $this->key1;
        $mac = hash_hmac('sha256', $data, $this->key1);
        $payload = [
            'app_id' => $this->appId,
            'app_trans_id' => $appTransId,
            'app_user' => 'crmeb_user',
            'app_time' => $appTime,
            'amount' => $amountInt,
            'item' => $item,
            'embed_data' => $embedData,
            'description' => mb_substr($description, 0, 256),
            'bank_code' => '',
            'callback_url' => $this->ipnUrl,
            'mac' => $mac,
        ];
        $response = $this->postForm($this->endpoint(), $payload);
        if (($response['return_code'] ?? -1) != 1) {
            throw new PayException('ZaloPay: ' . ($response['return_message'] ?? 'Không tạo được giao dịch'));
        }
        $orderUrl = (string)($response['order_url'] ?? '');
        if ($orderUrl === '') {
            throw new PayException('ZaloPay không trả về order_url');
        }
        return ['orderUrl' => $orderUrl, 'appTransId' => $appTransId];
    }

    /**
     * @return array{valid:bool,order_id?:string,transaction_id?:string}
     */
    public function verifyCallback(array $input): array
    {
        $data = (string)($input['data'] ?? '');
        $mac = (string)($input['mac'] ?? '');
        $calc = hash_hmac('sha256', $data, $this->key2);
        if (!hash_equals($calc, $mac)) {
            return ['valid' => false];
        }
        $decoded = json_decode($data, true);
        if (!is_array($decoded)) {
            return ['valid' => false];
        }
        $embed = json_decode((string)($decoded['embed_data'] ?? ''), true);
        $orderId = is_array($embed) ? (string)($embed['order_id'] ?? '') : '';
        if ($orderId === '') {
            $appTransId = (string)($decoded['app_trans_id'] ?? '');
            if (($pos = strpos($appTransId, '_')) !== false) {
                $orderId = substr($appTransId, $pos + 1);
            } else {
                $orderId = $appTransId;
            }
        }
        return [
            'valid' => true,
            'order_id' => $orderId,
            'transaction_id' => (string)($decoded['zp_trans_id'] ?? ''),
        ];
    }

    protected function endpoint(): string
    {
        return $this->sandbox
            ? 'https://sb-openapi.zalopay.vn/v2/create'
            : 'https://openapi.zalopay.vn/v2/create';
    }

    protected function postForm(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_TIMEOUT => 30,
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        if ($errno) {
            Log::error('ZaloPay curl error: ' . $errno);
            throw new PayException('Không kết nối được ZaloPay');
        }
        $decoded = json_decode((string)$body, true);
        return is_array($decoded) ? $decoded : [];
    }
}
