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


use crmeb\exceptions\ApiException;

/**
 * Class AccessTokenServeService
 * @package crmeb\services
 */
class AccessTokenServeService extends HttpService
{
    /**
     * Cấu hình
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $cacheTokenPrefix = "_crmeb_plat";

    /**
     * @var string
     */
    protected $apiHost = 'https://sms.crmeb.net/api/';

    /**
     * Địa chỉ hộp cát
     * @var string
     */
    protected $sandBoxApi = 'https://api_v2.crmeb.net/api/';

    /**
     * chế độ hộp cát
     * @var bool
     */
    protected $sandBox = false;

    /**
     * Giao diện đăng nhập
     */
    const USER_LOGIN = "v2/user/login";


    /**
     * AccessTokenServeService constructor.
     * @param string $account
     * @param string $secret
     */
    public function __construct(string $account, string $secret)
    {
        $this->account = $account;
        $this->secret = $secret;
    }

    /**
     * Nhận cấu hình
     * @return array
     */
    public function getConfig()
    {
        return [
            'access_key' => $this->account,
            'secret_key' => $this->secret
        ];
    }

    /**
     * Nhận bộ đệmtoken
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken()
    {
        $accessTokenKey = md5($this->account . '_v2_' . $this->secret . $this->cacheTokenPrefix);
        $cacheToken = CacheService::get($accessTokenKey);
        if (!$cacheToken) {
            $getToken = $this->getTokenFromServer();
            CacheService::set($accessTokenKey, $getToken['access_token'], $getToken['expires_in'] - 60);
            $cacheToken = $getToken['access_token'];
        }
        $this->accessToken = $cacheToken;

        return $cacheToken;

    }

    /**
     * Nhận từ máy chủtoken
     * @return mixed
     */
    public function getTokenFromServer()
    {
        $params = [
            'access_key' => $this->account,
            'secret_key' => $this->secret,
        ];
        $response = $this->postRequest($this->get(self::USER_LOGIN), $params);
        $response = json_decode($response, true);
        if (!$response) {
            throw new ApiException('Không thể lấy được mã thông báo{:msg}', ['msg' => '']);
        }
        if ($response['status'] === 200) {
            return $response['data'];
        } else {
            throw new ApiException('Không thể lấy được mã thông báo{:msg}', ['msg' => ':' . $response['msg']]);
        }
    }

    /**
     * hỏi
     * @param string $url
     * @param array $data
     * @param string $method
     * @param bool $isHeader
     * @return array|mixed
     */
    public function httpRequest(string $url, array $data = [], string $method = 'POST', bool $isHeader = true, array $header = [])
    {
        if ($isHeader) {
            $this->getToken();
            if (!$this->accessToken) {
                throw new ApiException('Cấu hình đã được thay đổi hoặc mã thông báo đã hết hạn');
            }
            $header = array_merge($header, ['Authorization:Bearer-' . $this->accessToken]);
        }

        $res = $this->request($this->get($url), $method, $data, $header);
        if (!$res) {
            throw new ApiException('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');

        }
        $result = json_decode($res, true) ?: false;
        if (!isset($result['status']) || $result['status'] != 200) {
            throw new ApiException($result['msg']);
        }
        return $result['data'] ?? [];

    }

    /**
     * @param string $apiUrl
     * @return string
     */
    public function get(string $apiUrl = '')
    {
        if ($this->sandBox) {
            return $this->sandBoxApi . $apiUrl;
        }
        return $this->apiHost . $apiUrl;
    }
}
