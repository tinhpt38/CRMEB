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

namespace crmeb\services\easywechat\open3rd;


use crmeb\exceptions\ApiException;
use crmeb\services\HttpService;

/**
 * Class AccessTokenServeService
 * @package crmeb\services
 */
class AccessToken extends HttpService
{
    /**
     * Nền tảng của bên thứ ba appid
     * @var string
     */
    protected $component_appid;

    /**
     * Nền tảng của bên thứ ba appsecret
     * @var string
     */
    protected $component_appsecret;

    /**
     * Được thúc đẩy bởi nền WeChat ticket
     * @var string
     */
    protected $component_verify_ticket;

    /**
     * @var Cache|null
     */
    protected $cache;

    /**
     * Nền tảng của bên thứ batoken
     * @var string
     */
    protected $component_access_token;

    /**
     * Bên được ủy quyềnappid
     * @var string
     */
    protected $authorizer_appid;

    /**
     * Mã thông báo cuộc gọi giao diện (giá trị trả về này chỉ khả dụng khi tài khoản chính thức/chương trình nhỏ được ủy quyền có quyền API）
     * @var string
     */
    protected $authorizer_access_token;

    /**
     * Mã thông báo làm mới (giá trị trả về này chỉ khả dụng khi tài khoản chính thức được ủy quyền có quyền API). Mã thông báo làm mới chủ yếu được sử dụng bởi các nền tảng của bên thứ ba để lấy và làm mới Authorizer_access_token của người dùng được ủy quyền. Sau khi bị mất, người dùng chỉ có thể nhận lại mã thông báo làm mới mới bằng cách ủy quyền lại cho nó. Sau khi người dùng ủy quyền lại, mã thông báo làm mới trước đó sẽ không hợp lệ.
     * @var string
     */
    protected $authorizer_refresh_token;

    /**
     * @var string
     */
    protected $cacheTokenPrefix = "component_access_token_crmeb";

    /**
     * Nhận nền tảng của bên thứ batoken
     * @var string
     */
    const TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';

    /**
     * Nhận thông tin ủy quyền bằng mã ủy quyền
     */
    const AUTH_INFO = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth';
    /**
     * Nhận và làm mới các cuộc gọi giao diệntoken
     */
    const AUTHORIZER_TOKEN = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token';

    /**
     * AccessTokenServeService constructor.
     * @param string $component_appid
     * @param string $component_appsecret
     * @param string $component_verify_ticket
     * @param string $authorizer_appid
     * @param Cache|null $cache
     */
    public function __construct(string $component_appid, string $component_appsecret, string $component_verify_ticket, string $authorizer_appid = '', $cache = null)
    {
        if (!$cache) {
            $cache = app()->make(\crmeb\services\CacheService::class);
        }
        $this->component_appid = $component_appid;
        $this->component_appsecret = $component_appsecret;
        $this->component_verify_ticket = $component_verify_ticket;
        $this->authorizer_appid = $authorizer_appid;
        $this->cache = $cache;
    }

    /**
     * Nhận cấu hình
     * @return array
     */
    public function getConfig()
    {
        return [
            'component_appid' => $this->component_appid,
            'component_appsecret' => $this->component_appsecret,
            'component_verify_ticket' => $this->component_verify_ticket,
        ];
    }

    /**
     * @return string
     */
    public function getComponentAppid()
    {
        return $this->component_appid;
    }

    /**
     * @return string
     */
    public function getComponentAppsecret()
    {
        return $this->component_appsecret;
    }

    /**
     * @return string
     */
    public function getAuthorizerAppid()
    {
        return $this->authorizer_appid;
    }

