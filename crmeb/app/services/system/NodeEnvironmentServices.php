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
namespace app\services\system;

use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use think\facade\Log;

/**
 * Node.js Dịch vụ quản lý môi trường
 *
 * Tổng quan về chức năng:
 * Lớp dịch vụ này chịu trách nhiệm phát hiện và quản lý môi trường hoạt động cần thiết để tải lên CI chương trình nhỏ.
 * Bao gồm hướng dẫn phát hiện và cài đặt cho các công cụ Node.js, npm và miniprogram-ci.
 *
 * Chức năng chính:
 * 1. Phát hiện môi trường - phát hiện trạng thái cài đặt và phiên bản của Node.js, npm, miniprogram-ci
 * 2. Nhận dạng hệ thống - xác định loại hệ điều hành (CentOS/Ubuntu/macOS/Windows)
 * 3. Hướng dẫn cài đặt - Cung cấp các lệnh cài đặt tương ứng theo hệ điều hành
 * 4. Phát hiện hàm exec - kiểm tra PHP exec() Chức năng này có sẵn không?
 *
 * Yêu cầu về môi trường:
 * - Node.js >= 14.0.0 (gợi ý 18.x LTS)
 * - npm (Đã cài đặt với Node.js)
 * - miniprogram-ci (Cài đặt trên toàn cầu thông qua npm install -g miniprogram-ci)
 * - PHP exec() Chức năng không bị vô hiệu hóa
 *
 * @package app\services\system
 */
class NodeEnvironmentServices extends BaseServices
{
    /**
     * Node.js Yêu cầu phiên bản tối thiểu
     *
     * Công cụ miniprogram-ci yêu cầu Node.js 14.0.0 trở lên。
     */
    const MIN_NODE_VERSION = '14.0.0';

    /**
     * Phiên bản Node.js được đề xuất
     *
     * Nên sử dụng phiên bản Node.js 18 LTS để mang lại hiệu suất và bảo mật tốt hơn。
     */
    const RECOMMENDED_NODE_VERSION = '18';

    /**
     * Nhận thông tin trạng thái môi trường đầy đủ
     *
     * Phát hiện và trả về tất cả thông tin môi trường cần thiết để tải lên CI chương trình nhỏ,
     * Giao diện người dùng hiển thị trạng thái sẵn sàng của môi trường hoặc hướng dẫn người dùng hoàn tất cấu hình môi trường dựa trên thông tin này.
     *
     * @return mảng thông tin trạng thái môi trường đầy đủ, bao gồm:
     *               - os: Thông tin hệ điều hành {family, type, version}
     *               - node: Node.js tình trạng {installed, version, path, meets_requirement}
     *               - npm: npm tình trạng {installed, version}
     *               - miniprogram_ci: CItrạng thái công cụ {installed, version}
     *               - ready: Môi trường đã hoàn toàn sẵn sàng chưa?
     *               - can_install: Có hỗ trợ cài đặt tự động hay không
     *               - exec_enabled: exec Chức năng này có sẵn không?
     *               - message: Tin nhắn nhắc nhở
     */
    public function getEnvironmentStatus(): array
    {
        // Kiểm tra các môi trường khác nhau
        $nodeInfo = $this->checkNodeInstalled();       // Node.js tình trạng
        $npmInfo = $this->checkNpmInstalled();         // npm tình trạng
        $ciInfo = $this->checkMiniprogramCIInstalled(); // miniprogram-ci tình trạng
        $osInfo = $this->getOsInfo();                  // Thông tin hệ điều hành
        $execEnabled = $this->isExecEnabled();         // exec Tính khả dụng của chức năng

        return [
            'os' => $osInfo,                           // Thông tin hệ điều hành
            'node' => $nodeInfo,                       // Node.js tình trạng
            'npm' => $npmInfo,                         // npm tình trạng
            'miniprogram_ci' => $ciInfo,               // miniprogram-ci Trạng thái
            //Điều kiện sẵn sàng của môi trường: Node.js + npm + miniprogram-ci Cả hai đều được cài đặt và thực thi có sẵn
            'ready' => $nodeInfo['installed'] && $npmInfo['installed'] && $ciInfo['installed'] && $execEnabled,
            'can_install' => $this->canAutoInstall(),  // Có hỗ trợ cài đặt tự động hay không
            'exec_enabled' => $execEnabled,            // exec Chức năng này có sẵn không?
            'message' => $execEnabled ? '' : 'Máy chủ đã tắt chức năng thực thi và không thể sử dụng chức năng tải lên chương trình mini. Vui lòng liên hệ với quản trị viên máy chủ của bạn để kích hoạt chức năng exec。',
        ];
    }

