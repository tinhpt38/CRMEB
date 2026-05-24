<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\services\zalo;

use app\services\BaseServices;
use app\services\system\config\SystemConfigServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use think\Model;

/**
 * Design tokens giao diện Zalo Mini App (f-chan).
 */class ZaloMiniAppThemeServices extends BaseServices
{
    public const CONFIG_THEME = 'zalo_mini_app_theme';
    public const CONFIG_VERSION = 'zalo_mini_app_theme_version';

    /** Font đã embed trong bundle f-chan. */    public const FONT_WHITELIST = [
        'system' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'inter' => '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
    ];

    public const DEFAULT_THEME = [
        'shopName' => '',
        'logoUrl' => '',
        'faviconUrl' => '',
        'primary' => '#52b361',
        'primaryForeground' => '#ffffff',
        'background' => '#f7f7f8',
        'foreground' => '#0d0d0d',
        'section' => '#ffffff',
        'subtitle' => '#6f7071',
        'inactive' => '#a9adb2',
        'danger' => '#f50000',
        'gradient' => '#52b361',
        'secondary' => '#d1f0db',
        'secondaryForeground' => '#135328',
        'fontFamily' => 'system',
        'baseFontSize' => 15,
        'headerColor' => '#52b361',
        'statusBar' => 'default',
    ];

    public function getPublicThemePayload(): array
    {
        $theme = $this->getNormalizedTheme();
        $version = $this->getVersion();

        return [
            'version' => $version,
            'theme' => $this->formatThemeForClient($theme),
            'updatedAt' => (int)sys_config('zalo_mini_app_theme_updated_at', 0),
        ];
    }

    public function getAdminTheme(): array
    {
        $theme = $this->getNormalizedTheme();
        if (!empty($theme['logoUrl'])) {
            $theme['logoUrl'] = set_file_url($theme['logoUrl']);
        }
        if (!empty($theme['faviconUrl'])) {
            $theme['faviconUrl'] = set_file_url($theme['faviconUrl']);
        }

        return $theme;
    }

    public function saveTheme(array $input): array
    {
        $stored = $this->readStoredTheme();
        $theme = $this->normalizeThemeInput(array_merge($stored, $input));
        $version = $this->getVersion() + 1;

        /** @var SystemConfigServices $configServices */        $configServices = app()->make(SystemConfigServices::class);
        $this->ensureThemeConfigRows($configServices);

        $configServices->update(self::CONFIG_THEME, ['value' => json_encode($theme, JSON_UNESCAPED_UNICODE)], 'menu_name');
        $configServices->update(self::CONFIG_VERSION, ['value' => json_encode($version)], 'menu_name');
        $configServices->update('zalo_mini_app_theme_updated_at', ['value' => json_encode(time())], 'menu_name');

        $this->clearThemeConfigCache();

        return [
            'version' => $version,
            'theme' => $theme,
        ];
    }

    public function resetTheme(): array
    {
        return $this->saveTheme(self::DEFAULT_THEME);
    }

    /**
     * Sao chép palette từ theme mall uni-app (một lần, không đồng bộ hai chiều).
     */    public function importPaletteFromMallTheme(): array
    {
        /** @var \app\services\diy\ThemeServices $themeServices */        $themeServices = app()->make(\app\services\diy\ThemeServices::class);
        $mallTheme = $themeServices->getThemeInfo(0, 'theme');
        if (!is_array($mallTheme) || empty($mallTheme['theme_color'])) {
            throw new ApiException('Không tìm thấy theme mall đang active');
        }

        $current = $this->getNormalizedTheme();
        $current['primary'] = $this->normalizeColor((string)$mallTheme['theme_color'], self::DEFAULT_THEME['primary']);
        if (!empty($mallTheme['gradient_color'])) {
            $current['gradient'] = $this->normalizeColor((string)$mallTheme['gradient_color'], $current['primary']);
        }
        if (!empty($mallTheme['sub_color'])) {
            $current['secondary'] = $this->normalizeColor((string)$mallTheme['sub_color'], self::DEFAULT_THEME['secondary']);
        }
        $current['headerColor'] = $current['primary'];

        return $this->saveTheme($current);
    }

    private function getVersion(): int
    {
        return max(0, (int)sys_config(self::CONFIG_VERSION, 0));
    }

    private function getNormalizedTheme(): array
    {
        return $this->normalizeThemeInput($this->readStoredTheme());
    }

    private function readStoredTheme(): array
    {
        $raw = sys_config(self::CONFIG_THEME, '');
        if (is_array($raw)) {
            return $raw;
        }
        if (!is_string($raw) || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function normalizeThemeInput(array $input): array
    {
        $theme = array_merge(self::DEFAULT_THEME, $input);

        if (array_key_exists('shopName', $input)) {
            $theme['shopName'] = preg_replace('/\s+/u', ' ', trim((string)$input['shopName']));
        }
        if (array_key_exists('logoUrl', $input)) {
            $theme['logoUrl'] = $this->normalizeAssetUrl((string)$input['logoUrl']);
        }
        if (array_key_exists('faviconUrl', $input)) {
            $theme['faviconUrl'] = $this->normalizeAssetUrl((string)$input['faviconUrl']);
        }

        foreach (['primary', 'primaryForeground', 'background', 'foreground', 'section', 'subtitle', 'inactive', 'danger', 'gradient', 'secondary', 'secondaryForeground', 'headerColor'] as $key) {
            if (array_key_exists($key, $input)) {
                $theme[$key] = $this->normalizeColor((string)$input[$key], self::DEFAULT_THEME[$key]);
            }
        }

        $fontKey = (string)($input['fontFamily'] ?? $theme['fontFamily']);
        $theme['fontFamily'] = array_key_exists($fontKey, self::FONT_WHITELIST) ? $fontKey : 'system';

        $baseFontSize = (int)($input['baseFontSize'] ?? $theme['baseFontSize']);
        $theme['baseFontSize'] = max(13, min(16, $baseFontSize));

        $statusBar = (string)($input['statusBar'] ?? $theme['statusBar']);
        $theme['statusBar'] = in_array($statusBar, ['default', 'transparent', 'light', 'dark'], true)
            ? $statusBar
            : 'default';

        if ($theme['gradient'] === self::DEFAULT_THEME['gradient'] && $theme['primary'] !== self::DEFAULT_THEME['primary']) {
            $theme['gradient'] = $theme['primary'];
        }
        if ($theme['headerColor'] === self::DEFAULT_THEME['headerColor'] && $theme['primary'] !== self::DEFAULT_THEME['primary']) {
            $theme['headerColor'] = $theme['primary'];
        }

        return $theme;
    }

    private function formatThemeForClient(array $theme): array
    {
        $formatted = $theme;
        $formatted['logoUrl'] = $formatted['logoUrl'] ? set_file_url($formatted['logoUrl']) : '';
        $formatted['faviconUrl'] = $formatted['faviconUrl'] ? set_file_url($formatted['faviconUrl']) : '';
        $formatted['fontFamilyCss'] = self::FONT_WHITELIST[$formatted['fontFamily']] ?? self::FONT_WHITELIST['system'];

        return $formatted;
    }

    private function normalizeColor(string $value, string $fallback): string
    {
        $value = trim($value);
        if ($value === '') {
            return $fallback;
        }
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            if (strlen($value) === 4) {
                return sprintf(
                    '#%s%s%s%s%s%s',
                    $value[1],
                    $value[1],
                    $value[2],
                    $value[2],
                    $value[3],
                    $value[3]
                );
            }
            return strtolower($value);
        }
        if (preg_match('/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+(?:\s*,\s*(?:0|1|0?\.\d+))?\s*\)$/i', $value)) {
            return $value;
        }
        return $fallback;
    }

    private function normalizeAssetUrl(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }
        return $value;
    }

    private function clearThemeConfigCache(): void
    {
        CacheService::delete(\crmeb\services\SystemConfigService::CACHE_SYSTEM . '_' . self::CONFIG_THEME);
        CacheService::delete(\crmeb\services\SystemConfigService::CACHE_SYSTEM . '_' . self::CONFIG_VERSION);
        CacheService::delete(\crmeb\services\SystemConfigService::CACHE_SYSTEM . '_zalo_mini_app_theme_updated_at');
        CacheService::clear();
    }

    private function ensureThemeConfigRows(SystemConfigServices $configServices): void
    {
        $ref = $configServices->getOne(['menu_name' => 'zalo_login_open'])
            ?: $configServices->getOne(['menu_name' => 'site_name']);
        if (!$ref) {
            return;
        }
        $base = $ref instanceof Model ? $ref->toArray() : (array)$ref;

        $defs = [
            self::CONFIG_THEME => [
                'info' => 'Zalo Mini App theme JSON',
                'desc' => 'Design tokens giao diện Zalo Mini App (f-chan).',
                'type' => 'textarea',
                'value' => json_encode(self::DEFAULT_THEME, JSON_UNESCAPED_UNICODE),
            ],
            self::CONFIG_VERSION => [
                'info' => 'Zalo Mini App theme version',
                'desc' => 'Version cache theme mini app.',
                'type' => 'text',
                'value' => '0',
            ],
            'zalo_mini_app_theme_updated_at' => [
                'info' => 'Zalo Mini App theme updated at',
                'desc' => 'Unix timestamp cập nhật theme mini app.',
                'type' => 'text',
                'value' => '0',
            ],
        ];

        foreach ($defs as $menuName => $meta) {
            if ($configServices->be(['menu_name' => $menuName])) {
                continue;
            }
            $row = $base;
            unset($row['id']);
            $row['menu_name'] = $menuName;
            $row['info'] = $meta['info'];
            $row['desc'] = $meta['desc'];
            $row['type'] = $meta['type'];
            $row['value'] = json_encode($meta['value']);
            $row['input_type'] = 'input';
            $row['required'] = '';
            $row['parameter'] = '';
            $row['level'] = 0;
            $row['link_id'] = 0;
            $row['link_value'] = 0;
            $configServices->dao->save($row);
        }
    }
}