    /**
     * Nhận bộ đệm của bên thứ batoken
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getComponentToken()
    {
        $accessTokenKey = md5($this->component_appid . '_' . $this->component_appid . '_' . $this->component_verify_ticket . '_' . $this->cacheTokenPrefix);
        $component_access_token = $this->cache->get($accessTokenKey);
        if (!$component_access_token) {
            $getToken = $this->getTokenFromServer();
            $this->cache->set($accessTokenKey, $getToken['component_access_token'], $getToken['expires_in'] ? $getToken['expires_in'] - 200 : 7000);
            $component_access_token = $getToken['component_access_token'];
        }
        $this->component_access_token = $component_access_token;

        return $component_access_token;

    }

    /**
     * Nhận từ máy chủtoken
     * @return mixed
     */
    public function getTokenFromServer()
    {
        $config = $this->getConfig();
        if (!$config['component_appid'] || !$config['component_appsecret']) {
            throw new ApiException('Vui lòng định cấu hình bên thứ ba trướccomponent_appid、component_appsecret');
        }
        if (!$config['component_verify_ticket']) {
            throw new ApiException('Nền tảng mở WeChat không được định cấu hình hoặc không nhận được thông báo đẩy.ticket,Vui lòng đợi 10 phút và thử lại');
        }
        $res = $this->postRequest(self::TOKEN_URL, $config);
        $res = json_decode($res, true);
        if (!$res || $res['errcode'] != 0 || !isset($res['component_access_token']) || !$res['component_access_token']) {
            throw new ApiException('Không lấy được thành phần_access_token,lý do：' . $res['errmsg']);
        }
        return $res;
    }

    /**
     * Nhận bên được ủy quyềntoken
     * @param $authorizer_appid
     * @return mixed
     */
    public function getAccessToken($authorizer_appid)
    {
        $accessTokenKey = md5('authorizer_access_token' . $authorizer_appid . '_' . $this->cacheTokenPrefix);
        $authorizer_access_token = $this->cache->get($accessTokenKey);
        if (!$authorizer_access_token) {
            $refreshTokenKey = md5('authorizer_refresh_token' . $authorizer_appid . '_' . $this->cacheTokenPrefix);
            $authorizer_refresh_token = $this->cache->get($refreshTokenKey);
            if (!$authorizer_refresh_token) {
                throw new ApiException('Vui lòng ủy quyền lại');
            }
            $res = $this->freshAuthorizationToken($authorizer_appid, $authorizer_refresh_token);
            $this->cache->set(md5('authorizer_access_token' . $res['authorizer_appid'] . '_' . $this->cacheTokenPrefix), $res['authorizer_access_token'], $res['expires_in'] ? $res['expires_in'] - 200 : 7000);
            $this->cache->set(md5('authorizer_refrssh_token' . $res['authorizer_appid'] . '_' . $this->cacheTokenPrefix), $res['authorizer_refresh_token'], 30 * 24 * 3600);
            $authorizer_access_token = $res['authorizer_access_token'];
        }
        return $authorizer_access_token;
    }

    /**
     * Nhận thông tin ủy quyền
     * @param $authorization_code Mã ủy quyền
     * @return Authorizer_appid ứng dụng ủy quyền chuỗi
     * @return Authorizer_access_token Mã thông báo cuộc gọi giao diện chuỗi (giá trị trả về này chỉ khả dụng khi tài khoản chính thức/chương trình nhỏ được ủy quyền có quyền API)
     * @return Authorizer_refresh_token string Mã thông báo làm mới (giá trị trả về này chỉ khả dụng khi tài khoản công khai được ủy quyền có quyền API). Mã thông báo làm mới chủ yếu được sử dụng bởi các nền tảng của bên thứ ba để lấy và làm mới Authorizer_access_token của người dùng được ủy quyền. Sau khi bị mất, người dùng chỉ có thể nhận lại mã thông báo làm mới mới bằng cách ủy quyền lại cho nó. Sau khi người dùng ủy quyền lại, mã thông báo làm mới trước đó sẽ không hợp lệ.
     * @return array|bool|mixed
     */
    public function getAuthorizationInfo($authorization_code)
    {
        $res = $this->httpRequest(self::AUTH_INFO, ['authorization_code' => $authorization_code], false);
        if (!$res || $res['errcode'] != 0 || !isset($res['authorization_info']) || !$res['authorization_info']) {
            throw new ApiException('Không lấy được Authorizer_access_token');
        }
        $res = $res['authorization_info'];
        $this->cache->set(md5('authorizer_access_token' . $res['authorizer_appid'] . '_' . $this->cacheTokenPrefix), $res['authorizer_access_token'], $res['expires_in'] ? $res['expires_in'] - 200 : 7000);
        //Giá trị trả về này chỉ khả dụng khi tài khoản chính thức được ủy quyền có quyền API.
        if (isset($res['authorizer_refrsh_token'])) {
            $this->cache->set(md5('authorizer_refresh_token' . $res['authorizer_appid'] . '_' . $this->cacheTokenPrefix), $res['authorizer_refrsh_token'], 30 * 24 * 3600);
        }
        return $res;
    }

