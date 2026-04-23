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

use app\services\system\config\SystemGroupDataServices;

/**
 * Nhận cấu hình dữ liệu kết hợp
 * Class GroupDataService
 * @package crmeb\services
 */
class GroupDataService
{
    /**
     * Nhận một giá trị duy nhất
     * @param string $config_name Tên cấu hình
     * @param int $limit Bao nhiêu lần đánh chặn
     * @param bool $isCaChe Có đọc bộ đệm hay không
     * @return array
     */
    public static function getData(string $config_name, int $limit = 0, bool $isCaChe = false): array
    {
        $callable = function () use ($config_name, $limit) {
            try {
                /** @var SystemGroupDataServices $service */
                $service = app()->make(SystemGroupDataServices::class);
                return $service->getConfigNameValue($config_name, $limit);
            } catch (\Exception $e) {
                return [];
            }
        };
        try {
            $cacheName = $limit ? "data_{$config_name}_{$limit}" : "data_{$config_name}";

            if ($isCaChe)
                return $callable();

            return CacheService::remember($cacheName, $callable);

        } catch (\Throwable $e) {
            return $callable();
        }
    }

    /**
     * Nhận một giá trị duy nhất dựa trên id
     * @param int $id
     * @param bool $isCaChe Có đọc bộ đệm hay không
     * @return array
     */
    public static function getDataNumber(int $id, bool $isCaChe = false): array
    {
        $callable = function () use ($id) {
            try {

                /** @var SystemGroupDataServices $service */
                $service = app()->make(SystemGroupDataServices::class);
                $data = $service->getDateValue($id);
                if (is_object($data))
                    $data = $data->toArray();
                return $data;
            } catch (\Exception $e) {
                return [];
            }
        };
        try {
            $cacheName = "data_number_{$id}";

            if ($isCaChe)
                return $callable();

            return CacheService::remember($cacheName, $callable);

        } catch (\Throwable $e) {
            return $callable();
        }
    }

    public static function getDataNumbers($ids)
    {
        try {
            if (is_string($ids)) $ids = explode(',', $ids);
            /** @var SystemGroupDataServices $service */
            $service = app()->make(SystemGroupDataServices::class);
            $data = $service->getGroupDataColumn($ids);
            if (is_object($data))
                $data = $data->toArray();
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}
