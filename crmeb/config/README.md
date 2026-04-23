crmeb/configThư mục được sử dụng để lưu trữ các tập tin cấu hình dự án.

Trong phát triển dự án PHP,File cấu hình đóng vai trò rất quan trọng:

- Đặt các thông số và cài đặt cấp hệ thống khác nhau,Chẳng hạn như thông tin kết nối cơ sở dữ liệu, v.v.
- Tách biệt mã phần mềm và cài đặt môi trường hoạt động,Dễ dàng triển khai
- Tải các dịch vụ và thành phần hệ thống theo cấu hình khi chạy
- Các thông số có thể được thay đổi mà không cần sửa đổi mã

Thư mục cấu hình trong dự án CRMEB chịu trách nhiệm:

- Đặt các cấu hình hệ thống như cơ sở dữ liệu, bộ đệm cục bộ, API mở của bên thứ ba, v.v.
- Xác định cơ chế tải tự động các thành phần dự án
- Quy tắc định tuyến và viết lại URL
- Mức độ lỗi và nhật ký đầu ra
- Cấu hình cách ly sự khác biệt trong các thông số môi trường khác nhau

Dự án sẽ tải và phân tích các cấu hình này khi chạy:

- Khởi tạo các dịch vụ hệ thống như kết nối cơ sở dữ liệu
- Đăng ký các thành phần vào container
- Load môi trường đang chạy theo cấu hình
- Cung cấp các tham số và biến cho các module khác

Vì vậy thư mục này xác định kiến trúc hệ thống và môi trường vận hành của dự án,Có tác động quan trọng đến dự án.

Nó đạt được khả năng cấu hình và khả năng mở rộng của dự án thông qua cấu hình。