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

use app\services\diy\ThemeDownloadServices;
use app\services\diy\ThemeServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Nhiệm vụ hàng đợi xuất chủ đề
 * Class ThemeExportJob
 * @package app\jobs
 */class ThemeExportJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Thực hiện nhiệm vụ xuất chủ đề
     * 1. Đóng gói các tập tin chủ đề để tạo zip
     * 2. Viết download_url quay lại bản ghi eb_theme_download
     *
     * @param $info Thông tin chủ đề
     * @param int $recordId eb_theme_download GhiID
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/3/10
     */    public function export($info, int $recordId): bool
    {
        try {
            /** @var ThemeServices $themeServices */            $themeServices = app()->make(ThemeServices::class);
            /** @var ThemeDownloadServices $themeDownloadServices */            $themeDownloadServices = app()->make(ThemeDownloadServices::class);

            $downloadUrl = $themeServices->exportThemePackage($info);

            // Ghi lại địa chỉ tải xuống đã tạo vào bản ghi tải xuống
            $themeDownloadServices->updateDownloadUrl($recordId, $downloadUrl);
        } catch (\Throwable $e) {
            Log::error('Hàng đợi xuất chủ đề không thành công, lý do：' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return false;
        }
        return true;
    }
}
