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

use crmeb\basic\BaseStorage;
use crmeb\services\AccessTokenServeService;

abstract class BaseInvoice extends BaseStorage
{
    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;

    /**
     * BaseInvoice constructor.
     * @param string $name
     * @param AccessTokenServeService $accessTokenServeService
     * @param string $configFile
     * @param array $config
     */
    public function __construct(string $name, AccessTokenServeService $accessTokenServeService, string $configFile, array $config = [])
    {
        $this->accessToken = $accessTokenServeService;
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }

    /**
     * khởi tạo
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config = [])
    {

    }
}