    /**
     * nghiên cứu PHP exec() Chức năng này có sẵn không?
     *
     * Chức năng tải lên CI của chương trình mini dựa trên PHP exec() Chức năng thực thi các công cụ dòng lệnh.
     * Nhiều máy chủ sẽ tắt chức năng này vì lý do bảo mật và cần phải kiểm tra tính khả dụng của nó.
     *
     * Các bước phát hiện:
     * 1. Kiểm tra xem chức năng exec có tồn tại không
     * 2. Kiểm tra xem cấu hìnhdisable_functions có chứa exec hay không
     * 3. Thử thực hiện một lệnh đơn giản để xác minh tính khả dụng thực tế
     *
     * @return hàm exec bool có thể trả về true, nếu không thì trả về false
     */
    public function isExecEnabled(): bool
    {
        // Kiểm tra xem chức năng exec có tồn tại không
        if (!function_exists('exec')) {
            return false;
        }

        // Kiểm tra xem vô hiệu hóa chức năng có bị tắt trong cấu hình không exec
        $disabled = explode(',', ini_get('disable_functions'));
        $disabled = array_map('trim', $disabled);
        if (in_array('exec', $disabled)) {
            return false;
        }

        // Hãy thử thực hiện một lệnh đơn giản để xác minh tính khả dụng thực tế
        $output = [];
        $code = 0;
        @exec('echo test 2>&1', $output, $code);

        return $code === 0 && !empty($output);
    }

    /**
     * Kiểm tra xem Node.js đã được cài đặt chưa
     *
     * Lấy trạng thái cài đặt và thông tin phiên bản của Node.js bằng cách thực hiện lệnh node -v.
     *
     * @return mảng Thông tin trạng thái Node.js, bao gồm:
     *               - installed: Nó đã được cài đặt chưa
     *               - version: số phiên bản (giống 18.17.0)
     *               - path: Đường dẫn tập tin thực thi
     *               - meets_requirement: Nó có đáp ứng các yêu cầu phiên bản tối thiểu không?
     */
    public function checkNodeInstalled(): array
    {
        $result = [
            'installed' => false,
            'version' => '',
            'path' => '',
            'meets_requirement' => false,
        ];

        // Thực thi node -v để lấy thông tin phiên bản
        $output = $this->execCommand('node -v 2>&1');
        if ($output && preg_match('/v?(\d+\.\d+\.\d+)/', $output, $matches)) {
            $result['installed'] = true;
            $result['version'] = $matches[1];
            // Kiểm tra xem phiên bản có đáp ứng yêu cầu tối thiểu không (>= 14.0.0)
            $result['meets_requirement'] = version_compare($matches[1], self::MIN_NODE_VERSION, '>=');

            // Nhận đường dẫn đầy đủ đến tệp thực thi của nút
            $path = $this->execCommand('which node 2>&1');
            $result['path'] = trim($path);
        }

        return $result;
    }

    /**
     * Kiểm tra xem npm đã được cài đặt chưa
     *
     * npm là trình quản lý gói cho Node.js và thường được cài đặt cùng với Node.js.
     * Được sử dụng để cài đặt các gói npm như miniprogram-ci.
     *
     * @return mảng thông tin trạng thái npm, bao gồm:
     *               - installed: Nó đã được cài đặt chưa
     *               - version: số phiên bản
     */
    public function checkNpmInstalled(): array
    {
        $result = [
            'installed' => false,
            'version' => '',
        ];

        // Thực thi npm -v để lấy thông tin phiên bản
        $output = $this->execCommand('npm -v 2>&1');
        if ($output && preg_match('/(\d+\.\d+\.\d+)/', $output, $matches)) {
            $result['installed'] = true;
            $result['version'] = $matches[1];
        }

        return $result;
    }

