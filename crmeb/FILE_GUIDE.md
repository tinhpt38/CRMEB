# crmeb Mô tả tập tin danh mục

## Tổng quan về cấu trúc thư mục
```
crmeb/
├── app/                 # Mã lõi ứng dụng (bộ điều khiển, mô hình, dịch vụ, v.v.)）
├── backup/              # tập tin sao lưu dữ liệu
├── config/              # Các tập tin cấu hình (cơ sở dữ liệu, bộ đệm, giao diện, v.v.)）
├── crmeb/               # Các mô-đun công cộng hoặc thư viện mở rộng trong dự án
├── public/              # Web Mục nhập có thể truy cập (tài nguyên tĩnh、index.php）
├── route/               # định nghĩa tuyến đường
├── runtime/             # Bộ đệm thời gian chạy, nhật ký, Phiên, v.v. (cần bỏ qua kiểm soát phiên bản）
│
├── .constant            # Tệp định nghĩa không đổi (cần bỏ qua kiểm soát phiên bản）
├── .dockerignore        # Docker Xây dựng quy tắc bỏ qua
├── .env                 # Cấu hình biến môi trường (thông tin nhạy cảm) (cần bỏ qua kiểm soát phiên bản）
├── .env.example         # Tệp mẫu biến môi trường
├── .htaccess            # Apache Viết lại quy tắc
├── .phpstorm.meta.php   # PhpStorm Siêu dữ liệu
├── .travis.yml          # Travis CI Cấu hình
├── .version             # Thông tin phiên bản
├── Dockerfile           # Docker Tập tin xây dựng hình ảnh
├── LICENSE.txt          # Thỏa thuận cấp phép nguồn mở
├── README.md            # Mô tả dự án
├── build.example.php    # Xây dựng kịch bản ví dụ
├── composer.json        # Composer Phụ thuộc vào cấu hình
├── composer.lock        # Composer phiên bản bị khóa
├── filetree.txt         # Ảnh chụp nhanh cấu trúc cây tệp
├── index.html           # Trang chủ mặc định (thư mục chống truy cập）
├── my.cnf               # MySQL Cấu hình tùy chỉnh (cần bỏ qua kiểm soát phiên bản）
├── nginx.conf           # Nginx Cấu hình (cần bỏ qua kiểm soát phiên bản）
├── php-fpm.conf         # PHP-FPM Cấu hình (cần bỏ qua kiểm soát phiên bản）
├── php-ini-overrides.ini# PHP Ghi đè ini tùy chỉnh (cần bỏ qua kiểm soát phiên bản）
├── redis.conf           # Redis Cấu hình (cần bỏ qua kiểm soát phiên bản）
├── start.sh             # Tập lệnh khởi động dự án (cần bỏ qua kiểm soát phiên bản）
├── supervisord.conf     # Supervisor Cấu hình quản lý quy trình (cần bỏ qua kiểm soát phiên bản）
├── think                # ThinkPHP Tệp nhập khung
├── vhost.conf           # Cấu hình máy chủ ảo (cần bỏ qua kiểm soát phiên bản）
└── workerman.bat        # Windows Kịch bản khởi động Workerman tiếp theo
```

## Mô tả danh mục chính
- **ứng dụng/**
  Lưu trữ mã cốt lõi của logic nghiệp vụ, bao gồm cả bộ điều khiển(Controller)、Người mẫu(Model)、lớp dịch vụ(Service)v.v., theo mô hình MVC hoặc kiến ​​trúc phân lớp tương tự.
- **sao lưu/**
  Các tập tin sao lưu dùng để lưu trữ cơ sở dữ liệu hoặc dữ liệu quan trọng. Nên dọn dẹp các bản sao lưu cũ thường xuyên.
- **cấu hình/**
  Các tệp cấu hình ứng dụng và môi trường khác nhau, chẳng hạn như cơ sở dữ liệu, bộ đệm, hàng đợi, xác thực giao diện, v.v.
- **crmeb/**
  Các mô-đun công khai trong dự án hoặc tích hợp SDK của bên thứ ba có thể bao gồm một số lớp công cụ hoặc chức năng mở rộng.
- **công khai/**
  Thư mục gốc của máy chủ web, nơi đặt các tài nguyên (chẳng hạn như hình ảnh, JS, CSS) và các tệp mục nhập có thể được truy cập trực tiếp thông qua trình duyệt. `index.php`。
- **route/**  
  Tệp định nghĩa định tuyến, được sử dụng để ánh xạ các yêu cầu URL tới các phương thức điều khiển cụ thể.
- **thời gian chạy/**
  Lưu trữ dữ liệu tạm thời như bộ đệm, nhật ký và phiên được tạo trong thời gian chạy; thư mục này nên ở trong `.gitignore` bị bỏ qua trong .

## Mô tả tệp chính
- **.env/.env.example**
  Cấu hình và ví dụ về biến môi trường，`.env` Chứa thông tin nhạy cảm, không gửi nó đến cơ sở mã.
- **composer.json/composer.lock**
  Cấu hình quản lý phụ thuộc dự án PHP và các tệp khóa.
- **Liên quan đến Dockerfile/docker-compose**
  Cấu hình để triển khai trong container.
- **my.cnf/redis.conf/nginx.conf**
  Cấu hình tùy chỉnh của các dịch vụ khác nhau.
- **start.sh**
  Tập lệnh khởi động máy chủ hoặc cục bộ của dự án.
- **nghĩ**
  Tệp nhập hợp nhất của khung ThinkPHP, chịu trách nhiệm khởi tạo khung và phân phối yêu cầu。