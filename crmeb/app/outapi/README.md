crmeb/app/outapiThư mục được sử dụng để xác định giao diện bên ngoài của dự án.

Cụ thể:

- Đây là thư mục định nghĩa giao diện API mà dự án được mở cho bên thứ ba.

- Giao diện ở đây có thể được các bên thứ ba gọi trực tiếp để lấy dữ liệu hoặc hoàn thiện các dịch vụ liên quan

- Loại giao diện API này khác với API sử dụng nội bộ:

  - Mở cửa với thế giới bên ngoài,Không cần ủy quyền đăng nhập
  - Hạn chế bảo mật chặt chẽ hơn,Chỉ cung cấp các giao diện cần thiết
  - Đặc tả giao diện tuân theo nguyên tắc RESTful

- Các tình huống phổ biến:

  - Applet/APP của bên thứ ba trực tiếp lấy dữ liệu sản phẩm
  - Đồng bộ hóa thông tin đơn hàng với hệ thống phụ trợ người bán bên thứ ba
  - Giao diện thông báo gọi lại thanh toán chương trình mini

Sử dụng thư mục này để xác định giao diện bên ngoài:

- Đạt được sự tích hợp sâu sắc với các hệ thống khác

- Cho phép nhiều tình huống hơn để sử dụng các khả năng do CRMEB cung cấp

- Giảm sự xâm nhập của bên thứ ba,Chỉ mở các giao diện cần thiết

Vậy tóm lại,outapiĐược sử dụng để làm cho các dự án mở ra thế giới bên ngoàiAPI,Mở rộng khả năng truy cập của bên thứ ba。