# Tài liệu hướng dẫn cài đặt CRMEB bằng Docker

## 1. Chuẩn bị môi trường

### 1.1 Cài đặt Docker

Vui lòng chọn cách cài Docker phù hợp với hệ điều hành của bạn:

#### Windows / macOS
Truy cập trang chính thức của Docker để tải và cài đặt Docker Desktop:
[https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

#### Linux
Sử dụng lệnh sau để cài đặt Docker:
```bash
curl -sSL https://get.daocloud.io/docker | sh
```

### 1.2 Cài đặt Docker Compose

#### Windows / macOS
Docker Desktop đã bao gồm Docker Compose, không cần cài thêm.

#### Linux
Vui lòng tham khảo tài liệu chính thức để cài Docker Compose:
[https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)

## 2. Tải chương trình CRMEB

1. Tải mã nguồn mở mới nhất:
[https://gitee.com/ZhongBangKeJi/CRMEB](https://gitee.com/ZhongBangKeJi/CRMEB)

2. Giải nén chương trình và đặt ở cùng cấp thư mục với `docker-compose`.

## 3. Cấu hình cài đặt theo từng hệ điều hành

### 3.1 Cấu hình chung (mặc định)

Áp dụng cho hầu hết hệ thống Linux và macOS dùng chip Intel.

Đường dẫn file cấu hình: `docker-compose/docker-compose.yml`

### 3.2 Hệ thống Linux

Cấu hình dành riêng cho Linux, bao gồm thiết lập tương thích nền tảng.

Đường dẫn file cấu hình: `docker-compose/linux/docker-compose.yml`

### 3.3 macOS (chip Intel)

Cấu hình dành riêng cho macOS dùng chip Intel.

Đường dẫn file cấu hình: `docker-compose/MacIntel/docker-compose.yml`

### 3.4 macOS (chip Apple Silicon)

Cấu hình dành riêng cho MacBook M1/M2/M3 và các máy dùng chip Apple Silicon, đã xử lý vấn đề tương thích MySQL.

Đường dẫn file cấu hình: `docker-compose/MacArm/docker-compose.yml`

### 3.5 Hệ thống Windows

Cấu hình dành riêng cho Windows.

Đường dẫn file cấu hình: `docker-compose/window/docker-compose.yml`

## 4. Mô tả cấu hình dịch vụ

### 4.1 Cơ sở dữ liệu MySQL

| Mục cấu hình | Giá trị mặc định | Mô tả |
|--------|--------|------|
| Tên container | crmeb_mysql | Tên container Docker |
| Image | mysql:5.7 | Image cơ sở dữ liệu (MacArm dùng mysql/mysql-server) |
| Cổng | 3336:3306 | Cổng máy chủ: cổng container |
| Tên người dùng | root | Tên người dùng cơ sở dữ liệu |
| Mật khẩu | 123456 | Mật khẩu cơ sở dữ liệu |
| Tên cơ sở dữ liệu | crmeb | Tên cơ sở dữ liệu được tạo mặc định |
| IP container | 192.168.10.11 | IP cố định trong mạng nội bộ |

### 4.2 Bộ nhớ đệm Redis

| Mục cấu hình | Giá trị mặc định | Mô tả |
|--------|--------|------|
| Tên container | crmeb_redis | Tên container Docker |
| Image | redis:alpine | Image Redis |
| Cổng | 6379:6379 | Cổng máy chủ: cổng container |
| IP container | 192.168.10.10 | IP cố định trong mạng nội bộ |

### 4.3 Ứng dụng PHP

| Mục cấu hình | Giá trị mặc định | Mô tả |
|--------|--------|------|
| Tên container | crmeb_php | Tên container Docker |
| Image | crmeb_php | Image PHP (được build từ Dockerfile) |
| Cổng | 9000:9000 | Cổng PHP-FPM |
|  | 20002:20002 | Cổng kết nối dài 1 |
|  | 20003:20003 | Cổng kết nối dài 2 |
| IP container | 192.168.10.90 | IP cố định trong mạng nội bộ |
| Thư mục chương trình | /var/www | Đường dẫn dự án bên trong container |

### 4.4 Máy chủ Nginx

| Mục cấu hình | Giá trị mặc định | Mô tả |
|--------|--------|------|
| Tên container | crmeb_nginx | Tên container Docker |
| Image | nginx:alpine | Image Nginx |
| Cổng | 8011:80 | Cổng máy chủ: cổng container |
| IP container | 192.168.10.80 | IP cố định trong mạng nội bộ |

## 5. Khởi động dự án

### 5.1 Các bước khởi động cơ bản

1. Vào thư mục docker-compose:
```bash
cd docker-compose
```

2. Khởi động tất cả dịch vụ:
```bash
docker-compose up -d
```

3. Kiểm tra trạng thái container:
```bash
docker-compose ps
```

### 5.2 Khởi động các dịch vụ bổ sung (bắt buộc)

Vào container PHP và khởi động hàng đợi, tác vụ định kỳ và dịch vụ kết nối dài:

1. Vào container PHP:
```bash
docker exec -it crmeb_php /bin/bash
```

2. Vào thư mục dự án:
```bash
cd /var/www
```

3. Khởi động tác vụ định kỳ:
```bash
php think timer start --d
```

4. Khởi động dịch vụ kết nối dài:
```bash
php think workerman start --d
```

5. Khởi động dịch vụ hàng đợi:
```bash
php think queue:listen --queue
```

## 6. Truy cập hệ thống CRMEB

### 6.1 Địa chỉ truy cập

Nhập địa chỉ sau vào trình duyệt để truy cập hệ thống CRMEB:
```
http://localhost:8011/
```

### 6.2 Cài đặt hệ thống

Khi truy cập lần đầu, bạn sẽ vào trình hướng dẫn cài đặt CRMEB, hãy làm theo hướng dẫn để hoàn tất cài đặt hệ thống.

#### Cấu hình cơ sở dữ liệu

| Mục cấu hình | Giá trị |
|--------|-----|
| Địa chỉ cơ sở dữ liệu | 192.168.10.11 |
| Cổng | 3306 |
| Tên người dùng | root |
| Mật khẩu | 123456 |
| Tên cơ sở dữ liệu | crmeb |

#### Cấu hình Redis

| Mục cấu hình | Giá trị |
|--------|-----|
| Địa chỉ Redis | 192.168.10.10 |
| Cổng | 6379 |
| Cơ sở dữ liệu | 0 |
| Mật khẩu | 123456 |

## 7. Quản lý container

### 7.1 Dừng dịch vụ

```bash
# Dừng tất cả dịch vụ
docker-compose down

# Dừng dịch vụ chỉ định
docker-compose stop <service-name>
```

### 7.2 Khởi động lại dịch vụ

```bash
# Khởi động lại tất cả dịch vụ
docker-compose restart

# Khởi động lại dịch vụ chỉ định
docker-compose restart <service-name>
```

### 7.3 Xem log

```bash
# Xem log của tất cả dịch vụ
docker-compose logs

# Xem log của dịch vụ chỉ định
docker-compose logs <service-name>

# Xem log theo thời gian thực
docker-compose logs -f <service-name>
```

## 8. Câu hỏi thường gặp và cách xử lý

### 8.1 Cổng bị chiếm dụng

**Vấn đề**: Xuất hiện lỗi cổng bị chiếm khi khởi động

**Giải pháp**:
1. Sửa ánh xạ cổng trong `docker-compose.yml`, ví dụ đổi `8011:80` thành `8080:80`
2. Khởi động lại dịch vụ

### 8.2 Xung đột địa chỉ IP

**Vấn đề**: `Error response from daemon: Address already in use`

**Giải pháp**:
1. Sửa `ipv4_address` của container bị xung đột trong `docker-compose.yml`
2. Đảm bảo IP nằm trong dải `192.168.*.*` và không trùng với thiết bị khác

### 8.3 Container MySQL khởi động thất bại (Mac chip ARM)

**Vấn đề**: Container MySQL không thể khởi động, không có log đầu ra

**Giải pháp**:
1. Sử dụng cấu hình chuyên dụng trong thư mục MacArm
2. Đảm bảo dùng đúng image MySQL (`mysql/mysql-server`)
3. Kiểm tra thiết lập quyền tệp

### 8.4 Thiếu extension PHP

**Vấn đề**: Hệ thống báo thiếu một số extension PHP

**Giải pháp**:
1. Vào container PHP
2. Cài extension cần thiết
3. Hoặc sửa `docker-compose/php/Dockerfile` để thêm extension rồi build lại image

### 8.5 Vấn đề quyền tệp

**Vấn đề**: Chương trình không thể ghi file hoặc tạo thư mục

**Giải pháp**:
1. Kiểm tra quyền của thư mục `crmeb` trên máy chủ
2. Đảm bảo người dùng `www-data` trong container có đủ quyền
3. Có thể thử sửa quyền thư mục:
   ```bash
   chmod -R 777 crmeb/runtime
   chmod -R 777 crmeb/public/upload
   ```

## 9. Lưu ý

### 9.1 Lưu trữ dữ liệu lâu dài

- Dữ liệu MySQL mặc định được mount tại thư mục `docker-compose/mysql/data`
- Dữ liệu Redis mặc định chưa được mount, nếu cần lưu trữ lâu dài hãy sửa file cấu hình
- Mã dự án được mount tại thư mục `crmeb`, mọi thay đổi mã trên máy chủ sẽ ảnh hưởng trực tiếp đến chương trình trong container

### 9.2 Cấu hình mạng

- Tất cả dịch vụ chạy trong mạng `app_net`, sử dụng địa chỉ IP cố định
- Máy chủ và container giao tiếp qua ánh xạ cổng
- Các container có thể giao tiếp trực tiếp với nhau qua IP nội bộ

### 9.3 Tối ưu hiệu năng

- Điều chỉnh giới hạn tài nguyên container theo cấu hình máy chủ
- Môi trường production nên đổi mật khẩu và cổng mặc định
- Cấu hình chiến lược dọn log phù hợp

### 9.4 Hướng dẫn nâng cấp

1. Dừng tất cả dịch vụ
2. Sao lưu dữ liệu và file cấu hình
3. Cập nhật mã nguồn
4. Khởi động lại dịch vụ
5. Thực thi migrate cơ sở dữ liệu (nếu cần)

## 10. Cấu hình nâng cao

### 10.1 Thay đổi mật khẩu mặc định

Sửa file `docker-compose.yml`, thay đổi các biến môi trường sau:

- MySQL: `MYSQL_ROOT_PASSWORD`, `MYSQL_PASS`
- Redis: cấu hình mật khẩu trong `redis.conf`

### 10.2 Cấu hình HTTPS

1. Bật ánh xạ cổng 443 trong `docker-compose.yml`
2. Chuẩn bị chứng chỉ SSL
3. Sửa `nginx/vhost.conf` để cấu hình HTTPS

### 10.3 Tùy chỉnh cấu hình PHP

Sửa file `docker-compose/php/php-ini-overrides.ini` để tùy chỉnh cấu hình PHP.

## 11. Hỗ trợ kỹ thuật

Nếu bạn gặp vấn đề trong quá trình cài đặt, có thể nhận hỗ trợ qua các kênh sau:

- Cộng đồng chính thức CRMEB: [https://gitee.com/ZhongBangKeJi/CRMEB/issues](https://gitee.com/ZhongBangKeJi/CRMEB/issues)
- Tài liệu chính thức Docker: [https://docs.docker.com/](https://docs.docker.com/)

---

**Phiên bản tài liệu**: v1.0
**Ngày cập nhật**: 2023-12-04
**Phiên bản áp dụng**: CRMEB v5.6+
---

> **Gợi ý**: Tài liệu này được AI tạo ra, chỉ mang tính tham khảo.
