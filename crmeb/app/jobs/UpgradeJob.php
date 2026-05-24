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
namespace app\jobs;

use app\services\system\log\SystemFileMd5Services;
use app\services\system\UpgradeServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Gói nâng cấp
 * Class UpgradeJob
 * @package app\jobs
 */class UpgradeJob extends BaseJobs
{
    use QueueTrait;

    /**
     * tải về
     * @param $seq
     * @param $url
     * @param $filePath
     * @param $filename
     * @param $timeout
     * @return bool
     */    public function download($seq, $url, $filePath, $filename, $timeout): bool
    {
        try {
            /** @var UpgradeServices $services */            $services = app()->make(UpgradeServices::class);
            $services->download($seq, $url, $filePath, $filename, $timeout);
        } catch (\Exception $e) {
            Log::error('Tải xuống gói nâng cấp không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }

    /**
     * Sao lưu cơ sở dữ liệu
     * @param $token
     * @return bool
     */    public function databaseBackup($token): bool
    {
        try {
            /** @var UpgradeServices $services */            $services = app()->make(UpgradeServices::class);
            $services->databaseBackup($token);
        } catch (\Exception $e) {
            Log::error('Sao lưu cơ sở dữ liệu không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }

    /**
     * Sao lưu dự án
     * @param $token
     * @return bool
     */    public function projectBackup($token): bool
    {
        try {
            /** @var UpgradeServices $services */            $services = app()->make(UpgradeServices::class);
            $services->projectBackup($token);
        } catch (\Exception $e) {
            Log::error('Sao lưu dự án không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }

    /**
     * Ghi đè tập tin dự án
     * @param $token
     * @return bool
     */    public function coverageProject($token): bool
    {
        try {
            /** @var UpgradeServices $services */            $services = app()->make(UpgradeServices::class);
            $services->coverageProject($token);
        } catch (\Exception $e) {
            Log::error('Không thể ghi đè dự án,Lý do thất bại:' . $e->getMessage());
            // Đồng thời đặt trạng thái khi xảy ra lỗi để tránh các vòng lặp vô hạn
            \crmeb\services\CacheService::set($token . '_coverage_project', -1, 86400);
            \crmeb\services\CacheService::set($token . 'upgrade_status', -1, 86400);
            \crmeb\services\CacheService::set($token . 'upgrade_status_tip', 'Không thể ghi đè dự án: ' . $e->getMessage(), 86400);
        }
        return true;
    }

    /**
     * Kiểm tra tập tinMD5
     * @param $token
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/2/26
     */    public function checkFileMd5($token)
    {
        try {
            /** @var SystemFileMd5Services $services */            $services = app()->make(SystemFileMd5Services::class);
            $data = $services->checkFile();
            \crmeb\services\CacheService::set($token . '_check_md5_file', $data, 86400);
            \crmeb\services\CacheService::set($token . '_check_md5_status', empty($data) ? 2 : 1, 86400);
        } catch (\Exception $e) {
            Log::error('Kiểm tra tệp MD5 không thành công,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }
}
