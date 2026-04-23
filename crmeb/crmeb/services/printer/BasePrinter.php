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

use crmeb\basic\BaseStorage;

/**
 * Class BasePrinter
 * @package crmeb\basic
 */
abstract class BasePrinter extends BaseStorage
{

    /**
     * tokenxử lý
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * In nội dung
     * @var string
     */
    protected $printerContent;

    /**
     * Số lượng bản in
     * @var string
     */
    protected $times;

    /**
     * BasePrinter constructor.
     * @param string $name
     * @param AccessToken $accessToken
     * @param string $configFile
     */
    public function __construct(string $name, AccessToken $accessToken, string $configFile)
    {
        parent::__construct($name, [], $configFile);
        $this->accessToken = $accessToken;
    }

    /**
     * Bắt đầu in
     * @return mixed
     */
    abstract public function startPrinter();

    /**
     * Đặt nội dung in
     * @param $content
     * @return mixed
     */
    abstract public function setPrinterContent($content);

}
