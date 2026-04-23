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

namespace crmeb\services\easywechat\oauth2\wechat;


use crmeb\services\SystemConfigService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use think\facade\Request;

/**
 * Ủy quyền trang web WeChat
 * Class WechatOauthProvider
 * @package crmeb\services\easywechat\oauth\wechat
 * @method oauth(string $code = '') codeMua lại ủy quyềnacces_token openid
 * @method getUserInfo($openId, $lang = 'zh_CN') openid Lấy thông tin người dùng
 * @method  setRequest(Request $request) Đặt đối tượng yêu cầu
 */
class WechatOauth2Provider implements ServiceProviderInterface
{

    /**
     *
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $request = app('request');
        $wechat = SystemConfigService::more(['wechat_appid', 'wechat_app_appid', 'wechat_app_appsecret', 'wechat_appsecret']);
        if ($request->isApp()) {
            $appId = isset($wechat['wechat_app_appid']) ? trim($wechat['wechat_app_appid']) : '';
            $appsecret = isset($wechat['wechat_app_appsecret']) ? trim($wechat['wechat_app_appsecret']) : '';
        } else {
            $appId = isset($wechat['wechat_appid']) ? trim($wechat['wechat_appid']) : '';
            $appsecret = isset($wechat['wechat_appsecret']) ? trim($wechat['wechat_appsecret']) : '';
        }

        $pimple['oauth2'] = function ($pimple) use ($appId, $appsecret) {
            return new WechatOauth($pimple['access_token'], $appId, $appsecret);
        };
    }
}
