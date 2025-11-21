# Hướng dẫn cài đặt CRMEB trên macOS sử dụng FlyEnv

Tài liệu này hướng dẫn cách thiết lập môi trường và cài đặt mã nguồn CRMEB trên macOS sử dụng công cụ quản lý môi trường **FlyEnv**.

## Giới thiệu về FlyEnv

FlyEnv là một công cụ quản lý môi trường phát triển cục bộ mạnh mẽ cho macOS, cho phép bạn dễ dàng cài đặt và chuyển đổi giữa các phiên bản PHP, MySQL, Nginx, v.v. Nó giúp việc thiết lập môi trường cho CRMEB trở nên đơn giản và nhanh chóng.

## Yêu cầu

- Hệ điều hành: macOS
- Đã cài đặt [Homebrew](https://brew.sh/)

## Bước 1: Cài đặt FlyEnv

Nếu bạn chưa cài đặt FlyEnv, hãy mở Terminal và chạy lệnh sau:

```bash
brew install --cask flyenv
```

Sau khi cài đặt xong, hãy mở ứng dụng FlyEnv từ thư mục Applications.

## Bước 2: Thiết lập môi trường

Trong giao diện FlyEnv, bạn cần cài đặt các dịch vụ sau để đáp ứng yêu cầu của CRMEB:

1.  **PHP**: Chọn phiên bản **7.4** (Khuyến nghị).
    -   Sau khi cài đặt PHP, hãy đảm bảo các extension sau đã được bật (thường được bật mặc định): `fileinfo`, `redis`, `mysqli`, `pdo_mysql`.
2.  **MySQL**: Chọn phiên bản **5.7**.
    -   Khởi động dịch vụ MySQL.
    -   Tạo một cơ sở dữ liệu mới (ví dụ: `crmeb`).
    -   Ghi nhớ thông tin đăng nhập (mặc định thường là root/root).
3.  **Redis**: Cài đặt và khởi động dịch vụ Redis (phiên bản mới nhất đều được).
4.  **Nginx**: Cài đặt và khởi động Nginx.

## Bước 3: Cấu hình trang web (Site)

1.  Trong FlyEnv, tạo một trang web mới (Add Site).
2.  **Domain**: Nhập tên miền cục bộ bạn muốn sử dụng (ví dụ: `crmeb.test`). FlyEnv sẽ tự động thêm vào file hosts.
3.  **Root Directory**: Trỏ đến thư mục `public` trong mã nguồn dự án CRMEB của bạn.
    -   Ví dụ: `/Users/username/Workspace/CRMEB/public`
4.  **PHP Version**: Chọn PHP 7.4.
5.  **Pseudo-static (URL Rewrite)**:
    -   Chọn cấu hình cho **ThinkPHP**.
    -   Nếu FlyEnv không có sẵn mẫu ThinkPHP, hãy sử dụng cấu hình Nginx sau:
    ```nginx
    location / {
        if (!-e $request_filename){
            rewrite  ^(.*)$  /index.php?s=$1  last;   break;
        }
    }
    ```

## Bước 4: Cài đặt dự án

### Cách 1: Cài đặt qua giao diện web (Khuyến nghị)

1.  Đảm bảo bạn đã cấp quyền ghi cho các thư mục cần thiết:
    ```bash
    cd /đường/dẫn/đến/dự/án
    chmod -R 777 public runtime
    ```
2.  Truy cập tên miền bạn đã cấu hình ở Bước 3 (ví dụ: `http://crmeb.test`).
3.  Trình cài đặt CRMEB sẽ xuất hiện. Làm theo hướng dẫn trên màn hình:
    -   Kiểm tra môi trường.
    -   Nhập thông tin cơ sở dữ liệu (Host: 127.0.0.1, User: root, Pass: root, DB Name: crmeb).
    -   Thiết lập tài khoản quản trị viên.
4.  Sau khi cài đặt thành công, xóa thư mục `public/install` hoặc đổi tên file `install/index.php` để bảo mật.

### Cách 2: Cài đặt thủ công

1.  Import file SQL `public/install/crmeb.sql` vào cơ sở dữ liệu bạn đã tạo.
2.  Sao chép file `.env.example` thành `.env` và cấu hình thông tin kết nối database, redis.
3.  Cấp quyền cho thư mục `public` và `runtime`.

## Bước 5: Cấu hình các dịch vụ nền (Background Services)

Để hệ thống hoạt động đầy đủ tính năng (hàng đợi tin nhắn, thông báo realtime), bạn cần chạy các dịch vụ sau.

### Hàng đợi (Queue) - Supervisor

FlyEnv có thể hỗ trợ quản lý Supervisor hoặc bạn có thể chạy thủ công trên Terminal:

```bash
# Chạy lệnh này trong thư mục gốc của dự án
php think queue:listen --queue
```

### Tác vụ định kỳ (Timer)

```bash
php think timer start --d
```

### Dịch vụ kết nối dài (Workerman) - Chat & Thông báo

```bash
php think workerman start --d
```
*Lưu ý: Trên macOS, bạn có thể cần chạy với `sudo` nếu gặp lỗi quyền hạn.*

## Hoàn tất

Bây giờ bạn có thể truy cập:
-   **Trang chủ**: `http://crmeb.test/`
-   **Trang quản trị**: `http://crmeb.test/admin/`

Chúc bạn thành công!
