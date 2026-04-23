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

namespace crmeb\services\serve;


use crmeb\basic\BaseManager;
use crmeb\services\AccessTokenServeService;
use crmeb\services\serve\storage\Crmeb;
use think\facade\Config;
use think\Container;

/**
 * Class Serve
 * @package crmeb\services\serve
 * @mixin Crmeb
 */
class Serve extends BaseManager
{
    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\serve\\storage\\';

    /**
     * Trình điều khiển mặc định
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('serve.default', 'crmeb');
    }

    /**
     * Lấy một thể hiện của một lớp
     * @param $class
     * @return mixed|void
     */
    protected function invokeClass($class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException('class not exists: ' . $class);
        }
        $this->getConfigFile();

        if (!$this->config) {
            $this->config = Config::get($this->configFile . '.stores.' . $this->name, []);
        }

        $handleAccessToken = new AccessTokenServeService($this->config['account'] ?? '', $this->config['secret'] ?? '');
        $handle = Container::getInstance()->invokeClass($class, [$this->name, $handleAccessToken, $this->configFile]);
        $this->config = [];
        return $handle;
    }
}
