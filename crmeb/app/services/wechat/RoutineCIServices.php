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
namespace app\services\wechat;

use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FileService;
use think\facade\Log;

/**
 * Chương trình nhỏ CI (Continuous Integration) Lớp dịch vụ cốt lõi
 *
 * Tổng quan về chức năng:
 * Lớp dịch vụ này gói gọn logic gọi của công cụ miniprogram-ci chính thức của WeChat.
 * Triển khai chức năng tải lên và xem trước tự động của mã chương trình mini.
 *
 * Chức năng chính:
 * 1. Quản lý khóa tải lên - lưu/xóa khóa tải lên mã chương trình nhỏ
 * 2. Chuẩn bị dự án - sao chép mã nguồn và thay thế AppId, URL và các cấu hình khác
 * 3. Code upload - gọi lệnh miniprogram-ci upload để upload code
 * 4. Xem trước mã QR - gọi lệnh xem trước miniprogram-ci để tạo mã xem trước
 *
 * Phụ thuộc vào công cụ:
 * - Node.js >= 14.0.0
 * - npm (Node.js Trình quản lý gói)
 * - miniprogram-ci (Cài đặt toàn cầu: npm install miniprogram-ci -g)
 * 
 * Mua lại chìa khóa:
 * Nền tảng công cộng WeChat -> quản lý phát triển -> Cài đặt phát triển -> Tải lên mã chương trình nhỏ
 * 
 * @see https://developers.weixin.qq.com/miniprogram/dev/devtools/ci.html
 * @package app\services\wechat
 */
class RoutineCIServices extends BaseServices
{
    /**
     * Đường dẫn lưu trữ tệp dự án chương trình nhỏ
     *
     * Trước khi tải lên, mã nguồn sẽ được sao chép vào thư mục này và cấu hình sẽ được thay thế trước khi tải lên.
     * Đường dẫn mặc định: public/statics/download
     * 
     * @var string
     */
    protected $projectPath;

    /**
     * Đường dẫn lưu trữ tệp chính tải lên mã chương trình nhỏ
     *
     * Tệp khóa riêng RSA để xác thực bằng miniprogram-ci.
     * Đường dẫn mặc định: config/routine_private.key
     * 
     * Biện pháp phòng ngừa an toàn: Tệp này chứa thông tin nhạy cảm và phải đặt quyền truy cập tệp thích hợp.
     * và loại trừ nó trong .gitignore để tránh phạm vào hệ thống kiểm soát phiên bản。
     * 
     * @var string
     */
    protected $privateKeyPath;

    /**
     * AppId chương trình nhỏ
     *
     * Đọc từ cấu hình hệ thống, mục cấu hình là Routine_appId.
     * Dùng để xác định applet đích, phải phù hợp với applet tương ứng với key khi upload。
     * 
     * @var string
     */
    protected $appId;

    /**
     * Trình xây dựng - khởi tạo đường dẫn cấu hình
     *
     * Khởi tạo các cấu hình đường dẫn khác nhau cần thiết để tải lên các chương trình nhỏ:
     * - Đường dẫn lưu trữ file dự án
     * - Đường dẫn lưu trữ file key
     * - đọc từ cấu hình hệ thống AppId
     */
    public function __construct()
    {
        // Đặt thư mục lưu trữ tệp dự án (public/statics/download)
        $this->projectPath = public_path() . 'statics' . DIRECTORY_SEPARATOR . 'download';
        // Đặt đường dẫn lưu trữ tệp chính (config/routine_private.key)
        $this->privateKeyPath = app()->getRootPath() . 'config' . DIRECTORY_SEPARATOR . 'routine_private.key';
        // Đọc applet từ cấu hình hệ thống AppId
        $this->appId = sys_config('routine_appId', '');
    }

