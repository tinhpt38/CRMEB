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

namespace upgrade;

use think\facade\Config;
use think\facade\Db;
use think\facade\Log;
use crmeb\exceptions\AdminException;

/**
 * trình quản lý phiên bản
 * Được sử dụng để quản lý nâng cấp nhiều phiên bản
 * Class VersionManager
 * @package upgrade
 */
class VersionManager
{
    /**
     * Cấu hình
     * @var array
     */
    protected $config = [];

    /**
     * Tiền tố bảng cơ sở dữ liệu
     * @var string
     */
    protected $prefix = '';

    /**
     * Thông tin phiên bản hiện tại
     * @var array
     */
    protected $currentVersion = [];

    /**
     * SQLLoại mô tả
     */
    const SQL_TYPE_CREATE_TABLE = 1;    // Tạo bảng
    const SQL_TYPE_DROP_TABLE = 2;       // Xóa bảng
    const SQL_TYPE_ADD_COLUMN = 3;       // Thêm trường
    const SQL_TYPE_MODIFY_COLUMN = 4;    // Sửa đổi các trường
    const SQL_TYPE_DROP_COLUMN = 5;      // Xóa trường
    const SQL_TYPE_INSERT_DATA = 6;      // Thêm dữ liệu
    const SQL_TYPE_UPDATE_DATA = 7;      // Sửa đổi dữ liệu
    const SQL_TYPE_DELETE_DATA = 8;      // Xóa dữ liệu
    const SQL_TYPE_RAW = -1;             // Thực hiện trực tiếpSQL

    /**
     * VersionManager constructor.
     */
    public function __construct()
    {
        $this->config = Config::get('upgrade', []);
        $this->prefix = config('database.connections.' . config('database.default'))['prefix'];
        $this->currentVersion = $this->getCurrentVersion();
    }

    /**
     * Nhận thông tin phiên bản hệ thống hiện tại
     * @return array
     */
    public function getCurrentVersion(): array
    {
        $file = app()->getRootPath() . '.version';
        $arr = [];

        if (!file_exists($file)) {
            return $arr;
        }

        $list = @file($file);
        if (!$list) {
            return $arr;
        }

        foreach ($list as $val) {
            $val = str_replace(["\r", "\n", "\t"], '', $val);
            if (strpos($val, '=') !== false) {
                list($k, $v) = explode('=', $val, 2);
                $arr[trim($k)] = trim($v);
            }
        }

        return $arr;
    }

    /**
     * Lấy mã phiên bản hiện tại
     * @return int
     */
    public function getCurrentVersionCode(): int
    {
        return (int)($this->currentVersion['version_code'] ?? 0);
    }

    /**
     * Lấy tên phiên bản hiện tại
     * @return string
     */
    public function getCurrentVersionName(): string
    {
        return $this->currentVersion['version'] ?? '';
    }

    /**
     * Nhận danh sách tất cả các phiên bản
     * @return array
     */
    public function getAllVersions(): array
    {
        return $this->config['versions'] ?? [];
    }

    /**
     * Nhận thông tin phiên bản mới nhất
     * @return array
     */
    public function getLatestVersion(): array
    {
        $versions = $this->getAllVersions();
        return end($versions) ?: [];
    }

    /**
     * Lấy danh sách các phiên bản cần nâng cấp
     * Tất cả các phiên bản từ phiên bản hiện tại đến phiên bản mới nhất
     * @return array
     */
    public function getPendingVersions(): array
    {
        $currentCode = $this->getCurrentVersionCode();
        $versions = $this->getAllVersions();
        $pending = [];
        foreach ($versions as $version) {
            if ($version['code'] > $currentCode) {
                $pending[] = $version;
            }
        }

        // Sắp xếp theo mã phiên bản từ nhỏ đến lớn
        usort($pending, function ($a, $b) {
            return $a['code'] - $b['code'];
        });

        return $pending;
    }

    /**
     * Nhận khoảng cách nâng cấp phiên bản
     * @return int
     */
    public function getVersionGap(): int
    {
        return count($this->getPendingVersions());
    }

