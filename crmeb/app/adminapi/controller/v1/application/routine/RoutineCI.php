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
namespace app\adminapi\controller\v1\application\routine;

use app\adminapi\controller\AuthController;
use app\services\system\NodeEnvironmentServices;
use app\services\wechat\RoutineCIServices;
use think\facade\App;

/**
 * Bộ điều khiển tải lên tự động CI chương trình nhỏ
 *
 * Tổng quan về chức năng:
 * Bộ điều khiển này cung cấp giao diện API để tự động tải lên mã chương trình mini WeChat và được triển khai dựa trên công cụ miniprogram-ci chính thức của WeChat.
 * Thông qua các giao diện này, quản trị viên có thể tải trực tiếp mã chương trình nhỏ lên máy chủ WeChat ở chế độ nền mà không cần sử dụng các công cụ dành cho nhà phát triển WeChat.
 *
 * Chức năng chính:
 * 1. Phát hiện môi trường - phát hiện xem Node.js và miniprogram-ci có được cài đặt trên máy chủ hay không
 * 2. Hướng dẫn cài đặt - Cung cấp hướng dẫn cài đặt cho các hệ điều hành khác nhau.
 * 3. Quản lý cấu hình - Quản lý khóa tải lên chương trình mini và cấu hình AppId
 * 4. Tải mã lên - Tải mã chương trình mini lên phiên bản phát triển WeChat
 * 5. Chức năng xem trước - tạo mã QR xem trước chương trình nhỏ để thử nghiệm
 *
 * Điều kiện sử dụng:
 * - Máy chủ đã được cài đặt Node.js (>=14.0.0) và npm
 * - được cài đặt trên toàn cầu miniprogram-ci (npm install miniprogram-ci -g)
 * - Đã nhận được khóa tải lên mã chương trình nhỏ trên nền tảng công cộng WeChat
 * - Máy chủ PHP exec() Chức năng không bị vô hiệu hóa
 * 
 * @see https://developers.weixin.qq.com/miniprogram/dev/devtools/ci.html Tài liệu CI chính thức của WeChat
 * @package app\adminapi\controller\v1\application\routine
 */
class RoutineCI extends AuthController
{
    /**
     * Node.js Ví dụ về dịch vụ phát hiện môi trường
     *
     * Được sử dụng để phát hiện xem môi trường máy chủ có đáp ứng các yêu cầu để tải lên chương trình mini hay không:
     * - Node.js Phát hiện phiên bản
     * - kiểm tra tính khả dụng của npm
     * - phát hiện trạng thái cài đặt miniprogram-ci
     * - Nhận dạng loại hệ điều hành
     * 
     * @var NodeEnvironmentServices
     */
    protected $envServices;

    /**
     * Phiên bản dịch vụ lõi CI chương trình nhỏ
     *
     * Xử lý logic nghiệp vụ cốt lõi của việc tải lên mã chương trình nhỏ:
     * - Tải lên quản lý khóa
     * - Chuẩn bị tài liệu dự án
     * – Thực thi lệnh miniprogram-ci
     * - Tạo mã QR xem trước
     * 
     * @var RoutineCIServices
     */
    protected $ciServices;

    /**
     * Trình xây dựng - khởi tạo các phụ thuộc dịch vụ
     *
     * Tiêm các phiên bản lớp dịch vụ được yêu cầu thông qua việc chèn phụ thuộc,
     * Vùng chứa của ThinkPHP sẽ tự động phân giải và đưa vào các phần phụ thuộc này。
     * 
     * @param App $app ThinkPHP Ví dụ ứng dụng
     * @param NodeEnvironmentServices $envServices Dịch vụ kiểm tra môi trường
     * @param RoutineCIServices $ciServices CI Dịch vụ tải lên
     */
    public function __construct(App $app, NodeEnvironmentServices $envServices, RoutineCIServices $ciServices)
    {
        parent::__construct($app);
        $this->envServices = $envServices;
        $this->ciServices = $ciServices;
    }

