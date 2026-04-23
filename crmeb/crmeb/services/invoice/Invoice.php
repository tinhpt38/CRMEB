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
namespace crmeb\services\invoice;

use crmeb\basic\BaseManager;
use crmeb\services\AccessTokenServeService;
use think\Container;
use think\facade\Config;

class Invoice extends BaseManager
{
    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\invoice\\storage\\';

    /**
     * Trình điều khiển mặc định
     * @return mixed
     */
    protected function getDefaultDriver()
    {
//        return Config::get('invoice.default');
        return 'yihaotong';
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
        $handleAccessToken = new AccessTokenServeService($this->config['account'] ?? '', $this->config['secret'] ?? '');
        $handle = Container::getInstance()->invokeClass($class, [$this->name, $handleAccessToken, $this->configFile, $this->config]);
        $this->config = [];
        return $handle;
    }
}