    /**
     * Kiểm tra xem miniprogram-ci có được cài đặt trên toàn cầu không
     *
     * miniprogram-ci là công cụ tải lên mã chương trình mini được WeChat cung cấp chính thức.
     * Yêu cầu cài đặt toàn cầu thông qua npm install -g miniprogram-ci.
     *
     * @return mảng thông tin trạng thái chương trình nhỏ-ci, bao gồm:
     *               - installed: Nó đã được cài đặt chưa
     *               - version: số phiên bản
     */
    public function checkMiniprogramCIInstalled(): array
    {
        $result = [
            'installed' => false,
            'version' => '',
        ];

        // Kiểm tra các gói được cài đặt trên toàn cầu thông qua npm list -g
        $output = $this->execCommand('npm list -g miniprogram-ci --depth=0 2>&1');
        if ($output && preg_match('/miniprogram-ci@(\d+\.\d+\.\d+)/', $output, $matches)) {
            $result['installed'] = true;
            $result['version'] = $matches[1];
        }

        return $result;
    }

    /**
     * Nhận thông tin hệ điều hành
     *
     * Xác định loại hệ điều hành của máy chủ để cung cấp hướng dẫn cài đặt tương ứng.
     * Sử dụng tính năng phát hiện dòng lệnh để tránh bị ảnh hưởng bởi các hạn chế open_basedir.
     *
     * Hỗ trợ hệ thống nhận dạng:
     * - Linux: CentOS/RHEL/Rocky/Ubuntu/Debian/Alpine Chờ đợi
     * - macOS: Nhận phiên bản qua sw_vers
     * - Windows: Được xác định bởi PHP_OS_FAMILY
     *
     * @return mảng thông tin hệ điều hành, bao gồm:
     *               - family: gia đình hệ thống (Linux/Darwin/Windows)
     *               - type: loại bê tông (centos/ubuntu/debian/macos/windows)
     *               - version: Số phiên bản hệ thống
     */
    public function getOsInfo(): array
    {
        $os = PHP_OS_FAMILY;  // Nhận họ hệ điều hành được PHP công nhận
        $type = 'unknown';
        $version = '';

        if ($os === 'Linux') {
            // Linux hệ thống: Đọc /etc/os-release thông qua dòng lệnh để lấy thông tin phát hành
            $osRelease = $this->execCommand('cat /etc/os-release 2>/dev/null');

            if ($osRelease) {
                // Phân tích nội dung của tệp phát hành os
                $lines = explode("\n", $osRelease);
                $osInfo = [];
                foreach ($lines as $line) {
                    if (strpos($line, '=') !== false) {
                        list($key, $value) = explode('=', $line, 2);
                        $osInfo[trim($key)] = trim($value, '"\' ');
                    }
                }

                $id = strtolower($osInfo['ID'] ?? '');
                $version = $osInfo['VERSION_ID'] ?? '';

                // Bản đồ loại hệ điều hành
                if (in_array($id, ['centos', 'rhel', 'rocky', 'almalinux', 'fedora'])) {
                    $type = 'centos';  // Red Hat loạt
                } elseif ($id === 'ubuntu') {
                    $type = 'ubuntu';
                } elseif (in_array($id, ['debian', 'raspbian'])) {
                    $type = 'debian';
                } elseif ($id === 'alpine') {
                    $type = 'alpine';
                } else {
                    $type = $id ?: 'linux';
                }
            } else {
                // kế hoạch dự phòng: Sử dụng lệnh uname
                $uname = $this->execCommand('uname -a 2>/dev/null');
                $type = 'linux';
                $version = $uname ?: '';
            }
        } elseif ($os === 'Darwin') {
            // macOS hệ thống
            $type = 'macos';
            $version = $this->execCommand('sw_vers -productVersion 2>&1') ?: '';
        } elseif ($os === 'Windows') {
            // Windows hệ thống
            $type = 'windows';
        }

        return [
            'family' => $os,            // gia đình hệ thống
            'type' => $type,            // loại bê tông
            'version' => trim($version), // số phiên bản
        ];
    }

