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

namespace crmeb\services\upload\extend\obs;


use crmeb\exceptions\UploadException;
use crmeb\services\upload\BaseClient;
use crmeb\services\upload\extend\cos\XML;

/**
 * Tải lên đám mây Huawei
 * Lớp khách hàng
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/5/18
 * @package crmeb\services\upload\extend\obs
 */
class Client extends BaseClient
{
    const HEADER_PREFIX = 'x-obs-';

    const INTEREST_HEADER_KEY_LIST = ['content-type', 'content-md5', 'date'];

    const ALTERNATIVE_DATE_HEADER = 'x-obs-date';

    const ALLOWED_RESOURCE_PARAMTER_NAMES = [
        'acl',
        'policy',
        'torrent',
        'logging',
        'location',
        'storageinfo',
        'quota',
        'storagepolicy',
        'requestpayment',
        'versions',
        'versioning',
        'versionid',
        'uploads',
        'uploadid',
        'partnumber',
        'website',
        'notification',
        'lifecycle',
        'deletebucket',
        'delete',
        'cors',
        'restore',
        'tagging',
        'response-content-type',
        'response-content-language',
        'response-expires',
        'response-cache-control',
        'response-content-disposition',
        'response-content-encoding',
        'x-image-process',

        'backtosource',
        'storageclass',
        'replication',
        'append',
        'position',
        'x-oss-process'
    ];

    //xôacl
    const OBS_ACL = [
        [
            'value' => 'public-read',
            'label' => 'đọc trước công chúng(gợi ý)',
        ],
        [
            'value' => 'public-read-write',
            'label' => 'biết chữ công cộng',
        ],
    ];
    //mặc địnhacl
    const DEFAULT_OBS_ACL = 'public-read';

    protected $isCname = false;

    protected $pathStyle;

    /**
     * @var
     */
    protected $accessKeyId;

    /**
     * @var
     */
    protected $secretKey;

    /**
     * Tên nhóm
     * @var string
     */
    protected $bucketName;

    /**
     * khu vực
     * @var string
     */
    protected $region;

    /**
     * @var mixed|string
     */
    protected $uploadUrl;

    /**
     * @var string
     */
    protected $baseUrl = 'obs.cn-north-1.myhuaweicloud.com';

