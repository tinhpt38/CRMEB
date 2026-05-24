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
namespace app\adminapi\controller\v1\diy;

use app\adminapi\controller\AuthController;
use app\jobs\ThemeExportJob;
use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\article\ArticleServices;
use app\services\diy\ThemeDownloadServices;
use app\services\diy\ThemeServices;
use app\services\product\product\StoreProductServices;
use SplFileInfo;
use think\facade\App;

/**
 * Bộ điều khiển quản lý chủ đề
 * Các chức năng xử lý như danh sách, chi tiết, lưu, nhập, xuất chủ đề
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2025/12/18
 */class Theme extends AuthController
{

    /**
     * @var ThemeServices Lớp dịch vụ chủ đề
     */    protected $services;

    /**
     * hàm tạo
     * Tiêm dịch vụ ThemeServices
     * @param App $app Phiên bản vùng chứa Ứng dụng
     * @param ThemeServices $services Ví dụ dịch vụ chủ đề
     */    public function __construct(App $app, ThemeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lấy danh sách chủ đề
     * Hỗ trợ lọc dựa trên tiêu đề, loại và trạng thái
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */    public function getThemeList()
    {
        // Nhận tham số yêu cầu và đặt giá trị mặc định
        $where = $this->request->getMore([
            ['title', ''],
            ['type', ''],
            ['page_type', ''],
            ['is_del', 0],
        ]);
        // Gọi lớp dịch vụ để lấy dữ liệu danh sách
        $data = $this->services->getThemeList($where);
        return app('json')->success($data);
    }

    /**
     * Nhận chi tiết chủ đề
     * @param int $id chủ đềID
     * @param string $type Loại truy vấn (tùy chọn）
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */    public function getThemeInfo($id, $type = '')
    {
        $data = $this->services->getThemeInfo($id, $type);
        return app('json')->success($data);
    }

    /**
     * Lưu thông tin chủ đề cơ bản
     * @param int $id chủ đềID
     * @return \think\Response JSONphản hồi được định dạng
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */    public function saveTheme($id)
    {
        $data = $this->request->getMore([
            ['tid', 0],
            ['title', ''],
            ['type', ''],
            ['value', ''],
            ['page_type', 'theme'],
        ]);
        $id = $this->services->saveTheme($id, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Lưu thông tin tiêu đề chủ đề
     * @param int $id chủ đềID
     * @return \think\Response JSONphản hồi được định dạng
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */    public function saveThemeTitle($id)
    {
        $data = $this->request->getMore([
            ['tid', 0],
            ['title', ''],
            ['info', ''],
            ['page_type', 'theme'],
        ]);
        $id = $this->services->saveThemeTitle($id, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Lưu thông tin hình ảnh chủ đề
     * @param int $id chủ đềID
     * @return \think\Response JSONphản hồi được định dạng
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */    public function saveThemeImage($id)
    {
        $data = $this->request->getMore([
            ['image', ''],
            ['type', ''],
        ]);
        $id = $this->services->saveThemeImage($id, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Nhận danh sách bài viết thành phần tùy chỉnh
     * Nguồn dữ liệu cho thành phần bài viết lựa chọn trang DIY
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/12
     */    public function getThemeArticleList()
    {
        $where = $this->request->getMore([
            ['ids', ''],
            ['cid', ''],
            ['order', 0],
            ['sort', 0],
            ['limit', 10],
        ]);
        $data = app()->make(ArticleServices::class)->getThemeArticle($where);
        return app('json')->success($data);
    }

    /**
     * Nhận danh sách phiếu giảm giá thành phần tùy chỉnh
     * Nguồn dữ liệu cho thành phần phiếu giảm giá lựa chọn trang DIY
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/13
     */    public function getThemeCouponList()
    {
        $where = $this->request->getMore([
            ['ids', ''],
            ['type', ''],
            ['user_type', ''],
            ['send_type', ''],
            ['is_min_price', 0],
            ['min_price', 0],
            ['start_time', ''],
            ['end_time', ''],
            ['order', 0],
            ['sort', 0],
            ['limit', 10],
        ]);
        $data = app()->make(StoreCouponIssueServices::class)->getThemeCoupon($where);
        return app('json')->success($data);
    }

    /**
     * Nhận danh sách sản phẩm thành phần tùy chỉnh
     * Nguồn dữ liệu để chọn thành phần sản phẩm trên trang DIY
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/13
     */    public function getThemeProductList()
    {
        $where = $this->request->getMore([
            ['ids', ''],
            ['cate_ids', ''],
            ['order', 0],
            ['sort', 0],
            ['limit', 10],
        ]);
        $data = app()->make(StoreProductServices::class)->getThemeProduct($where);
        return app('json')->success($data);
    }

    /**
     * Xuất dữ liệu chủ đề
     * 1. Viết bản ghi đang chờ xử lý trong eb_theme_download (không bao gồm download_url)
     * 2. Đẩy tác vụ đóng gói thực tế vào hàng đợi để thực thi không đồng bộ
     * 3. Chèn lấp sau khi hàng đợi hoàn thành download_url
     *
     * @param int $id chủ đềID
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/3/10
     */    public function exportTheme($id)
    {
        // Xác định xem bộ đệm Redis có được sử dụng hay không và hàng đợi tin nhắn có được bật hay không
        $queueEnabled = sys_config('queue_open', 0) == 1 && \think\facade\Env::get('cache.driver', 'file') == 'redis';
        if (!$queueEnabled) {
            return app('json')->fail('Chức năng xuất cần bật bộ nhớ đệm Redis và hàng đợi tin nhắn. Vui lòng vào phần Cài đặt hệ thống để kích hoạt cấu hình tương ứng trước.');
        }

        $id = (int)$id;

        // 1. Tìm kiếm thông tin cơ bản của chủ đề và lấy tiêu đề
        $info = $this->services->getThemeInfo($id);

        // 2. Tạo thư mục đóng gói chủ đề
        $dir = public_path() . 'theme/download/' . $id . '/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // 3. Làm sạch thư mục đóng gói để tránh ô nhiễm dữ liệu
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                @rmdir($fileInfo->getRealPath());
            } else {
                @unlink($fileInfo->getRealPath());
            }
        }

        // 4. Tạo thư mục hình ảnh chủ đề
        $imagesDir = $dir . 'images/';
        if (!is_dir($imagesDir)) mkdir($imagesDir, 0755, true);

        // 5. Viết các bản ghi đang chờ xử lý vào eb_theme_download (download_url chưa được điền）
        /** @var ThemeDownloadServices $themeDownloadServices */        $themeDownloadServices = app()->make(ThemeDownloadServices::class);
        $recordId = $themeDownloadServices->addDownloadRecord($id, $info['title'], '');

        // 6. Đẩy nhiệm vụ đóng gói vào hàng đợi
        ThemeExportJob::dispatch('export', [$info, $recordId]);

        return app('json')->success('Đang xuất, vui lòng không Thao tác trang！', ['record_id' => $recordId]);
    }

    /**
     * Bản ghi xuất chủ đề truy vấn
     * Giao diện Khách hàng thăm dò giao diện và khi download_url không trống, hàng đợi đã hoàn tất.
     *
     * @param int $record_id Lịch sử tải xuốngID
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/3/10
     */    public function getExportRecord($record_id)
    {
        /** @var ThemeDownloadServices $themeDownloadServices */        $themeDownloadServices = app()->make(ThemeDownloadServices::class);
        $record = $themeDownloadServices->getDownloadInfo((int)$record_id);
        return app('json')->success([
            'download_url' => $record['download_url'] ?? '',
        ]);
    }

    /**
     * Nhập chủ đề
     * Tải lên gói Zip, giải nén và khôi phục cấu hình chủ đề
     * 1. Giải nén gói Zip vào theme/import/
     * 2. Đọc config.json
     * 3. Trích xuất hình ảnh trong gói và di chuyển chúng vào thư mục uploads/theme/
     * 4. Duyệt cấu hình đệ quy, sửa đường dẫn ảnh và thay thế tên miền
     *
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/15
     */    public function importTheme()
    {
        // 1 Nhận tập tin
        [$importUrl] = $this->request->postMore([
            ['url', ''],
        ], true);
        $realPath = public_path() . $importUrl;
        if (!file_exists($realPath)) return app('json')->fail('Tập tin không tồn tại');

        // 2. Giải nén file vào thư mục theme/import/
        $dir = 'theme/import/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        // Làm sạch thư mục nhập để tránh ô nhiễm dữ liệu
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        /** @var SplFileInfo $fileInfo */        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                @rmdir($fileInfo->getRealPath());
            } else {
                @unlink($fileInfo->getRealPath());
            }
        }
        $zip = new \ZipArchive();
        $zip->open($realPath);
        $zip->extractTo($dir);
        $zip->close();

        // 3. Đọc tệp config.json đã giải nén
        $configPath = $dir . 'config.json';
        if (!file_exists($configPath)) return app('json')->fail('Tập tin không tồn tại');
        $config = json_decode(file_get_contents($configPath), true);
        if (!is_array($config)) return app('json')->fail('Lỗi Nội dung tập tin');

        // 4. Xử lý di chuyển tài nguyên hình ảnh
        // Di chuyển Tất cả hình ảnh trong gói nén sang uploads/theme/{Dấu thời gian}/ dưới thư mục
        $timestamp = date('YmdHis');
        $themeDir = 'uploads/theme/' . $timestamp . '/';
        if (!is_dir($themeDir)) mkdir($themeDir, 0755, true);

        $rootPath = realpath($dir);
        $imageMap = []; // Ghi lại ánh xạ các đường dẫn tương đối tới các đường dẫn tải lên mới

        // Quét đệ quy Tất cả ảnh trong thư mục giải nén
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) continue;
            $filePath = $fileInfo->getRealPath();
            // Bỏ qua tập tin cấu hình
            if (basename($filePath) === 'config.json') continue;
            // Chỉ xử lý hình ảnh có tiện ích mở rộng được chỉ định
            if (!preg_match('/\.(png|jpe?g|gif|webp|svg)$/i', $filePath)) continue;

            // Lấy đường dẫn tương đối của file tương ứng với thư mục gốc giải nén
            $relative = ltrim(str_replace($rootPath, '', $filePath), DIRECTORY_SEPARATOR);
            $basename = basename($filePath);

            // đường dẫn đích
            $target = $themeDir . $basename;
            // Di chuyển/sao chép tập tin
            if (@copy($filePath, $target)) {
                $imageMap[$relative] = $target; // Bản ghi ánh xạ: đường dẫn tương đối trong gói => Đường dẫn hệ thống mới
            }
        }

        // 5. Chuẩn bị cơ sở cho việc thay thế tên miền URL
        $siteUrl = rtrim(sys_config('site_url'), '/');
        $siteParts = parse_url($siteUrl);
        $base = '';
        if ($siteParts && isset($siteParts['host'])) {
            $scheme = $siteParts['scheme'] ?? 'http';
            $base = $scheme . '://' . $siteParts['host'];
            if (isset($siteParts['port'])) {
                $base .= ':' . $siteParts['port'];
            }
        }

        $rewriteArray = function (&$data) use (&$rewriteArray, $imageMap, $base) {
            if (!is_array($data)) return;

            foreach ($data as $k => &$v) {
                if (is_array($v)) {
                    $rewriteArray($v);
                } elseif (is_string($v)) {
                    $decoded = json_decode($v, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $rewriteArray($decoded);
                        $v = json_encode($decoded, JSON_UNESCAPED_UNICODE);
                        continue;
                    }

                    // Kiểm tra xem nó có trong bảng ánh xạ hình ảnh hay không (xử lý hình ảnh được nhập cục bộ)
                    // Nếu nó nằm trong bảng ánh xạ thì nghĩa là image đã được giải nén từ gói nén và upload lên thư mục uploads/theme/
                    // Lúc này, bạn cần thay thế đường dẫn của nó bằng đường dẫn đầy đủ bằng tên miền trang web hiện tại. URL
                    if (isset($imageMap[$v])) {
                        if ($base !== '') {
                            // Ghép tên miền + đường dẫn mới
                            $v = rtrim($base, '/') . '/' . ltrim($imageMap[$v], '/');
                        } else {
                            // Nếu không lấy được tên miền thì chỉ cần sử dụng đường dẫn tương đối
                            $v = $imageMap[$v];
                        }
                        continue;
                    }

                    // Tương thích với tiền tố chủ đề/tải xuống/khi xuất Chủ đề
                    // Khi xuất, các trường như home_image được gán giá trị theme/download/xxx.png
                    //Tệp trong gói nén thực tế là xxx.png, khiến việc khớp trực tiếp imageMap không thành công.
                    // Vì vậy, bạn cần xóa tiền tố theme/download/ và thử khớp lại
                    if (strpos($v, 'theme/download/') === 0) {
                        $rel = substr($v, strlen('theme/download/'));
                        if (isset($imageMap[$rel])) {
                            if ($base !== '') {
                                // Đồng thời ghép tên miền + đường dẫn mới
                                $v = rtrim($base, '/') . '/' . ltrim($imageMap[$rel], '/');
                            } else {
                                $v = $imageMap[$rel];
                            }
                            continue;
                        }
                    }

                    // Xử lý việc thay thế tên miền (thay thế liên kết từ tên miền cũ bằng tên miền trang web hiện tại)
                    // Ngăn cấu hình theme đã nhập chứa tên miền của trang cũ khiến hình ảnh không tải được.

                    $parts = parse_url($v);
                    if (!$parts || !isset($parts['host']) || $base === '') {
                        continue;
                    }
                    $path = $parts['path'] ?? '';
                    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
                    $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
                    $v = rtrim($base, '/') . $path . $query . $fragment;
                }
            }
            unset($v);
        };

        // Thực hiện logic thay thế
        $rewriteArray($config);

        // Thực hiện ghi dữ liệu
        $themeId = $this->services->importThemeData($config);

        // Trả về thành công
        return app('json')->success('Nhập thành công', ['theme_id' => $themeId]);
    }

    /**
     * @description: Sử dụng chủ đề
     * @param int $id chủ đềID
     * @return array
     */    public function useTheme(int $id)
    {
        $this->services->useTheme($id);
        return app('json')->success('Đã sử dụng thành công');
    }

    /**
     * @description: Sử dụng dữ liệu chủ đề
     * @param array $data Dữ liệu chủ đề
     * @return array
     */    public function useThemeData($id)
    {
        [$theme_id, $type] = $this->request->getMore([
            ['theme_id', 0],
            ['type', ''],
        ], true);
        $this->services->useThemeData($id, $theme_id, $type);
        return app('json')->success('Đã sử dụng thành công');
    }

    /**
     * @description: Sử dụng chủ đề
     * @return array
     */    public function getUsingTheme()
    {
        $theme = $this->services->getUsingTheme();
        return app('json')->success($theme);
    }

    /**
     * @description: Khôi phục chủ đề
     * @param int $id chủ đềID
     * @return array
     */    public function restoreTheme(int $id)
    {
        $this->services->restoreTheme($id);
        return app('json')->success('Khôi phục thành công');
    }

    /**
     * @description: Xóa chủ đề
     * @param int $id chủ đềID
     * @return array
     */    public function deleteTheme(int $id)
    {
        $this->services->deleteTheme($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Nhận danh sách dữ liệu micropage
     * @return \think\Response JSONphản hồi được định dạng
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/20
     */    public function getMicroPageList()
    {
        $data = $this->services->getMicroPageList();
        return app('json')->success($data);
    }
}
