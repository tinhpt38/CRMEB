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

namespace app\adminapi\controller;


use app\Request;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\SystemRouteServices;
use crmeb\services\CacheService;
use think\Response;
use think\facade\Db;

class PublicController
{

    /**
     * Tải tập tin xuống
     * @param string $key
     * @return Response|\think\response\File
     */
    public function download(Request $request, string $key = '')
    {
        if ($key == '') {
            $key = $request->getMore([
                ['key', ''],
            ], true);
        }
        if (!$key) {
            return Response::create()->code(500);
        }
        $fileName = CacheService::get($key);
        if (is_array($fileName) && isset($fileName['path']) && isset($fileName['fileName']) && $fileName['path'] && $fileName['fileName'] && file_exists($fileName['path'])) {
            CacheService::delete($key);
            return download($fileName['path'], $fileName['fileName']);
        }
        return Response::create()->code(500);
    }

    /**
     * Nhận tên miền yêu cầu của công nhân
     * @return mixed
     */
    public function getWorkerManUrl()
    {
        return app('json')->success(getWorkerManUrl());
    }

    /**
     * Quét mã để tải lên
     * @param Request $request
     * @param int $upload_type
     * @param int $type
     * @return Response
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/13
     */
    public function scanUpload(Request $request, $upload_type = 0, $type = 0)
    {
        [$file, $uploadToken, $pid] = $request->postMore([
            ['file', 'file'],
            ['uploadToken', ''],
            ['pid', 0]
        ], true);
        $service = app()->make(SystemAttachmentServices::class);
        if (CacheService::get('scan_upload') != $uploadToken) {
            return app('json')->fail('Cấu hình đã được thay đổi hoặc mã thông báo đã hết hạn');
        }
        $service->upload((int)$pid, $file, $upload_type, $type, '', $uploadToken);
        return app('json')->success('Tải lên thành công');
    }

    public function import(Request $request)
    {
        $filePath = $request->param('file_path', '');
        if (empty($filePath)) {
            return app('json')->fail('Tập tin không tồn tại');
        }
        app()->make(SystemRouteServices::class)->import($filePath);
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Thông tin máy chủ
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/9/24
     */
    public function getSystemInfo()
    {
        $info['server'] = [
            ['name' => 'Hệ thống máy chủ', 'require' => 'loạiUNIX', 'value' => PHP_OS],
            ['name' => 'WEBmôi trường', 'require' => 'Apache/Nginx/IIS', 'value' => $_SERVER['SERVER_SOFTWARE']],
        ];
        $gd_info = function_exists('gd_info') ? gd_info() : array();
        $info['environment'] = [
            ['name' => 'PHPPhiên bản', 'require' => '7.1-7.4', 'value' => phpversion()],
            ['name' => 'MySqlPhiên bản', 'require' => '5.6-8.0', 'value' => Db::query("SELECT VERSION()")[0]['VERSION()']],
            ['name' => 'MySqli', 'require' => 'bật lên', 'value' => function_exists('mysqli_connect')],
            ['name' => 'Openssl', 'require' => 'bật lên', 'value' => function_exists('openssl_encrypt')],
            ['name' => 'Session', 'require' => 'bật lên', 'value' => function_exists('session_start')],
            ['name' => 'Safe_Mode', 'require' => 'bật lên', 'value' => !ini_get('safe_mode')],
            ['name' => 'GD', 'require' => 'bật lên', 'value' => !empty($gd_info['GD Version'])],
            ['name' => 'Curl', 'require' => 'bật lên', 'value' => function_exists('curl_init')],
            ['name' => 'Bcmath', 'require' => 'bật lên', 'value' => function_exists('bcadd')],
            ['name' => 'Upload', 'require' => 'bật lên', 'value' => (bool)ini_get('file_uploads')],
        ];

        $info['permissions'] = [
            ['name' => 'backup', 'require' => 'Đọc và viết', 'value' => is_readable(root_path('backup')) && is_writable(root_path('backup'))],
            ['name' => 'public', 'require' => 'Đọc và viết', 'value' => is_readable(root_path('public')) && is_writable(root_path('public'))],
            ['name' => 'runtime', 'require' => 'Đọc và viết', 'value' => is_readable(root_path('runtime')) && is_writable(root_path('runtime'))],
            ['name' => '.env', 'require' => 'Đọc và viết', 'value' => is_readable(root_path() . '.env') && is_writable(root_path() . '.env')],
            ['name' => '.version', 'require' => 'Đọc và viết', 'value' => is_readable(root_path() . '.version') && is_writable(root_path() . '.version')],
            ['name' => '.constant', 'require' => 'Đọc và viết', 'value' => is_readable(root_path() . '.constant') && is_writable(root_path() . '.constant')],
        ];

        if (function_exists('exec')) {
            $workermanOutput = $timerOutput = $queueOutput = [];
            // exec("ps aux | grep 'php think workerman' | grep -v grep", $workermanOutput);
            $targetPort = config('workerman.chat.port');
            $thinkPath = root_path(); // think đường dẫn tuyệt đối đến tập tin
            $checkService = function($service) {
                if($service === 'queue'){
                    $command = 'queue:listen'; 
                    // Thực hiện lệnh ps để tìm quá trình xếp hàng
                    exec("ps aux | grep '{$command}' | grep -v grep", $output);
                    
                    // Nếu đầu ra không trống, điều đó có nghĩa là quá trình tồn tại
                    return !empty($output);
                } else {
                    $pidFile = root_path('runtime') . $service . '.pid';
                    // Kiểm tra tập tin PID trước
                    if (!file_exists($pidFile)) {
                        return false;
                    }
                    
                    // Nếu tệp PID tồn tại, hãy thử lấy trạng thái quy trình
                    $pid = trim(file_get_contents($pidFile));
                    if ($pid && is_numeric($pid)) {
                        if (function_exists('posix_kill') && posix_kill($pid, 0)) {
                            return true;
                        }
                        // Phương pháp kiểm tra thay thế
                        if (function_exists('exec')) {
                            $output = [];
                            exec("ps -ef | grep " . escapeshellarg($pid) . " | grep -v grep", $output);
                            // Xác định xem có đầu ra từ quy trình không phải grep không
                            return !empty($output);
                        }
                    }
                }
            };
        
            $info['process'] = [
                ['name' => 'liên kết dài', 'require' => 'bật lên', 'value' => $checkService('workerman')],
                ['name' => 'nhiệm vụ theo lịch trình', 'require' => 'bật lên', 'value' => $checkService('timer')],
                ['name' => 'hàng đợi tin nhắn', 'require' => 'bật lên', 'value' => $checkService('queue')],
            ];
            
        } else {
            $info['process'] = [
                ['name' => 'liên kết dài', 'require' => 'bật lên', 'value' => file_exists(root_path('runtime') . 'workerman.pid')],
                ['name' => 'nhiệm vụ theo lịch trình', 'require' => 'bật lên', 'value' => file_exists(root_path('runtime') . 'timer.pid')],
                ['name' => 'hàng đợi tin nhắn', 'require' => 'bật lên', 'value' => file_exists(root_path('runtime') . '.queue')],
            ];
        }

        return app('json')->success($info);
    }

    public function customAdminJs()
    {
        return sys_config('custom_admin_js', '');
    }
}
