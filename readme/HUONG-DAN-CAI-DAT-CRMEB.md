# Hướng dẫn cài đặt CRMEB

CRMEB là hệ thống cửa hàng mã nguồn mở (phiên bản PHP), dùng ThinkPHP 6 + ElementUI + UniApp.

---

## 1. Yêu cầu môi trường

| Thành phần | Yêu cầu |
|------------|---------|
| **Hệ điều hành** | Linux / Windows |
| **Web server** | Nginx / Apache / IIS |
| **PHP** | 7.1 ~ 7.4 |
| **MySQL** | 5.7 ~ 8.0 (engine InnoDB) |
| **Redis** | Tùy chọn (không cài thì dùng file cache) |
| **PHP mở rộng** | fileinfo (tùy chọn), redis (tùy chọn) |

**Lưu ý:** Không hỗ trợ hosting ảo (shared hosting). Nên dùng VPS/Cloud và có thể dùng **Bảo Tháp (宝塔)** để quản lý dễ hơn.

---

## 2. Cài đặt nhanh (một bước – qua trình cài web)

### Bước 1: Chuẩn bị mã nguồn

- Tải mã nguồn từ: https://gitee.com/ZhongBangKeJi/CRMEB  
- Hoặc dùng bản đã có trong thư mục `crmeb/`.

### Bước 2: Cấu hình Web server

- **Thư mục gốc website (Document Root)** phải trỏ vào thư mục **`public`** trong `crmeb/`.  
  Ví dụ: nếu code nằm tại `/var/www/crmeb`, thì Document Root là `/var/www/crmeb/public`.
- Bật **URL rewrite** (伪静态) theo chuẩn ThinkPHP (xem mục 5 bên dưới).

### Bước 3: Chạy trình cài đặt

1. Mở trình duyệt, truy cập: **`http://tên-miền-của-bạn/`** (hoặc IP nếu chưa có domain).
2. Trình cài sẽ chạy tự động, bạn làm theo từng bước và nhập thông tin **database** khi được yêu cầu.
3. **Nhớ lưu lại tài khoản và mật khẩu admin** do hệ thống tạo.
4. Sau khi cài xong, **nên xóa hoặc đổi tên thư mục `public/install`** để tránh cài lại nhầm.

### Địa chỉ truy cập sau khi cài

- **Trang chủ / H5:** `http://tên-miền/`
- **Quản trị:** `http://tên-miền/admin`  
- Mặc định đăng nhập: **admin** / **crmeb.com** (nếu cài tay và import SQL mặc định).

---

## 3. Cài đặt thủ công (không dùng trình cài web)

### Bước 1: Tạo database và import SQL

1. Tạo database MySQL (ví dụ: `crmeb`).
2. Import file: **`crmeb/public/install/crmeb.sql`** vào database vừa tạo.

### Bước 2: Cấu hình file `.env`

Tạo hoặc sửa file **`.env`** ở **thư mục gốc của project** (cùng cấp với thư mục `app/`, `config/`), nội dung tham khảo:

```ini
APP_DEBUG = true

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai

[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1
HOSTPORT = 3306
DATABASE = crmeb
USERNAME = root
PASSWORD = mat_khau_mysql
PREFIX = eb_
CHARSET = utf8mb4
DEBUG = true

[LANG]
default_lang = zh-cn

[CACHE]
DRIVER = file
CACHE_PREFIX = cache_xxxx:
CACHE_TAG_PREFIX = cache_tag_xxxx:

[REDIS]
REDIS_HOSTNAME = 127.0.0.1
PORT = 6379
REDIS_PASSWORD =
SELECT = 0

[QUEUE]
QUEUE_NAME = crmeb
```

Thay `DATABASE`, `USERNAME`, `PASSWORD` cho đúng với MySQL của bạn.

### Bước 3: Phân quyền thư mục (Linux)

Cấp quyền ghi cho web server (thường user `www-data` hoặc `www`):

- Thư mục **`crmeb/`** (hoặc thư mục runtime bên trong)
- Thư mục **`template/`** (nếu có, trong project)

Ví dụ:

