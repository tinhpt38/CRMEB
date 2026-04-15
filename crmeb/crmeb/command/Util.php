<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace crmeb\command;


use app\services\system\log\SystemFileInfoServices;
use app\services\system\SystemRouteServices;
use app\services\system\lang\LangCodeServices;
use crmeb\exceptions\AdminException;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;
use think\facade\Db;

class Util extends Command
{
    protected function configure()
    {
        $this->setName('util')
            ->addArgument('type', Argument::REQUIRED, '类型replace/route/file/apifox/lang-package')
            ->addOption('h', null, Option::VALUE_REQUIRED, '替换成当前域名')
            ->addOption('u', null, Option::VALUE_REQUIRED, '替换的域名')
            ->addOption('a', null, Option::VALUE_REQUIRED, '应用名')
            ->addOption('f', null, Option::VALUE_REQUIRED, '导入文件路径，文件只能在项目根目录下或者根目录下的其他文件夹内')
            ->addOption('action', null, Option::VALUE_REQUIRED, 'lang-package 操作: export/import')
            ->addOption('out', null, Option::VALUE_REQUIRED, 'lang-package 导出文件路径')
            ->addOption('file', null, Option::VALUE_REQUIRED, 'lang-package 导入文件路径')
            ->setDescription('工具类');
    }

    protected function execute(Input $input, Output $output)
    {
        $type = $input->getArgument('type');

        switch ($type) {
            case 'replace':
                $host = $input->getOption('h');
                $url = $input->getOption('u');
                if (!$host) {
                    return $output->error('缺少替换域名');
                }
                if (!$url) {
                    return $output->error('缺少替换的域名');
                }
                $this->replaceSiteUrl($url, $host);
                break;
            case 'route':
                $appName = $input->getOption('a');
                if (!$appName) {
                    return $output->error('缺少应用名称');
                }
                app()->make(SystemRouteServices::class)->syncRoute($appName);
                break;
            case 'file':
                app()->make(SystemFileInfoServices::class)->syncfile();
                break;
            case 'apifox':
                $filePath = $input->getOption('f');
                if (!$filePath) {
                    return $output->error('缺少导入文件地址');
                }
                app()->make(SystemRouteServices::class)->import($filePath);
                break;
            case 'lang-package':
                $action = $input->getOption('action');
                if (!$action || !in_array($action, ['export', 'import'])) {
                    return $output->error('lang-package 缺少有效操作，请传 --action=export/import');
                }
                if ($action === 'export') {
                    $out = $input->getOption('out') ?: 'runtime/lang_packages/lang_' . date('Ymd_His') . '.sql';
                    $this->exportLangPackage($out);
                } else {
                    $file = $input->getOption('file');
                    if (!$file) {
                        return $output->error('lang-package 导入缺少 --file 参数');
                    }
                    $this->importLangPackage($file);
                }
                break;
        }

        $output->info('执行成功');
    }