    /**
     * Kiểm tra xem cài đặt tự động có được hỗ trợ không
     *
     * Kiểm tra xem môi trường máy chủ có hỗ trợ cài đặt tự động Node.js và các công cụ liên quan hay không.
     *Chức năng cài đặt tự động tùy thuộc vào loại hệ điều hành và tính khả dụng của chức năng PHP.
     *
     * Hệ điều hành được hỗ trợ: CentOS/RHEL、Ubuntu、Debian、Alpine、macOS
     *
     * @return bool Hỗ trợ quay lại cài đặt tự động true
     */
    public function canAutoInstall(): bool
    {
        $os = $this->getOsInfo();

        // Danh sách hệ điều hành hỗ trợ cài đặt tự động
        $supportedOs = ['centos', 'rhel', 'ubuntu', 'debian', 'alpine', 'macos'];

        if (!in_array($os['type'], $supportedOs)) {
            return false;
        }

        // Kiểm tra xem bạn có quyền thực thi lệnh không (exec hoặc shell_exec)
        if (!function_exists('exec') && !function_exists('shell_exec')) {
            return false;
        }

        return true;
    }

    /**
     * Thực hiện lệnh và trả về đầu ra
     *
     * Phương thức thực thi lệnh được đóng gói, sử dụng exec trước, nếu không có thì thử shell_exec。
     *
     * @param string $command lệnh để thực thi
     * @return string|null Đầu ra lệnh, trả về nếu thực thi không thành công null
     */
    protected function execCommand(string $command): ?string
    {
        // Thích sử dụng chức năng exec
        if (function_exists('exec')) {
            $output = [];
            exec($command, $output);
            return implode("\n", $output);
        }
        // dự phòng: Sử dụng hàm shell_exec
        elseif (function_exists('shell_exec')) {
            return shell_exec($command);
        }

        return null;
    }

    /**
     * Nhận URL tập lệnh cài đặt bằng một cú nhấp chuột
     *
     * Trả về địa chỉ của tập lệnh shell được sử dụng để tự động cài đặt môi trường Node.js.
     *
     * @return địa chỉ URL chuỗi của tập lệnh cài đặt
     */
    public function getInstallScriptUrl(): string
    {
//        return sys_config('site_url', '') . '/statics/scripts/install_node_env.sh';
        return 'https://www.crmeb.com/static/upgrade/install_node_env.sh';
    }