```bash
chmod -R 777 crmeb/runtime
chmod -R 777 crmeb
# Hoặc chown cho user chạy web: chown -R www-data:www-data crmeb
```

### Bước 4: Truy cập

- Trang chủ: `http://tên-miền/`
- Admin: `http://tên-miền/admin`  
- Đăng nhập mặc định: **admin** / **crmeb.com**

---

## 4. Xử lý lỗi sql_mode (MySQL 5.7 / 8.0)

Trình cài CRMEB kiểm tra `sql_mode` của MySQL. Nếu đang dùng cấu hình mặc định của **MySQL 5.7 hoặc 8.0** (có `STRICT_TRANS_TABLES`, `ONLY_FULL_GROUP_BY`, …), bạn có thể gặp thông báo:

> *Vui lòng sửa đổi tham số sql-mode hoặc sql_mode trong tệp cấu hình MySQL thành NO_AUTO_CREATE_USER, NO_ENGINE_SUBSTITUTION.*

**Nguyên nhân:** Trình cài yêu cầu `sql_mode` **không** chứa `STRICT_TRANS_TABLES` (và một số mode tương tự). Cấu hình mặc định MySQL 8.0 thường là:  
`ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION` → bị coi là không hợp lệ.

**Lưu ý MySQL 8.0:** Từ MySQL 8.0, **NO_AUTO_CREATE_USER** đã bị loại bỏ, nên bạn **không thể** đặt đúng như thông báo cũ. Chỉ cần đặt `sql_mode` sao cho **không có** `STRICT_TRANS_TABLES` (và các mode strict khác) là đủ.

### Cách 1: Sửa file cấu hình MySQL (khuyến nghị)