    /**
     * Bạn có cần nâng cấp không?
     * @return bool
     */
    public function needUpgrade(): bool
    {
        return $this->getVersionGap() > 0;
    }

    /**
     * Nhận cấu hình yêu cầu phiên bản tối thiểu
     * @return array
     */
    public function getMinVersionConfig(): array
    {
        return $this->config['min_version'] ?? [];
    }

    /**
     * Kiểm tra xem phiên bản hiện tại có đáp ứng yêu cầu phiên bản tối thiểu không
     * @return bool
     */
    public function meetsMinVersionRequirement(): bool
    {
        $minVersion = $this->getMinVersionConfig();
        if (empty($minVersion)) {
            return true; // Phiên bản tối thiểu không được định cấu hình và được cho phép theo mặc định.
        }

        $minCode = $minVersion['code'] ?? 0;
        $currentCode = $this->getCurrentVersionCode();

        return $currentCode >= $minCode;
    }

    /**
     * Nhận thông báo lỗi phiên bản tối thiểu
     * @return string
     */
    public function getMinVersionMessage(): string
    {
        $minVersion = $this->getMinVersionConfig();
        return $minVersion['message'] ?? 'Phiên bản hiện tại không hỗ trợ chức năng nâng cấp trực tuyến giữa các phiên bản';
    }

    /**
     * Kiểm tra tính khả dụng của bản nâng cấp trên nhiều phiên bản
     * @return array ['available' => bool, 'message' => string, 'current_version' => string, 'min_version' => string]
     */
    public function checkUpgradeAvailability(): array
    {
        $currentCode = $this->getCurrentVersionCode();
        $currentVersion = $this->getCurrentVersionName();
        $minVersion = $this->getMinVersionConfig();

        if (empty($minVersion)) {
            return [
                'available' => true,
                'message' => 'Có sẵn các bản nâng cấp đa phiên bản',
                'current_version' => $currentVersion,
                'current_code' => $currentCode,
                'min_version' => '',
                'min_code' => 0
            ];
        }

        $minCode = $minVersion['code'] ?? 0;
        $available = $currentCode >= $minCode;

        return [
            'available' => $available,
            'message' => $available ? 'Có sẵn các bản nâng cấp đa phiên bản' : $this->getMinVersionMessage(),
            'current_version' => $currentVersion,
            'current_code' => $currentCode,
            'min_version' => $minVersion['version'] ?? '',
            'min_code' => $minCode
        ];
    }

    /**
     * Nhận tập lệnh nâng cấp phiên bản
     * @param array $version
     * @return array
     */
    public function getVersionUpgradeData(array $version): array
    {
        $filePath = ($this->config['upgrade_path'] ?? '') . ($version['file'] ?? '');

        if (!file_exists($filePath)) {
            return [];
        }

        $data = include $filePath;
        return is_array($data) ? $data : [];
    }

    /**
     * Nhận tất cả các nâng cấp đang chờ xử lýSQL
     * @return array
     */
    public function getAllPendingUpgradeSql(): array
    {
        $pendingVersions = $this->getPendingVersions();
        $allSql = [];

        foreach ($pendingVersions as $version) {
            $upgradeData = $this->getVersionUpgradeData($version);
            if (!empty($upgradeData['update_sql'])) {
                foreach ($upgradeData['update_sql'] as $sql) {
                    $sql['version'] = $version['version'];
                    $sql['version_code'] = $version['code'];
                    $allSql[] = $sql;
                }
            }
        }

        return $allSql;
    }

