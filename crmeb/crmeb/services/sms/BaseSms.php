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

namespace crmeb\services\sms;

use crmeb\basic\BaseStorage;
use crmeb\services\AccessTokenServeService;

/**
 * Class BaseSmss
 * @package crmeb\basic
 */
abstract class BaseSms extends BaseStorage
{

    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;

    /**
     * BaseSmss constructor.
     * @param string $name
     * @param AccessTokenServeService $accessTokenServeService
     * @param string $configFile
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


    /**
     * Kích hoạt dịch vụ
     * @return mixed
     */
    abstract public function open();

    /**Sửa đổi chữ ký
     * @return mixed
     */
    abstract public function modify(string $sign = null, string $phone, string $code);

    /**Thông tin người dùng
     * @return mixed
     */
    abstract public function info();

    /**gửi tin nhắn văn bản
     * @return mixed
     */
    abstract public function send(string $phone, string $templateId, array $data);

    /**
     * mẫu tin nhắn
     * @param int $page
     * @param int $limit
     * @param int $type
     * @return mixed
     */
    abstract public function temps(int $page, int $limit, int $type);


    /**
     * Mẫu đơn đăng ký
     * @param string $title
     * @param string $content
     * @param int $type
     * @return mixed
     */
    abstract public function apply(string $title, string $content, int $type);

    /**
     * Bản ghi mẫu
     * @param int $tempType
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    abstract public function applys(int $tempType, int $page, int $limit);

    /**Gửi bản ghi
     * @return mixed
     */
    abstract public function record($record_id);
}