1. Mở file cấu hình MySQL:
   - **Linux:** thường là `/etc/mysql/mysql.conf.d/mysqld.cnf` hoặc `/etc/my.cnf`
   - **macOS (Homebrew):** `usr/local/etc/my.cnf` hoặc path tương tự
   - **Windows:** `my.ini` (trong thư mục cài MySQL, ví dụ `C:\ProgramData\MySQL\MySQL Server 8.0\`)
   - **Bảo Tháp (宝塔):** Có thể sửa qua giao diện “软件商店” → MySQL → “设置” → “配置修改”

2. Tìm dòng `sql_mode` (hoặc `sql-mode`). Nếu không có thì thêm vào trong section `[mysqld]`.

3. Đặt **một trong hai** giá trị sau (MySQL 5.7 và 8.0 đều dùng được):

   ```ini
   [mysqld]
   sql_mode = "NO_ENGINE_SUBSTITUTION"
   ```

   Hoặc để “thoải mái” hơn (tắt nhiều kiểm tra strict):

   ```ini
   [mysqld]
   sql_mode = ""
   ```

4. Lưu file và **khởi động lại** MySQL:
   - Linux: `sudo systemctl restart mysql` (hoặc `mysqld`)
   - Bảo Tháp: Restart MySQL trong panel
   - Windows: Restart dịch vụ MySQL trong “Services”

5. Kiểm tra: đăng nhập MySQL và chạy `SELECT @@GLOBAL.sql_mode;` — kết quả không được chứa `STRICT_TRANS_TABLES`. Sau đó chạy lại trình cài CRMEB.

### Cách 2: Chỉ đổi tạm trong phiên (không khuyến nghị cho cài đặt)

Trong phiên MySQL hiện tại (hoặc mỗi lần kết nối), có thể đặt:

```sql
SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
```

Sau khi restart MySQL, giá trị sẽ quay lại theo file cấu hình. Nếu bạn cài CRMEB bằng trình web, cần đặt **trước** khi bước kiểm tra DB chạy, và tốt hơn vẫn nên sửa file cấu hình (Cách 1) để ổn định lâu dài.

### Tóm tắt

| Mục | Nội dung |
|-----|----------|
| **Lỗi** | Trình cài báo sửa sql_mode thành NO_AUTO_CREATE_USER, NO_ENGINE_SUBSTITUTION. |
| **Nguyên nhân** | sql_mode hiện tại có STRICT_TRANS_TABLES (hoặc mode tương tự). |
| **MySQL 8.0** | Không dùng được NO_AUTO_CREATE_USER (đã bị xóa). Chỉ cần bỏ STRICT_* và giữ NO_ENGINE_SUBSTITUTION (hoặc sql_mode rỗng). |
| **Cách làm** | Sửa file cấu hình MySQL: `sql_mode = "NO_ENGINE_SUBSTITUTION"` (hoặc `""`) trong `[mysqld]`, rồi restart MySQL. |

---

## 5. Cài đặt bằng Docker

### Bước 1: Cài Docker và Docker Compose

- Docker: https://www.docker.com/products/docker-desktop  
- Hoặc Linux: `curl -sSL https://get.daocloud.io/docker | sh`  
- Docker Compose: https://docs.docker.com/compose/install/

### Bước 2: Đặt mã nguồn CRMEB

Đặt toàn bộ mã nguồn CRMEB **cùng cấp** với thư mục `docker-compose` (theo cấu trúc trong repo).

### Bước 3: Chạy Docker

```bash
cd docker-compose
docker-compose up -d
```

### Bước 4: Vào container PHP và chạy lệnh nền

```bash
docker exec -it crmeb_php /bin/bash
cd /var/www
php think timer start --d
php think workerman start --d
php think queue:listen --queue
```

### Bước 5: Truy cập và cài đặt qua web

- Truy cập: **http://localhost:8011/** (hoặc port đã đổi trong `docker-compose.yml`).
- Chạy trình cài web và nhập thông tin database/Redis theo hướng dẫn trong file `docker-compose/README.md` (host MySQL/Redis có thể là tên service trong Docker).

---

## 6. Cấu hình URL rewrite (Nginx / Apache)

### Nginx – ThinkPHP

```nginx
location / {
    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php?s=$1 last;
        break;
    }
}
```

### Apache

Đảm bảo `mod_rewrite` bật và file `.htaccess` trong `public/` có nội dung rewrite chuẩn ThinkPHP (thường đã có sẵn trong source).

---

## 7. Các dịch vụ nền (Queue, Timer, Workerman)

Để đủ chức năng (tự động nhận hàng, cảnh báo tồn kho, thông báo, chat…), cần chạy thêm:

### Hàng đợi (Queue)

```bash
cd crmeb
php think queue:listen --queue
```

Nên dùng **Supervisor** để giữ lệnh này chạy liên tục.

### Định thời (Timer)

```bash
php think timer start --d
```

### Long connection (Workerman – thông báo, chat)

```bash
php think workerman start --d
```

Trên Windows có thể cần chạy từng service theo tài liệu trong `readme/安装说明.md`.

---

## 8. Cài lại (reinstall)

1. Xóa toàn bộ dữ liệu trong database (hoặc tạo database mới và import lại `crmeb.sql` nếu cài tay).
2. Xóa file **`crmeb/public/install.lock`**.
3. Truy cập lại `http://tên-miền/` để chạy lại trình cài web (nếu vẫn còn thư mục `install`).

---

## 9. Tài liệu thêm

- **Đa ngôn ngữ / Tiếng Việt:** xem [DA-NGON-NGU-TIENG-VIET.md](DA-NGON-NGU-TIENG-VIET.md) – cơ chế đa ngôn ngữ trong DB và cách dùng/chuyển sang tiếng Việt.
- **Kế hoạch làm việc tiếng Việt:** xem [KE-HOACH-TIENG-VIET.md](KE-HOACH-TIENG-VIET.md) – kế hoạch từng giai đoạn để chuyển hệ thống sang tiếng Việt.
- **Tài liệu chính:** https://doc.crmeb.com/single_open  
- **Cài nhanh (một bước):** https://doc.crmeb.com/single_open/open_v54/20366  
- **Cài tay:** https://doc.crmeb.com/single_open/open_v54/20389  
- **Docker:** https://doc.crmeb.com/single_open/open_v54/20145  
- **Bảo Tháp:** https://doc.crmeb.com/single_open/open_v54/19892  

---

*Tài liệu này tổng hợp từ README và hướng dẫn trong repo CRMEB.*
