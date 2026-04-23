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

namespace crmeb\services\app;

use crmeb\services\easywechat\Application;

class WechatOpenService
{

    protected function options()
    {
        $options = [
            'app_id' => sys_config('wechat_open_app_id'),
            'secret' => sys_config('wechat_open_app_secret')
        ];
        return $options;
    }

    /**
     * @return Application
     */
    protected function application()
    {
        return new Application($this->options());
    }

    /**
     * @return mixed
     */
    public function serve()
    {
        return $this->application()->open_platform->server->serve();
    }

    /**
     * Sử dụng mã ủy quyền để trao đổi thông tin xác thực cuộc gọi giao diện và thông tin ủy quyền của tài khoản chính thức
     * @param string $code
     * @return \EasyWeChat\Support\Collection
     */
    public function getAuthorizationInfo()
    {
        return $this->application()->oauth->user();
    }

    /**
     * Lấy thông tin cơ bản về tài khoản chính thức của bên được ủy quyền
     * @param string $appid
     * @return \EasyWeChat\Support\Collection
     */
    public function getAuthorizerInfo()
    {
        return $this->application()->oauth->user();
    }


}
