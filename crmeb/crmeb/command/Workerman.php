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

use app\services\system\config\SystemConfigServices;
use Channel\Server;
use crmeb\services\workerman\chat\ChatService;
use crmeb\services\workerman\WorkermanService;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class Workerman extends Command
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var Worker
     */
    protected $workerServer;

    /**
     * @var Worker
     */
    protected $chatWorkerServer;

    /**
     * @var Server
     */
    protected $channelServer;

    /**
     * @var Input
     */
    public $input;

    /**
     * @var Output
     */
    public $output;

    protected function configure()
    {
        // Cấu hình lệnh
        $this->setName('workerman')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addArgument('server', Argument::OPTIONAL, 'admin/chat/channel')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（daemon) để bắt đầu')
            ->setDescription('start/stop/restart workerman');
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;
        $argv[1] = $input->getArgument('status') ?: 'start';
        $server = $input->getArgument('server');
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }

        $this->config = config('workerman');

        return $server;
    }

    protected function execute(Input $input, Output $output)
    {
        $server = $this->init($input, $output);
        /** @var SystemConfigServices $services */
        $services = app()->make(SystemConfigServices::class);
        $sslConfig = $services->getSslFilePath();
//        $confing['wss_open'] = $sslConfig['wssOpen'] ?? 0;
        $confing['wss_open'] = 0;
        $confing['wss_local_cert'] = $sslConfig['wssLocalCert'] ?? '';
        $confing['wss_local_pk'] = $sslConfig['wssLocalpk'] ?? '';
        // Giấy chứng nhận tốt nhất nên là giấy chứng nhận được áp dụng cho
        if ($confing['wss_open']) {
            $context = [
                'ssl' => [
                    // Vui lòng sử dụng đường dẫn tuyệt đối
                    'local_cert' => realpath('public' . $confing['wss_local_cert']), // Nó cũng có thể là một tập tin crt
                    'local_pk' => realpath('public' . $confing['wss_local_pk']),
                    'verify_peer' => false,
                ]
            ];
        } else {
            $context = [];
        }
        Worker::$pidFile = app()->getRootPath() . 'runtime/workerman.pid';
        Worker::$logFile = app()->getRootPath() . 'runtime/workerman.log';
        if (!$server || $server == 'admin') {
            var_dump('admin');
            //Tạo dịch vụ kết nối dài của quản trị viên
            $this->workerServer = new Worker($this->config['admin']['protocol'] . '://' . $this->config['admin']['ip'] . ':' . $this->config['admin']['port'], $context);
            $this->workerServer->count = $this->config['admin']['serverCount'];
            if ($confing['wss_open']) {
                $this->workerServer->transport = 'ssl';
            }
        }

        if (!$server || $server == 'chat') {
            var_dump('chat');
            //Tạo dịch vụ kết nối dài chat h5
            $this->chatWorkerServer = new Worker($this->config['chat']['protocol'] . '://' . $this->config['chat']['ip'] . ':' . $this->config['chat']['port'], $context);
            $this->chatWorkerServer->count = $this->config['chat']['serverCount'];
            if ($confing['wss_open']) {
                $this->chatWorkerServer->transport = 'ssl';
            }
        }

        if (!$server || $server == 'channel') {
            var_dump('channel');
            //Tạo dịch vụ liên lạc nội bộ
            $this->channelServer = new Server($this->config['channel']['ip'], $this->config['channel']['port']);
        }
        $this->bindHandle();
        try {
            Worker::runAll();
        } catch (\Exception $e) {
            $output->warning($e->getMessage());
        }
    }

    /**
     * Lệnh gọi lại sự kiện Bind Workerman
     *
     * Phương pháp này chịu trách nhiệm“Nền tảng quản lý dịch vụ kết nối dài”Và“Phòng chat dịch vụ kết nối dài”Liên kết với các lớp xử lý kinh doanh tương ứng tương ứng.
     * Cho phép logic nghiệp vụ tương ứng được gọi tự động khi máy khách kết nối, gửi tin nhắn hoặc các quá trình được khởi động hoặc ngắt kết nối.
     *
     * 1. Nếu dịch vụ quản trị đã được tạo（$this->workerServer không rỗng):
     * - Khởi tạo WorkermanService, chuyển vào phiên bản worker hiện tại và phiên bản dịch vụ kênh
     * - Liên kết bốn sự kiện onConnect/onMessage/onWorkerStart/onClose với phương thức cùng tên của WorkermanService
     *
     * 2. Nếu dịch vụ trò chuyện đã được tạo（$this->chatWorkerServer không rỗng):
     * - Khởi tạo ChatService, chuyển vào phiên bản nhân viên trò chuyện hiện tại và phiên bản dịch vụ kênh
     * - Đồng thời liên kết bốn sự kiện trên với phương thức cùng tên của ChatService
     *
     * Bằng cách này, mã doanh nghiệp được tách khỏi lõi Workerman, tạo điều kiện thuận lợi cho việc bảo trì và mở rộng sau này.。
     */
    protected function bindHandle()
    {
        // Sự kiện dịch vụ quản trị viên ràng buộc
        // Chỉ khi phiên bản dịch vụ kết nối dài của quản trị viên được tạo（$this->workerServer không phải là rỗng).
        if (!is_null($this->workerServer)) {
            // Khởi tạo WorkermanService, chuyển vào phiên bản nhân viên quản trị hiện tại và phiên bản dịch vụ kênh
            // WorkermanService chịu trách nhiệm xử lý logic nghiệp vụ liên quan đến nền tảng quản lý.
            $server = new WorkermanService($this->workerServer, $this->channelServer);
            
            // Liên kết bốn sự kiện cốt lõi của Workerman với phương thức cùng tên của WorkermanService
            // Kích hoạt khi kết nối client thành công
            $this->workerServer->onConnect = [$server, 'onConnect'];
            // Kích hoạt khi nhận được tin nhắn từ client
            $this->workerServer->onMessage = [$server, 'onMessage'];
            // Được kích hoạt khi tiến trình công nhân bắt đầu (chỉ một lần trong vòng đời của tiến trình）
            $this->workerServer->onWorkerStart = [$server, 'onWorkerStart'];
            // Được kích hoạt khi máy khách ngắt kết nối
            $this->workerServer->onClose = [$server, 'onClose'];
        }

        // Liên kết các sự kiện dịch vụ trò chuyện
        // Chỉ khi phiên bản dịch vụ kết nối dài trò chuyện được tạo（$this->chatWorkerServer không phải là rỗng).
        if (!is_null($this->chatWorkerServer)) {
            // Khởi tạo ChatService, chuyển vào phiên bản nhân viên trò chuyện hiện tại và phiên bản dịch vụ kênh
            // ChatService chịu trách nhiệm xử lý logic nghiệp vụ liên quan đến phòng chat
            $chatServer = new ChatService($this->chatWorkerServer, $this->channelServer);
            
            // Liên kết bốn sự kiện cốt lõi của Workerman với các phương thức cùng tên của ChatService
            // Kích hoạt khi kết nối client thành công
            $this->chatWorkerServer->onConnect = [$chatServer, 'onConnect'];
            // Kích hoạt khi nhận được tin nhắn từ client
            $this->chatWorkerServer->onMessage = [$chatServer, 'onMessage'];
            // Được kích hoạt khi tiến trình công nhân bắt đầu (chỉ một lần trong vòng đời của tiến trình）
            $this->chatWorkerServer->onWorkerStart = [$chatServer, 'onWorkerStart'];
            // Được kích hoạt khi máy khách ngắt kết nối
            $this->chatWorkerServer->onClose = [$chatServer, 'onClose'];
        }
    }
}
