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

namespace crmeb\services\template;

use crmeb\basic\BaseManager;
use think\facade\Config;

/**
 * Class Template
 * @package crmeb\services\template
 * @mixin \crmeb\services\template\storage\Wechat
 * @mixin \crmeb\services\template\storage\Subscribe
 */
class Template extends BaseManager
{

    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\template\\storage\\';

    /**
     * Đặt tiện ích mở rộng mặc định
     * @return mixed|string
     */
    protected function getDefaultDriver()
    {
        return 'wechat';
    }
}
