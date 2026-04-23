crmeb/app/apiThư mục là giao diện người dùng của trang web(Phụ trợ phi hành chính)Thư mục giao diện API.

Sự khác biệt giữa nó và thư mục adminapi là:

- adminapiTrong thư mục là giao diện API để quản lý hệ thống phụ trợ.
- Thư mục api là hệ thống front-end của website(Phiên bản di động/applet WeChat/H5, v.v.)Giao diện API

Cụ thể:

- apiThư mục cũng sử dụng bộ điều khiển(Controller)Cách tổ chức mã giao diện
- Mỗi bộ điều khiển tương ứng với một module chức năng,Ví dụ: OrderController chịu trách nhiệm về các giao diện liên quan đến đơn hàng, v.v.
- Giao diện được sử dụng cho các yêu cầu ajax trên các trang front-end,Lấy dữ liệu để kết xuất
- Giao diện cũng được thiết kế theo phong cách RESTful

Ví dụ:

- Giao diện đăng ký người dùng theo phương thức đăng ký của UserController
- Lấy danh sách đơn hàng theo phương thức list của OrderController
- Thông báo kết quả thanh toán theo phương thức notification của PayController

Giống như thư mục adminapi,apiThư mục cũng có giao diện được xác định rõ ràng,Front-end và back-end được tách rời,Hãy để giao diện người dùng tập trung nhiều hơn vào việc trình bày kinh doanh.

Sự khác biệt là người dùng mục tiêu khác nhau:

- adminapiĐể sử dụng bởi quản trị viên phụ trợ
- Giao diện trong thư mục api là giao diện người dùng front-end(Phiên bản di động, chương trình mini, v.v.)Cung cấp dịch vụ dữ liệu

Vì vậy, cả hai đều đóng vai trò quan trọng trong việc tách mặt trước và mặt sau.。