    protected function replaceSiteUrl(string $url, string $siteUrl)
    {
        // 解析站点 URL 的协议
        $siteUrlScheme = parse_url($siteUrl)['scheme'];
        // 将站点 URL 中的协议替换为 JSON 格式
        $siteUrlJson = str_replace($siteUrlScheme . '://', $siteUrlScheme . ':\\\/\\\/', $siteUrl);

        // 获取当前 URL 的协议
        $urlScheme = parse_url($url)['scheme'];
        // 将当前 URL 中的协议替换为 JSON 格式
        $urlJson = str_replace($urlScheme . '://', $urlScheme . ':\\\/\\\/', $url);
        // 获取数据库表前缀
        $prefix = Config::get('database.connections.' . Config::get('database.default') . '.prefix');

        // 构建 SQL 语句数组
        $sql = [
            "UPDATE `{$prefix}agent_level` SET `image` = replace(`image` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}agreement` SET `content` = replace(content ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}article` SET `image_input` = replace(`image_input` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}article_category` SET `image` = replace(`image` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}article_content` SET `content` = replace(`content` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}cache` SET `result` = replace(result ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}delivery_service` SET `avatar` = replace(`avatar` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}division_agent_apply` SET `images` = replace(images ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}diy` SET `value` = replace(value ,'{$siteUrlJson}','{$urlJson}'),`default_value` = replace(default_value ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}diy` SET `value` = replace(value ,'{$siteUrl}','{$url}'),`default_value` = replace(default_value ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}live_anchor` SET `cover_img` = replace(`cover_img` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}live_goods` SET `cover_img` = replace(`cover_img` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}live_room` SET `cover_img` = replace(`cover_img` ,'{$siteUrl}','{$url}'),`share_img` = replace(`share_img` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}luck_lottery` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`content` = replace(content ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}luck_prize` SET `image` = replace(image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}member_card_batch` SET `qrcode` = replace(qrcode ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}member_right` SET `image` = replace(image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}out_interface` SET `return_params` = replace(return_params ,'{$siteUrl}','{$url}'),`request_example` = replace(request_example ,'{$siteUrl}','{$url}'),`return_example` = replace(return_example ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}qrcode` SET `url` = replace(url ,'{$siteUrl}','{$url}'),`qrcode_url` = replace(qrcode_url ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_bargain` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`images` = replace(images,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_category` SET `pic` = replace(`pic` ,'{$siteUrl}','{$url}'),`big_pic` = replace(`big_pic` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_combination` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`images` = replace(images,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_integral` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`images` = replace(images,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_integral_order` SET `image` = replace(`image` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_order_cart_info` SET `cart_info` = replace(cart_info ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_order_refund` SET `refund_img` = replace(refund_img ,'{$siteUrl}','{$url}'),`cart_info` = replace(cart_info,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_pink` SET `avatar` = replace(`avatar` ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_product` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`slider_image` = replace(slider_image ,'{$siteUrlJson}','{$urlJson}'),`recommend_image` = replace(recommend_image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_product_attr_result` SET `result` = replace(result ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_product_attr_value` SET `image` = replace(image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_product_description` SET `description`= replace(description,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_product_reply` SET `avatar` = replace(avatar ,'{$siteUrl}','{$url}'),`pics` = replace(pics,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_seckill` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`images` = replace(images,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_service` SET `avatar` = replace(avatar ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}store_service_log` SET `msn` = replace(msn ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}store_service_record` SET `avatar` = replace(avatar ,'{$siteUrl}','{$url}'),`message` = replace(message,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}system_admin` SET `head_pic` = replace(head_pic ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}system_attachment` SET `att_dir` = replace(att_dir ,'{$siteUrl}','{$url}'),`satt_dir` = replace(satt_dir ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}system_config` SET `value` = replace(value ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}system_config` SET `value` = replace(value ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}system_group_data` SET `value` = replace(value ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}system_store` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`oblong_image` = replace(oblong_image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}system_store_staff` SET `avatar` = replace(avatar ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}system_user_level` SET `image` = replace(image ,'{$siteUrl}','{$url}'),`icon` = replace(icon ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}user` SET `avatar` = replace(avatar ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}user_extract` SET `qrcode_url` = replace(qrcode_url ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}wechat_qrcode` SET `image` = replace(image ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}wechat_user` SET `headimgurl` = replace(headimgurl ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}theme` SET `home_data` = replace(home_data ,'{$siteUrlJson}','{$urlJson}'),`detail_data` = replace(detail_data ,'{$siteUrlJson}','{$urlJson}'),`user_data` = replace(user_data ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}theme` SET `home_default_data` = replace(home_default_data ,'{$siteUrlJson}','{$urlJson}'),`detail_default_data` = replace(detail_default_data ,'{$siteUrlJson}','{$urlJson}'),`user_default_data` = replace(user_default_data ,'{$siteUrlJson}','{$urlJson}')",
            "UPDATE `{$prefix}theme` SET `home_data` = replace(home_data ,'{$siteUrl}','{$url}'),`detail_data` = replace(detail_data ,'{$siteUrl}','{$url}'),`user_data` = replace(user_data ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}theme` SET `home_default_data` = replace(home_default_data ,'{$siteUrl}','{$url}'),`detail_default_data` = replace(detail_default_data ,'{$siteUrl}','{$url}'),`user_default_data` = replace(user_default_data ,'{$siteUrl}','{$url}')",
            "UPDATE `{$prefix}theme` SET `home_image` = replace(home_image ,'{$siteUrl}','{$url}'),`category_image` = replace(category_image ,'{$siteUrl}','{$url}'),`detail_image` = replace(detail_image ,'{$siteUrl}','{$url}'),`user_image` = replace(user_image ,'{$siteUrl}','{$url}'),`home_default_image` = replace(home_default_image ,'{$siteUrl}','{$url}'),`category_default_image` = replace(category_default_image ,'{$siteUrl}','{$url}'),`detail_default_image` = replace(detail_default_image ,'{$siteUrl}','{$url}'),`user_default_image` = replace(user_default_image ,'{$siteUrl}','{$url}')",
        ];

        // 执行 SQL 语句
        return Db::transaction(function () use ($sql) {
            try {
                foreach ($sql as $item) {
                    Db::execute($item);
                }
            } catch (\Throwable $e) {
                throw new AdminException('替换失败,失败原因:{:msg}', ['msg' => $e->getMessage()]);
            }
        });
    }

