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

namespace crmeb\services;

use app\services\system\config\SystemConfigServices;
use crmeb\utils\Arr;

/** Nhận lớp dịch vụ cấu hình hệ thống
 * Class SystemConfigService
 * @package service
 */
class SystemConfigService
{
    const CACHE_SYSTEM = 'system_config';

    /**
     * Có được một cấu hình duy nhất sẽ hiệu quả hơn
     * @param string $key
     * @param $default
     * @param bool $isCaChe Có lấy cấu hình bộ đệm hay không
     * @return bool|mixed|string
     */
    public static function get(string $key, $default = '', bool $isCaChe = true)
    {
        $callable = function () use ($key) {
            return app()->make(SystemConfigServices::class)->getConfigValue($key);
        };

        try {
            if ($isCaChe) {
                return CacheService::remember(self::CACHE_SYSTEM . '_' . $key, $callable);
            }
            return $callable();
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Nhận nhiều cấu hình
     * @param array $keys Ví dụ [['appid','1'],'appkey']
     * @param bool $isCaChe Có lấy cấu hình bộ đệm hay không
     * @return array
     */
    public static function more(array $keys, bool $isCaChe = true)
    {
        $callable = function () use ($keys) {
            return Arr::getDefaultValue($keys, app()->make(SystemConfigServices::class)->getConfigAll($keys));
        };

        try {
            if ($isCaChe){
                return CacheService::remember(self::CACHE_SYSTEM . '_' . md5(implode(',', $keys)), $callable);
            }
            return $callable();
        } catch (\Throwable $e) {
            return Arr::getDefaultValue($keys);
        }
    }
}
