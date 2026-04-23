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

use crmeb\basic\BaseManager;
use think\facade\Config;
use think\Container;

/**
 * Class Printer
 * @package crmeb\services\auth
 * @mixin \crmeb\services\printer\storage\YiLianYun
 */
class Printer extends BaseManager
{

    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\printer\\storage\\';

    /**
     * @var object
     */
    protected $handleAccessToken;

    /**
     * Trình điều khiển mặc định
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('printer.default', 'yi_lian_yun');
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

        if (!$this->handleAccessToken) {
            $this->handleAccessToken = new AccessToken($this->config, $this->name, $this->configFile);
        }

        $handle = Container::getInstance()->invokeClass($class, [$this->name, $this->handleAccessToken, $this->configFile]);
        $this->config = [];
        return $handle;
    }

}