    /**
     * Nhận hướng dẫn cài đặt (Hướng dẫn cài đặt thủ công)
     *
     * Trả về các bước cài đặt Node.js và miniprogram-ci tương ứng theo loại hệ điều hành máy chủ.
     * Mỗi hệ điều hành đều có các lệnh cài đặt được tối ưu hóa đặc biệt.
     *
     * Hệ điều hành được hỗ trợ:
     * - CentOS/RHEL: Sử dụng kho lưu trữ vòng/phút của NodeSource
     * - Ubuntu/Debian: kho lưu trữ deb bằng NodeSource
     * - macOS: Sử dụng trình quản lý gói Homebrew
     * - Windows: Tải xuống gói cài đặt từ trang web chính thức của Node.js
     *
     * @return thông tin hướng dẫn cài đặt mảng, bao gồm:
     *               - title: Tiêu đề hướng dẫn (Chẳng hạn như "Hướng dẫn cài đặt CentOS/RHEL")
     *               - steps: Mảng các bước cài đặt, chứa hướng dẫn dòng lệnh cụ thể
     *               - script_url: Địa chỉ URL của tập lệnh cài đặt bằng một cú nhấp chuột
     */
    public function getInstallGuide(): array
    {
        // Nhận thông tin hệ điều hành hiện tại
        $os = $this->getOsInfo();

        // Hướng dẫn cài đặt cho từng hệ điều hành
        $guides = [
            // CentOS/RHEL Series - sử dụng trình quản lý gói yum
            'centos' => [
                'title' => 'CentOS/RHEL Hướng dẫn cài đặt',
                'steps' => [
                    '1. Thêm kho lưu trữ NodeSource:',
                    '   curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -',
                    '2. Cài đặt Node.js:',
                    '   sudo yum install -y nodejs',
                    '3. Xác minh cài đặt:',
                    '   node -v && npm -v',
                    '4. Cài đặt miniprogram-ci:',
                    '   sudo npm install miniprogram-ci -g',
                ],
            ],
            // Ubuntu - Sử dụng trình quản lý gói apt
            'ubuntu' => [
                'title' => 'Ubuntu/Debian Hướng dẫn cài đặt',
                'steps' => [
                    '1. Thêm kho lưu trữ NodeSource:',
                    '   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -',
                    '2. Cài đặt Node.js:',
                    '   sudo apt-get install -y nodejs',
                    '3. Xác minh cài đặt:',
                    '   node -v && npm -v',
                    '4. Cài đặt miniprogram-ci:',
                    '   sudo npm install miniprogram-ci -g',
                ],
            ],
            // Debian - Tương tự như Ubuntu
            'debian' => [
                'title' => 'Ubuntu/Debian Hướng dẫn cài đặt',
                'steps' => [
                    '1. Thêm kho lưu trữ NodeSource:',
                    '   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -',
                    '2. Cài đặt Node.js:',
                    '   sudo apt-get install -y nodejs',
                    '3. Xác minh cài đặt:',
                    '   node -v && npm -v',
                    '4. Cài đặt miniprogram-ci:',
                    '   sudo npm install miniprogram-ci -g',
                ],
            ],
            // macOS - sử dụng Homebrew
            'macos' => [
                'title' => 'macOS Hướng dẫn cài đặt',
                'steps' => [
                    '1. Cài đặt Homebrew (Nếu không được cài đặt):',
                    '   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"',
                    '2. Cài đặt Node.js:',
                    '   brew install node@18',
                    '3. Xác minh cài đặt:',
                    '   node -v && npm -v',
                    '4. Cài đặt miniprogram-ci:',
                    '   npm install miniprogram-ci -g',
                ],
            ],
            // Windows - Tải xuống và cài đặt từ trang web chính thức
            'windows' => [
                'title' => 'Windows Hướng dẫn cài đặt',
                'steps' => [
                    '1. Tải xuống gói cài đặt Node.js:',
                    '   truy cập https://nodejs.org/zh-cn/download/',
                    '2. Chạy trình cài đặt và làm theo lời nhắc để hoàn tất cài đặt',
                    '3. Mở dấu nhắc lệnh và xác minh cài đặt:',
                    '   node -v && npm -v',
                    '4. Cài đặt miniprogram-ci:',
                    '   npm install miniprogram-ci -g',
                ],
            ],
        ];

        // Nhận hướng dẫn tương ứng dựa trên loại hệ điều hành, hướng dẫn chung cho các hệ thống chưa biết
        $type = $os['type'] ?: 'unknown';
        $guide = isset($guides[$type]) ? $guides[$type] : [
            'title' => 'Hướng dẫn cài đặt chung',
            'steps' => [
                '1. Truy cập trang web chính thức của Node.js để tải xuống gói cài đặt:',
                '   https://nodejs.org/zh-cn/download/',
                '2. Hoàn tất cài đặt theo tài liệu chính thức',
                '3. Xác minh cài đặt:',
                '   node -v && npm -v',
                '4. Cài đặt miniprogram-ci:',
                '   npm install miniprogram-ci -g',
            ],
        ];

        // Thêm tập lệnh cài đặt bằng một cú nhấp chuột URL
        $guide['script_url'] = $this->getInstallScriptUrl();

        return $guide;
    }
}
