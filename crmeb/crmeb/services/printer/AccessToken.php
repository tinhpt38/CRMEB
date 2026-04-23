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
namespace crmeb\services\printer;


use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use crmeb\services\HttpService;
use think\helper\Str;

/**
 *
 * Class AccessToken
 * @package crmeb\services\printer
 */
class AccessToken extends HttpService
{

    /**
     * token
     * @var array
     */
    protected $accessToken;

    /**
     * Giao diện yêu cầu
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * số thiết bị đầu cuối
     * @var string
     */
    protected $machineCode;

    /**
     * Nhà phát triểnid
     * @var string
     */
    protected $partner;

    /**
     * Loại ổ đĩa
     * @var string
     */
    protected $name;

    /**
     * Tên tập tin cấu hình
     * @var string
     */
    protected $configFile;

    /**
     * api key
     * @var string
     */
    protected $apiKey;

    /**
     * Đám mây ngỗng baySN
     * @var string
     */
    protected $feySn;

    /**
     * Đám mây ngỗng bayUYEK
     * @var string
     */
    protected $feyUkey;

    /**
     * Đám mây ngỗng bayUSER
     * @var string
     */
    protected $feyUser;

    public function __construct(array $config = [], string $name, string $configFile)
    {
        $this->clientId = $config['clientId'] ?? null;
        $this->apiKey = $config['apiKey'] ?? null;
        $this->partner = $config['partner'] ?? null;
        $this->machineCode = $config['terminal'] ?? null;
        $this->feyUser = $config['feyUser'] ?? null;
        $this->feyUkey = $config['feyUkey'] ?? null;
        $this->feySn = $config['feySn'] ?? null;
        $this->name = $name;
        $this->configFile = $configFile;
    }

    /**
     * lấytoken
     * @return mixed|null|string
     * @throws \Exception
     */
    public function getAccessToken()
    {
        if (isset($this->accessToken[$this->name])) {
            return $this->accessToken[$this->name];
        }

        $action = 'get' . Str::studly($this->name) . 'AccessToken';
        if (method_exists($this, $action)) {
            return $this->{$action}();
        } else {
            throw new \RuntimeException(__CLASS__ . '->' . $action . '(),Method not worn in');
        }
    }

    /**
     * Nhận đám mây Yiliantoken
     * @return mixed|null|string
     * @throws \Exception
     */
    protected function getYiLianYunAccessToken()
    {
        $this->accessToken[$this->name] = CacheService::remember('YLY_access_token', function () {
            $request = self::postRequest('https://open-api.10ss.net/oauth/oauth', [
                'client_id' => $this->clientId,
                'grant_type' => 'client_credentials',
                'sign' => strtolower(md5($this->clientId . time() . $this->apiKey)),
                'scope' => 'all',
                'timestamp' => time(),
                'id' => $this->createUuid(),
            ]);
            $request = json_decode($request, true);
            $request['error'] = $request['error'] ?? 0;
            $request['error_description'] = $request['error_description'] ?? '';
            if ($request['error'] == 0 && $request['error_description'] == 'success') {
                return $request['body']['access_token'] ?? '';
            }
            return '';
        }, 86400);
        if (!$this->accessToken[$this->name]){
            CacheService::delete('YLY_access_token');
            throw new AdminException('Không lấy được access_token');
        }
        return $this->accessToken[$this->name];
    }

    /**
     * phát raUUID4
     * @return string
     */
    public function createUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * Nhận thuộc tính
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, ['clientId', 'apiKey', 'accessToken', 'partner', 'terminal', 'machineCode', 'feyUser', 'feyUkey', 'feySn'])) {
            return $this->{$name};
        }
    }
}
