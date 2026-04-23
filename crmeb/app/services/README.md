crmeb/app/servicesDanh mục được sử dụng để xác định các lớp dịch vụ kinh doanh của dự án.

Các tính năng và chức năng chính của các lớp dịch vụ bao gồm:

1. Các lớp dịch vụ đóng gói các quy tắc và logic nghiệp vụ cụ thể.

2. Hoàn thành việc trừu tượng hóa các mô-đun chức năng,Cung cấp một giao diện kinh doanh thống nhất.

3. Tách rời các phần khác nhau của dự án,Giảm sự liên kết giữa chúng.

4. Có sẵn cho toàn bộ môi trường của bối cảnh.

Cụ thể:

- Mỗi lớp dịch vụ tương ứng với một chức năng kinh doanh độc lập hoặc một bộ quy tắc.

- Các module khác có thể được gọi trong lớp để hoàn thành các yêu cầu nghiệp vụ.

- Cung cấp giao diện kinh doanh đơn giản với thế giới bên ngoài,Ẩn chi tiết triển khai nội bộ.

- Lớp dịch vụ có sự phụ thuộc,Có thể gọi nhau để thực hiện các dịch vụ tổng hợp.

Sử dụng thiết kế lớp dịch vụ có thể:

- Lỏng lẻo vài mô-đun,Cải thiện khả năng mở rộng và khả năng tái sử dụng.

- Quy tắc kinh doanh tương tự được sử dụng lại trong nhiều tình huống.

- Tăng cường khả năng kiểm thử và bảo trì của dự án.

Vì vậy, thư mục này xác định mô-đun dịch vụ kinh doanh cốt lõi của dự án,Cung cấp các khả năng cốt lõi có thể tái sử dụng cho thế giới bên ngoài。