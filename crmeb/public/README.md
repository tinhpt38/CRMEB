# public Mô tả cấu trúc thư mục

## Cấu trúc thư mục

```
:.
├── .htaccess               # ApacheCấu hình giả tĩnh
├── admin/                  # Quản lý nền tài nguyên tĩnh
├── assets/                 # Tài nguyên tĩnh công cộng
├── favicon.ico             # Biểu tượng trang web
├── index.html              # Mục nhập HTML tĩnh
├── index.php              # PHPTệp nhập
├── install/                # Thư mục hướng dẫn cài đặt
├── mobile.html             # Cổng thông tin di độngHTML
├── nginx.htaccess          # NginxCấu hình giả tĩnh
├── pages/                  # Thư mục mẫu trang
├── product_migration.xlsx  # Mẫu Excel di chuyển sản phẩm
├── robots.txt              # Tệp robot công cụ tìm kiếm
├── router.php              # Tệp nhập định tuyến
├── service_pay_result.html # Trang kết quả thanh toán
├── static/                 # Thư mục tập tin tĩnh
├── statics/                # Thư mục tài nguyên tĩnh (kiểu, tập lệnh）
├── upgrade/                # Thư mục hướng dẫn nâng cấp
├── uploads/                # Tải lên thư mục tập tin
└── README.md              # Tệp mô tả danh mục
```

## Mô tả danh mục

- **admin/** - Tài nguyên tĩnh phía quản lý phụ trợ (CSS, JS, hình ảnh)
- **assets/** - Tài nguyên tĩnh công cộng của dự án
- **index.php** - Tệp nhập chính của dự án
- **install/** - Trình hướng dẫn cài đặt hệ thống
- **static/statics** - thư mục tài nguyên tĩnh giao diện người dùng
- **uploads/** - Thư mục file tải lên của người dùng
- **pages/** - Mẫu trang dành cho thiết bị di động

## Mô tả chức năng

Thư mục public là thư mục đầu vào của trang web:

- **Chức năng lối vào** - Lối vào duy nhất để tiếp cận dự án từ bên ngoài
- **Tài nguyên tĩnh** - Lưu trữ các tệp tĩnh như CSS, JS, hình ảnh, v.v.
- **Cách ly bảo mật** - Định tuyến qua các tệp mục nhập để ẩn cấu trúc bên trong của dự án
- **Giả tĩnh** - Viết lại URL qua .htaccess

## Hướng dẫn an toàn

- Không nên lưu trữ các tập tin nhạy cảm trong thư mục này
- Thư mục upload nên hạn chế loại file
- Thường xuyên dọn dẹp các file upload tạm thời
