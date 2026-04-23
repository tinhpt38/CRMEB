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

namespace crmeb\services;

use think\facade\Log;

/**
 * Class HttpService
 * @package crmeb\services
 */
class HttpService
{
    /**
     * thông báo lỗi
     * @var string
     */
    private static $curlError;

    /**
     * headerthông tin tiêu đề
     * @var string
     */
    private static $headerStr;

    /**
     * Trạng thái yêu cầu
     * @var int
     */
    private static $status;

    /**
     * @return string
     */
    public static function getCurlError()
    {
        return self::$curlError;
    }

    /**
     * @return mixed
     */
    public static function getStatus()
    {
        return self::$status;
    }

    /**
     * Mô phỏng yêu cầu GET
     * @param $url
     * @param array $data
     * @param bool $header
     * @param int $timeout
     * @return bool|string
     */
    public static function getRequest($url, $data = array(), $header = false, $timeout = 10)
    {
        if (!empty($data)) {
            $url .= (stripos($url, '?') === false ? '?' : '&');
            $url .= (is_array($data) ? http_build_query($data) : $data);
        }
        return self::request($url, 'get', array(), $header, $timeout);
    }

    /**
     * curl hỏi
     * @param $url
     * @param string $method
     * @param array $data
     * @param bool $header
     * @param int $timeout
     * @return bool|string
     */
    public static function request($url, $method = 'get', $data = array(), $header = false, $timeout = 15)
    {
        self::$status = null;
        self::$curlError = null;
        self::$headerStr = null;

        $curl = curl_init($url);
        $method = strtoupper($method);
        //Phương thức yêu cầu
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        //Mang thông số
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        } elseif ($method == 'GET' && count($data)) {
            $url .= '?' . http_build_query($data);
            curl_setopt($curl, CURLOPT_URL, $url);
        }
        //hết thời gian
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //Đặt tiêu đề
        if ($header !== false) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

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
        self::$curlError = curl_error($curl);

        list($content, $status) = [curl_exec($curl), curl_getinfo($curl), curl_close($curl)];
        self::$status = $status;
        self::$headerStr = trim(substr($content, 0, $status['header_size']));
        $content = trim(substr($content, $status['header_size']));
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

    /**
     * Mô phỏng yêu cầu POST
     * @param $url
     * @param $data
     * @param bool $header
     * @param int $timeout
     * @return bool|string
     */
    public static function postRequest($url, $data = array(), $header = false, $timeout = 10)
    {
        return self::request($url, 'post', $data, $header, $timeout);
    }

    /**
     * Nhận loại chuỗi tiêu đề
     * @return mixed
     */
    public static function getHeaderStr()
    {
        return self::$headerStr;
    }

    /**
     * Nhận loại mảng tiêu đề
     * @return array
     */
    public static function getHeader()
    {
        $headArr = explode("\r\n", self::$headerStr);
        return $headArr;
    }

}
