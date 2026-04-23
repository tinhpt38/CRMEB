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

namespace crmeb\basic;


/**
 * Class BaseStorage
 * @package crmeb\basic
 */
abstract class BaseStorage
{

    /**
     * Tên tài xế
     * @var string
     */
    protected $name;

    /**
     * Tên tập tin cấu hình trình điều khiển
     * @var string
     */
    protected $configFile;

    /**
     * thông báo lỗi
     * @var string
     */
    protected $error;

    /**
     * BaseStorage constructor.
     * @param string $name Tên tài xế
     * @param string $configFile Tên cấu hình trình điều khiển
     * @param array $config Các cấu hình khác
     */
    public function __construct(string $name, array $config = [], string $configFile = null)
    {
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }


    /**
     * Đặt thông báo lỗi
     * @param string|null $error
     * @return bool
     */
    protected function setError(?string $error = null)
    {
        $this->error = $error ?: 'lỗi không xác định';
        return false;
    }

    /**
     * Nhận thông báo lỗi
     * @return string
     */
    public function getError()
    {
        $error = $this->error;
        $this->error = null;
        return $error;
    }

    /**
     * khởi tạo
     * @param array $config
     * @return mixed
     */
    abstract protected function initialize(array $config);

}
