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
/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace crmeb\services\easywechat\miniPayment;

use EasyWeChat\Payment\Merchant;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use EasyWeChat\MiniProgram\AccessToken;

/**
 * Class ServiceProvider.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $pimple)
    {
        $pimple['merchant'] = function ($pimple) {
            $config = array_merge(
                ['app_id' => $pimple['config']['app_id']],
                $pimple['config']->get('payment', [])
            );

            return new Merchant($config);
        };

        $pimple['mini_program.access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['mini_program']['app_id'],
                $pimple['config']['mini_program']['secret'],
                $pimple['cache']
            );
        };

        $pimple['minipay'] = function ($pimple) {
            return new WeChatClient($pimple['mini_program.access_token'],$pimple['merchant']);
        };
    }
}
