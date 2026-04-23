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

namespace crmeb\services\upload\extend\cos;

/**
 * Class Tạo chữ ký
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2022/9/26
 * @package crmeb\services\upload\extend\cos
 */
class Signature
{
    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var array
     */
    private $options;

    /**
     * Signature constructor.
     * @param string $accessKey
     * @param string $secretKey
     * @param array $options
     * @param string $token
     */
    public function __construct(string $accessKey, string $secretKey, array $options = [], string $token = '')
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->options = $options;
        $this->token = $token;
        $this->signHeader = [
            'cache-control',
            'content-disposition',
            'content-encoding',
            'content-length',
            'content-md5',
            'content-type',
            'expect',
            'expires',
            'host',
            'if-match',
            'if-modified-since',
            'if-none-match',
            'if-unmodified-since',
            'origin',
            'range',
            'response-cache-control',
            'response-content-disposition',
            'response-content-encoding',
            'response-content-language',
            'response-content-type',
            'response-expires',
            'transfer-encoding',
            'versionid',
        ];
        date_default_timezone_set('PRC');
    }

    public function needCheckHeader($header)
    {
        if ($this->startWith($header, 'x-cos-')) {
            return true;
        }
        if (in_array($header, $this->signHeader)) {
            return true;
        }
        return false;
    }

    /**
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/29
     * @param $haystack
     * @param $needle
     * @return bool
     */
    protected function startWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string $method
     * @param string $urlPath
     * @param array $querys
     * @param array $headers
     * @return string[]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/26
     */
    public function signRequest(string $method, string $urlPath, array $querys = [], array $headers = [])
    {
        $authorization = $this->createAuthorization($method, $urlPath, $querys, $headers);
        return ['Authorization' => $authorization];
    }

    /**
     * @param string $method
     * @param string $urlPath
     * @param array $querys
     * @param array $headers
     * @param string $expires
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/26
     */
    public function createAuthorization(string $method, string $urlPath, array $querys = [], array $headers = [], $expires = '+30 minutes')
    {
        if (is_null($expires) || !strtotime($expires)) {
            $expires = '+30 minutes';
        }
        $signTime = ( string )(time() - 60) . ';' . ( string )(strtotime($expires));
        $urlParamListArray = [];
        foreach ($querys as $query) {
            if (!empty($query)) {
                $tmpquery = explode('=', $query);
                //Để đảm bảo rằng khóa CI chứa=Số này cũng có thể được chuyển bình thường. CI đã được mã hóa trước lớp này. Nó cần phải được tháo rời và mã hóa lại để tránh trường hợp phát nổ ở trên bị tháo rời không chính xác.
                $key = strtolower(rawurlencode(urldecode($tmpquery[0])));
                if (count($tmpquery) >= 2) {
                    $value = $tmpquery[1];
                } else {
                    $value = "";
                }
                //hostcông tắc
                if (!$this->options['signHost'] && $key == 'host') {
                    continue;
                }
                $urlParamListArray[$key] = $key . '=' . $value;
            }
        }
        ksort($urlParamListArray);
        $urlParamList = join(';', array_keys($urlParamListArray));
        $httpParameters = join('&', array_values($urlParamListArray));

        $headerListArray = [];
        foreach ($headers as $key => $value) {
            $key = strtolower(urlencode($key));
            $value = rawurlencode($value);
            if (!$this->options['signHost'] && $key == 'host') {
                continue;
            }
            if ($this->needCheckHeader($key)) {
                $headerListArray[$key] = $key . '=' . $value;
            }
        }
        ksort($headerListArray);
        $headerList = join(';', array_keys($headerListArray));
        $httpHeaders = join('&', array_values($headerListArray));
        $httpString = strtolower($method) . "\n" . urldecode($urlPath) . "\n" . $httpParameters .
            "\n" . $httpHeaders . "\n";

        $sha1edHttpString = sha1($httpString);
        $stringToSign = "sha1\n$signTime\n$sha1edHttpString\n";
        $signKey = hash_hmac('sha1', $signTime, trim($this->secretKey));
        $signature = hash_hmac('sha1', $stringToSign, $signKey);
        $authorization = 'q-sign-algorithm=sha1&q-ak=' . trim($this->accessKey) .
            "&q-sign-time=$signTime&q-key-time=$signTime&q-header-list=$headerList&q-url-param-list=$urlParamList&" .
            "q-signature=$signature";
        return $authorization;
    }

    /**
     * @param string $url
     * @param string $method
     * @param string $urlPath
     * @param array $querys
     * @param array $headers
     * @param string $expires
     * @return string[]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/26
     */
    public function createPresignedUrl(string $url, string $method, string $urlPath, array $querys = [], array $headers = [], string $expires = '+30 minutes')
    {
        $authorization = $this->createAuthorization($method, $urlPath, $querys, $headers, $expires);
        $uri = $url;
        $query = 'sign=' . urlencode($authorization) . '&' . implode('&', $querys);
        if ($this->token != null) {
            $query = $query . '&x-cos-security-token=' . $this->token;
        }
        return [$uri, $query];
    }
}
