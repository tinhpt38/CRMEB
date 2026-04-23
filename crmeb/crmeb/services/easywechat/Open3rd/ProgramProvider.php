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
namespace crmeb\services\easywechat\Open3rd;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Đăng ký nền tảng của bên thứ ba
 * Class ProgramProvider
 * @package crmeb\utils
 */
class ProgramProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['mini_program.component_access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['open3rd']['component_appid'],
                $pimple['config']['open3rd']['component_appsecret'],
                $pimple['config']['open3rd']['component_verify_ticket'],
                $pimple['config']['open3rd']['authorizer_appid']
            );
        };

        $pimple['mini_program.open3rd'] = function ($pimple) {
            return new ProgramOpen3rd($pimple['mini_program.component_access_token']);
        };
    }
}
