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
namespace crmeb\services\easywechat\wechatTemplate;

use EasyWeChat\Core\AccessToken;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProgramProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['wechat.access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['app_id'],
                $pimple['config']['secret'],
                $pimple['cache']
            );
        };

        $pimple['new_notice'] = function ($pimple) {
            return new ProgramTemplate($pimple['wechat.access_token']);
        };
    }
}