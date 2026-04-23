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

namespace crmeb\services\oauth;


use crmeb\basic\BaseManager;
use crmeb\services\oauth\storage\MiniProgram;
use crmeb\services\oauth\storage\TouTiao;
use crmeb\services\oauth\storage\Wechat;
use think\facade\Config;

/**
 * Đăng nhập của bên thứ ba
 * Class OAuth
 * @package crmeb\services\oauth
 * @mixin Wechat
 * @mixin TouTiao
 * @mixin MiniProgram
 */
class OAuth extends BaseManager
{

    /**
     * Tên không gian
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\oauth\\storage\\';

    /**
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('oauth.default', 'wechat');
    }
}
