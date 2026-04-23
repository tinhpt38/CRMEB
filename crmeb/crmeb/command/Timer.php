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

use app\services\system\crontab\SystemCrontabServices;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class Timer extends Command
{
    /**
     * @var int
     */
    protected $timer;

    /**
     * @var int|float
     */
    protected $interval = 1;

    protected function configure()
    {
        // Cấu hình lệnh
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（daemon) để bắt đầu')
            ->addOption('i', null, Option::VALUE_OPTIONAL, 'Tần suất thực hiện,Có thể chính xác đến0.001')
            ->setDescription('start/stop/restart nhiệm vụ theo lịch trình');
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;

        if ($input->hasOption('i'))
            $this->interval = floatval($input->getOption('i'));

        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }

    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        Worker::$pidFile = app()->getRootPath() . 'runtime/timer.pid';
        $task = new Worker();
        date_default_timezone_set('PRC');
        $task->count = 1;
        $task->onWorkerStart = function () use ($task) {
            app()->make(SystemCrontabServices::class)->crontabCommandRun($task);
        };
        $task->runAll();
    }
}
