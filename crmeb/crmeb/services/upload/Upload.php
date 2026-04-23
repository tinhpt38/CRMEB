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
namespace crmeb\services\upload;

use crmeb\basic\BaseManager;
use think\facade\Config;

/**
 * Class Upload
 * @package crmeb\services\upload
 * @mixin \crmeb\services\upload\storage\Local
 * @mixin \crmeb\services\upload\storage\OSS
 * @mixin \crmeb\services\upload\storage\COS
 * @mixin \crmeb\services\upload\storage\Qiniu
 * @mixin \crmeb\services\upload\storage\Jdoss
 * @mixin \crmeb\services\upload\storage\Tyoss
 */
class Upload extends BaseManager
{
    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\upload\\storage\\';

    /**
     * Đặt loại tải lên mặc định
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('upload.default', 'local');
    }


}
