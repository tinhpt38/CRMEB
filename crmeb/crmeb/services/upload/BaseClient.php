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

namespace crmeb\services\upload;

/**
 * Yêu cầu cơ bản
 * Lớp BaseClient
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/5/18
 * @package crmeb\services\upload
 */
abstract class BaseClient
{

    /**
     * Có nên giải quyết hay khôngxml
     * @var bool
     */
    protected $isXml = true;

    /**
     *
     * @var []callable
     */
    protected $curlFn = [];

    /**
     * @param callable $curlFn
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    public function middleware(callable $curlFn)
    {
        $this->curlFn[] = $curlFn;
        return $this;
    }

    /**
     * Đưa ra yêu cầu
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $clientHeader
     * @param int $timeout
     * @return array|extend\cos\SimpleXMLElement
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/18
     */
    protected function requestClient(string $url, string $method, array $data = [], array $clientHeader = [], int $timeout = 10)
    {
        $headers = [];
        foreach ($clientHeader as $key => $item) {
            $headers[] = $key . ':' . $item;
        }
        $curl = curl_init($url);
        //Phương thức yêu cầu
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        //posthỏi
        if (!empty($data['body'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['body']);
        } else if (!empty($data['json'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['json']));
        } else {
            $curlFn = $this->curlFn;
            foreach ($curlFn as $item) {
                if ($item instanceof \Closure) {
                    $curlFn($curl);
                }
            }
        }
        //hết thời gian
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //Đặt tiêu đề
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

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
        [$content, $status] = [curl_exec($curl), curl_getinfo($curl)];
        $content = trim(substr($content, $status['header_size']));
        if ($this->isXml) {
            return XML::parse($content);
        } else {
            return json_decode($content, true);
        }
    }
}
