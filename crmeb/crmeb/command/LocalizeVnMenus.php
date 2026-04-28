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
namespace crmeb\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

/**
 * Đồng bộ Localization tiếng Việt cho các bảng cấu hình hiển thị trong DB
 * (eb_system_menus, eb_system_config_tab, eb_system_config, eb_system_group)
 * dựa trên tools/localization/vi-glossary.json.
 *
 * Idempotent: chỉ UPDATE khi giá trị thực sự thay đổi.
 * Không đụng cột key/path (`menu_path`, `name` code, `menu_name_key`, ...).
 *
 * Usage:
 *   php think localize:vn-menus --dry-run
 *   php think localize:vn-menus
 */
class LocalizeVnMenus extends Command
{
    /** @var array<int, array{bad: string, good: string}> */
    protected $entries = [];
    /** @var array<int, array{bad: string, good: string}> */
    protected $menuEntriesExact = [];

    protected function configure()
    {
        $this->setName('localize:vn-menus')
            ->addOption('dry-run', null, Option::VALUE_NONE, 'Chỉ in các thay đổi, không UPDATE DB')
            ->addOption('glossary', null, Option::VALUE_REQUIRED, 'Đường dẫn tới vi-glossary.json (mặc định tools/localization/vi-glossary.json)')
            ->setDescription('Đồng bộ text tiếng Việt chuẩn TMĐT vào các bảng hiển thị trong DB theo glossary.');
    }

    protected function execute(Input $input, Output $output)
    {
        $dryRun = (bool)$input->getOption('dry-run');
        $glossaryPath = $input->getOption('glossary') ?: $this->defaultGlossaryPath();

        if (!file_exists($glossaryPath)) {
            $output->error('Không tìm thấy glossary: ' . $glossaryPath);
            return 1;
        }

        $data = json_decode(file_get_contents($glossaryPath), true);
        if (!is_array($data)) {
            $output->error('File glossary không phải JSON hợp lệ: ' . $glossaryPath);
            return 1;
        }
        $this->entries = $data['entries'] ?? [];
        $this->menuEntriesExact = $data['menuEntriesExact'] ?? [];

        // Sort theo độ dài giảm dần: cụm dài match trước, tránh thay chồng lên nhau.
        usort($this->entries, function ($a, $b) {
            return mb_strlen($b['bad']) - mb_strlen($a['bad']);
        });

        $output->info(($dryRun ? '[DRY-RUN] ' : '') . 'Đang chạy localize:vn-menus với ' . count($this->entries) . ' entries + ' . count($this->menuEntriesExact) . ' exact menu entries');

        $targets = [
            // [table, idCol, textCol]
            ['eb_system_menus',      'id', 'menu_name'],
            ['eb_system_config_tab', 'id', 'title'],
            ['eb_system_config',     'id', 'info'],
            ['eb_system_config',     'id', 'desc'],
            ['eb_system_group',      'id', 'name'],
        ];

        $totalChanged = 0;
        foreach ($targets as [$table, $idCol, $col]) {
            try {
                $changed = $this->processTable($output, $dryRun, $table, $idCol, $col);
                $totalChanged += $changed;
            } catch (\Throwable $e) {
                $output->warning(sprintf('Bỏ qua %s.%s: %s', $table, $col, $e->getMessage()));
            }
        }

        $output->info(($dryRun ? 'Would update ' : 'Updated ') . $totalChanged . ' rows total.');
        return 0;
    }

    protected function defaultGlossaryPath(): string
    {
        // `crmeb/crmeb/command/LocalizeVnMenus.php` → ../../../tools/localization/vi-glossary.json
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR
            . 'tools' . DIRECTORY_SEPARATOR
            . 'localization' . DIRECTORY_SEPARATOR
            . 'vi-glossary.json';
    }

    protected function processTable(Output $output, bool $dryRun, string $table, string $idCol, string $col): int
    {
        $rows = Db::table($table)->field([$idCol, $col])->select()->toArray();
        $changed = 0;
        foreach ($rows as $row) {
            $oldValue = (string)($row[$col] ?? '');
            if ($oldValue === '') continue;
            $newValue = $this->translate($oldValue, $table === 'eb_system_menus' && $col === 'menu_name');
            if ($newValue === $oldValue) continue;
            $changed++;
            $output->writeln(sprintf('  %s.%s #%s: "%s" → "%s"', $table, $col, $row[$idCol], $oldValue, $newValue));
            if (!$dryRun) {
                Db::table($table)->where($idCol, $row[$idCol])->update([$col => $newValue]);
            }
        }
        if ($changed > 0) {
            $output->info(sprintf('[%s.%s] %s %d rows', $table, $col, $dryRun ? 'Would update' : 'Updated', $changed));
        }
        return $changed;
    }

    /**
     * Áp glossary vào 1 chuỗi text.
     * - exact match (menuEntriesExact): chỉ dùng khi $applyExact = true và $text khớp nguyên chuỗi.
     * - partial (entries): thay cụm con.
     */
    protected function translate(string $text, bool $applyExact): string
    {
        if ($applyExact) {
            foreach ($this->menuEntriesExact as $e) {
                if ($text === ($e['bad'] ?? '')) {
                    return (string)($e['good'] ?? $text);
                }
            }
        }
        $out = $text;
        foreach ($this->entries as $e) {
            $bad = $e['bad'] ?? '';
            $good = $e['good'] ?? '';
            if ($bad === '' || $good === $bad) continue;
            if (mb_strpos($out, $bad) !== false) {
                $out = str_replace($bad, $good, $out);
            }
        }
        return $out;
    }
}
