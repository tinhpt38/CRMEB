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

namespace crmeb\services\express;

use crmeb\basic\BaseStorage;
use crmeb\services\AccessTokenServeService;

/**
 * Điều tra hậu cần
 * Class BaseExpress
 * @package crmeb\basic
 */
abstract class BaseExpress extends BaseStorage
{

    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;


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

    /**Theo dõi hậu cần
     * @return mixed
     */
    abstract public function query(string $num, string $com = '');

    /**Mẫu điện tử
     * @return mixed
     */
    abstract public function dump($data);

    /**công ty chuyển phát nhanh
     * @return mixed
     */
    //abstract public function express($type, $page, $limit);

    /**Mẫu khuôn mặt
     * @return mixed
     */
    abstract public function temp(string $com);
}