    /**
     * Nhận thông tin trạng thái cấu hình tải lên
     *
     * Trả về tất cả trạng thái cấu hình liên quan đến tải lên chương trình mini hiện tại,
     * Giao diện người dùng hiển thị trạng thái cấu hình dựa trên thông tin này và hướng dẫn người dùng hoàn tất cấu hình.
     *
     * @return thông tin trạng thái cấu hình mảng, bao gồm:
     *               - app_id: Chương trình nhỏ AppId
     *               - app_id_configured: AppId Nó đã được cấu hình chưa?
     *               - private_key_exists: Tệp khóa có tồn tại không?
     *               - private_key_path: Đường dẫn tệp chính
     *               - project_path: Đường dẫn tệp dự án
     *               - project_exists: Thư mục dự án có tồn tại không?
     */
    public function getUploadConfig(): array
    {
        return [
            'app_id' => $this->appId,                           // Chương trình nhỏ AppId
            'app_id_configured' => !empty($this->appId),        // AppId Nó đã được cấu hình chưa?
            'private_key_exists' => file_exists($this->privateKeyPath), // Tệp khóa có tồn tại không?
            'private_key_path' => $this->privateKeyPath,        // Đường dẫn đầy đủ của tệp chính
            'project_path' => $this->projectPath,               // Đường dẫn lưu trữ file dự án
            'project_exists' => is_dir($this->projectPath),     // Thư mục dự án có tồn tại không?
        ];
    }

    /**
     * Lưu khóa tải lên mã chương trình mini
     *
     * Lưu nội dung chính được tải xuống từ nền tảng công cộng WeChat vào máy chủ.
     * Khóa được sử dụng để xác thực bằng công cụ miniprogram-ci.
     *
     *Quy trình xử lý:
     * 1. Xác minh định dạng khóa (Phải bắt đầu bằng -----BEGIN RSA PRIVATE KEY-----)
     * 2. Đảm bảo thư mục lưu trữ khóa tồn tại
     * 3. Ghi nội dung chính vào file
     * 4. Đặt quyền truy cập tệp thành 0600 (Chỉ chủ sở hữu mới có thể đọc và viết)
     * 
     * @param string $keyContent Nội dung chính (RSA Định dạng PEM khóa riêng)
     * @return bool Trả về true nếu lưu thành công
     * @throws AdminException được ném ra khi định dạng khóa không chính xác hoặc không lưu được
     */
    public function savePrivateKey(string $keyContent): bool
    {
        // Xác minh định dạng khóa: Phải ở định dạng PEM khóa riêng RSA
        if (strpos($keyContent, '-----BEGIN RSA PRIVATE KEY-----') === false) {
            throw new AdminException('Định dạng khóa không hợp lệ, vui lòng tải lên mã chương trình mini chính xác để tải khóa lên');
        }

        // Đảm bảo thư mục lưu trữ khóa tồn tại
        $keyDir = dirname($this->privateKeyPath);
        if (!is_dir($keyDir)) {
            mkdir($keyDir, 0755, true);
        }

        // Viết nội dung chính vào tập tin
        $result = file_put_contents($this->privateKeyPath, $keyContent);
        if ($result === false) {
            throw new AdminException('Lưu khóa không thành công, vui lòng kiểm tra quyền thư mục');
        }

        // Đặt quyền truy cập tệp thành 0600 (Chỉ chủ sở hữu mới có thể đọc và viết)，Cải thiện an ninh
        chmod($this->privateKeyPath, 0600);

        return true;
    }

    /**
     * Xóa khóa tải lên mã chương trình mini
     *
     * Xóa tệp khóa đã lưu khỏi máy chủ.
     * Nếu tệp khóa không tồn tại, thành công sẽ được trả về trực tiếp.
     *
     * @return bool Trả về khi xóa thành công hoặc file không tồn tại true
     */
    public function deletePrivateKey(): bool
    {
        if (file_exists($this->privateKeyPath)) {
            return unlink($this->privateKeyPath);
        }
        return true;
    }