    /**
     *  Nhận/làm mới mã thông báo cuộc gọi giao diện
     * @param $authorizer_appid Bên được ủy quyềnappid
     * @param $authorizer_refresh_token Làm mới mã thông báo, nhận được khi lấy thông tin ủy quyền
     * @return Authorizer_access_token Mã thông báo ủy quyền chuỗi
     * @return Authorizer_refresh_token Mã thông báo làm mới chuỗi
     * @return array|bool|mixed
     */
    public function freshAuthorizationToken($authorizer_appid, $authorizer_refresh_token)
    {
        $res = $this->postRequest(self::AUTHORIZER_TOKEN, ['component_access_token' => $this->component_access_token, 'authorizer_appid' => $authorizer_appid, 'authorizer_refresh_token' => $authorizer_refresh_token]);
        if (!$res || $res['errcode'] != 0 || !isset($res['authorizer_access_token']) || !$res['authorizer_access_token']) {
            throw new ApiException('Làm mới Authorizer_access_token không thành công,Vui lòng xin lại ủy quyền');
        }
        return $res;
    }


    /**
     * hỏi
     * @param string $url
     * @param array $data
     * @param string $method
     * @param bool $isHeader
     * @return array|mixed
     */
    public function httpRequest(string $url, array $data = [], bool $is_atuh = true, string $method = 'POST')
    {
        if (!$is_atuh) {
            $this->getComponentToken();
            if (!$this->component_access_token) {
                throw new ApiException('Cấu hình đã thay đổi hoặc thành phần_access_token đã hết hạn');
            }
            $url .= '?component_access_token=' . $this->component_access_token;
            $data = array_merge($data, ['component_appid' => $this->component_appid]);
        } else {
            if (!$this->authorizer_appid) {
                throw new ApiException('Thiếu bên được ủy quyềnauthorizer_appid');
            }
            $access_token = $this->getAccessToken($this->authorizer_appid);
            if (!$access_token) {
                throw new ApiException('Cấu hình đã bị thay đổi hoặc ủy quyền đã hết hạn. Vui lòng ủy quyền lại cho nó.');
            }
            $url .= '?access_token=' . $access_token;
        }
        $res = $this->request($url, $method, $data);
        if (!$res) {
            throw new ApiException('Đã xảy ra ngoại lệ khi yêu cầu máy chủ WeChat, vui lòng thử lại sau.');
        }
        return json_decode($res, true) ?: false;
    }

    /**
     * XMLChuyển đổi dữ liệu thành mảng mảng
     * @param string $xml
     * @return array
     */
    public function xmlToArray($xml)
    {
        // Cấm tham chiếu các thực thể xml bên ngoài
        libxml_disable_entity_loader(true);
        $res = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return (array)$res;
    }

    /**
     * Điền và xóa bản rõ được giải mã
     * @param đã giải mã văn bản thuần túy được giải mã
     * @return Xóa văn bản thuần sau khi đệm
     */
    public function decode($text)
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * Giải mã bản mã
     * @param string $encodingAesKey Giải mã
     * @param string $encrypted Văn bản mật mã cần được giải mã
     * @return chuỗi bản rõ thu được bằng cách giải mã
     */
    public function decrypt($encodingAesKey, $encrypted)
    {
        try {
            //Sử dụng BASE64 để giải mã chuỗi cần giải mã
            $ciphertext_dec = base64_decode($encrypted);
            $iv = substr(base64_decode($encodingAesKey . "="), 0, 16);
            $decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        } catch (\Throwable $e) {
            throw new ApiException($e->getMessage());
        }
        try {
            //Loại bỏ các ký tự đệm
            $result = $this->decode($decrypted);
            //Xóa chuỗi ngẫu nhiên 16 bit,Thứ tự byte mạng vàAppId
            if (strlen($result) < 16)
                return "";
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
        } catch (\Throwable $e) {
            throw new ApiException($e->getMessage());
        }
        return $xml_content;
    }
}