    /**
     * Nhận trạng thái môi trường đang chạy của máy chủ
     *
     * Phát hiện và trả về tất cả thông tin môi trường cần thiết để tải lên chương trình mini và giao diện người dùng sẽ trả về kết quả dựa trên
     * Hiển thị trạng thái sẵn sàng của môi trường hoặc hướng dẫn người dùng hoàn tất cấu hình môi trường.
     *
     * Trả về cấu trúc dữ liệu:
     * - os: Thông tin hệ điều hành (family, type, version)
     * - node: Node.js tình trạng (installed, version, path, meets_requirement)
     * - npm: npm tình trạng (installed, version)
     * - miniprogram_ci: CItrạng thái công cụ (installed, version)
     * - ready: Giá trị Boolean, cho dù môi trường đã hoàn toàn sẵn sàng
     * - can_install: Có hỗ trợ cài đặt tự động hay không
     * - exec_enabled: execChức năng này có sẵn không?
     * - message: Tin nhắn nhắc nhở
     *
     * @return phản hồi JSON hỗn hợp, bao gồm thông tin trạng thái môi trường hoàn chỉnh
     */
    public function environment()
    {
        $data = $this->envServices->getEnvironmentStatus();
        return app('json')->success($data);
    }

    /**
     * Nhận hướng dẫn cài đặt môi trường
     *
     * Trả về các bước cài đặt Node.js và miniprogram-ci tương ứng tùy theo loại hệ điều hành máy chủ.
     * Hệ điều hành được hỗ trợ: CentOS/RHEL、Ubuntu/Debian、macOS、Windows
     * 
     * Trả về cấu trúc dữ liệu:
     * - title: Tiêu đề hướng dẫn (Chẳng hạn như "Hướng dẫn cài đặt CentOS/RHEL")
     * - steps: Mảng các bước cài đặt, chứa hướng dẫn dòng lệnh
     * - script_url: Địa chỉ URL của tập lệnh cài đặt bằng một cú nhấp chuột
     *
     * @return phản hồi JSON hỗn hợp, chứa hướng dẫn cài đặt phù hợp với hệ thống hiện tại
     */
    public function installGuide()
    {
        $guide = $this->envServices->getInstallGuide();
        return app('json')->success($guide);
    }

    /**
     * Nhận trạng thái cấu hình tải lên chương trình nhỏ
     *
     * Trả về thông tin cấu hình được tải lên hiện tại, được sử dụng cho giao diện người dùng để hiển thị trạng thái cấu hình và hướng dẫn quá trình cấu hình.
     *
     * Trả về cấu trúc dữ liệu:
     * - app_id: Chương trình nhỏ AppId
     * - app_id_configured: AppId Nó đã được cấu hình chưa?
     * - private_key_exists: Tệp khóa tải lên có tồn tại hay không
     * - private_key_path: Đường dẫn lưu trữ tập tin chính
     * - project_path: Lộ trình dự án chương trình nhỏ
     * - project_exists: Thư mục dự án có tồn tại không?
     *
     * @return phản hồi JSON hỗn hợp, bao gồm thông tin trạng thái cấu hình tải lên
     */
    public function uploadConfig()
    {
        $config = $this->ciServices->getUploadConfig();
        return app('json')->success($config);
    }

    /**
     * Lưu khóa tải lên mã chương trình mini
     *
     * Nhận và lưu khóa tải lên mã chương trình nhỏ được tải xuống từ nền tảng công cộng WeChat.
     * Key dùng để xác thực bằng công cụ miniprogram-ci, đảm bảo chỉ những người dùng được ủy quyền mới có thể tải mã lên.
     *
     * Yêu cầu thông số:
     * - key_content: string, Bắt buộc, nội dung khóa riêng RSA (Bắt đầu với -----BEGIN RSA PRIVATE KEY-----)
     * 
     * Phương pháp lấy chìa khóa:
     * Nền tảng công cộng WeChat -> quản lý phát triển -> Cài đặt phát triển -> Tải lên mã chương trình nhỏ -> Khóa tải xuống
     *
     * Hướng dẫn an toàn:
     * - Tệp khóa được lưu trong config/routine_private.key
     * - Quyền của tệp được đặt thành 0600, chỉ chủ sở hữu mới có thể đọc và ghi
     * - Không cam kết tệp chính vào hệ thống kiểm soát phiên bản
     *
     * @return phản hồi JSON hỗn hợp, thông tin nhắc nhở được trả về nếu thành công, lý do lỗi được trả về nếu thất bại
     */
    public function savePrivateKey()
    {
        // Nhận nội dung chính từ yêu cầu POST
        $keyContent = $this->request->post('key_content', '');

        // Nội dung khóa xác minh không được để trống
        if (empty($keyContent)) {
            return app('json')->fail('Vui lòng cung cấp nội dung chính');
        }

        // Gọi lớp dịch vụ để lưu khóa (lớp dịch vụ sẽ xác minh định dạng khóa）
        $this->ciServices->savePrivateKey($keyContent);
        return app('json')->success('Đã lưu khóa thành công');
    }