    /**
     * 导出多语言包
     * @param string $outputPath
     */
    protected function exportLangPackage(string $outputPath): void
    {
        $fullOutputPath = $this->resolveProjectPath($outputPath);
        $dir = dirname($fullOutputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $prefix = Config::get('database.connections.' . Config::get('database.default') . '.prefix');
        $table = $prefix . 'lang_code';
        $rows = Db::name('lang_code')->order('id asc')->select()->toArray();

        $sql = [
            '-- CRMEB language package export',
            '-- generated at: ' . date('Y-m-d H:i:s'),
            "DELETE FROM `{$table}`;",
        ];
        foreach ($rows as $row) {
            $columns = array_keys($row);
            $columnSql = implode('`,`', $columns);
            $values = array_map(function ($value) {
                if ($value === null) {
                    return 'NULL';
                }
                return "'" . addslashes((string)$value) . "'";
            }, array_values($row));
            $valueSql = implode(',', $values);
            $sql[] = "INSERT INTO `{$table}` (`{$columnSql}`) VALUES ({$valueSql});";
        }

        file_put_contents($fullOutputPath, implode(PHP_EOL, $sql) . PHP_EOL);
    }

    /**
     * 导入多语言包
     * @param string $filePath
     */
    protected function importLangPackage(string $filePath): void
    {
        $fullPath = $this->resolveProjectPath($filePath, true);
        if (!is_file($fullPath)) {
            throw new AdminException('导入失败，文件不存在：{:file}', ['file' => $filePath]);
        }
        $sql = trim((string)file_get_contents($fullPath));
        if ($sql === '') {
            throw new AdminException('导入失败，文件为空');
        }
        Db::transaction(function () use ($sql) {
            $sqlList = $this->parseSqlStatements($sql);
            foreach ($sqlList as $item) {
                Db::execute($item);
            }
            app()->make(LangCodeServices::class)->clearLangCache();
        });
    }

    /**
     * 解析项目内路径
     * @param string $path
     * @param bool $mustExist
     * @return string
     */
    protected function resolveProjectPath(string $path, bool $mustExist = false): string
    {
        $root = rtrim(app()->getRootPath(), DIRECTORY_SEPARATOR);
        $candidate = $path;
        if (substr($path, 0, 1) !== DIRECTORY_SEPARATOR) {
            $candidate = $root . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
        }
        $normalizedCandidate = str_replace('\\', '/', $candidate);
        $normalizedRoot = str_replace('\\', '/', $root);

        // 先做一次字符串前缀检查，允许导出到项目内的新路径（目录可不存在）
        if (strpos($normalizedCandidate, $normalizedRoot . '/') !== 0 && $normalizedCandidate !== $normalizedRoot) {
            throw new AdminException('文件路径不合法，仅允许项目目录内路径');
        }

        // 防止通过 ../ 逃逸目录
        $relativePath = ltrim(substr($normalizedCandidate, strlen($normalizedRoot)), '/');
        $segments = $relativePath === '' ? [] : explode('/', $relativePath);
        $depth = 0;
        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }
            if ($segment === '..') {
                $depth--;
            } else {
                $depth++;
            }
            if ($depth < 0) {
                throw new AdminException('文件路径不合法，仅允许项目目录内路径');
            }
        }

        if ($mustExist) {
            $realPath = realpath($candidate);
            if (!$realPath || strpos(str_replace('\\', '/', $realPath), $normalizedRoot . '/') !== 0) {
                throw new AdminException('文件路径不合法，仅允许项目目录内路径');
            }
        }
        return $candidate;
    }

    /**
     * 解析 SQL 文件为可执行语句
     * @param string $sqlContent
     * @return array
     */
    protected function parseSqlStatements(string $sqlContent): array
    {
        $lines = preg_split('/\r\n|\n|\r/', $sqlContent);
        $statements = [];
        $buffer = '';
        foreach ($lines as $line) {
            $trim = trim($line);
            if ($trim === '' || strpos($trim, '--') === 0) {
                continue;
            }
            $buffer .= $line . PHP_EOL;
            if (substr(rtrim($trim), -1) === ';') {
                $statements[] = trim($buffer);
                $buffer = '';
            }
        }
        if (trim($buffer) !== '') {
            $statements[] = trim($buffer);
        }
        return $statements;
    }
}
