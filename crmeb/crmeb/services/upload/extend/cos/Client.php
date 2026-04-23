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

use crmeb\exceptions\UploadException;
use crmeb\services\upload\XML;

/**
 * Class Client
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2022/9/29
 * @package crmeb\services\upload\extend\cos
 */
class Client
{

    /**
     * @var string
     */
    protected $accessKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $appid;

    /**
     * @var mixed|string
     */
    protected $bucket;

    /**
     * @var mixed|string
     */
    protected $region;

    /**
     * @var mixed|string
     */
    protected $uploadUrl;

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var array
     */
    protected $response = ['content' => null, 'code' => 200, 'header' => []];

    /**
     * @var array
     */
    protected $request = ['header' => [], 'body' => [], 'host' => ''];

    /**
     * @var string
     */
    protected $cosacl = 'public-read';

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->accessKey = $config['accessKey'] ?? '';
        $this->secretKey = $config['secretKey'] ?? '';
        $this->appid = $config['appid'] ?? '';
        $this->bucket = $config['bucket'] ?? '';
        $this->region = $config['region'] ?? 'ap-chengdu';
        $this->uploadUrl = $config['uploadUrl'] ?? '';
    }

    /**
     * Nhận yêu cầu thực tế
     * @return mảng
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function getResponse()
    {
        $response = $this->response;

        $this->response = ['content' => null, 'http_code' => 200, 'header' => []];

        return $response;
    }

    /**
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function getRequest()
    {
        $request = $this->request;

        $this->request = ['header' => [], 'body' => [], 'host' => ''];

        return $request;
    }

    /**
     * Địa chỉ yêu cầu nối
     * @return chuỗi
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2022/9/29
     */
    protected function makeUpUrl()
    {
        return $this->bucket . '.cos.' . $this->region . '.myqcloud.com';
    }

    /**
     * @return bool
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/29
     */
    protected function ssl()
    {
        return strstr($this->uploadUrl, 'https://') !== false;
    }

    /**
     * Kiểm tra thông số
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2022/9/29
     */
    protected function checkOptions()
    {
        if (!$this->bucket) {
            throw new UploadException('Vui lòng chuyển tên nhóm');
        }
        if (!$this->region) {
            throw new UploadException('Vui lòng nhập khu vực của bạn');
        }
        if (!$this->accessKey) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretId');
        }
        if (!$this->secretKey) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretKey');
        }
    }

    /**
     * Tải tập tin lên
     * @param string $key
     * @param $body
     * @return string[]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/29
     */
    public function putObject(string $key, $body)
    {

        $this->checkOptions();

        $url = $this->makeUpUrl();

        $header = [
            'Content-Type' => 'image/jpeg',
            'x-cos-acl' => $this->cosacl,
            'Content-MD5' => base64_encode(md5($body, true)),
            'Host' => $url
        ];

        $imageUrl = ($this->ssl() ? 'https://' : 'http://') . $url . '/' . $key;

        $res = $this->request($imageUrl, 'PUT', ['body' => $body], $header);

        if ($res && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }

        return [
            'name' => $key,
            'path' => $imageUrl
        ];
    }

    /**
     * Xóa tập tin
     * @param string $bucket
     * @param string $key
     * @return array|false
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/19
     */
    public function deleteObject(string $bucket, string $key)
    {
        $url = $this->getRequestHost($bucket);

        $header = [
            'Host' => $url
        ];

        $res = $this->request('https://' . $url . '/' . $key, 'delete', [], $header);

        if ($res && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }

        return $res;
    }

    /**
     * Nhận danh sách nhóm
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/19
     */
    public function listBuckets()
    {
        $url = 'service.cos.myqcloud.com';

        $header = [
            'Host' => $url
        ];

        $res = $this->request('https://' . $url . '/', 'get', [], $header);

        if ($res && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }

        return $res;
    }

    /**
     * Phát hiện nhóm, trả lại nếu nó không tồn tạitrue
     * @param string $bucket
     * @param string $region
     * @return bool
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function headBucket(string $bucket, string $region = '')
    {
        $url = $this->getRequestHost($bucket, $region);

        $header = [
            'Host' => $url
        ];

        $this->request('https://' . $url, 'head', [], $header);

        $response = $this->getResponse();

        return $response['code'] == 404;
    }

    /**
     * Tạo nhóm
     * @param string $bucket
     * @param string $region
     * @param string $acl
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function createBucket(string $bucket, string $region = '', string $acl = 'public-read')
    {
        return $this->noBodyRequest('put', $bucket, $region, $acl);
    }

    /**
     * kết hợp thànhxml
     * @param array $data
     * @param string $root
     * @param string $itemKey
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    protected function xmlBuild(array $xmlAttr, string $root = 'xml', string $itemKey = 'item')
    {
        $xml = '<' . $root . '>';
        $xml .= '<' . $itemKey . '>';

        foreach ($xmlAttr as $kk => $vv) {
            if (is_array($vv)) {
                foreach ($vv as $v) {
                    $xml .= '<' . $kk . '>' . $v . '</' . $kk . '>';
                }
            } else {
                $xml .= '<' . $kk . '>' . $vv . '</' . $kk . '>';
            }
        }
        $xml .= '</' . $itemKey . '>';
        $xml .= '</' . $root . '>';

        return $xml;
    }

    /**
     * Thiết lập tên miền chéo
     * @param string $bucket
     * @param string $region
     * @param array $data
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function putBucketCors(string $bucket, array $data, string $region = '')
    {
        $url = $this->getRequestHost($bucket, $region);

        $xml = $this->xmlBuild($data, 'CORSConfiguration', 'CORSRule');

        $header = [
            'Host' => $url,
            'Content-Type' => 'application/xml',
            'Content-Length' => strlen($xml),
            'Content-MD5' => base64_encode(md5($xml, true))
        ];

        $res = $this->request('https://' . $url . '/?cors', 'put', ['xml' => $xml], $header);

        if ($res && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }

        return $res;
    }

    /**
     * xóa bỏ
     * @param string $name
     * @param string $region
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function deleteBucket(string $name, string $region = '')
    {
        return $this->noBodyRequest('delete', $name, $region);
    }

    /**
     * Lấy cái xô
     * @param string $name
     * @param string $region
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function getBucketDomain(string $name, string $region = '')
    {
        $this->action = 'domain';
        return $this->noBodyRequest('get', $name, $region);
    }

    /**
     * Ràng buộc tên miền
     * @param string $bucket
     * @param string $region
     * @param array $data
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/19
     */
    public function putBucketDomain(string $bucket, string $region, array $data)
    {
        $url = $this->getRequestHost($bucket, $region);

        $xml = $this->xmlBuild($data, 'DomainConfiguration', 'DomainRule');

        $header = [
            'Host' => $url,
            'Content-Type' => 'application/xml',
            'Content-Length' => strlen($xml),
            'Content-MD5' => base64_encode(md5($xml, true))
        ];

        $res = $this->request('https://' . $url . '/?domain', 'put', ['xml' => $xml], $header);

        if ($res && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }

        return $res;
    }

    /**
     * @param string $bucket
     * @param string $region
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    protected function getRequestHost(string $bucket, string $region = '')
    {
        if (!$this->accessKey) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretId');
        }
        if (!$this->secretKey) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretKey');
        }

        if (strstr($bucket, '-') === false) {
            $bucket = $bucket . '-' . $this->appid;
        }

        return $bucket . '.cos.' . ($region ?: $this->region) . '.myqcloud.com';
    }

    /**
     * @param string $method
     * @param string $bucket
     * @param string $region
     * @param string|null $acl
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/10/17
     */
    public function noBodyRequest(string $method, string $bucket, string $region = '', string $acl = null, bool $isExc = true)
    {

        $url = $this->getRequestHost($bucket, $region);

        $header = [
            'Host' => $url
        ];

        if ($acl) {
            $header['x-cos-acl'] = $acl;
        }

        if (in_array($method, ['put', 'post'])) {
            $header['Content-Length'] = 0;
        }

        $res = $this->request('https://' . $url . '/' . ($this->action ? '?' . $this->action : ''), $method, [], $header);
        $this->action = '';

        if ($isExc) {
            if ($res && !empty($res['Message'])) {
                throw new UploadException($res['Message']);
            }
        }

        return $res;
    }

    /**
     * Đưa ra yêu cầu
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $header
     * @param int $timeout
     * @return array|false|\SimpleXMLElement|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/29
     */
    public function request(string $url, string $method, array $data, array $header = [], int $timeout = 5)
    {

        $this->request['body'] = $data;
        $this->request['host'] = $url;


        $urlAttr = parse_url($url);
        $curl = curl_init($url);
        $method = strtoupper($method);
        //Phương thức yêu cầu
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        //hết thời gian
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //Đặt tiêu đề

        $header = array_merge($header, $this->getSign($url, $method, $urlAttr['path'] ?? '', [], $header));

        $this->request['header'] = $header;

        $clientHeader = [];
        foreach ($header as $key => $item) {
            $clientHeader[] = $key . ':' . $item;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $clientHeader);


        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        //Trả về dữ liệu thu thập thông tin
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //Thông tin tiêu đề đầu ra
        curl_setopt($curl, CURLOPT_HEADER, true);
        //TRUE Chuỗi yêu cầu khi xử lý theo dõi, có sẵn bắt đầu từ PHP 5.1.3. Điều này rất quan trọng, nó cho phép bạn xem yêu cầuheader
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //httpshỏi
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        //posthỏi
        if ($method == 'PUT' && !empty($data['body'])) {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            // Hãy chú ý ở đây'file'Tên khóa có được chỉ định trong địa chỉ tải lên không
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['body']);
        }

        if (!empty($data['xml'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['xml']);
        }

        list($content, $status) = [curl_exec($curl), curl_getinfo($curl), curl_close($curl)];

        $content = trim(substr($content, $status['header_size']));

        $this->response['content'] = $content;
        $this->response['code'] = $status['http_code'];
        $this->response['header'] = $status;

        $res = XML::parse($content);
        if ($res) {
            return $res;
        }
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

    /**
     * Nhận chữ ký
     * @param string $method
     * @param string $urlPath
     * @param array $query
     * @param array $headers
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/27
     */
    public function getSign(string $url, string $method, string $urlPath, array $query = [], array $headers = [])
    {
        return (new Signature($this->accessKey, $this->secretKey, ['signHost' => $url]))->signRequest($method, $urlPath, $query, $headers);
    }
}