    /**
     * Tải mã chương trình mini lên phiên bản phát triển WeChat
     *
     * Tải mã dự án chương trình nhỏ cục bộ lên phiên bản phát triển của máy chủ WeChat.
     * Sau khi tải lên thành công, bạn có thể xem phiên bản phát triển mới được tải lên trong phần quản lý phiên bản của nền tảng công cộng WeChat.
     *
     * Yêu cầu thông số:
     * - version: string, Bắt buộc, số phiên bản, định dạng là x.x.x (giống 1.0.0)
     * - desc: string, Tùy chọn, mô tả phiên bản, mặc định là "phiên bản {version}"
     * - is_live: int, Tùy chọn, có bật chức năng phát sóng trực tiếp hay không，0=đóng cửa 1=Bật, tắt theo mặc định
     *
     *Quy trình thực hiện:
     * 1. Xác minh định dạng số phiên bản
     * 2. Kiểm tra môi trường hoạt động (Node.js、Các tập tin chính, v.v.)
     * 3. Chuẩn bị tài liệu dự án (Sao chép và thay thế cấu hình)
     * 4. Thực hiện lệnh tải lên miniprogram-ci
     * 5. Trả về kết quả upload
     *
     * Trả về cấu trúc dữ liệu:
     * - success: Nó có thành công không?
     * - version: số phiên bản
     * - desc: Mô tả phiên bản
     * - message: Tin nhắn nhắc nhở
     * - output: Đầu ra thực hiện lệnh
     *
     * @return phản hồi JSON hỗn hợp, bao gồm thông tin kết quả tải lên
     */
    public function upload()
    {
        // Nhận tham số yêu cầu theo lô
        [$version, $desc, $isLive] = $this->request->postMore([
            ['version', ''],     // số phiên bản
            ['desc', ''],        // Mô tả phiên bản
            ['is_live', 0],      // Có bật phát sóng trực tiếp hay không
        ], true);

        // Cần có số phiên bản xác minh
        if (empty($version)) {
            return app('json')->fail('Vui lòng nhập số phiên bản');
        }

        // Xác minh định dạng số phiên bản: phải ở định dạng x.x.x (giống 1.0.0, 2.1.3)
        if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            return app('json')->fail('Định dạng số phiên bản sai, vui lòng sử dụng định dạng x.x.x');
        }

        // Gọi lớp dịch vụ để thực hiện tải lên
        $result = $this->ciServices->upload($version, $desc, (bool)$isLive);
        return app('json')->success($result);
    }

    /**
     * Nhận mã QR xem trước chương trình mini
     *
     * Tạo mã QR để xem trước chương trình nhỏ. Sau khi quét mã, bạn có thể xem trước tác dụng của chương trình mini trên điện thoại di động của mình.
     * Phiên bản xem trước sẽ không ảnh hưởng đến phiên bản trực tuyến và phù hợp để phát triển và thử nghiệm.
     *
     * Yêu cầu thông số:
     * - page_path: string, Tùy chọn, đường dẫn trang để xem trước (giống pages/index/index)
     *              Khi trống, trang chủ sẽ được xem trước theo mặc định.
     *
     *Quy trình thực hiện:
     * 1. Kiểm tra môi trường hoạt động
     * 2. Chuẩn bị hồ sơ dự án
     * 3. Thực hiện lệnh xem trước miniprogram-ci
     * 4. Tạo hình ảnh mã QR
     * 5. Trả về URL hình ảnh mã QR
     *
     * Trả về cấu trúc dữ liệu:
     * - success: Nó có thành công không?
     * - qrcode_url: Truy cập vào hình ảnh mã QR URL
     * - message: Tin nhắn nhắc nhở
     * - output: Đầu ra thực hiện lệnh
     *
     *Ghi chú:
     * - Mã QR xem trước có thời hạn hiệu lực ngắn và cần được tạo lại sau khi hết hạn.
     * - Chỉ những nhà phát triển và trải nghiệm các chương trình mini mới có thể quét mã để xem trước
     *
     * @return phản hồi JSON hỗn hợp, bao gồm thông tin mã QR xem trước
     */
    public function preview()
    {
        // Nhận thông số đường dẫn trang xem trước
        $pagePath = $this->request->post('page_path', '');
        
        // Gọi lớp dịch vụ để tạo mã QR xem trước
        $result = $this->ciServices->preview($pagePath);
        return app('json')->success($result);
    }
}
