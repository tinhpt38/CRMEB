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

namespace crmeb\utils;

/**
 * Xác thực số điện thoại theo chuẩn Việt Nam.
 */
class PhoneValidate
{
    /** Số di động VN: 0xxxxxxxxx hoặc +84/84xxxxxxxxx */
    public const VN_MOBILE_PATTERN = '/^(?:\+84|84|0)(3|5|7|8|9)\d{8}$/';

    public static function isVnMobile(string $phone): bool
    {
        $phone = preg_replace('/\s+/', '', $phone);

        return (bool)preg_match(self::VN_MOBILE_PATTERN, $phone);
    }
}
