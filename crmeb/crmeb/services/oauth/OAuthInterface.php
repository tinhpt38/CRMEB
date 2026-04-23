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

/**
 * Đăng nhập của bên thứ ba
 * Interface OAuthInterface
 * @package crmeb\services\oauth
 */
interface OAuthInterface
{

    /**
     * Lấy thông tin người dùng
     * @param string $openid
     * @return mixed
     */
    public function getUserInfo(string $openid);

    /**
     * Ủy quyền
     * @param string|null $code
     * @param array $options
     * @return mixed
     */
    public function oauth(string $code = null, array $options = []);

}