    /**
     * Chuẩn bị hồ sơ dự án chương trình nhỏ
     *
     * Chuẩn bị dự án trước khi upload, bao gồm copy mã nguồn và thay thế cấu hình:
     * 1. Dọn dẹp các tập tin dự án cũ (nếu tồn tại)
     * 2. Sao chép mã nguồn applet từ thư mục mp_view vào thư mục tải xuống
     * 3. Thay thế appid và projectname trong project.config.json
     * 4. Xác định xem có nên xóa cấu hình plug-in phát sóng trực tiếp hay không dựa trên việc tính năng phát sóng trực tiếp có được bật hay không.
     * 5. Thay thế tên miền API trong mã bằng tên miền máy chủ hiện tại
     * 
     * @param bool $isLive Có bật chức năng phát sóng trực tiếp hay không, theo mặc định nó sẽ bị đóng
     * Cấu hình plug-in phát sóng trực tiếp trong app.json sẽ bị xóa khi đóng
     * @return string Đường dẫn dự án sau khi chuẩn bị xong
     * @throws AdminException Ném khi AppId không được định cấu hình hoặc xảy ra lỗi trong quá trình chuẩn bị
     */
    public function prepareProject(bool $isLive = false): string
    {
        // Kiểm tra xem AppId đã được định cấu hình chưa
        if (empty($this->appId)) {
            throw new AdminException('Hãy cấu hình chương trình mini trước AppId');
        }

        try {
            // bước chân1: Dọn dẹp các tập tin dự án cũ
            if (is_dir($this->projectPath)) {
                $this->deleteDirectory($this->projectPath);
            }

            // bước chân2: Sao chép mã nguồn chương trình mini vào thư mục đích
            // thư mục nguồn: public/statics/mp_view (Tổng hợp mã nguồn của chương trình mini)
            /** @var FileService $fileService */
            $fileService = app(FileService::class);
            $fileService->copyDir(public_path() . 'statics/mp_view', $this->projectPath);

            // bước chân3: Thay thế tên appid và dự án trong project.config.json
            $this->updateConfigJson($this->appId, sys_config('routine_name', ''));

            // bước chân4: Nếu tính năng phát sóng trực tiếp không được bật, hãy xóa cấu hình plugin phát sóng trực tiếp trong app.json
            if (!$isLive) {
                $this->updateAppJson();
            }

            // bước chân5: Thay thế tên miền API trong mã bằng tên miền máy chủ hiện tại
            $this->updateUrl('https://' . $_SERVER['HTTP_HOST']);

            return $this->projectPath;
        } catch (\Throwable $e) {
            throw new AdminException('Dự án chuẩn bị thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Tải mã chương trình mini lên phiên bản phát triển WeChat
     *
     * Gọi lệnh tải lên miniprogram-ci để tải mã chương trình mini lên máy chủ WeChat.
     * Sau khi tải lên thành công, bạn có thể xem phiên bản mới trong phần quản lý phiên bản của nền tảng công cộng WeChat.
     *
     *Quy trình thực hiện:
     * 1. Kiểm tra môi trường hoạt động (Node.js、tập tin quan trọng、miniprogram-ci)
     * 2. Chuẩn bị tài liệu dự án
     * 3. Build và thực thi lệnh upload
     * 4. Ghi lại nhật ký đầu ra lệnh
     * 5. Trả về kết quả upload
     * 
     * @param string $version Số phiên bản, ở định dạng x.x.x (giống 1.0.0)
     * @param string $desc Mô tả phiên bản, mặc định là "phiên bản {version}"
     * @param bool $isLive Việc bật chức năng phát sóng trực tiếp có ảnh hưởng đến việc chuẩn bị dự án hay không
     * @return kết quả tải lên mảng, bao gồm: success, version, desc, message, output
     * @throws AdminException Ngoại lệ được đưa ra khi kiểm tra môi trường không thành công hoặc tải lên không thành công
     */
    public function upload(string $version, string $desc = '', bool $isLive = false): array
    {
        // Kiểm tra xem môi trường hoạt động có đáp ứng yêu cầu không
        $this->checkEnvironment();

        // Chuẩn bị tài liệu dự án (Sao chép và thay thế cấu hình)
        $projectPath = $this->prepareProject($isLive);

        // Xây dựng lệnh tải lên miniprogram-ci
        $command = $this->buildUploadCommand($version, $desc);

        // Ghi nhật ký lệnh
        Log::info('miniprogram-ci upload command: ' . $command);

        // thực hiện lệnh
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        $outputStr = implode("\n", $output);
        Log::info('miniprogram-ci upload output: ' . $outputStr);

        // Kiểm tra kết quả thực hiện. Mã trả về khác 0 cho biết lỗi.
        if ($returnCode !== 0) {
            throw new AdminException('Tải lên không thành công: ' . $outputStr);
        }

        return [
            'success' => true,
            'version' => $version,
            'desc' => $desc,
            'message' => 'Tải lên thành công',
            'output' => $outputStr,
        ];
    }

    /**
     * Tạo mã QR xem trước chương trình nhỏ
     *
     * Gọi lệnh xem trước miniprogram-ci để tạo mã QR xem trước.
     *Quét mã QR để xem trước tác dụng của chương trình mini trên điện thoại di động của bạn.
     *
     *Quy trình thực hiện:
     * 1. Kiểm tra môi trường hoạt động
     * 2. Chuẩn bị hồ sơ dự án
     * 3. Build và thực thi lệnh xem trước
     * 4. Tạo hình ảnh mã QR và lưu nó
     * 5. Trả lại hình ảnh mã QR URL
     * 
     * @param string $pagePath Đường dẫn trang xem trước (giống pages/index/index)
     *                         Khi trống, trang chủ của chương trình mini sẽ được xem trước theo mặc định.
     * @return kết quả xem trước mảng, bao gồm: success, qrcode_url, message, output
     * @throws AdminException Ngoại lệ được đưa ra khi kiểm tra môi trường không thành công hoặc xem trước không thành công
     */
    public function preview(string $pagePath = ''): array
    {
        // Kiểm tra xem môi trường hoạt động có đáp ứng yêu cầu không
        $this->checkEnvironment();

        // Chuẩn bị tài liệu dự án
        $projectPath = $this->prepareProject();

        // Đặt đường dẫn lưu cho hình ảnh mã QR
        $qrcodePath = public_path() . 'statics' . DIRECTORY_SEPARATOR . 'routine_preview.jpg';

        // Xây dựng lệnh xem trước miniprogram-ci
        $command = $this->buildPreviewCommand($qrcodePath, $pagePath);

        // Ghi nhật ký lệnh
        Log::info('miniprogram-ci preview command: ' . $command);

        // thực hiện lệnh
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        $outputStr = implode("\n", $output);
        Log::info('miniprogram-ci preview output: ' . $outputStr);

        // Kiểm tra kết quả thực hiện
        if ($returnCode !== 0) {
            throw new AdminException('Xem trước không thành công: ' . $outputStr);
        }

        // Ghép URL truy cập của hình ảnh mã QR và thêm dấu thời gian để ngăn bộ nhớ đệm
        $qrcodeUrl = sys_config('site_url') . '/statics/routine_preview.jpg?t=' . time();

        return [
            'success' => true,
            'qrcode_url' => $qrcodeUrl,
            'message' => 'Xem trước mã QR được tạo thành công',
            'output' => $outputStr,
        ];
    }

    /**
     * Xây dựng lệnh tải lên miniprogram-ci
     *
     * Xây dựng chuỗi dòng lệnh để tải lên mã applet.
     *
     *Mô tả tham số lệnh:
     * - --pp: Đường dẫn dự án (project path)
     * - --pkp: Đường dẫn tệp chính (private key path)
     * - --appid: Chương trình nhỏ AppId
     * - --uv: Tải lên số phiên bản (upload version)
     * - -r: Số robot đã tải lên, mặc định 1
     * - --desc: Mô tả phiên bản
     * 
     * @param string $version số phiên bản
     * @param string $desc Mô tả phiên bản, mặc định là "phiên bản {version}"
     * @return string Hoàn thành chuỗi dòng lệnh
     */
    protected function buildUploadCommand(string $version, string $desc = ''): string
    {
        // Mô tả phiên bản mặc định
        $desc = $desc ?: 'Phiên bản ' . $version;

        // Xây dựng lệnh tải lên miniprogram-ci
        $command = sprintf(
            'miniprogram-ci upload --pp "%s" --pkp "%s" --appid "%s" --uv "%s" -r 1 --desc "%s"',
            $this->projectPath,    // Đường dẫn dự án
            $this->privateKeyPath, // Đường dẫn tệp chính
            $this->appId,          // Chương trình nhỏ AppId
            $version,              // số phiên bản
            addslashes($desc)      // Mô tả phiên bản (Thoát khỏi ký tự đặc biệt)
        );

        return $command;
    }

    /**
     * Xây dựng lệnh xem trước miniprogram-ci
     *
     * Xây dựng chuỗi dòng lệnh để tạo mã QR xem trước.
     *
     *Mô tả tham số lệnh:
     * - --pp: Đường dẫn dự án
     * - --pkp: Đường dẫn tệp chính
     * - --appid: Chương trình nhỏ AppId
     * - --qrcode-format: Định dạng đầu ra mã QR (image)
     * - --qrcode-output-dest: Đường dẫn đầu ra mã QR
     * - --compile-condition: Điều kiện biên dịch, được sử dụng để chỉ định trang xem trước
     * 
     * @param string $qrcodePath Đường dẫn lưu ảnh mã QR
     * @param string $pagePath Đường dẫn trang xem trước (Không bắt buộc)
     * @return string Hoàn thành chuỗi dòng lệnh
     */
    protected function buildPreviewCommand(string $qrcodePath, string $pagePath = ''): string
    {
        // Xây dựng các lệnh xem trước cơ bản
        $command = sprintf(
            'miniprogram-ci preview --pp "%s" --pkp "%s" --appid "%s" --qrcode-format image --qrcode-output-dest "%s"',
            $this->projectPath,    // Đường dẫn dự án
            $this->privateKeyPath, // Đường dẫn tệp chính
            $this->appId,          // Chương trình nhỏ AppId
            $qrcodePath            // Đường dẫn đầu ra mã QR
        );

        // Nếu trang xem trước được chỉ định, hãy thêm tham số điều kiện biên dịch
        if ($pagePath) {
            $command .= sprintf(' --compile-condition \'{"pathName":"%s"}\'', addslashes($pagePath));
        }

        return $command;
    }

    /**
     * Kiểm tra xem môi trường hoạt động có đáp ứng yêu cầu không
     *
     * Kiểm tra các điều kiện môi trường cần thiết trước khi thực hiện tải lên hoặc xem trước:
     * 1. Chương trình nhỏ AppId được định cấu hình
     * 2. Tệp khóa được tải lên đã tồn tại
     * 3. Công cụ miniprogram-ci đã được cài đặt trên toàn cầu
     *
     * @throws AdminException ném ngoại lệ khi không đáp ứng bất kỳ điều kiện nào
     */
    protected function checkEnvironment(): void
    {
        // nghiên cứu1: AppId Nó đã được cấu hình chưa?
        if (empty($this->appId)) {
            throw new AdminException('Hãy cấu hình chương trình mini trước AppId');
        }

        // nghiên cứu2: Tệp khóa có tồn tại không?
        if (!file_exists($this->privateKeyPath)) {
            throw new AdminException('Vui lòng tải lên mã chương trình mini để tải khóa trước');
        }

        // nghiên cứu3: miniprogram-ci Nó đã được cài đặt trên toàn cầu chưa?
        $output = [];
        exec('which miniprogram-ci 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new AdminException('miniprogram-ci Chưa cài đặt, vui lòng cài đặt môi trường hoạt động trước');
        }
    }

    /**
     * Thay thế tên miền API trong mã dự án
     *
     * Thay đổi tên miền API mặc định trong mã chương trình mini (https://demo.crmeb.com)
     * Thay thế nó bằng tên miền của máy chủ hiện tại để đảm bảo rằng chương trình mini có thể gọi chính xác giao diện phụ trợ.。
     * 
     * @param string $url Tên miền mới sẽ được thay thế bằng (giống https://your-domain.com)
     */
    protected function updateUrl(string $url): void
    {
        // Xây dựng đường dẫn tệp của nhà cung cấp.js (Chứa cấu hình tên miền API)
        $fileUrl = $this->projectPath . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'vendor.js';
        if (!file_exists($fileUrl)) {
            return;
        }

        // Đọc nội dung tập tin
        $string = file_get_contents($fileUrl);
        // Thay thế tên miền mặc định bằng tên miền máy chủ hiện tại
        $string = str_replace('https://demo.crmeb.com', $url, $string);
        // viết lại tập tin
        file_put_contents($fileUrl, $string);
    }

    /**
     * Cập nhật cấu hình app.json - xóa plugin phát sóng trực tiếp
     *
     * Khi không cần chức năng phát sóng trực tiếp, hãy xóa cấu hình plugin trình phát trực tiếp trong app.json.
     * Điều này có thể tránh tạo ra các phần phụ thuộc không cần thiết khi không sử dụng tính năng phát trực tiếp。
     */
    protected function updateAppJson(): void
    {
        // app.json đường dẫn tập tin
        $fileUrl = $this->projectPath . DIRECTORY_SEPARATOR . 'app.json';
        if (!file_exists($fileUrl)) {
            return;
        }

        $string = file_get_contents($fileUrl);
        // Xóa cấu hình trình cắm plugin trình phát trực tiếp bằng cách sử dụng biểu thức thông thường
        // Định dạng khớp: , "plugins": { "live-player-plugin": { ... } }
        $pattern = '/,\s*"plugins"\s*:\s*\{\s*"live-player-plugin"\s*:\s*\{[^}]*\}\s*\}/s';
        $string = preg_replace($pattern, '', $string);
        file_put_contents($fileUrl, $string);
    }

    /**
     * Cập nhật cấu hình project.config.json
     *
     * Thay thế appid và projectname trong tệp cấu hình dự án,
     * Đảm bảo chương trình mini đã tải lên sử dụng đúng danh tính。
     * 
     * @param string $appId Chương trình nhỏ AppId
     * @param string $projectName Tên dự án (Không bắt buộc)
     */
    protected function updateConfigJson(string $appId, string $projectName = ''): void
    {
        // project.config.json đường dẫn tập tin
        $fileUrl = $this->projectPath . DIRECTORY_SEPARATOR . 'project.config.json';
        if (!file_exists($fileUrl)) {
            return;
        }

        $string = file_get_contents($fileUrl);

        // thay thế appid
        $appIdPattern = '/"appid"\s*:\s*"[^"]*"/';
        $string = preg_replace($appIdPattern, '"appid": "' . $appId . '"', $string);

        // Thay thế tên dự án (nếu được cung cấp)
        if ($projectName) {
            $namePattern = '/"projectname"\s*:\s*"[^"]*"/';
            $string = preg_replace($namePattern, '"projectname": "' . $projectName . '"', $string);
        }

        file_put_contents($fileUrl, $string);
    }

    /**
     * Đệ quy xóa một thư mục và tất cả nội dung của nó
     *
     * Dùng để dọn dẹp các tập tin dự án cũ trước khi chuẩn bị một dự án mới.
     * Sẽ xóa đệ quy tất cả các tệp và thư mục con trong thư mục đã chỉ định。
     * 
     * @param string $dir Đường dẫn thư mục cần xóa
     * @return bool Xóa trả lại thành công true
     */
    protected function deleteDirectory(string $dir): bool
    {
        // Nếu thư mục không tồn tại, thành công sẽ được trả về trực tiếp.
        if (!is_dir($dir)) {
            return true;
        }

        // Duyệt qua tất cả các tập tin và thư mục con trong một thư mục (loại trừ . Và ..)
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            // Đệ quy xóa các thư mục con và xóa các tập tin trực tiếp
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        // Xóa các thư mục trống
        return rmdir($dir);
    }

    /**
     * Nhận lịch sử tải lên (Để được hiện thực hóa)
     * 
     * Phương pháp này được sử dụng để trả về bản ghi tải lên lịch sử của mã chương trình mini.
     *Bao gồm số phiên bản, thời gian tải lên, người tải lên và các thông tin khác.
     *
     * Mảng @return Mảng lịch sử tải lên
     */
    public function getUploadHistory(): array
    {
        // TODO: Triển khai chức năng lịch sử tải lên
        // Bạn có thể lưu các bản ghi tải lên cơ sở dữ liệu, bao gồm:
        // - Số phiên bản, mô tả phiên bản
        // - Thời gian tải lên, người tải lên
        // - Tải kết quả lên (thành công/thất bại)
        // - Nhật ký đầu ra lệnh
        return [];
    }
}