    protected $type = 'hw';

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->accessKeyId = $config['accessKey'] ?? '';
        $this->secretKey = $config['secretKey'] ?? '';
        $this->bucketName = $config['bucket'] ?? '';
        $this->region = $config['region'] ?? 'ap-chengdu';
        $this->uploadUrl = $config['uploadUrl'] ?? '';
        $this->type = $config['type'] ?? 'hw';
    }

    /**
     * Phát hiện
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    protected function checkOptions()
    {
        if (!$this->bucketName) {
            throw new UploadException('Vui lòng chuyển tên nhóm');
        }
        if (!$this->region) {
            throw new UploadException('Vui lòng nhập khu vực của bạn');
        }
        if (!$this->accessKeyId) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretId');
        }
        if (!$this->secretKey) {
            throw new UploadException('Xin vui lòng chuyển vàoSecretKey');
        }

        return $this;
    }

    /**
     * Tải ảnh lên
     * @param string $key
     * @param $body
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function putObject(string $key, $body, string $contentType = 'image/jpeg')
    {
        $header = [
            'Host' => $this->getRequestUrl($this->bucketName, $this->region),
            'Content-Type' => $contentType,
            'Content-Length' => strlen($body),
        ];

        $res = $this->checkOptions()->request('https://' . $header['Host'] . '/' . $key, 'PUT', [
            'bucket' => $this->bucketName,
            'body' => $body
        ], $header);

        return $this->response($res);
    }

    /**
     * Xóa đối tượng tải lên
     * @param string $key
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function deleteObject(string $key)
    {
        $header = [
            'Host' => $this->getRequestUrl($this->bucketName, $this->region),
        ];

        $res = $this->request('https://' . $header['Host'] . '/' . $key, 'DELETE', [
            'bucket' => $this->bucketName
        ], $header);

        return $this->response($res);
    }

    /**
     * lấy xô
     * @return false|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function listBuckets()
    {
        $header = [
            'Host' => $this->getRequestUrl('', $this->region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/', 'GET', [], []);
        return $this->response($res);
    }

    public function headBucket(string $bucket, string $region)
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/', 'HEAD', [], []);
        return $this->response($res);
    }

    /**
     * Đặt chính sách nhóm
     * @param string $bucket
     * @param string $region
     * @param array $data
     * @return mixed
     *
     * @date 2023/06/08
     * @author yyw
     */
    public function putPolicy(string $bucket, string $region, array $data)
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
            "Content-Type" => "application/json"
        ];
        $res = $this->request('https://' . $header['Host'] . '/?policy', 'PUT', [
            'bucket' => $bucket,
            'json' => $data
        ], $header);

        return $this->response($res);
    }

    /**
     * Tạo nhóm
     * @param string $bucket
     * @param string $region
     * @param string $acl
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function createBucket(string $bucket, string $region, string $acl = self::DEFAULT_OBS_ACL)
    {
        $header = [
            'x-obs-acl' => $acl,
            'Host' => $this->getRequestUrl($bucket, $region),
            "Content-Type" => "application/xml"
        ];
        $xml = "<CreateBucketConfiguration><Location>{$region}</Location></CreateBucketConfiguration>";
        $res = $this->request('https://' . $header['Host'] . '/', 'PUT', [
            'bucket' => $bucket,
            'body' => $xml
        ], $header);

        return $this->response($res);
    }

    /**
     * Xóa nhóm
     * @param string $bucket
     * @param string $region
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function deleteBucket(string $bucket, string $region)
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/', 'DELETE', [
            'bucket' => $bucket
        ], $header);

        return $this->response($res);
    }

    /**
     * Lấy tên miền tùy chỉnh của nhóm
     * @param string $bucket
     * @param string $region
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function getBucketDomain(string $bucket, string $region)
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/?customdomain', 'GET', [
            'bucket' => $bucket
        ], $header);

        return $this->response($res);
    }

    /**
     * Đặt tên miền tùy chỉnh cho nhóm
     * @param string $bucket
     * @param string $region
     * @param array $data
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function putBucketDomain(string $bucket, string $region, array $data = [])
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/?customdomain=' . $data['domainname'], 'PUT', [
            'bucket' => $bucket
        ], $header);

        return $this->response($res);
    }

    /**
     * Thiết lập tên miền chéo
     * @return bool
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function putBucketCors(string $bucket, string $region, array $data = [])
    {
        $xml = $this->xmlBuild($data, 'CORSConfiguration', 'CORSRule');
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
            'Content-Type' => 'application/xml',
            'Content-Length' => strlen($xml),
            'Content-MD5' => base64_encode(md5($xml, true))
        ];
        $res = $this->request('https://' . $header['Host'] . '/?cors', 'PUT', [
            'bucket' => $bucket,
            'body' => $xml
        ], $header);

        return $this->response($res);
    }

    /**
     * Xóa tên miền chéo
     * @param string $bucket
     * @param string $region
     * @return mixed
     *
     * @date 2023/06/08
     * @author yyw
     */
    public function deleteBucketCors(string $bucket, string $region)
    {
        $header = [
            'Host' => $this->getRequestUrl($bucket, $region),
        ];
        $res = $this->request('https://' . $header['Host'] . '/?cors', 'DELETE', [
            'bucket' => $bucket,
        ], $header);

        return $this->response($res);
    }

    /**
     * @param $res
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    protected function response($res)
    {
        if (!empty($res['Code']) && !empty($res['Message'])) {
            throw new UploadException($res['Message']);
        }
        return $res;
    }

    /**
     * Nhận tên miền được yêu cầu
     * @param string $bucket
     * @param string $region
     * @return string
     *
     * @date 2023/06/08
     * @author yyw
     */
    protected function getRequestUrl(string $bucket = '', string $region = '')
    {
        if ($this->type == 'hw') {
            $url = '.myhuaweicloud.com';  // Huawei
        } else {
            $url = '.ctyun.cn';  // Thiên Nhất
        }
        if ($bucket) {
            return $bucket . '.obs.' . $region . $url;
        } else {
            return 'obs.' . $region . $url;
        }
    }


    /**
     * Tên lãnh thổ
     * @return \string[][]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    public function getRegion()
    {
        return [
            [
                'value' => 'cn-north-1',
                'label' => 'Bắc Trung Quốc-Bắc Kinh 1',
            ],
//            [
//                'value' => 'cn-north-4',
//                'label' => 'Bắc Trung Quốc-Bắc Kinh 4',
//            ],
            [
                'value' => 'cn-north-9',
                'label' => 'Bắc Trung Quốc-Ulanqab 1',
            ],
            [
                'value' => 'cn-east-2',
                'label' => 'Đông Trung Quốc-Thượng Hải 2',
            ],
            [
                'value' => 'cn-east-3',
                'label' => 'Đông Trung Quốc-Thượng Hải 1',
            ],
            [
                'value' => 'cn-south-1',
                'label' => 'Nam Trung Quốc-Quảng Châu',
            ],
            [
                'value' => 'ap-southeast-1',
                'label' => 'Trung Quốc-Hồng Kông',
            ],
            [
                'value' => 'cn-south-4',
                'label' => 'Môi trường người dùng thân thiện với Nam Trung Quốc-Quảng Châu',
            ],
            [
                'value' => 'cn-southwest-2',
                'label' => 'Tây Nam-Quý Dương 1',
            ],
            [
                'value' => 'la-north-2',
                'label' => 'Châu Mỹ Latinh-Thành phố Mexico II',
            ],
            [
                'value' => 'na-mexico-1',
                'label' => 'Mỹ Latinh-Thành phố Mexico 1',
            ],
            [
                'value' => 'sa-brazil-1',
                'label' => 'Châu Mỹ Latinh - Sao Paulo 1',
            ],
            [
                'value' => 'la-south-2',
                'label' => 'Châu Mỹ Latinh - Santiago',
            ],
            [
                'value' => 'tr-west-1',
                'label' => 'Türkiye-Istanbul',
            ],
            [
                'value' => 'ap-southeast-2',
                'label' => 'Châu Á Thái Bình Dương-Bangkok',
            ],
            [
                'value' => 'ap-southeast-3',
                'label' => 'Châu Á Thái Bình Dương- Singapore',
            ],
            [
                'value' => 'af-south-1',
                'label' => 'Châu Phi-Johannesburg',
            ]
        ];
    }

    /**
     * Đặt tên nhóm
     * @param string $bucketName
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function setBucketName(string $bucketName)
    {
        $this->bucketName = $bucketName;
        return $this;
    }


    /**
     * Nhận chữ ký
     * @param array $result
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    protected function getSign(array $result)
    {
        $result['headers']['Date'] = gmdate('D, d M Y H:i:s \G\M\T');
        $canonicalstring = $this->makeCanonicalstring($result['method'], $result['headers'], $result['pathArgs'], $result['dnsParam'], $result['uriParam']);

        $result['cannonicalRequest'] = $canonicalstring;

        $signature = base64_encode(hash_hmac('sha1', $canonicalstring, $this->secretKey, true));

        $authorization = 'OBS ' . $this->accessKeyId . ':' . $signature;

        $result['headers']['Authorization'] = $authorization;

        return $result;
    }

    /**
     * Xử lý dữ liệu chữ ký
     * @param $method
     * @param $headers
     * @param $pathArgs
     * @param $bucketName
     * @param $objectKey
     * @param null $expires
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    public function makeCanonicalstring($method, $headers, $pathArgs, $bucketName, $objectKey, $expires = null)
    {
        $buffer = [];
        $buffer[] = $method;
        $buffer[] = "\n";
        $interestHeaders = [];

        foreach ($headers as $key => $value) {
            $key = strtolower($key);
            if (in_array($key, self::INTEREST_HEADER_KEY_LIST) || strpos($key, self::HEADER_PREFIX) === 0) {
                $interestHeaders[$key] = $value;
            }
        }

        if (array_key_exists(self::ALTERNATIVE_DATE_HEADER, $interestHeaders)) {
            $interestHeaders['date'] = '';
        }

        if ($expires !== null) {
            $interestHeaders['date'] = strval($expires);
        }

        if (!array_key_exists('content-type', $interestHeaders)) {
            $interestHeaders['content-type'] = '';
        }

        if (!array_key_exists('content-md5', $interestHeaders)) {
            $interestHeaders['content-md5'] = '';
        }

        ksort($interestHeaders);

        foreach ($interestHeaders as $key => $value) {
            if (strpos($key, self::HEADER_PREFIX) === 0) {
                $buffer[] = $key . ':' . $value;
            } else {
                $buffer[] = $value;
            }
            $buffer[] = "\n";
        }

        $uri = '';

        $bucketName = $this->isCname ? $headers['Host'] : $bucketName;

        if ($bucketName) {
            $uri .= '/';
            $uri .= $bucketName;
            if (!$this->pathStyle) {
                $uri .= '/';
            }
        }

        if ($objectKey) {
            if (!($pos = strripos($uri, '/')) || strlen($uri) - 1 !== $pos) {
                $uri .= '/';
            }
            $uri .= $objectKey;
        }

        $buffer[] = $uri === '' ? '/' : $uri;


        if (!empty($pathArgs)) {
            ksort($pathArgs);
            $_pathArgs = [];
            foreach ($pathArgs as $key => $value) {
                if (in_array(strtolower($key), self::ALLOWED_RESOURCE_PARAMTER_NAMES) || strpos($key, self::HEADER_PREFIX) === 0) {
                    $_pathArgs[] = $value === null || $value === '' ? $key : $key . '=' . urldecode($value);
                }
            }
            if (!empty($_pathArgs)) {
                $buffer[] = '?';
                $buffer[] = implode('&', $_pathArgs);
            }
        }

        return implode('', $buffer);
    }

    /**
     * Đưa ra yêu cầu
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $clientHeader
     * @param int $timeout
     * @return false|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function request(string $url, string $method, array $data = [], array $clientHeader = [], int $timeout = 10)
    {
        $method = strtoupper($method);
        $urlAttr = pathinfo($url);
        $urlParse = parse_url($urlAttr['dirname'] ?? '');

        $uriParam = '';
        if ($urlAttr['dirname'] !== 'https:') {
            if (isset($urlParse['path'])) {
                $uriParam .= substr($urlParse['path'], 1) . '/';
            }
            if (isset($urlAttr['basename'])) {
                $uriParam .= $urlAttr['basename'];
            }
        }

        $result = $this->getSign([
            'method' => $method,
            'headers' => $clientHeader,
            'pathArgs' => '',
            'dnsParam' => $data['bucket'] ?? '',
            'uriParam' => $uriParam,
        ]);

        return $this->requestClient($url, $method, $data, $result['headers'], $timeout);
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

}