    /**
     * Thực hiện một nâng cấp duy nhấtSQL
     * @param array $sqlItem
     * @return array ['success' => bool, 'message' => string]
     */
    public function executeSqlItem(array $sqlItem): array
    {
        $type = $sqlItem['type'] ?? 0;
        $table = $this->prefix . ($sqlItem['table'] ?? '');
        $field = $sqlItem['field'] ?? '';
        $findSql = $sqlItem['findSql'] ?? '';
        $sql = $sqlItem['sql'] ?? '';
        $whereSql = $sqlItem['whereSql'] ?? '';
        $whereTable = isset($sqlItem['whereTable']) ? $this->prefix . $sqlItem['whereTable'] : '';
        $newTable = isset($sqlItem['new_table']) ? $this->prefix . $sqlItem['new_table'] : '';

        try {
            // Thay thế tên bảng
            if ($findSql) {
                $findSql = str_replace('@table', $table, $findSql);
            }

            // kiểm tra trước
            if ($findSql) {
                $exists = !empty(Db::query($findSql));

                switch ($type) {
                    case self::SQL_TYPE_CREATE_TABLE:
                    case self::SQL_TYPE_ADD_COLUMN:
                    case self::SQL_TYPE_INSERT_DATA:
                        if ($exists) {
                            return ['success' => true, 'message' => $this->getSkipMessage($type, $table, $field), 'skipped' => true];
                        }
                        break;
                    case self::SQL_TYPE_MODIFY_COLUMN:
                    case self::SQL_TYPE_DROP_COLUMN:
                    case self::SQL_TYPE_UPDATE_DATA:
                        if (!$exists) {
                            return ['success' => true, 'message' => $this->getSkipMessage($type, $table, $field), 'skipped' => true];
                        }
                        break;
                    case self::SQL_TYPE_DELETE_DATA:
                        if (!$exists) {
                            return ['success' => true, 'message' => 'Dữ liệu không tồn tại, bỏ qua việc xóa', 'skipped' => true];
                        }
                        break;
                }
            }

            // Thay thế phần giữ chỗ trong SQL
            $execSql = str_replace('@table', $table, $sql);

            // Xử lý các truy vấn bảng quan hệ
            if (in_array($type, [self::SQL_TYPE_INSERT_DATA, self::SQL_TYPE_UPDATE_DATA]) && $whereSql && $whereTable) {
                $whereSql = str_replace('@whereTable', $whereTable, $whereSql);
                $result = Db::query($whereSql);
                $tabId = $result[0]['tabId'] ?? 0;
                if (!$tabId) {
                    return ['success' => true, 'message' => 'Dữ liệu liên quan không tồn tại, bỏ qua', 'skipped' => true];
                }
                $execSql = str_replace('@tabId', $tabId, $execSql);
            }

            // Xử lý tên bảng mới
            if ($type == self::SQL_TYPE_RAW && $newTable) {
                $execSql = str_replace('@new_table', $newTable, $execSql);
            }

            // thực hiệnSQL
            if ($execSql) {
                Db::execute($execSql);
                Log::write(['type' => 'upgrade_sql', 'sql' => $execSql, 'item' => json_encode($sqlItem)], 'notice');
            }

            return ['success' => true, 'message' => $this->getSuccessMessage($type, $table, $field)];

        } catch (\Throwable $e) {
            Log::error(['type' => 'upgrade_sql_error', 'error' => $e->getMessage(), 'item' => json_encode($sqlItem)]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Nhận tin nhắn bỏ qua
     */
    protected function getSkipMessage(int $type, string $table, string $field): string
    {
        $messages = [
            self::SQL_TYPE_CREATE_TABLE => "{$table} bảng đã tồn tại",
            self::SQL_TYPE_DROP_TABLE => "{$table} bảng không tồn tại",
            self::SQL_TYPE_ADD_COLUMN => "{$table} trong bảng {$field} Trường đã tồn tại",
            self::SQL_TYPE_MODIFY_COLUMN => "{$table} trong bảng {$field} Trường không tồn tại",
            self::SQL_TYPE_DROP_COLUMN => "{$table} trong bảng {$field} Trường không tồn tại",
            self::SQL_TYPE_INSERT_DATA => "{$table} Dữ liệu đã tồn tại",
            self::SQL_TYPE_UPDATE_DATA => "{$table} Dữ liệu không tồn tại",
        ];
        return $messages[$type] ?? 'nhảy qua';
    }

    /**
     * Nhận thông báo thành công
     */
    protected function getSuccessMessage(int $type, string $table, string $field): string
    {
        $messages = [
            self::SQL_TYPE_CREATE_TABLE => "{$table} Bảng được tạo thành công",
            self::SQL_TYPE_DROP_TABLE => "{$table} Đã xóa bảng thành công",
            self::SQL_TYPE_ADD_COLUMN => "{$table} trong bảng {$field} Trường được thêm thành công",
            self::SQL_TYPE_MODIFY_COLUMN => "{$table} trong bảng {$field} Trường đã được sửa đổi thành công",
            self::SQL_TYPE_DROP_COLUMN => "{$table} trong bảng {$field} Trường đã được xóa thành công",
            self::SQL_TYPE_INSERT_DATA => "{$table} Dữ liệu được thêm thành công",
            self::SQL_TYPE_UPDATE_DATA => "{$table} Dữ liệu được sửa đổi thành công",
            self::SQL_TYPE_DELETE_DATA => "{$table} Đã xóa dữ liệu thành công",
            self::SQL_TYPE_RAW => "{$table} SQLĐã thực hiện thành công",
        ];
        return $messages[$type] ?? 'Đã thực hiện thành công';
    }

    /**
     * Cập nhật tập tin phiên bản
     * @param string $version
     * @param int $code
     * @return bool
     */
    public function updateVersionFile(string $version, int $code): bool
    {
        $file = app()->getRootPath() . '.version';
        $data = $this->getCurrentVersion();

        $data['version'] = $version;
        $data['version_code'] = $code;
        $data['platform'] = $this->config['platform'] ?? 'CRMEB';
        $data['app_id'] = $data['app_id'] ?? ($this->config['app_id'] ?? '');
        $data['app_key'] = $data['app_key'] ?? ($this->config['app_key'] ?? '');

        $content = '';
        foreach ($data as $key => $value) {
            $content .= "{$key}={$value}\n";
        }

        return file_put_contents($file, $content) !== false;
    }

    /**
     * Nhận thông tin tổng quan về nâng cấp
     * @return array
     */
    public function getUpgradeOverview(): array
    {
        $currentVersion = $this->getCurrentVersionName();
        $currentCode = $this->getCurrentVersionCode();
        $latestVersion = $this->getLatestVersion();
        $pendingVersions = $this->getPendingVersions();

        return [
            'current_version' => $currentVersion,
            'current_code' => $currentCode,
            'latest_version' => $latestVersion['version'] ?? '',
            'latest_code' => $latestVersion['code'] ?? 0,
            'need_upgrade' => $this->needUpgrade(),
            'version_gap' => $this->getVersionGap(),
            'pending_versions' => array_map(function ($v) {
                return [
                    'version' => $v['version'],
                    'code' => $v['code'],
                    'description' => $v['description'] ?? ''
                ];
            }, $pendingVersions)
        ];
    }

    /**
     * Nhận tất cả các bộ xử lý di chuyển dữ liệu đang chờ xử lý
     * @return array
     */
    public function getAllPendingDataHandlers(): array
    {
        $pendingVersions = $this->getPendingVersions();
        $allHandlers = [];

        foreach ($pendingVersions as $version) {
            $upgradeData = $this->getVersionUpgradeData($version);
            if (!empty($upgradeData['data_handlers'])) {
                foreach ($upgradeData['data_handlers'] as $handler) {
                    $handler['version'] = $version['version'];
                    $handler['version_code'] = $version['code'];
                    $allHandlers[] = $handler;
                }
            }
        }

        return $allHandlers;
    }

    /**
     * Nhận phiên bản được chỉ định của bộ xử lý di chuyển dữ liệu
     * @param array $version
     * @return array
     */
    public function getVersionDataHandlers(array $version): array
    {
        $upgradeData = $this->getVersionUpgradeData($version);
        return $upgradeData['data_handlers'] ?? [];
    }
}
