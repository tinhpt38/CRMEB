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


use crmeb\services\FileService;
use crmeb\utils\Terminal;
use think\console\Command;
use think\console\input\Argument;
use think\console\input\Option;

/**
 * Class Npm
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/13
 * @package crmeb\command
 */
class Npm extends Command
{
    /**
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    protected function configure()
    {
        $this->setName('npm')
            ->addOption('path', 'dp', Option::VALUE_OPTIONAL, 'đường dẫn mặc định')
            ->addOption('build', 'bu', Option::VALUE_OPTIONAL, 'Đường dẫn lưu trữ gói')
            ->setDescription('NPMDụng cụ đóng gói');
    }

    /**
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    public function handle()
    {
        $path = $this->input->getOption('path');
        $build = $this->input->getOption('build');
        if (!$build) {
            $build = public_path() . 'admin';
        }

        $terminal = new Terminal();
        $terminal->setOutput($this->output);

        $adminPath = $path ?: $terminal->adminTemplatePath();

        $adminPath = dirname($adminPath);

        if (is_dir($adminPath . DS . 'dist')) {
            $question = $this->output->confirm($this->input, 'Phát hiện xem có đóng gói lại tệp gói đã được tạo hay không?', false);
            if (!$question) {
                $this->output->info('Đã thoát khỏi chương trình đóng gói');
                return;
            }
        }

        $dir = $adminPath . DS . 'node_modules';
        if (!is_dir($dir)) {
            $terminal->run('npm-install');
        }


        $terminal->run('npm-build');

        if (!is_dir($adminPath . DS . 'dist')) {
            $this->output->error('Đóng gói không thành công');
            return;
        }

        $this->app->make(FileService::class)->copyDir($adminPath . DS . 'dist', $build);

        $this->output->info('Đã thực hiện thành công');
    }
}
