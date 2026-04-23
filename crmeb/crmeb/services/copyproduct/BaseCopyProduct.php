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

namespace crmeb\services\copyproduct;

use crmeb\services\AccessTokenServeService;
use crmeb\basic\BaseStorage;

/**
 * Class BaseProduct
 * @package crmeb\services\copyproduct
 */
abstract class BaseCopyProduct extends BaseStorage
{

    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;

    /**
     * BaseProduct constructor.
     * @param string $name
     * @param AccessTokenServeService $accessTokenServeService
     * @param string $configFile
     */
    public function __construct(string $name, AccessTokenServeService $accessTokenServeService, string $configFile)
    {
        parent::__construct($name, [], $configFile);
        $this->accessToken = $accessTokenServeService;
    }

    /**
     * khởi tạo
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config = [])
    {
//        parent::initialize($config);
    }

    /**
     * Kích hoạt dịch vụ
     * @return mixed
     */
    abstract public function open();

    /**Sao chép sản phẩm
     * @param string $url
     * @param array $options
     * @return mixed
     */
    abstract public function goods(string $url, array $options = []);
}
