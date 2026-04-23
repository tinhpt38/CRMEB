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
namespace crmeb\services\pay\extend\allinpay;

use crmeb\exceptions\ApiException;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Class Client
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2022/12/27
 * @package crmeb\services\pay\extend\allinpay
 */
class Client
{

    //Địa chỉ sản xuất
    const API_URL = 'https://vsp.allinpay.com/apiweb/';

    //Địa chỉ giao diện thử nghiệm
    const BETA_API_URL = 'https://syb-test.allinpay.com/apiweb/';

    //số phiên bản
    const VERSION_NUM_11 = '11';

    //số phiên bản
    const VERSION_NUM_12 = '12';


    protected $signType = 'MD5';

    /**
     * @var string
     */
    protected $cusid = '';

    /**
     * @var string
     */
    protected $appid = '';

    /**
     * @var string
     */
    protected $privateKey = '';

    /**
     * @var
     */
    protected $publicKey = '';

    /**
     * địa chỉ gọi lại
     * @var string
     */
    protected $notifyUrl = '';

    /**
     * Có nên kiểm tra không
     * @var bool
     */
    protected $isBeta = true;

    /**
     * debugngười mẫu
     * @var bool
     */
    protected $isDebug = true;

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->appid = $config['appid'] ?? '';
        $this->cusid = $config['cusid'] ?? '';
        $this->privateKey = $config['privateKey'] ?? '';
        $this->publicKey = $config['publicKey'] ?? '';
        $this->notifyUrl = $config['notifyUrl'] ?? '';
        $this->isBeta = $config['isBeta'] ?? true;
    }

    /**
     * Gửi yêu cầu
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function send(string $url, array $options = [])
    {
        $data = $options['data'] ?? [];
        $header = $options['header'] ?? [];

        $data['cusid'] = $this->cusid;
        $data['appid'] = $this->appid;
        if (!isset($data['version'])) {
            $data['version'] = self::VERSION_NUM_11;
        }

        $data['signtype'] = $this->signType;
        $data['randomstr'] = uniqid();

        if (!empty($options['form'])) {
            $data['charset'] = 'UTF-8';
            $data['version'] = self::VERSION_NUM_12;
            $data['sign'] = $this->sign($data);
            return $data;
        }

        $data['sign'] = $this->sign($data);

        $response = $this->request($url, $data, 'post', $header);

        if ('SUCCESS' !== $response['retcode']) {
            throw new ApiException($response['retmsg']);
        }

        if (!empty($response['trxstatus']) && !in_array($response['trxstatus'], ['0000', '2008', '2000'])) {
            throw new ApiException($response['errmsg']);
        }

        if ($this->validSign($response)) {
            return $response;
        }

        throw new ApiException('Đơn hàng được tạo thành công nhưng xác minh chữ ký không thành công');
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $timeout
     * @return mixed
     */
    public function request(string $url, array $data = [], string $method = 'post', array $header = [], int $timeout = 10)
    {
        $headerData = [];
        if ($header) {
            foreach ($header as $key => $item) {
                $headerData[] = $key . ':' . $item;
            }
        }

        $content = HttpService::request($this->baseUrl($url), $method, $data, $headerData, $timeout);

        $respones = json_decode($content, true, 512, JSON_BIGINT_AS_STRING);

        $this->debugLog('API response decoded:', ['content' => $respones, 'header' => $header]);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ApiException('Failed to parse JSON: ' . json_last_error_msg());
        }

        return $respones;
    }

    /**
     * @param string $message
     * @param array $contents
     */
    protected function debugLog(string $message, array $contents = [])
    {
        $this->isDebug && Log::debug($message, $contents);
    }

    /**
     * @param string|null $url
     * @return string
     */
    protected function baseUrl(string $url = null)
    {
        $baseUrl = $this->isBeta ? self::BETA_API_URL : self::API_URL;
        if ($url) {
            $baseUrl .= $url;
        }

        return $baseUrl;
    }

    /**
     * @param array $data
     * @return string
     */
    public function sign(array $data)
    {

        $private_key = $this->privateKey;

        if ($this->signType === 'MD5') {
            $data['key'] = $private_key;
            ksort($data);

            $bufSignSrc = $this->toUrlParams($data);

            return md5($bufSignSrc);
        } else {
            ksort($data);

            $bufSignSrc = $this->toUrlParams($data);

            $private_key = chunk_split($private_key, 64, "\n");

            $key = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key) . "-----END RSA PRIVATE KEY-----";

            openssl_sign($bufSignSrc, $signature, $key);

            $sign = base64_encode($signature);//Nội dung được mã hóa thường chứa các ký tự đặc biệt và yêu cầu chuyển đổi mã hóa. Khi truyền qua các URL giữa các mạng, hãy chú ý xem liệu mã hóa base64 có an toàn cho URL hay không.

            return $sign;
        }
    }

    /**
     * @param array $data
     * @return string
     */
    public function toUrlParams(array $data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @param array $data
     * @return false|int
     */
    public function validSign(array $data)
    {
        $sign = $data['sign'];
        unset($data['sign']);

        if ($this->signType === 'MD5') {
            $data['key'] = $this->privateKey;
            ksort($data);
            $bufSignSrc = $this->toUrlParams($data);
            return strtolower($sign) == strtolower(md5($bufSignSrc));
        } else {
            ksort($data);
            $bufSignSrc = $this->toUrlParams($data);
            $public_key = $this->publicKey;

            $public_key = chunk_split($public_key, 64, "\n");

            $key = "-----BEGIN PUBLIC KEY-----\n$public_key-----END PUBLIC KEY-----\n";

            return openssl_verify($bufSignSrc, base64_decode($sign), $key);
        }
    }

    /**
     * @param string $notifyUrl
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/7
     */
    public function setNotifyUrl(string $notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }
}
